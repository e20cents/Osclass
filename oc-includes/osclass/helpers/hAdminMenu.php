<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Helper Menu Admin
     * @package OSClass
     * @subpackage Helpers
     * @author OSClass
     */
    
    /**
     * Draws menu with sections and subsections
     */
    function osc_draw_admin_menu_new() 
    {
        $something_selected = false;
        $adminMenu          = AdminMenu::newInstance() ;
        $aMenu              = $adminMenu->get_array_menu() ;
        $current_menu_id    = osc_current_menu();
        $is_moderator       = osc_is_moderator();
        
        // Remove hook admin_menu when osclass 4.0 be released
        // hack, compatibility with menu plugins.
        ob_start(); 
        osc_run_hook('admin_menu') ;
        $plugins_out = ob_get_contents();
        ob_end_clean();
        // -----------------------------------------------------
        
        $sMenu = '<!-- menu -->'.PHP_EOL ;
        $sMenu .= '<div id="sidebar">'.PHP_EOL ;
        $sMenu .= '<ul class="oscmenu">'.PHP_EOL ;
        foreach($aMenu as $key => $value) {
            $sSubmenu   = "";
            $credential = $value[3];
            if(!$is_moderator || $is_moderator && $credential == 'moderator') { // show
                if( array_key_exists('sub', $value) ) {
                    // submenu
                    $aSubmenu = $value['sub'] ;
                    if($aSubmenu) {
                        $sSubmenu .= "<ul>".PHP_EOL;
                        foreach($aSubmenu as $aSub) {
                            $credential_sub = $aSub[4];
                            if(!$is_moderator || $is_moderator && $credential_sub == 'moderator') { // show
                                $sSubmenu .= '<li><a id="'.$aSub[2].'" href="'.$aSub[1].'">'.$aSub[0].'</a></li>'.PHP_EOL ;
                            }   
                        }
                        // hardcoded plugins/themes under menu plugins 
                        if($key == 'plugins') {
                            $sSubmenu .= $plugins_out;
                        }
                        
                        $sSubmenu .= '<li class="arrow"></li>'.PHP_EOL;
                        $sSubmenu .= "</ul>".PHP_EOL;
                    }
                }

                $class = '';
                if($current_menu_id ==  $value[2]) {
                    $class = 'current';
                    $something_selected = true;
                }

                $sMenu .= '<li id="menu_'.$value[2].'" class="'.$class.'">'.PHP_EOL ;
                $sMenu .= '<h3><a id="'.$value[2].'" href="'.$value[1].'">'.$value[0].'</a></h3>'.PHP_EOL ;
                $sMenu .= $sSubmenu;
                $sMenu .= '</li>'.PHP_EOL ;
            }
                
            
        }
//        if(!$is_moderator) {
//            $class = '';
//            if(!$something_selected) $class = 'current';
//            $sMenu .= '<li id="menu_personal" class="'.$class.'">'.PHP_EOL ;
//
//            // Remove hook admin_menu when osclass 4.0 be released
//            // hack, compatibility with menu plugins.
//            ob_start(); 
//            osc_run_hook('admin_menu') ;
//            $plugins_out = ob_get_contents();
//            ob_end_clean();
//            // -----------------------------------------------------
//
//            $sMenu .= $plugins_out.PHP_EOL;
//            $sMenu .= '</li>'.PHP_EOL ;
//        }
        $sMenu .= '</ul>'. PHP_EOL;
        
        $sMenu .= '<div id="show-more">'.PHP_EOL ;
	$sMenu .= '<h3><a id="stats" href="#"><div class="ico ico-48 ico-more"></div>Show more</a></h3>'.PHP_EOL ;
	$sMenu .= '<ul id="hidden-menus">'.PHP_EOL ;
	$sMenu .= '</ul>'.PHP_EOL ;
        $sMenu .= '</div>'.PHP_EOL ;
        
        $sMenu .= '</div>'.PHP_EOL ;
        $sMenu .= '<!-- menu end -->'.PHP_EOL ;
        echo $sMenu;
    }
    
    /**
     * Draws menu with sections and subsections
     */
    function osc_draw_admin_menu() 
    {
        $something_selected = false;
        $adminMenu          = AdminMenu::newInstance() ;
        $aMenu              = $adminMenu->get_array_menu() ;
        $current_menu_id    = osc_current_menu();
        $is_moderator       = osc_is_moderator();
        
        // Remove hook admin_menu when osclass 4.0 be released
        // hack, compatibility with menu plugins.
        ob_start(); 
        osc_run_hook('admin_menu') ;
        $plugins_out = ob_get_contents();
        ob_end_clean();
        
        $sMenu = '<!-- menu -->'.PHP_EOL ;
        $sMenu = '<div id="left-side" class="left">'.PHP_EOL ;
        $sMenu .= '<ul class="oscmenu">'.PHP_EOL ;
        foreach($aMenu as $key => $value) {
            $sSubmenu   = "";
            $credential = $value[3];
            if(!$is_moderator || $is_moderator && $credential == 'moderator') { // show
                if( array_key_exists('sub', $value) ) {
                    // submenu
                    $aSubmenu = $value['sub'] ;
                    if($aSubmenu) {
                        $sSubmenu .= "<ul>".PHP_EOL;
                        foreach($aSubmenu as $aSub) {
                            $credential_sub = $aSub[4];
                            if(!$is_moderator || $is_moderator && $credential_sub == 'moderator') { // show
                                $sSubmenu .= '<li><a id="'.$aSub[2].'" href="'.$aSub[1].'">'.$aSub[0].'</a></li>'.PHP_EOL ;
                            }   
                        }
                        
                        if($key == 'plugins') {
                            $sSubmenu .= $plugins_out;
                        }
                        
                        $sSubmenu .= "</ul>".PHP_EOL;
                    }
                }

                $class = '';
                if($current_menu_id ==  $value[2]) {
                    $class = 'current-menu-item';
                    $something_selected = true;
                }

                $sMenu .= '<li id="menu_'.$value[2].'" class="'.$class.'">'.PHP_EOL ;
                $sMenu .= '<h3><a id="'.$value[2].'" href="'.$value[1].'">'.$value[0].'</a></h3>'.PHP_EOL ;
                $sMenu .= $sSubmenu;
                $sMenu .= '</li>'.PHP_EOL ;
            }
                
            
        }
//        if(!$is_moderator) {
//            $class = '';
//            if(!$something_selected) $class = 'current-menu-item';
//            $sMenu .= '<li id="menu_personal" class="'.$class.'">'.PHP_EOL ;
//
//            // Remove hook admin_menu when osclass 4.0 be released
//            // hack, compatibility with menu plugins.
//            ob_start(); 
//            osc_run_hook('admin_menu') ;
//            $plugins_out = ob_get_contents();
//            ob_end_clean();
//            // -----------------------------------------------------
//
//            $sMenu .= $plugins_out.PHP_EOL;
//            $sMenu .= '</li>'.PHP_EOL ;
//        }
        $sMenu .= '</ul>'. PHP_EOL; 
        $sMenu .= '</div>'.PHP_EOL ;
        $sMenu .= '<!-- menu end -->'.PHP_EOL ;
        echo $sMenu;
    }
    
    /**
     * Add menu entry
     * 
     * @param type $array
     * @param type $id_menu 
     */
    function osc_add_admin_menu_page( $menu_title, $url, $menu_id, $icon_url = null, $capability = null , $position = null )
    {
        AdminMenu::newInstance()->add_menu($menu_title, $url, $menu_id, $icon_url = null, $capability, $position);
    }
    
    /**
     * Remove menu section with id $id_menu
     * @param type $id_menu 
     */
    function osc_remove_admin_menu_page($menu_id)
    {
        AdminMenu::newInstance()->remove_menu( $menu_id ) ;
    }
    
    /**
     * Add submenu under menu id $id_menu, with $array information
     * @param type $array
     * @param type $id_menu 
     */
    function osc_add_admin_submenu_page( $menu_id, $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_submenu( $menu_id, $submenu_title, $url, $submenu_id, $capability, $icon_url ) ;
    }
    
    /**
     * Remove submenu with id $id_submenu under menu id $id_menu
     * 
     * @param type $id_menu
     * @param type $id_submenu 
     */
    function osc_remove_admin_submenu_page( $menu_id, $submenu_id )
    {
        AdminMenu::newInstance()->remove_submenu( $menu_id, $submenu_id ) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_items( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_items( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_categories( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_categories( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_pages( $submenu_title, $url, $submenu_id, $capability = null, $icon_url= null)
    {
        AdminMenu::newInstance()->add_menu_pages( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_appearance( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_appearance( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_plugins( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_plugins( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_settings( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_settings( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_tools( $submenu_title, $url, $submenu_id,$capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_tools( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_users( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_users( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_stats( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_stats( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    function osc_current_menu() {
        $current_menu       = 'dash';
        $something_selected = false;
        $aMenu = AdminMenu::newInstance()->get_array_menu() ;
        
        $url_actual = '?'.$_SERVER['QUERY_STRING'];
        if(preg_match('/(^.*action=\w+)/', $url_actual, $matches)) {
            $url_actual = $matches[1] ;
        } else if(preg_match('/(^.*page=\w+)/', $url_actual, $matches)) {
            $url_actual = $matches[1] ;
        } else if($url_actual == '?') {
            $url_actual = '';
        }
        
        foreach($aMenu as $key => $value) {
            $aMenu_actions = array();
            $url = str_replace(osc_admin_base_url(true) , '', $value[1] ) ;
            $url = str_replace(osc_admin_base_url()     , '', $value[1] ) ;
            array_push($aMenu_actions, $url);
            if( array_key_exists('sub', $value) ) {
                $aSubmenu = $value['sub'] ;
                if($aSubmenu) {
                    foreach($aSubmenu as $aSub) {
                        $url = str_replace(osc_admin_base_url(true), '', $aSub[1] ) ;
                        array_push($aMenu_actions, $url);
                    }
                }
            }
            if(in_array($url_actual , $aMenu_actions)) {
                $something_selected = true;
                $menu_id = $value[2];
            } 
        }
        
        if($something_selected)
            return $menu_id;
        
        // try again without action
        $url_actual = preg_replace('/(&action=.+)/', '', $url_actual);
        foreach($aMenu as $key => $value) {
            $aMenu_actions = array();
            $url = str_replace(osc_admin_base_url(true) , '', $value[1] ) ;
            $url = str_replace(osc_admin_base_url()     , '', $value[1] ) ;

            array_push($aMenu_actions, $url);
            if( array_key_exists('sub', $value) ) {
                $aSubmenu = $value['sub'] ;
                if($aSubmenu) {
                    foreach($aSubmenu as $aSub) {
                        $url = str_replace(osc_admin_base_url(true), '', $aSub[1] ) ;
                        array_push($aMenu_actions, $url);
                    }
                }
            }
            if(in_array($url_actual , $aMenu_actions)) {
                $something_selected = true;
                $menu_id = $value[2];
            } 
        }
        return $menu_id;
    }
?>