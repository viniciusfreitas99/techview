<?php

if (!defined('GLPI_ROOT')) { die("Sorry. You can't access this file directly"); }

class PluginTechviewMenu extends CommonGLPI {
    
    static $rightname = 'ticket';

    static function getTypeName($nb = 0) {
        $cur_lang = $_SESSION['glpilanguage'] ?? 'en_GB';
        
        if ($cur_lang === 'pt_BR') {
            return 'Visão do Técnico';
        }
        return 'Tech View';
    }

    static function getMenuName() {
        return self::getTypeName();
    }

    static function getIcon() {
        return "fas fa-user-md"; 
    }

    
    static function getMenuContent() {
       global $CFG_GLPI;
       $menu = [];
       $menu['title'] = self::getTypeName();
       $menu['page']  = $CFG_GLPI['root_doc'] . '/plugins/techview/front/main.php';
       $menu['icon']  = self::getIcon();
       return $menu;
    }

}