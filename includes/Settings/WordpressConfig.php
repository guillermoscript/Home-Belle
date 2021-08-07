<?php
/**
 * @package  Asia Home Shops
 */
namespace App\Settings;

defined( 'ABSPATH' ) || exit;

class WordpressConfig
{
	public function __construct()
    {
        // remove_action( 'woocommerce_before_customer_login_form', array('OceanWP_WooCommerce_Config', 'oceanwp_login_wrap_before' ));
        
        // add_action( 'template_redirect', array($this, 'is404') );
        add_action( 'wp_logout',         array($this, 'logout_redirect'), 11);
        add_action( 'phpmailer_init',    array($this, 'send_smtp_email') );

        // add_action( 'comment_post',      array($this, 'add_custom_comment_field') );

        // add_role( 'wholesaler', __( 'Wholesaler' ), array( 'read' => true, ));
    }

    // public function is404()
    // {
    //     if ( is_404() ) {
    //         wp_redirect( home_url() ); exit;
    //     }
    // }

    public function logout_redirect()
    {
        wp_redirect( home_url($_SERVER['REQUEST_URI']) );
        exit;
    }

    // public function add_custom_comment_field( $comment_id ) {
    //     add_comment_meta( $comment_id, 'order_id', $_POST['order_id'] );
    // }

    public function send_smtp_email( $phpmailer ) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = SMTP_HOST;
        $phpmailer->SMTPAuth   = SMTP_AUTH;
        $phpmailer->Port       = SMTP_PORT;
        $phpmailer->SMTPSecure = SMTP_SECURE;
        $phpmailer->Username   = SMTP_USERNAME;
        $phpmailer->Password   = SMTP_PASSWORD;
        $phpmailer->From       = SMTP_FROM;
        $phpmailer->FromName   = SMTP_FROMNAME;
    }
}