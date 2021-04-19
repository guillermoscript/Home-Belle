<?php
/**
 * @package Asia Home Shops
 */
namespace App\Pages;

defined( 'ABSPATH' ) || exit;

class AsiahsAdmin
{
    function __construct()
    {}
    
    public function register() {
        add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
    }
    
    public function add_admin_pages() {
        
        global $_wp_last_object_menu;
        
        $_wp_last_object_menu++;

        add_menu_page( 
            'AsiaHS',
            'AsiaHS',
            'administrator',
            'asiahs',
            array( $this, 'admin_index' ),
            // get_stylesheet_directory_uri() . '/admin/assets/img/logo-admin.svg',
            $_wp_last_object_menu
        );
    }

    public function admin_index() {
        require_once plugin_dir_path(dirname( __FILE__, 2 )) . 'template/admin/admin.php';
    }
}