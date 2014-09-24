<?php
/*
Plugin Name: GC Message Bar
Version: 2.3.8
Plugin URI: http://wordpress.org/plugins/gc-message-bar
Description: GC Message Bar is an easy to use plugin that allows you to place a sticky message and a Call To Action button to the top or bottom of your website
Author: GetConversion
Author URI: http://getconversion.com
License: GPL2
*/

/*  Copyright 2014 eRise Hungary Ltd.  (email : info@getconversion.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


require_once( plugin_dir_path( __FILE__ ) . 'default.php');
require_once( plugin_dir_path( __FILE__ ) . 'init-constants.php');
require_once( plugin_dir_path( __FILE__ ) . 'init-gc.php');
require_once( plugin_dir_path( __FILE__ ) . 'init-options.php');
require_once( plugin_dir_path( __FILE__ ) . 'admin-layout-gc-message-bar.php');
require_once( plugin_dir_path( __FILE__ ) . 'init-message-bar.php');

Gc_MessageBar_Util::initialize(__FILE__,GC_MESSAGE_BAR_NAME,GC_MESSAGE_BAR_TYPE);

$p = Gc_MessageBar_CF::create("Plugin");

$p->add_admin_controller(new Gc_Message_Bar_Admin_Controller(GC_MESSAGE_BAR_NS))
       ->add_controller(new Gc_Message_Bar_Controller(GC_MESSAGE_BAR_NS))
       ->run();