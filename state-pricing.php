<?php
/**
 * Plugin Name: State Pricing
 * Plugin URI: #
 * Description: For auto filling fee information on a client site. Shortcode to use is [state_pricing]
 * Version: 1.0
 * Author: Tabi Idris & Akombo Neville
 * Author URI: https://github.com/DriZy
 */

namespace state_pricing;

defined( 'ABSPATH' ) or die( 'Giving To Cesar What Belongs To Caesar' );

$error = false;

function kmgt_error_notice( $message = '' ) {
	if ( trim( $message ) != '' ):
		?>
        <div class="error notice is-dismissible">
            <p><b>State Pricing: </b><?php echo  $message ?></p>
        </div>
	<?php
	endif;
}

add_action( 'admin_notices', 'state_pricing\\kmgt_error_notice', 10, 1 );

// loads classes / files
function kmgt_loader() {
	global $error;
	$classes = array(
		'StatePricingAdmin.php', //
		'Simplexlsx.php',//
		'StatePricing.php', //
		'StatePricingShortCode.php', //
	);

	foreach ( $classes as $file ) {
		if ( ! $filepath = file_exists( plugin_dir_path( __FILE__ ) . "includes/" . $file ) ) {
			kmgt_error_notice( sprintf( __( 'Error locating <b>%s</b> for inclusion', 'kmgt' ), $file ) );
			$error = true;
		} else {
			include_once plugin_dir_path( __FILE__ ) . "includes/" . $file;
		}
	}
}

function kmgt_start_state_pricing() {
	$state_pricing = new StatePricing();
	$state_pricing->run();
}


kmgt_loader();
if ( ! $error ) {
	kmgt_start_state_pricing();
}


// remove options upon deactivation

register_deactivation_hook( __FILE__, 'kmgt_deactivation' );

function kmgt_deactivation() {
	// set options to remove here
}

// todo: for future use
load_plugin_textdomain( 'kmgt', false, basename( dirname( __FILE__ ) ) . '/languages' );

//ajaxcall
