<?php
class PkjLocation extends PkjCoreChild{
	
	public function register_scripts () {
		wp_register_script('google.maps', 'https://maps.googleapis.com/maps/api/js?sensor=false');
		wp_register_script( 'pkj.admin.location', get_template_directory_uri() . '/js/admin.location.js', array('google.maps'));
	}
	
	public function setup () {

		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		
		$this->registerPostType(new PkjLocationPostType());
	}
	
	
	
}