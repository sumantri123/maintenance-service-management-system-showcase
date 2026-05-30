<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

if (! function_exists('hasPermission')) {
    
    function hasPermission($moduleSlug, $action, $role)
    {
		
        $modul = DB::table('m_submenu')->where('submenu_link', $moduleSlug)->first();
        if (!$modul) {
            return false;
        }

        $permission = DB::table('m_submenu_det')
            ->where('id_role', $role)
            ->where('id_sub_menu', $modul->submenu_id)
            ->first();

        if (!$permission) {
            return false;
        }
        
        return isset($permission->{$action}) && $permission->{$action} == 1;
    }
}
