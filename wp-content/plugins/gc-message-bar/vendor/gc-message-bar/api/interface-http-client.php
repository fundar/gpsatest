<?php
if(interface_exists('Gc_MessageBar_HTTP_CLient_Interface')) {
    return;
}
interface Gc_MessageBar_HTTP_CLient_Interface{
    function set_endpoint($url);
    function set_action($cmd);
    function set_parameters($parametes);
    function get_response();
}

