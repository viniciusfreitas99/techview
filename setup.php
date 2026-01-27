<?php

define('PLUGIN_TECHVIEW_VERSION', '1.0.2');

function plugin_init_techview() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrfas_compliant']['techview'] = true;

   
   if (class_exists('Session') && Session::getLoginUserID()) {
       
       if (Session::getCurrentInterface() == 'central') {
           
           if (class_exists('PluginTechviewMenu')) {
               
               $PLUGIN_HOOKS['menu_toadd']['techview'] = ['helpdesk' => 'PluginTechviewMenu'];
           }
       }
   }
}

// Versao
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