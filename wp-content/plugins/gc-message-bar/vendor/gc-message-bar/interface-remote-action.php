<?php
if(interface_exists('Gc_MessageBar_Remote_Action_Interface')) {
    return;
}

interface Gc_MessageBar_Remote_Action_Interface{
    function execute($request);
}