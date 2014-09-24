<?php
if(isset($PATH)){
    $OLD_SAVE_PATH = $PATH;
}

$PATH = dirname(__FILE__).DIRECTORY_SEPARATOR;
require_once( $PATH .'interface-configurable.php');
require_once( $PATH .'interface-storable.php');
require_once( $PATH .'interface-serializable.php');
require_once( $PATH .'interface-testable.php');
require_once( $PATH .'interface-option.php');
require_once( $PATH .'interface-testable.php');
require_once( $PATH .'interface-option-store.php');
require_once( $PATH .'interface-option-meta-data.php');
require_once( $PATH .'interface-option-type-detection.php');
require_once( $PATH .'class-service-locator.php');
require_once( $PATH .'class-option-store.php');
require_once( $PATH .'class-url-filter.php');
require_once( $PATH .'class-instance-option-store.php');
require_once( $PATH .'class-empty-option-store.php');
require_once( $PATH .'class-option.php');
require_once( $PATH .'class-option-namespace.php');
require_once( $PATH .'class-option-repository.php');
require_once( $PATH .'class-multi-option-repository.php');
require_once( $PATH .'class-option-group.php');
require_once( $PATH .'class-option-repository-factory.php');
require_once( $PATH .'class-request.php');
require_once( $PATH .'class-registry.php');
require_once( $PATH .'class-util.php');
require_once( $PATH .'class-cache.php');
require_once( $PATH .'class-wp-cache.php');
require_once( $PATH .'interface-controller.php');

require_once( $PATH .'class-mygetconversion-worker.php');

require_once( $PATH .'event'.DIRECTORY_SEPARATOR.'class-manager.php');

require_once( $PATH .'http'.DIRECTORY_SEPARATOR.'class-http-request.php');



require_once( $PATH .'setting'.DIRECTORY_SEPARATOR.'class-store.php');
require_once( $PATH .'setting'.DIRECTORY_SEPARATOR.'class-repository.php');
require_once( $PATH .'setting'.DIRECTORY_SEPARATOR.'class-resultset.php');
require_once( $PATH .'setting'.DIRECTORY_SEPARATOR.'class-parameter-base.php');
require_once( $PATH .'setting'.DIRECTORY_SEPARATOR.'class-parameter.php');


require_once( $PATH .'api'.DIRECTORY_SEPARATOR.'class-api.php');
require_once( $PATH .'api'.DIRECTORY_SEPARATOR.'class-mygetconversion-api.php');
require_once( $PATH .'api'.DIRECTORY_SEPARATOR.'interface-http-client.php');
require_once( $PATH .'api'.DIRECTORY_SEPARATOR.'class-simple-http-client.php');

require_once( $PATH .'helper'.DIRECTORY_SEPARATOR.'class-lessjs.php');
require_once( $PATH .'helper'.DIRECTORY_SEPARATOR.'class-mygetconversion.php');
require_once( $PATH .'helper'.DIRECTORY_SEPARATOR.'class-metrix.php');

require_once( $PATH .'renderer'.DIRECTORY_SEPARATOR.'class-abstract.php');
require_once( $PATH .'renderer'.DIRECTORY_SEPARATOR.'class-container.php');
require_once( $PATH .'renderer'.DIRECTORY_SEPARATOR.'class-options-group-container.php');
require_once( $PATH .'renderer'.DIRECTORY_SEPARATOR.'class-options-subgroup-container.php');

require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-checkbox.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-radio.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-textarea.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-text.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-color.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-number.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-select.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-fonttype-select.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-slider.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-button-twostate.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-button-onoff.php');
require_once( $PATH .'typerenderer'.DIRECTORY_SEPARATOR.'class-button-darklight.php');


require_once( $PATH .'class-plugin.php');
require_once( $PATH .'class-theme.php');
require_once( $PATH .'class-theme-repository.php');
require_once( $PATH .'class-theme-repository-factory.php');
require_once( $PATH .'interface-remote-action.php');
require_once( $PATH .'interface-remote-action-output.php');
require_once( $PATH .'class-remote-action-handler.php');
require_once( $PATH .'class-remote-action-setoption.php');
require_once( $PATH .'class-empty-remote-action.php');
require_once( $PATH .'class-remote-action-output-json.php');
require_once( $PATH .'class-gccf.php');

if(isset($OLD_SAVE_PATH)){
    $PATH = $OLD_SAVE_PATH;
}