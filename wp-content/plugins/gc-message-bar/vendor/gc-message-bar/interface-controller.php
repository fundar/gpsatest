<?php
if(interface_exists("Gc_MessageBar_Controller_Interface")){
    return;
}

interface Gc_MessageBar_Controller_Interface{
    function initialize();
    function initialize_hooks();
    function handle_request();
    function render();
}
