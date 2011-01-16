<?php
if (!class_exists('FEOptions')) {
	class FEOptions {
		
		public function FEOptions() {
			add_action('admin_menu', array(&$this, 'createFabricatedEventsOptions'));
	   	}
		
		function createFabricatedEventsOptions() {
			// Add the options section.
			add_options_page('Fabricated Events Settings', 'Fabricated Events', 'manage_options', 'fabricated-events', array(&$this,'getFabricatedEventsOptionsForm'));
			
			// Create the main section of the form.
			add_settings_section('fabricated_events_main', '', array(&$this,'getFabricatedEventsMainSectionHtml'), 'fabricated-events');
			
			// Register the new settings.
			$this->registerFabricatedEventsSettings();
		}
		
		function getFabricatedEventsOptionsForm() {	
			?><div class="wrap">
				<h2>Fabricated Events Settings</h2>
				<form action="options.php" method="post">
					
					<?php settings_fields('fe_options'); ?>
					<?php do_settings_sections('fabricated-events'); ?>
					
					<p class="submit"> 
						<input type="submit" name="Submit" class="button-primary" value="<?php echo _e('Save Changes'); ?>" /> 
					</p>
				</form>
			</div><?
		}
		
		function getFabricatedEventsMainSectionHtml() {
			$fe_options = get_option('fabricated_events_settings');
			
			?><table class="form-table">
				<tr valign="top">
					<th scope="row"><?php echo _e('Map & Directions');?></th>
					<td>
						<p><label><input type="checkbox" name="fabricated_events_settings[show-map]" value="1" <?php checked('1', $fe_options['show-map']); ?> /> Show Google map</label></p>
						<p><label><input type="checkbox" name="fabricated_events_settings[show-directions-link]" value="1" <?php checked('1', $fe_options['show-directions-link']); ?> /> Display directions link</label></p>
						<p><strong>Note:</strong> In order for to display a map and directions, make sure you choose a Location for the event. The Location must contain an address as its description.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo _e('Calendar View Start Date');?></th>
					<td>
						<select name="fabricated_events_settings[calendar-start-day]">
							<option value="0" <?php selected('0', $fe_options['calendar-start-day']); ?>>Sunday</option>
							<option value="1" <?php selected('1', $fe_options['calendar-start-day']); ?>>Monday</option>
							<option value="2" <?php selected('2', $fe_options['calendar-start-day']); ?>>Tuesday</option>
							<option value="3" <?php selected('3', $fe_options['calendar-start-day']); ?>>Wednesday</option>
							<option value="4" <?php selected('4', $fe_options['calendar-start-day']); ?>>Thursday</option>
							<option value="5" <?php selected('5', $fe_options['calendar-start-day']); ?>>Friday</option>
							<option value="6" <?php selected('6', $fe_options['calendar-start-day']); ?>>Saturday</option>
						</select>
					</td>
				</tr>
			</table><?
		}
		
		function registerFabricatedEventsSettings() {
			register_setting( 'fe_options', 'fabricated_events_settings', array(&$this,'validateFabricatedEventsSettings') );
		}
		
		function validateFabricatedEventsSettings($input) {
			// No need to validate the fields at the moment.
			return $input;
		}

	   
	} //End Class FEOptions
}
?>