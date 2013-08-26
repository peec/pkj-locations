<?php
class PkjLocation extends PkjCoreChild{
	
	public function setup () {
		
		register_post_type('location', array (
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
				));
		
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
		PkjCore::getInstance()->registerMetaBox('locationmeta', 'Location');
		PkjCore::getInstance()->registerPostField('gmaplocation', 'location', 'location', array(), 'locationmeta');
		add_shortcode('map', array($this, 'shortcode_map'));
		
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
		wp_enqueue_script( 'pkj.site.location' );
	
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
	
				PkjCore::getInstance()->js->add("
				<script type='text/javascript'>
				jQuery(function () {
				Pkj.GoogleMapMarkers(document.getElementById('$id'),".json_encode($locations).", ".json_encode($mapOptions).");
		});
		</script>");
	
				return
				"<div id='$id' style='width: {$atts['width']}; height: {$atts['height']};'></div>
				";
	
	
	}
	
	
	
}