<?php
/**
 * Easily received payments on your website using an existing Stripe account, and the Stripe Checkout system.
 * Users are directed off-site to complete purchases, and upon success or cancel redirected to pre-set pages on your site.
 * This plugin stores NO data from your transactions, other than caching Stripe products and your public API key.
 *
 * @author Freeshifter LLC
 * @url https://www.freeshifter.com
 * @since 1.0.0
 */

namespace Freeshifter;

/**
 * Load the plugin.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', function() {

	require_once( __DIR__ . '/classes/SSC_Admin.php' );

});