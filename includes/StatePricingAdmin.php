<?php


namespace state_pricing;

class StatePricingAdmin {
	public function __construct() {
		// silence is goldern
		$this->run();
	}

	private function run() {
		$this->add_actions();
    }

    private function add_actions() {
        add_action( 'admin_menu', [$this,'admin_menu'] );
        // add_action( 'admin_enqueue_scripts',[$this, 'register_my_plugin_scripts'] );
    }

    function admin_menu() {
		add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
    }
    
    function load_my_plugin_scripts( $hook ) {

        // Load only on ?page=sample-page
        
        if( $hook != 'toplevel_page_sample-page' ) {
        
            return;
        }
        
    }


    function test_init(){
        echo "<h1>Hello World!</h1>";
}

    public function shortcode_callback() {
		echo "Shorcode: <code><b>[state_pricing]</b></code>";
	}
}