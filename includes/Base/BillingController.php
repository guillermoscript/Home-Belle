<?php
/**
 * @package  Asia Home Shops
 */
namespace App\Base;

defined( 'ABSPATH' ) || exit;

class BillingController 
{

    protected $envios_value = [
        '' => 'Selecciona una empresa',
        'envio_1' => 'MRW',
        'envio_2' => 'Domesa',
        'envio_3' => 'Zoom',
        'envio_4' => 'Tealca',
    ];

    protected $country_value = [
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
    ];

    protected $document_value = [
        'option_1' => 'V-',
        'option_2' => 'E-',
        'option_3' => 'J-',
    ];

    protected $retiro_value = [
        '' => 'Selecciona una opcion',
        'retiro_1' => 'Guick',
        'retiro_2' => 'Oficina',
    ];

    public function register()
	{
		add_filter( 'woocommerce_checkout_fields' , array($this,'add_custom_billing_info') ,PHP_INT_MAX);
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this,'display_my_custom_billing_info'), 10, 1 );
        
        // Hook in
        add_filter( 'woocommerce_default_address_fields' , [$this,'custom_override_default_address_fields'] );
        
        // Hook in
        add_filter( 'woocommerce_checkout_fields' , [$this,'custom_override_checkout_fields_xd'] );
        add_filter( 'woocommerce_checkout_fields', [$this,'remove_email'] , PHP_INT_MAX );


        add_filter( 'woocommerce_checkout_fields' , [$this,'custom_override_checkout_fields_ek'], 99 );
        

        add_filter('woocommerce_billing_fields', [$this,'wpb_custom_billing_fields']);

        add_filter( 'woocommerce_order_formatted_billing_address' , [$this,'woo_reorder_billing_fields'], 10, 2 );

        add_action( 'woocommerce_checkout_update_order_meta', [$this,'my_custom_checkout_field_update_order_meta'] );

        add_action( 'woocommerce_admin_order_data_after_billing_address', [$this,'my_custom_checkout_field_display_admin_order_meta'], 10, 1 );

        // add_action('woocommerce_checkout_process', [$this,'my_custom_checkout_field_process']);
    
	}

    public function add_custom_billing_info( $fields )
    {
        # code...
        $fields['billing']['codigo_telefono'] = array(
            // 'placeholder' => _x('', 'placeholder', 'woocommerce'),
            'required'    => true,
            'clear'       => false,
            'type'        => 'select',
            'label' => __(' ', 'woocommerce'),
            'priority'        => 90,
            'class'     => array('form-row-first', 'widto-little'),
            'options'     => $this->country_value
        );
        $fields['billing']['codigo_documento'] = array(
            // 'placeholder' => _x('', 'placeholder', 'woocommerce'),
            'required'    => true,
            'clear'       => false,
            'type'        => 'select',
            'label' => __(' ', 'woocommerce'),
            'priority'        => 100,
            'class'     => array('form-row-first', 'widto-little'),
            'options'     => $this->document_value
        );    
        return $fields;
    }

    public function display_my_custom_billing_info($order){

        echo '<p id ="as"><strong>'.__('Telefono').':</strong> ' . $this->country_value[get_post_meta( $order->get_id(), 'codigo_telefono', true )] . '</p>';
 
        if ($this->envios_value[get_post_meta( $order->get_id(), 'envios', true )] !== 'Selecciona una empresa') {
            echo '<p id ="rew"><strong>'.__('Empresa de envio').':</strong> ' . $this->envios_value[get_post_meta( $order->get_id(), 'envios', true )] . '</p>';
        }

        echo '<p><strong>'.__('Codigo Documento').':</strong> <br/>' . $this->document_value[get_post_meta( $order->get_id(), 'codigo_documento', true )] . '</p>';

        if ($this->envios_value[get_post_meta( $order->get_id(), 'envios', true )] !== 'Selecciona una opcion') {
            echo '<p><strong>'.__('Retiro Por').':</strong> <br/>' . $this->retiro_value[get_post_meta( $order->get_id(), 'retiro', true )] . '</p>';
        }
        if ( get_post_meta( $order->get_id(), 'retiro', true ) ) {

            $retiro_value = [
                '' => 'Selecciona una opcion',
                'retiro_1' => 'Guick',
                'retiro_2' => 'Oficina',
            ];  
            $fees = array(
                'Barcelona' => 3,
                'Lechería' => 2,
                'Guanta' => 5,
                'Puerto La Cruz' => 3,
            );
            if ($retiro_value[get_post_meta( $order->get_id(), 'retiro', true )] === 'Guick' ) {
                ?>
                    <p>
                        <span><?php echo ( 'Extra Por Envio Guick: a ' . $order->get_billing_city() ); ?></span>
                        <span><?php echo  '$' . $fees[$order->get_billing_city()]; ?></span>
                    </p>
                <?php
            }
        }
    }


    // Our hooked in function - $fields is passed via the filter!
    function custom_override_checkout_fields_xd( $fields ) {
        // $fields['billing']['billing_company']['class'][0] = 'form-row-first';
        $fields['billing']['billing_country']['class'][1] = 'form-row-first';
        $fields['billing']['billing_phone']['class'][2] = 'form-row-first';
        $fields['billing']['billing_email']['class'][1] = 'form-row-last';
        $fields['billing']['billing_email']['priority'] = 130 ;

        $fields['billing']['envios'] = array(
            'required'    => false,
            'clear'       => false,
            'type'        => 'select',
            'label'       => __('Compañias de envio (Cobro a destino)', 'woocommerce'),
            'priority'    => 140,
            'class'       => array('form-row-wide', 'envios','none'),
            'options'     => $this->envios_value
        );   

        $fields['billing']['retiro'] = array(
            'required'    => false,
            'clear'       => false,
            'type'        => 'select',
            'label'       => __('Retiro por', 'woocommerce'),
            'priority'    => 140,
            'class'       => array('form-row-wide', 'retiro','none'),
            'options'     => $this->retiro_value
        );   
        
        // $fields['shipping']['shipping_company']['class'][0] = 'form-row-first';
        $fields['shipping']['shipping_country']['class'][0] = 'form-row-first';
        $fields['shipping']['shipping_address_1']['class'][0] = 'form-row-first';
        $fields['shipping']['shipping_city']['class'][1] = 'form-row-first';
        $fields['shipping']['shipping_state']['class'][1] = 'form-row-last';

        return $fields;
    }


    // Our hooked in function - $address_fields is passed via the filter!
    function custom_override_default_address_fields( $address_fields ) {
        $address_fields['address_1']['class'][0] = 'form-row-last';

        return $address_fields;
    }

    // remove some fields from billing form
    // ref - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
    function wpb_custom_billing_fields( $fields = array() ) {

        unset($fields['billing_company']);
        return $fields;
    }

    /**
    * This function is used for remove email field from the checkout
    * 
    * @name _custom_checkout_fields
    * @param array $address_fields  array of the address fields
    */
    function remove_email( $address_fields ) {
        if( is_user_logged_in() ) {
            unset( $address_fields['billing']['billing_email'] );
        }
        return $address_fields;
    }
   // Remove some fields from billing form
        // Our hooked in function - $fields is passed via the filter!
        // Get all the fields - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
    function custom_override_checkout_fields_ek( $fields ) {
        unset($fields['shipping']['shipping_company']);
        unset($fields['shipping']['shipping_address_2']);
        unset($fields['shipping']['shipping_postcode']);
        return $fields;
    }

    function woo_reorder_billing_fields( $address, $wc_order ) {

        $address = array(
            'first_name'    => $wc_order->billing_first_name,
            'last_name'     => $wc_order->billing_last_name,
            'company'       => $wc_order->billing_company,
            'country'       => $wc_order->billing_country,
            'state'         => $wc_order->billing_state,
            'city'          => $wc_order->billing_city,
        );

        return $address;
    }


    /**
     * Update the order meta with field value
     */

    function my_custom_checkout_field_update_order_meta( $order_id ) {
        if ( ! empty( $_POST['envios'] ) ) {
            update_post_meta( $order_id, 'envios', sanitize_text_field( $_POST['envios'] ) );
        }
        if ( ! empty( $_POST['retiro'] ) ) {
            update_post_meta( $order_id, 'retiro', sanitize_text_field( $_POST['retiro'] ) );
        }
        if ( ! empty( $_POST['codigo_telefono'] ) ) {
            update_post_meta( $order_id, 'codigo_telefono', sanitize_text_field( $_POST['codigo_telefono'] ) );
        }
        if ( ! empty( $_POST['codigo_documento'] ) ) {
            update_post_meta( $order_id, 'codigo_documento', sanitize_text_field( $_POST['codigo_documento'] ) );
        }
    }

    // function my_custom_checkout_field_process() {
    //     // Check if set, if its not set add an error.
    //     if ( ! $_POST['envios'] )
    //         wc_add_notice( __( 'Phone 2 is compulsory. Please enter a value' ), 'error' );
    // }

    // /**
    //  * Display field value on the order edit page
    //  */

    // function my_custom_checkout_field_display_admin_order_meta($order){
    //     echo '<p><strong>'.__('Codigo Telefono').':</strong> <br/>' . $this->envios_value[get_post_meta( $order->get_id(), 'envios', true )] . '</p>';
    //     echo '<p><strong>'.__('Codigo Documento').':</strong> <br/>' . $this->envios_value[get_post_meta( $order->get_id(), 'codigo_documento', true )] . '</p>';
    //     echo '<p><strong>'.__('Cedula').':</strong> <br/>' . $this->envios_value[get_post_meta( $order->get_id(), 'retiro', true )] . '</p>';
    // }
}