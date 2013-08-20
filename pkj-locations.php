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



require dirname(__FILE__) . '/lib/PkjLocation.php';

$pkjCore = PkjCore::getInstance();

$pkjCore->registerChild(new PkjLocation(
		__DIR__, 
		'PkjLocation',
		// Dependencies
		array('PkjCore')		
));

