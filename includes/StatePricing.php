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
        wp_enqueue_style('boostrap-css',plugins_url('/css/bootstrap.min.css', dirname(__FILE__)),'', '4.0');
		wp_enqueue_style( 'gifted-booking', plugins_url( '/css/main.css', dirname( __FILE__ ) ), '', '1.0.0', '' );
		wp_enqueue_style( 'message-box-css', plugins_url( '/css/messagebox.min.css', dirname( __FILE__ ) ), '', '2.5.4', '' );

		wp_register_script( 'state-pricing', plugins_url( '/js/state-pricing.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'state-pricing', 'pricingAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
		wp_enqueue_script( 'state-pricing' );

        wp_enqueue_script('boostrap-js', plugins_url('/js/jquery-2.2.4.min.js', dirname( __FILE__) ), array('jquery'), '2.2.4');
        wp_enqueue_script('datepicer-js', plugins_url('/js/jquery.datetimepicker.full.min.js', dirname( __FILE__) ), array('jquery'), '2.5.4', true);
        wp_enqueue_script('jqueryui-js', plugins_url('/js/jquery-ui.min.js', dirname( __FILE__) ), array('jquery'), '1.11.4',true);
        wp_enqueue_script('moment-js', plugins_url('/js/moment.min.js', dirname( __FILE__) ), array('jquery'),true);

        wp_enqueue_script('modal-js', plugins_url('/js/bootstrap.min.js', dirname( __FILE__) ), array('jquery'), '4.0.0',true);

	}


	private function add_functionality() {
		new StatePricingAdmin();
		new SimpleXLSX();
		new StatePricingShortCode();
	}

}