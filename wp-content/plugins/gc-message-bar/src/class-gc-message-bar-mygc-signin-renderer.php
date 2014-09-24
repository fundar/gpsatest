<?php
if(class_exists("Gc_Message_Bar_MyGC_Signin_Renderer")){
	return;
}
class Gc_Message_Bar_MyGC_Signin_Renderer extends Gc_MessageBar_Abstract_Renderer{
    protected $connfiguration;
    public function __construct($namespace, $controller){
        parent::__construct($namespace,$controller);
        $this->configuration = Gc_MessageBar_Service_Locator::get("config");
	}
	
	public function render($params) {
        $urlPrefix = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

        ?>
        <script type="text/javascript" src="../wp-includes/js/jquery/jquery.js"></script>
        <link href="<?php echo $urlPrefix;?>fonts.googleapis.com/css?family=Roboto:400,700,300" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo $urlPrefix;?>fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo $urlPrefix;?>fonts.googleapis.com/css?family=Bitter:400,700" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo plugins_url('gc-message-bar/css/mygc-connect.css') ?>" rel="stylesheet" type="text/css" />
        <?php if($params['section'] === 'signin'): ?>
        <div class="loginwrapper">
            <h2>Connect To MY.GetConversion</h2>
            <form action="admin-ajax.php?action=gc-message-bar-mygc-signin" method="POST">
                <?php if(isset($params['failed']) && $params['failed'] === true && $params['error_type'] === 'AUTH_ERROR'): ?>
                <style> body { height:456px; } </style>
                <div class="errmsg error-error">Email or password you have entered is incorrect.</div>
                <?php elseif(isset($params['failed']) && $params['failed'] === true && $params['error_type'] === 'INTERNAL_ERROR'): ?>
                <style> body { height:456px; } </style>
                <div class="errmsg error-error">Internal error. Please try again later.</div>
                <?php elseif(isset($params['failed']) && $params['failed'] === true && $params['error_type'] === 'EMPTY_FORM'): ?>
                <style> body { height:456px; } </style>
                <div class="errmsg error-error">Email & Password field must be filled out!</div>
                <?php endif; ?>
                <label for="email">Your Email:</label>
                <input id="email" name="email" type="text" class="def" value="">
                <label for="passw">Your Password:</label>
                <input id="passw" name="passw" type="password" class="def hasmeta">
                <div class="forgotpass"><a href="<?php echo $this->configuration['MYGC'];?>/forgot-password/" target="_blank">Forgot your password?</a></div>
                <input class="ctabutton" type="submit" value="Sign in to MY.GetConversion">
                <div class="noaccount"><a href="<?php echo $this->configuration['MYGC'];?>/signup/plugin" target="_blank" id="signup">Sign up for FREE account today</a></div>
                <div class="haveproblem">If you're having trouble signing in, shoot us a note<br>to <a href="mailto:support@getconversion.net">support@getconversion.net</a> and we'll help you out!</div>
            </form>
        </div>
        <?php endif; ?>
        <?php if($params['section'] === 'success'): ?>
        <style> body { height:220px; } </style>
        <div class="connectsuccess">
            <form>
                <h3>Successfully connected to MY.GetConversion</h3>
                <input class="ctabutton button-ok" type="button" value="OK">
            </form>
        </div>
        <script>
            jQuery('.button-ok').click(function(){
                parent.jQuery('body').trigger('popBox.close');
                parent.window.location.href='plugins.php?page=<?php echo $params["redirect_to"]; ?>';
            });
        </script>
        <?php endif; ?>
        <?php
	}
}
