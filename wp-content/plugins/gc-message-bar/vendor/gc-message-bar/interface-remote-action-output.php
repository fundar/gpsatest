<?php
if(interface_exists('Gc_MessageBar_Remote_Action_Output_Interface')) {
    return;
}

interface Gc_MessageBar_Remote_Action_Output_Interface{
    function format($data);
}