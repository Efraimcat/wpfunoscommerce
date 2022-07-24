<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://efraim.cat
* @since      1.0.0
*
* @package    Wpfunoscommerce
* @subpackage Wpfunoscommerce/public
*/
class Wpfc_Paycomet {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode( 'wpfc-compra', array( $this, 'wpfcComprarProductoShortcode' ));
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpfc-paycomet.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpfc-paycomet.js', array( 'jquery' ), $this->version, false );
	}

	/**
	* Shortcode [wpfc-compra producto="84089"]
	* /finalizar-compra?add-to-cart=84089
	*
	* Redirigir a finaliza compra
	*/
	public function wpfcComprarProductoShortcode( $atts, $content = "" ) {
		$a = shortcode_atts( array(
			'producto'=>'',
		), $atts );
		global $woocommerce;
		$woocommerce->cart->empty_cart();
		$wpf_checkout = $woocommerce->cart->get_checkout_url();
		return $wpf_checkout.'?add-to-cart='.$a['producto'];
	}



}
