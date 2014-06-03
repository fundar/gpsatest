<?php
if(interface_exists('Gc_MessageBar_Option_Type_Detection_Interface')) {    
    return;
}

interface Gc_MessageBar_Option_Type_Detection_Interface{
    function is_text();
    function is_text_area();
    function is_checkbox();
    function is_radio();
    function is_color();
    function is_select();
    function is_number();

}