<?php
if (!class_exists('FabricatedEvents')) {
	class FabricatedEvents {
		
		public function FabricatedEvents() {
			// Create custom post type (with custom metaboxes) and associated taxonomies.
			add_action('init', array(&$this, 'createEventPostType'));
			add_action('admin_init', array(&$this, 'createEventMetaboxes'));
			
			// Create admin options.
			if (is_admin()) {
				$fe_options = new FEOptions();
			}
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
	   			'menu_icon' => FABRICATEDEVENTS_PLUGIN_URL.'/img/menu.png',
	   			'menu_position' => 5,
	   			'supports' => array('title','editor','thumbnail','revisions', 'excerpt')
	   		);
	   		register_post_type( 'event', $args );
	   		
	   		// Create custom taxonomies for the post type.
	   		$this->createEventTaxonomies();
	   		
	   		// Add action so we can save the custom post fields.
	   		add_action('save_post', array(&$this, 'saveFabricatedEventPostData'));
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
			add_meta_box( 'fabricatedevents_locinfo', 'Additional Information', array(&$this, 'getAdditionalInfoMetaboxHtml'), 'event' );
		}
		
		public function getAdditionalInfoMetaboxHtml() {
			global $post;
			
			$post_meta = get_post_meta($post->ID, 'fabricated_event', true);
		
			// Use nonce for verification
		  	wp_nonce_field( FABRICATEDEVENTS_PLUGIN_URL, 'fabricatedevents_noncename' );
<<<<<<< HEAD
			
			?><h4>Time &amp; Date</h4>
			<table class="form-table"> 
				<tr>
					<th scope="row"><label for="start-date"><?php echo _e('Start Date');?></label></th>
					<td><?php echo $this->_generateDateChooserFields('fabricated_event[start-date]', $post_meta['start-date']);?></td>
				</tr>
				<tr>
					<th scope="row"><label for="end-date"><?php echo _e('End Date');?></label></th>
					<td><?php echo $this->_generateDateChooserFields('fabricated_event[end-date]', $post_meta['end-date']);?></td>
				</tr>
			</table>
			<h4>Event URL</h4>
			<table class="form-table"> 
				<tr>
					<th scope="row"><label for="url"><?php echo _e('URL');?></label></th>
					<td><input type="text" name="fabricated_event[url]" size="35" value="<?php echo (!empty($post_meta['url']) && $post_meta['url'] != 'http://' ? $post_meta['url'] : 'http://');?>" /> (e.g. http://www.website.com)</td>
				</tr>
			</table>
			<h4>Contact Information</h4>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="contact-name"><?=_e('Name');?></label></th>
					<td><input type="text" name="fabricated_event[contact-name]" size="35" value="<?php echo (!empty($post_meta['contact-name']) ? $post_meta['contact-name'] : '');?>" /> (e.g. John Doe)</td>
				</tr>
				<tr>
					<th scope="row"><label for="contact-phone"><?=_e('Phone');?></label></th>
					<td>(<input type="text" name="fabricated_event[contact-phone][0]" size="2" value="<?php echo (!empty($post_meta['contact-phone'][0]) ? $post_meta['contact-phone'][0] : '');?>" />) <input type="text" name="fabricated_event[contact-phone][1]" size="2" value="<?php echo (!empty($post_meta['contact-phone'][1]) ? $post_meta['contact-phone'][1] : '');?>" />-<input size="3"  type="text" name="fabricated_event[contact-phone][2]" value="<?php echo (!empty($post_meta['contact-phone'][2]) ? $post_meta['contact-phone'][2] : '');?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="contact-email"><?=_e('Email');?></label></th>
					<td><input type="text" name="fabricated_event[contact-email]" size="35" value="<?php echo (!empty($post_meta['contact-email']) ? $post_meta['contact-email'] : '');?>" /> (e.g. sample@email.com)</td>
				</tr>
			</table><?
		  	
		}
		
		function saveFabricatedEventPostData($post_id) {
			// Verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times.
			if ( !wp_verify_nonce( $_POST['fabricatedevents_noncename'], FABRICATEDEVENTS_PLUGIN_URL )) {
				return $post_id;
			}
			
			// Verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
			// to do anything.
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
				return $post_id;
			}
			
			// Check permissions
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
				  return $post_id;
				}
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) ) {
				  return $post_id;
				}
			}
			
			// Convert the dates to timestamps;
			$date = $_POST['fabricated_event']['start-date'];
			
			die(var_dump($_POST['fabricated_event']['start-date']));
			
			$_POST['fabricated_event']['start-date'] = mktime($date['hours'],$date['minutes'],0,$date['mon'],$date['day'],$date['year']);
			
			$date = $_POST['fabricated_event']['end-date'];
			$_POST['fabricated_event']['end-date'] = mktime($date['hours'],$date['minutes'],0,$date['mon'],$date['day'],$date['year']);
			
			// Save the data.
			update_post_meta($post_id, 'fabricated_event', $_POST['fabricated_event']);
			
			return $mydata;
		}
		
		private function _generateDateChooserFields($fieldId, $values = null) {
			$output = '';
			
			if ($values != null) {
				$currentDate = getdate($values);
			} else {
				$currentDate = getdate();
			}
			
			$output .= '<select name="'.$fieldId.'[mon]" id="'.$fieldId.'">';	  	
			for ($i = 1; $i <= 12; $i++) {
=======
			
			?><h4>Time &amp; Date</h4>
			<table class="form-table"> 
				<tr>
					<th scope="row"><label for="start-date"><?php echo _e('Start Date');?></label></th>
					<td><?php echo $this->_generateDateChooserFields('fabricated_event[start-date]', $post_meta['start-date']);?></td>
				</tr>
				<tr>
					<th scope="row"><label for="end-date"><?php echo _e('End Date');?></label></th>
					<td><?php echo $this->_generateDateChooserFields('fabricated_event[end-date]', $post_meta['end-date']);?></td>
				</tr>
			</table>
			<h4>Event URL</h4>
			<table class="form-table"> 
				<tr>
					<th scope="row"><label for="url"><?php echo _e('URL');?></label></th>
					<td><input type="text" name="fabricated_event[url]" size="35" value="<?php echo (!empty($post_meta['url']) && $post_meta['url'] != 'http://' ? $post_meta['url'] : 'http://');?>" /> (e.g. http://www.website.com)</td>
				</tr>
			</table>
			<h4>Contact Information</h4>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="contact-name"><?=_e('Name');?></label></th>
					<td><input type="text" name="fabricated_event[contact-name]" size="35" value="<?php echo (!empty($post_meta['contact-name']) ? $post_meta['contact-name'] : '');?>" /> (e.g. John Doe)</td>
				</tr>
				<tr>
					<th scope="row"><label for="contact-phone"><?=_e('Phone');?></label></th>
					<td>(<input type="text" name="fabricated_event[contact-phone][0]" size="2" value="<?php echo (!empty($post_meta['contact-phone'][0]) ? $post_meta['contact-phone'][0] : '');?>" />) <input type="text" name="fabricated_event[contact-phone][1]" size="2" value="<?php echo (!empty($post_meta['contact-phone'][1]) ? $post_meta['contact-phone'][1] : '');?>" />-<input size="3"  type="text" name="fabricated_event[contact-phone][2]" value="<?php echo (!empty($post_meta['contact-phone'][2]) ? $post_meta['contact-phone'][2] : '');?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="contact-email"><?=_e('Email');?></label></th>
					<td><input type="text" name="fabricated_event[contact-email]" size="35" value="<?php echo (!empty($post_meta['contact-email']) ? $post_meta['contact-email'] : '');?>" /> (e.g. sample@email.com)</td>
				</tr>
			</table><?
		  	
		}
		
		function saveFabricatedEventPostData($post_id) {
			// Verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times.
			if ( !wp_verify_nonce( $_POST['fabricatedevents_noncename'], FABRICATEDEVENTS_PLUGIN_URL )) {
				return $post_id;
			}
			
			// Verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
			// to do anything.
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
				return $post_id;
			}
			
			// Check permissions
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
				  return $post_id;
				}
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) ) {
				  return $post_id;
				}
			}
			
			// Convert the dates to timestamps.
			$date = $_POST['fabricated_event']['start-date'];
			$startDate = strtotime(gmdate($date['year'].'-'.$date['mon'].'-'.$date['day'].' '.$date['hours'].':'.$date['minutes'], (time()+(get_option( 'gmt_offset' ) * 3600))));
			
			$date = $_POST['fabricated_event']['end-date'];
			$endDate = strtotime(gmdate($date['year'].'-'.$date['mon'].'-'.$date['day'].' '.$date['hours'].':'.$date['minutes'], (time()+(get_option( 'gmt_offset' ) * 3600))));
			
			// Make sure the start date is less than or equal to the end date.
			if ($startDate > $endDate) {
				return $post_id;
			}
			
			// Set up the data for saving.
			$post_data = array(
				'start-date' => $startDate,
				'end-date' => $endDate,
				'url' => $_POST['fabricated_event']['url'],
				'contact-name' => $_POST['fabricated_event']['contact-name'],
				'contact-phone' => array(
					$_POST['fabricated_event']['contact-phone'][0], 
					$_POST['fabricated_event']['contact-phone'][1],
					$_POST['fabricated_event']['contact-phone'][2]
				),
				'contact-email' => $_POST['fabricated_event']['contact-email']
			);
			
			// Save the data.
			update_post_meta($post_id, 'fabricated_event', $post_data);
		}
		
		private function _generateDateChooserFields($fieldId, $timestamp = null) {
			$output = '';
			
			if ($timestamp != null) {
				$currentDate = $timestamp;
			} else {
				$currentDate = strtotime(gmdate('Y-m-d H:i:s', (time()+(get_option( 'gmt_offset' ) * 3600))));
			}
			$currentDate = getdate($currentDate);
			
			$output .= '<select name="'.$fieldId.'[mon]" id="'.$fieldId.'">';	  	
			for ($i = 1; $i <= 12; $i++) {
				$i = sprintf("%02d", $i);
				$currentDate['mon'] = sprintf("%02d", $currentDate['mon']);
				
>>>>>>> Updated a few comments.
				$output .= '<option value="'.$i.'" '.selected($i,$currentDate['mon'],false).'>'.date('M', mktime(0, 0, 0, $i+1, 0, 0, 0)).'</option>';
			}
			$output .= '</select> </label>
			<select name="'.$fieldId.'[day]" id="'.$fieldId.'-day">';	  	
			for ($i = 1; $i <= 31; $i++) {
<<<<<<< HEAD
=======
				$i = sprintf("%02d", $i);
				$currentDate['mday'] = sprintf("%02d", $currentDate['mday']);
				
>>>>>>> Updated a few comments.
				$output .= '<option value="'.$i.'" '.selected($i,$currentDate['mday'],false).'>'.$i.'</option>';
			}
			$output .= '</select>, 
			<select name="'.$fieldId.'[year]" id="'.$fieldId.'-year">';	  	
			for ($i = $currentDate['year']; $i <= ($currentDate['year']+10); $i++) {
				$output .= '<option value="'.$i.'" '.selected($i,$currentDate['year'],false).'>'.$i.'</option>';
			}
			$output .= '</select> @ 
			<select name="'.$fieldId.'[hours]" id="'.$fieldId.'-hours">';	  	
			for ($i = 1; $i <= 23; $i++) {
<<<<<<< HEAD
				$output .= '<option value="'.$i.'" '.selected($i,date('G'),false).'>'.$i.'</option>';
=======
				$i = sprintf("%02d", $i);
				$currentDate['hours'] = sprintf("%02d", $currentDate['hours']);
				
				$output .= '<option value="'.$i.'" '.selected($i,$currentDate['hours'],false).'>'.$i.'</option>';
>>>>>>> Updated a few comments.
			}
			$output .= '</select> : 
			<select name="'.$fieldId.'[minutes]" id="'.$fieldId.'-minutes">';	  	
			for ($i = 1; $i <= 59; $i++) {
<<<<<<< HEAD
				$output .= '<option value="'.$i.'" '.selected($i,$currentDate['minutes'],false).'>'.$i.'</option>';
			}
			$output .= '</select>';
			
=======
				$i = sprintf("%02d", $i);
				$currentDate['minutes'] = sprintf("%02d", $currentDate['minutes']);
				
				$output .= '<option value="'.$i.'" '.selected($i,$currentDate['minutes'],false).'>'.$i.'</option>';
			}
			$output .= '</select>';

>>>>>>> Updated a few comments.
			return $output;
		}
	   
	} //End Class FabricatedEvents
}
?>