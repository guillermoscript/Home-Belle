<?php
/**
 * @package  Asia Home Shops
 */
namespace App\Base;

defined( 'ABSPATH' ) || exit;

class Shortcode
{
	public function __construct()
    {
        add_action( 'init', array($this, 'add_shortcodes') );
    }

    public function add_shortcodes()
    {
        add_shortcode( 'home', array($this, 'home_landing') );
        add_shortcode( 'print_notices', array($this, 'print_notices') );
    }
    
    public function home_landing()
    {
        require_once(dirname( __FILE__, 3) . '/template/home.php');

        return home_shortcode();
    }

    public function print_notices()
    {
        echo wc_print_notices();
    }
}