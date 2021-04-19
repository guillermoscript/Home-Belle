<?php
/**
 * @package  Asia Home Shops
 */
namespace App;

defined( 'ABSPATH' ) || exit;

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function getServices()
	{
		return array(
			
			Base\Enqueue::class,
			Base\Shortcode::class,
			Base\BillingController::class,
			// Base\TemplatesCustom::class,
			// Base\WC_Gateway_BACS_Custom::class,
			// Pages\AsiahsAdmin::class,
			// Settings\WordpressConfig::class,
			// Settings\WordpressConfig::class,
			Settings\WoocommerceConfig::class,
		);
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 * @return
	 */
	public static function registerServices()
	{
		foreach (self::getServices() as $class) {
			$service = self::instantiate($class);
			if (method_exists($service, 'register')) {
				$service->register();
			}
			if (method_exists($service, 'get_instance')) {
				add_action( 'plugins_loaded', function () {
					SP_Plugin::get_instance();
				} );
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class    class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function instantiate($class)
	{
		$service = new $class();
		return $service;
	}
}