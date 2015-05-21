<?php

class WSAL_Sensors_LogInOut extends WSAL_AbstractSensor {

	public function HookEvents() {
		add_action('wp_login', array($this, 'EventLogin'), 10, 2);
		add_action('wp_logout', array($this, 'EventLogout'));
		add_action('wp_login_failed', array($this, 'EventLoginFailure'));
		add_action('clear_auth_cookie', array($this, 'GetCurrentUser'), 10);
	}
	
	protected $_current_user = null;
	
	public function GetCurrentUser(){
		$this->_current_user = wp_get_current_user();
	}
	
	public function EventLogin($user_login, $user){
		$this->plugin->alerts->Trigger(1000, array(
			'Username' => $user_login,
			'CurrentUserRoles' => $this->plugin->settings->GetCurrentUserRoles($user->roles),
		), true);
	}
	
	public function EventLogout(){
		if($this->_current_user->ID != 0){
			$this->plugin->alerts->Trigger(1001, array(
				'CurrentUserID' => $this->_current_user->ID,
				'CurrentUserRoles' => $this->plugin->settings->GetCurrentUserRoles($this->_current_user->roles),
			), true);
		}
	}

	/**
	 * @return boolean Whether we are running on multisite or not.
	 */
	public function IsMultisite(){
		return function_exists('is_multisite') && is_multisite();
	}
	
	const TRANSIENT_FAILEDLOGINS = 'wsal-failedlogins-known';
	const TRANSIENT_FAILEDLOGINS_UNKNOWN = 'wsal-failedlogins-unknown';
	
	protected function GetLoginFailureLogLimit(){
		return 10;
	}
	
	protected function GetLoginFailureExpiration(){
		return 12 * 60 * 60;
	}
	
	protected function IsPastLoginFailureLimit($ip, $site_id, $user){
		$get_fn = $this->IsMultisite() ? 'get_site_transient' : 'get_transient';
		if($user) {
			$dataKnown = $get_fn(self::TRANSIENT_FAILEDLOGINS);
			return ($dataKnown !== false) && isset($dataKnown[$site_id.":".$user->ID.":".$ip]) && ($dataKnown[$site_id.":".$user->ID.":".$ip] > $this->GetLoginFailureLogLimit());
		} else {
			$dataUnknown = $get_fn(self::TRANSIENT_FAILEDLOGINS_UNKNOWN);
			return ($dataUnknown !== false) && isset($dataUnknown[$site_id.":".$ip]) && ($dataUnknown[$site_id.":".$ip] > $this->GetLoginFailureLogLimit());
		}
	}
	
	protected function IncrementLoginFailure($ip, $site_id, $user){
		$get_fn = $this->IsMultisite() ? 'get_site_transient' : 'get_transient';
		$set_fn = $this->IsMultisite() ? 'set_site_transient' : 'set_transient';
		if($user) {
			$dataKnown = $get_fn(self::TRANSIENT_FAILEDLOGINS);
			if(!$dataKnown)$dataKnown = array();
			if(!isset($dataKnown[$site_id.":".$user->ID.":".$ip]))$dataKnown[$site_id.":".$user->ID.":".$ip] = 1;
			$dataKnown[$site_id.":".$user->ID.":".$ip]++;
			$set_fn(self::TRANSIENT_FAILEDLOGINS, $dataKnown, $this->GetLoginFailureExpiration());
		} else {
			$dataUnknown = $get_fn(self::TRANSIENT_FAILEDLOGINS_UNKNOWN);
			if(!$dataUnknown)$dataUnknown = array();
			if(!isset($dataUnknown[$site_id.":".$ip]))$dataUnknown[$site_id.":".$ip] = 1;
			$dataUnknown[$site_id.":".$ip]++;
			$set_fn(self::TRANSIENT_FAILEDLOGINS_UNKNOWN, $dataUnknown, $this->GetLoginFailureExpiration());
		}
	}
	
	public function EventLoginFailure($username){
		
		list($y, $m, $d) = explode('-', date('Y-m-d'));
		
		$ip = $this->plugin->settings->GetMainClientIP();
		$tt1 = new WSAL_DB_Occurrence();
		$tt2 = new WSAL_DB_Meta();
		
		$username = $_POST["log"];
		$newAlertCode = 1003;
		$user = get_user_by('login', $username);
		$site_id = (function_exists('get_current_blog_id') ? get_current_blog_id() : 0);
		if ($user) {
			$newAlertCode = 1002;	
			$userRoles = $this->plugin->settings->GetCurrentUserRoles($user->roles);
		}

		if($this->IsPastLoginFailureLimit($ip, $site_id, $user))return;

		if ($newAlertCode == 1002) {
			if (!$this->plugin->alerts->CheckEnableUserRoles($username, $userRoles))return;
			$occ = WSAL_DB_Occurrence::LoadMultiQuery('
				SELECT occurrence.* FROM `' . $tt1->GetTable() . '` occurrence 
				INNER JOIN `' . $tt2->GetTable() . '` ipMeta on ipMeta.occurrence_id = occurrence.id
				and ipMeta.name = "ClientIP"
				and ipMeta.value = %s
				INNER JOIN `' . $tt2->GetTable() . '` usernameMeta on usernameMeta.occurrence_id = occurrence.id
				and usernameMeta.name = "Username"
				and usernameMeta.value = %s
				WHERE occurrence.alert_id = %d AND occurrence.site_id = %d
				AND (created_on BETWEEN %d AND %d)
				GROUP BY occurrence.id',
				array(
					json_encode($ip),
					json_encode($username),
					1002,
					$site_id,
					mktime(0, 0, 0, $m, $d, $y),
					mktime(0, 0, 0, $m, $d + 1, $y) - 1
				)
			);

			$occ = count($occ) ? $occ[0] : null;
			if($occ && $occ->IsLoaded()){
				// update existing record exists user
				$this->IncrementLoginFailure($ip, $site_id, $user);
				$new = $occ->GetMetaValue('Attempts', 0) + 1;
				
				if($new > $this->GetLoginFailureLogLimit())
					$new = $this->GetLoginFailureLogLimit() . '+';
				
				$occ->SetMetaValue('Attempts', $new);
				$occ->SetMetaValue('Username', $username);
				//$occ->SetMetaValue('CurrentUserRoles', $userRoles);
				$occ->created_on = null;
				$occ->Save();	
			} else {
				// create a new record exists user
				$this->plugin->alerts->Trigger($newAlertCode, array(
					'Attempts' => 1,
					'Username' => $username,
					'CurrentUserRoles' => $userRoles
				));
			} 
		} else {
			$occUnknown = WSAL_DB_Occurrence::LoadMultiQuery('
				SELECT occurrence.* FROM `' . $tt1->GetTable() . '` occurrence 
				INNER JOIN `' . $tt2->GetTable() . '` ipMeta on ipMeta.occurrence_id = occurrence.id 
				and ipMeta.name = "ClientIP" and ipMeta.value = %s 
				WHERE occurrence.alert_id = %d AND occurrence.site_id = %d
				AND (created_on BETWEEN %d AND %d)
				GROUP BY occurrence.id',
				array(
					json_encode($ip),
					1003,
					$site_id,
					mktime(0, 0, 0, $m, $d, $y),
					mktime(0, 0, 0, $m, $d + 1, $y) - 1
				)
			);
				
			$occUnknown = count($occUnknown) ? $occUnknown[0] : null;
			if($occUnknown && $occUnknown->IsLoaded()) {
				// update existing record not exists user
				$this->IncrementLoginFailure($ip, $site_id, false);
				$new = $occUnknown->GetMetaValue('Attempts', 0) + 1;
				
				if($new > $this->GetLoginFailureLogLimit())
					$new = $this->GetLoginFailureLogLimit() . '+';
				
				$occUnknown->SetMetaValue('Attempts', $new);
				$occUnknown->created_on = null;
				$occUnknown->Save();
			} else {
				// create a new record not exists user
				$this->plugin->alerts->Trigger($newAlertCode, array('Attempts' => 1));
			}
		}
	}	
}
