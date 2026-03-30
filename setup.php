<?php

if (!defined('GLPI_ROOT')) { die("Sorry. You can't access this file directly"); }

define('PLUGIN_TECHVIEW_VERSION', '1.0.2');


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
      'name'           => 'Visão do Técnico',
      'version'        => PLUGIN_TECHVIEW_VERSION,
      'author'         => 'Vinicius',
      'license'        => 'GPLv2+',
      'requirements'   => [
         'glpi' => [
            'min' => '11.0.0', 
            'max' => '11.0.99'
         ]
      ]
   ];
}