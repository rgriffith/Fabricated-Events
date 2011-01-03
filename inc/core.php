<?php
if (!class_exists('FabricatedEvents')) {
	class FabricatedEvents {
		
		public function FabricatedEvents() {
			
	   	}
	   	
	   	public function createEventPostType() {
	   		// Register the custom post-type.
	   		$args = array(
	   				'labels' => array(
	   				'name' => _x('Events', 'post type general name'),
	   				'singular_name' => _x('Event', 'post type singular name'),
	   				'add_new' => _x('Add New', 'Event'),
	   				'add_new_item' => __('Add New Event'),
	   				'edit_item' => __('Edit Event'),
	   				'new_item' => __('New Event'),
	   				'view_item' => __('View Event'),
	   				'search_items' => __('Search Event'),
	   				'not_found' =>  __('No Events found'),
	   				'not_found_in_trash' => __('No Events found in Trash'),
	   				'parent_item_colon' => ''
	   			),
	   			'public' => true,
	   			'publicly_queryable' => true,
	   			'show_ui' => true,
	   			'query_var' => true,
	   			'rewrite' => true,
	   			'capability_type' => 'post',
	   			'hierarchical' => false,
	   			'menu_position' => 5,
	   			'supports' => array('title','editor','thumbnail','revisions', 'excerpt')
	   		);
	   		register_post_type( 'event', $args );
	   		
	   		// Create custom taxonomies for the post type.
	   		$this->createEventTaxonomies();
	   		
	   		add_action( 'wp_print_scripts', array(&$this,'enqueueScripts') );
	   	}
	   	
	   	public function createEventTaxonomies() {
	   		// Create the calendar taxonomy
	   		$args = array(
		   		'hierarchical' => true,
		   		'labels' => array(
		   			'name' => _x( 'Calendars', 'taxonomy general name' ),
		   			'singular_name' => _x( 'calendar', 'taxonomy singular name' ),
		   			'search_items' =>  __( 'Search Calendars' ),
		   			'all_items' => __( 'All Calendars' ),
		   			'parent_item' => __( 'Parent Calendar' ),
		   			'parent_item_colon' => __( 'Parent Calendar:' ),
		   			'edit_item' => __( 'Edit Calendar' ), 
		   			'update_item' => __( 'Update Calendar' ),
		   			'add_new_item' => __( 'Add New Calendar' ),
		   			'new_item_name' => __( 'New Calendar' ),
		   			'menu_name' => __( 'Calendar' ),
		   		),
		   		'show_ui' => true,
		   		'query_var' => true,
		   		'rewrite' => array( 'slug' => 'calendar' ),
	   		);
	   		register_taxonomy( 'calendar', array('event'), $args );
	   		
	   		// Create a default calendar to start with.
	   		$args = array(
	   			'description' => 'A default calendar. Edit as needed.',
	   			'slug' => 'default'
	   		);
	   		wp_insert_term( 'Default', 'calendar', $args );
	   		
	   		// Create the calendar taxonomy
	   		$args = array(
	   			'hierarchical' => true,
	   			'labels' => array(
	   				'name' => _x( 'Locations', 'taxonomy general name' ),
	   				'singular_name' => _x( 'location', 'taxonomy singular name' ),
	   				'search_items' =>  __( 'Search Locations' ),
	   				'all_items' => __( 'All Locations' ),
	   				'parent_item' => __( 'Parent Location' ),
	   				'parent_item_colon' => __( 'Parent Location:' ),
	   				'edit_item' => __( 'Edit Location' ), 
	   				'update_item' => __( 'Update Location' ),
	   				'add_new_item' => __( 'Add New Location' ),
	   				'new_item_name' => __( 'New Location' ),
	   				'menu_name' => __( 'Location' ),
	   			),
	   			'show_ui' => true,
	   			'query_var' => true,
	   			'rewrite' => array( 'slug' => 'location' ),
	   		);
	   		register_taxonomy( 'location', array('event'), $args );
	   	}
	   	
		public function createEventMetaboxes() {
			add_meta_box( 'fabricatedevents_locinfo', 'Location Information', array(&$this, 'getLocationInfoMetaboxHtml'), 'event' );
		}
		
		public function getLocationInfoMetaboxHtml() {
			// Use nonce for verification
		  	wp_nonce_field( plugin_basename(__FILE__), 'fabricatedevents_noncename' );
			
			
			
		  	// The actual fields for data entry
		  	if ($locTerms = get_terms('location', 'orderby=count&hide_empty=0')) {
		  		echo '<label for="simple-location">' . __("Simple Location", 'fabricatedevents_textdomain' ) . '</label> ';
		  		echo '<select id="simple-location">';
		  		foreach ($locTerms as $loc) {
		  			echo '<option value="'.$loc->slug.'">'.$loc->name.'</option>';
		  		}
		  		echo '</select><br />';
		  	}
		  	
		  	echo $this->_generateDateChooserFields('start-date','Start Date');
		  	echo $this->_generateDateChooserFields('end-date','End Date');
		  	
		  	echo '<label for="geocode">' . __("Geocode", 'fabricatedevents_textdomain' ) . '</label> ';
		  	echo '<input type="text" id="geocode" name="geocode" value="" size="25" />';
		}
		
		private function _generateDateChooserFields($fieldId, $fieldLabel, $autoComplete = true) {
			$output = '<label for="'.$fieldId.'">' . __($fieldLabel, 'fabricatedevents_textdomain' ) . '</label> ';
			
			/*** the current month ***/
			$currentDate = $autoComplete ? date('n') : '';
			$output .= '<select name="'.$fieldId.'[]" id="'.$fieldId.'">';	  	
			for ($i = 1; $i <= 12; $i++) {
				$output .= '<option value="'.$i.'"'.($i==$currentMonth?' selected="selected"':'').'>'.date('M', mktime(0, 0, 0, $i+1, 0, 0, 0)).'</option>';
			}
			$output .= '</select> </label>
				<input type="text" id="'.$fieldId.'-day" name="'.$fieldId.'[]" value="'.($autoComplete?date('d'):'').'" size="3" />, 
				<input type="text" id="'.$fieldId.'-year" name="'.$fieldId.'[]" value="'.($autoComplete?date('Y'):'').'" size="5" /> @ 
				<input type="text" id="'.$fieldId.'-hour" name="'.$fieldId.'[]" value="'.($autoComplete?date('h'):'').'" size="3" /> : 
				<input type="text" id="'.$fieldId.'-min" name="'.$fieldId.'[]" value="'.($autoComplete?date('i'):'').'" size="3" />';
			
			$output .= '</fieldset></td> 
			</tr></table><br />';
			
			return $output;
		}
	   
	} //End Class FabricatedEvents
}
?>