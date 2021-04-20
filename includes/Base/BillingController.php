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
		add_filter( 'woocommerce_checkout_fields' , array($this,'add_custom_billing_info') ,PHP_INT_MAX);
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this,'display_my_custom_billing_info'), 10, 1 );
	}

    public function add_custom_billing_info( $fields )
    {
        # code...
        $fields['billing']['codigo_telefono'] = array(
            // 'placeholder' => _x('', 'placeholder', 'woocommerce'),
            'required'    => true,
            'clear'       => false,
            'type'        => 'select',
            'label' => __('Codigo Telefono', 'woocommerce'),
            'priority'        => 90,
            'class'     => array('form-row-first', 'widto-little'),
            'options'     => array(
                "option52" => "🇻🇪+58",
                "option0" => "🇨🇦+1",
                "option1" => "🇺🇸+1",
                "option2" => "🇷🇺+7",
                "option3" => "🇪🇬+20",
                "option4" => "🇿🇦+27",
                "option5" => "🇬🇷+30",
                "option6" => "🇳🇱+31",
                "option7" => "🇧🇪+32",
                "option8" => "🇫🇷+33",
                "option9" => "🇪🇸+34",
                "option10" => "🇭🇺+36",
                "option11" => "🇮🇹+39",
                "option12" => "🇷🇴+40",
                "option13" => "🇨🇭+41",
                "option14" => "🇦🇹+43",
                "option15" => "🇬🇧+44",
                "option16" => "🇬🇬+44",
                "option17" => "🇮🇲+44",
                "option18" => "🇯🇪+44",
                "option19" => "🇩🇰+45",
                "option20" => "🇸🇪+46",
                "option21" => "🇳🇴+47",
                "option22" => "🇸🇯+47",
                "option23" => "🇵🇱+48",
                "option24" => "🇩🇪+49",
                "option25" => "🇵🇪+51",
                "option26" => "🇲🇽+52",
                "option27" => "🇨🇺+53",
                "option28" => "🇦🇷+54",
                "option29" => "🇧🇷+55",
                "option30" => "🇨🇱+56",
                "option31" => "🇨🇴+57",
                "option32" => "🇲🇾+60",
                "option33" => "🇦🇺+61",
                "option34" => "🇨🇨+61",
                "option35" => "🇨🇽+61",
                "option36" => "🇮🇩+62",
                "option37" => "🇵🇭+63",
                "option38" => "🇳🇿+64",
                "option39" => "🇸🇬+65",
                "option40" => "🇹🇭+66",
                "option41" => "🇯🇵+81",
                "option42" => "🇰🇷+82",
                "option43" => "🇻🇳+84",
                "option44" => "🇨🇳+86",
                "option45" => "🇹🇷+90",
                "option46" => "🇮🇳+91",
                "option47" => "🇵🇰+92",
                "option48" => "🇦🇫+93",
                "option49" => "🇱🇰+94",
                "option50" => "🇲🇲+95",
                "option51" => "🇮🇷+98",
            )
        );
        $fields['billing']['codigo_documento'] = array(
            // 'placeholder' => _x('', 'placeholder', 'woocommerce'),
            'required'    => true,
            'clear'       => false,
            'type'        => 'select',
            'label' => __('Codigo Documento', 'woocommerce'),
            'priority'        => 100,
            'class'     => array('form-row-first', 'widto-little'),
            'options'     => array(
                'option_1' => 'V-',
                'option_2' => 'E-',
                'option_3' => 'J-',
            )
        );    
        return $fields;
    }

    public function display_my_custom_billing_info($order){
        echo '<p><strong>'.__('Phone From Checkout Form').':</strong> ' . get_post_meta( $order->get_id(), '_codigo_telefono', true ) . '</p>';
    }
}