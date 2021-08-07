<?php
/**
 * @package  Asia Home Shops
 */
namespace App\Base;

defined( 'ABSPATH' ) || exit;

class BillingController 
{
    public function register()
	{

    }

    public function get_google_sheet_data($query = '')
    {
        # code...
        $result = false;
        $key = "";
        $sheet = '';
        $url = '';
        $connection = wp_remote_get( $url, $args );
        if (! is_wp_error( $connection )) {
            $connection = json_decode(wp_remote_retrieve_body( $connection ),true);

            if (isset($connection['values'])) {
                $result = $connection['values'];
            }
        }

        return $result;
    }
}