<?php

if (!defined('GLPI_ROOT')) { die("Sorry. You can't access this file directly"); }

define('PLUGIN_TECHVIEW_VERSION', '1.1.4');


function plugin_techview_check_prerequisites() { return true; }
function plugin_techview_check_config() { return true; }

function plugin_init_techview() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['techview'] = true;

   if (class_exists('Session') && Session::getLoginUserID()) {
       if (Session::getCurrentInterface() == 'central') {
           $PLUGIN_HOOKS['menu_toadd']['techview'] = ['helpdesk' => 'PluginTechviewMenu'];
       }
   }
}

function plugin_version_techview() {
   return [
      'name'           => 'TechView',
      'version'        => PLUGIN_TECHVIEW_VERSION,
      'author'         => 'VGF',
      'license'        => 'GPLv3+',
      'requirements'   => [
         'glpi' => [
            'min' => '11.0.0', 
            'max' => '11.0.99'
         ]
      ]
   ];
}