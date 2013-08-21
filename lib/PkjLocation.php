<?php
class PkjLocation extends PkjCoreChild{
	
	public function register_scripts () {
		wp_register_script('google.maps', 'https://maps.googleapis.com/maps/api/js?sensor=false');
		wp_register_script( 'pkj.admin.location', $this->plugins_url( '/js/admin.location.js' ), array('jquery', 'google.maps'));
		wp_register_script( 'pkj.site.location', $this->plugins_url( '/js/site.location.js'), array('jquery', 'google.maps'));
	}
	
	public function setup () {

		add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		
		$this->registerPostType(new PkjLocationPostType());
	}
	
	
	
}