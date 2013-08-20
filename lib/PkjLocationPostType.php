<?php
class PkjLocationPostType extends PkjCorePostType {
	

	/**
	 * Section: Location, makes
	 * @return multitype:string multitype:multitype:string
	 */
	public function getSectionLocation () {
	
		return array (
				'hooks' => function () {
					add_action('admin_enqueue_scripts', function () {
						wp_enqueue_script( 'google.maps' );
						wp_enqueue_script( 'pkj.admin.location' );
					});
				},
				// Identity of this section.
				'id' => 'location_settings',
				// Label of the Widget area
				'label' => 'Location',
				// Override the standard theme to add google maps integration
				'viewfile' => 'admin/posttype/section_location_settings',
				// Description of the widget area.
				'description' => 'Place the marker or search for location',
				// The fields available.
				'fields' => array (
						// The name of the field. get_post_meta($post->ID, 'address', true) will work here.
						'address' => array (
								// id is used for Admin GUI related things. usually the key + "_f" is good enough.
								'id' => 'address_f',
								'label' => 'Address',
								'type' => 'input'
						),
						'lon' => array (
								'id' => 'longitude_f',
								'label' => 'Longitude',
								'type' => 'input'
						),
						'lat' => array (
								'id' => 'latitude_f',
								'label' => 'Latitude',
								'type' => 'input'
						)
				)
		);
	}
	
	
	
	public function shortcode_map($atts, $content = null) {
		
		$atts = shortcode_atts ( array (
				'taxonomies' => '',
				'ids' => '',
				'width' => '100%',
				'height' => '200px',
				'opt_scrollwheel' => true,
				'opt_streetViewControl' => true,
				'opt_panControl' => true,
				'opt_scaleControl' => true,
				'opt_zoomControl' => true
		), $atts );
		
		$mapOptions = array();
		// Build $mapOptions
		foreach($atts as $key => $val) {
			if (substr($key, 0, 4) == 'opt_') {
				if ($val === 'false') {
					$val = false;
				}
				if ($val === 'true') {
					$val = true;
				}
				
				$mapOptions[substr($key, 4)] = $val;
			}
		}
		
		wp_enqueue_script ( 'google.maps' ); // Enqueue JS script.
		
		$atts['taxonomies'] = array_filter(explode(',', $atts['taxonomies']), function ($item) {
			return trim($item);
		});
		
		$atts['ids'] = array_filter(explode(',', $atts['ids']), function ($item) {
			return trim($item);
		});

		$args=array();
			
		if (!empty($atts['ids'])) {
			$args['post__in'] = $atts['ids'];
		}
		
		
		if (!empty($atts['taxonomies'])) {
			$args['tax_query'] = array(
					array(
							'taxonomy' => 'region',
							'field' => 'slug',
							'terms' => $atts['taxonomies']
					)
			);
		}
		
		$query = new WP_Query( $args );
		
		$locations = array();
		// The Loop
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post = get_post();
				$loc = new stdClass();
				$loc->lat = get_post_meta($post->ID, 'lat', true);
				$loc->lon = get_post_meta($post->ID, 'lon', true);
				$loc->link = get_permalink($post->ID);
				$loc->title = get_the_title($post->ID);
				$locations[] = $loc;
			}
		} else {
			// no posts found
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		
		$id = 'map__'.md5 ( uniqid ( "google_map" ) );
		
		return 
		"<div id='$id' style='width: {$atts['width']}; height: {$atts['height']};'></div>
		<script type='text/javascript'>
		jQuery(function () {
			Pkj.GoogleMapMarkers(document.getElementById('$id'),".json_encode($locations).", ".json_encode($mapOptions).");
		});
		</script>";
	}
	
	
	public function setup() {
		add_shortcode('map', array($this, 'shortcode_map'));
		
		
		
		return array (
				'id' => 'location',
				'meta' => array (
						'labels' => array (
								'name' => 'Locations',
								'singular_name' => 'Location',
								'add_new' => 'Add New',
								'add_new_item' => 'Add New Location',
								'edit' => 'Edit',
								'edit_item' => 'Edit Location',
								'new_item' => 'New Location',
								'view' => 'View',
								'view_item' => 'View Location',
								'search_items' => 'Search Locations',
								'not_found' => 'No Locations found',
								'not_found_in_trash' => 'No Locations found in Trash',
								'parent' => 'Parent Location' 
						),
						
						'public' => true,
						'menu_position' => 15,
						'supports' => array (
								'title',
								'thumbnail'
						),
						'taxonomies' => array (
								'' 
						),
						'has_archive' => true 
				),
				'sections' => array(
						$this->getSectionLocation()
				)
				
		);
	}
	public function after() {
		// Add new "Locations" taxonomy to Posts
		register_taxonomy ( 'region', 'location', array (
				// Hierarchical taxonomy (like categories)
				'hierarchical' => true,
				// This array of options controls the labels displayed in the WordPress Admin UI
				'labels' => array (
						'name' => __('Regions'),
						'singular_name' => __ ( 'Region'),
						'search_items' => __ ( 'Search Regions' ),
						'all_items' => __ ( 'All Regions' ),
						'parent_item' => __ ( 'Parent Region' ),
						'parent_item_colon' => __ ( 'Parent Region:' ),
						'edit_item' => __ ( 'Edit Region' ),
						'update_item' => __ ( 'Update Region' ),
						'add_new_item' => __ ( 'Add New Region' ),
						'new_item_name' => __ ( 'New Region Name' ),
						'menu_name' => __ ( 'Regions' ) 
				),
				// Control the slugs used for this taxonomy
				'rewrite' => array (
						'slug' => 'locations', // This controls the base slug that will display before each term
						'with_front' => false, // Don't display the category base before "/locations/"
						'hierarchical' => true  // This will allow URL's like "/locations/boston/cambridge/"
								) 
		) );
	}
}

?>