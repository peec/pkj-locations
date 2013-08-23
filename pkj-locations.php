<?php
/**
 * 
 * Easily integrate Google Maps V3 to your app, add location based data!
 * 
 * @package   PKJ - Locations
 * @author    Petter Kjelkenes <kjelkenes@gmail.com>
 * @license   LGPL
 * @link      http://pkj.no
 * @copyright 2013 Petter Kjelkenes ENK
 *
 * @wordpress-plugin
 * Plugin Name: PKJ - Locations
 * Plugin URI:  http://pkj.no/plugins/pkj-locations
 * Description: Easily integrate google maps integration and easily add locations to your data.
 * Version:     1.0.0
 * Author:      Petter Kjelkenes ENK
 * Author URI:  http://pkj.no
 * Text Domain: PkjLocation
 * License:     LGPL
 */


add_filter( 'pkj-base-loaded', function () {
	$name = "PKJ - Locations";
	$ns = 'PkjLocation';
	$dependencies =array('PkjCore');
	
	// -- Bootstrap --
	if (class_exists('PkjCore')) {
		require dirname(__FILE__) . "/lib/$ns.php";
		$pkjCore = PkjCore::getInstance();
		$pkjCore->registerChild(new $ns(
				__DIR__,
				$ns,
				// Dependencies
				$dependencies
		));
	} else {
		add_action( 'admin_notices', function () use ($name) {
			echo sprintf('<div class="error"><p>PKJ - Core plugin is needed for %s</p></div>', $name);
		});
	}
});