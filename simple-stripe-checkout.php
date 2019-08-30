<?php
/**
 * Easily receive payments on your website using an existing Stripe account, and the Stripe Checkout system.
 * Users are directed off-site to complete purchases, and upon success or cancel redirected to pre-set pages on your site.
 * This plugin stores NO data from your transactions, other than caching Stripe products and your public API key.
 *
 * @author  Freeshifter LLC <contact@freeshifter.com>
 * @url     https://www.freeshifter.com
 * @license GPL-3.0+ https://www.gnu.org/licenses/gpl-3.0.en.html
 * @since   1.0.0
 * @package Freeshifter\Init
 */

namespace Freeshifter;

/*
 * Load the plugin.
 */
add_action(
	'plugins_loaded',
	function() {
		require_once __DIR__ . '/classes/SSC_Admin.php';
	}
);
