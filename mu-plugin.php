<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load Underpin

$underpin = plugin_dir_path( __FILE__ ) . 'vendor/alexstandiford/underpin/Underpin.php';

if ( file_exists( $underpin ) ) {
	require_once( $underpin );
}