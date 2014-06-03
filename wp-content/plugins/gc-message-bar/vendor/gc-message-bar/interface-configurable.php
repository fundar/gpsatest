<?php
if(interface_exists('Gc_MessageBar_Configurable_Interface')) {
    return;
}

interface Gc_MessageBar_Configurable_Interface{
    function configure(array $options);

}


