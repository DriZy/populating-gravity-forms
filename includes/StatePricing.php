<?php


namespace state_pricing;

class StatePricing {

	public function __construct() {
		// our constructor
	}

	public function run() {
		$this->add_actions();
		$this->add_functionality();
	}

	private function add_actions() {

		add_action( 'wp_enqueue_scripts', [ $this, 'add_scripts' ] );
		add_action( 'admin_notices', [ $this, 'error_notice' ], 10, 1 );
	}

	public function error_notice( $message = '' ) {
		if ( trim( $message ) != '' ):
			?>
            <div class="error notice">
                <p><b>State Pricing: </b><?php echo $message ?></p>
            </div>
		<?php
		endif;
	}

	public function add_scripts() {
		//wp_enqueue_style( 'style-name', get_stylesheet_uri() );
		wp_enqueue_style( 'font-awesome', plugins_url('/css/all.min.css', dirname(__FILE__)),  '', '5.11.2', '' );
		$field_datas = [
            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
            'form_id'    => get_option('form_id_field'),
            'state_field_id'    => get_option('state_field_id'),
            'l_type_field_id'   => get_option('l_type_field_id'),
            'bus_type_field_id' => get_option('bus_type_field_id'),
            'fee_field_id'      => get_option('fee_field_id'),
        ];
		wp_register_script( 'state-pricing', plugins_url( '/js/state-pricing.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'state-pricing', 'pricingAjax', $field_datas);
		wp_enqueue_script( 'state-pricing' );

	}


	private function add_functionality() {
		new StatePricingAdmin();
		new SimpleXLSX();
		new StatePricingShortCode();
	}

}
