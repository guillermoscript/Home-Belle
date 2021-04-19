<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

use App\Base\EmailReciber;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if(isset($_GET['p'])){                                                  // If accessed via an authentification link
	$data = unserialize(base64_decode($_GET['p']));
	$code = get_user_meta($data['id'], 'activationcode', true);
	$isActivated = get_user_meta($data['id'], 'is_activated', true);    // checks if the account has already been activated. We're doing this to prevent someone from logging in with an outdated confirmation link
	if( !isset($isActivated) && empty($isActivated) || $isActivated ) {                                                // generates an error message if the account was already active
		$message = 'Esta cuenta ya ha sido activada. Inicie sesión con su nombre de usuario y contraseña.';
	}
	else {
		$user_id = $data['id'];                                     // logs the user in
		$user = get_user_by( 'id', $user_id ); 
		if($code == $data['code']){                                     // checks whether the decoded code given is the same as the one in the data base
			update_user_meta($data['id'], 'is_activated', 1);           // updates the database upon successful activation
			if( $user ) {
				wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
				do_action( 'wp_login', $user->user_login, $user );
			}
			$message = 'Tu cuenta ha sido activada! Ha iniciado sesión y ahora puede usar el sitio en toda su extensión.';
			// $wc = new WC_Emails();
			// $wc->customer_new_account($user_id);
			$email = new EmailReciber();
			$email->send_email($user_id);
		} else {
			if($user->roles[0] === 'customer'){
				$message = 'La activación de la cuenta falló. Inténtelo de nuevo en unos minutos o <a href="'.wc_get_page_permalink( 'myaccount' ).'?u='.base64_encode($user_id).'"> Reenvíe el correo electrónico de activación </a>. <br /> Tenga en cuenta que cualquier enlace de activación anteriormente enviados pierden su validez tan pronto como se envía un nuevo correo electrónico de activación. <br /> Si la verificación falla repetidamente, comuníquese con nuestro administrador.';
			}
		}
	}
}

if ($_POST['username'] && $_POST['password']) {

	$creds = array();
	$creds['user_login'] = $_POST['username'];
	$creds['user_password'] = $_POST['password'];
	$creds['remember'] = true;
	$user = wp_signon( $creds, '' );
	if ( is_wp_error($user) )
		$message = 'Nombre de usuario o contraseña equivocada';
}

if(isset($_GET['u'])){
	$user_id = base64_decode($_GET['u']);
	if(empty($user_id)){
		$message = 'Error: no se pudo reenviar correo electrónico.';
	} else {
		my_user_register($user_id, true);
		$message = 'Su correo electrónico de activación ha sido reenviado. Por favor revise su correo electrónico y su carpeta de spam.';
	}
}
if(isset($_GET['n'])){
	$role = base64_decode($_GET['n']);
	if(!empty($role) && $role === 'wholesaler'){
		$message = 'Gracias por crear tu cuenta. Su cuenta se encuentra en proceso de autorización.';
	}
	if(!empty($role) && $role === 'customer'){
		$message = 'Gracias por crear tu cuenta. Deberá confirmar su dirección de correo electrónico para activar su cuenta. Se ha enviado un correo electrónico con el enlace de activación a su dirección de correo electrónico. Si el correo electrónico no llega en unos minutos, revise su carpeta de correo no deseado.';
	}
}
// do_action( 'woocommerce_before_customer_login_form' ); ?>
<div class="login_form">
	<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	<div class="u-columns col2-set" id="customer_login">
		
		<?php if(isset($_REQUEST['signup']) && $_REQUEST['signup'] === 'register'): ?>
		<div class="u-column1 col-1">
		<?php else: ?>
		<div class="u-column1 col-1 active">
		<?php endif; ?>

	<?php endif; ?>

	<?php if( isset($message) && !empty($message) ):?>
	<p style="padding-bottom: 1.25em;"><?php echo  $message  ?></p>
	<?php endif; ?>
	<form class="woocommerce-form woocommerce-form-login login" method="post">
		<h2><?php esc_html_e( 'Ingresa', 'woocommerce' ); ?></h2>

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="Correo electrónico" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="Contraseña" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<p class="form-row form-row-button">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
					</label>
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
				</p>
				<p class="woocommerce-LostPassword lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
				</p>

				<p class="woocommerce-login_register ">
					<a href=""><?php esc_html_e( 'Crea tu cuenta', 'woocommerce' ); ?></a>
				</p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

	<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

		</div>

		<?php if(isset($_REQUEST['signup']) && $_REQUEST['signup'] === 'register'): ?>
		<div class="u-column2 col-2 active">
		<?php else: ?>
		<div class="u-column2 col-2">
		<?php endif; ?>

		
		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
			<h2><?php esc_html_e( 'Complete sus datos', 'woocommerce' ); ?></h2>

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" data-title="correo electrónico" autocomplete="email" placeholder="Correo electrónico" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					<label id="reg_email-error" class="error" for="reg_email"></label>
				</p>

				<!-- <?php
				// global $woocommerce;
				// $countries_obj   = new WC_Countries();
				// $countries   = $countries_obj->__get('countries');
				// woocommerce_form_field('billing_country', array(
				// 	'type'       => 'select',
				// 	'class'      => array( 'form-row-col' ),
				// 	'label'      => __('País/Región'),
				// 	'placeholder'    => __('Enter something'),
				// 	'options'    => $countries
				// 	)
				// );
				?> -->

				<!-- <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_billing_address_1"><?php esc_html_e( 'Dirección de la calle', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_1" id="reg_billing_address_1" placeholder="Número de la casa y nombre de la calle" value="<?php echo ( ! empty( $_POST['billing_address_1'] ) ) ? esc_attr( wp_unslash( $_POST['billing_address_1'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_address_2" id="reg_billing_address_2" placeholder="Apartamento, habitación, etc. (opcional)" value="<?php echo ( ! empty( $_POST['billing_address_2'] ) ) ? esc_attr( wp_unslash( $_POST['billing_address_2'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-first">
					<label for="reg_billing_state"><?php esc_html_e( 'Región / Estado', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_state" id="reg_billing_state" placeholder="Estado" value="<?php echo ( ! empty( $_POST['billing_state'] ) ) ? esc_attr( wp_unslash( $_POST['billing_state'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-last">
					<label for="reg_billing_city"><?php esc_html_e( 'Localidad / Ciudad', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_city" id="reg_billing_city" placeholder="Localidad / Ciudad" value="<?php echo ( ! empty( $_POST['billing_city'] ) ) ? esc_attr( wp_unslash( $_POST['billing_city'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-first">
					<label for="reg_billing_postcode"><?php esc_html_e( 'Código postal', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_postcode" id="reg_billing_postcode" placeholder="Código postal" value="<?php echo ( ! empty( $_POST['billing_postcode'] ) ) ? esc_attr( wp_unslash( $_POST['billing_postcode'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-last">
					<label for="reg_billing_phone"><?php esc_html_e( 'Teléfono', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_billing_phone" placeholder="Teléfono" value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p> -->

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="form-row form-row-first">
						<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" data-title="nombre de usuario" autocomplete="username" placeholder="Usuario" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						<label id="reg_username-error" class="error" for="reg_username"></label>
					</p>

				<?php endif; ?>
				
				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="Contraseña" />
					</p>

				<?php else : ?>

					<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

				<?php endif; ?>

				<?php // do_action( 'woocommerce_register_form' ); ?>
				<?php do_action( 'woocommerce_register_form-child' ); ?>

				<p class="woocommerce-FormRow form-row form-row-button">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" disabled="disabled" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" ><?php esc_html_e( 'Crear Cuenta', 'woocommerce' ); ?></button>
				</p>

				<p class="woocommerce-login_register ">
					<a href=""><?php esc_html_e( 'Ingresar', 'woocommerce' ); ?></a>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

		</div>

	</div>
	<?php endif; ?>
</div>
<!-- <?php do_action( 'woocommerce_after_customer_login_form' ); ?> -->
