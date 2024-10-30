<?php
/**
 * Plugin Name: LH Xprofile forms
 * Plugin URI: https://lhero.org/portfolio/lh-xprofile-forms/
 * Description: Decouple xprofile forms from the profile and signup screens
 * Version: 1.03
 * Requires PHP: 5.6
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com/
 * Text Domain: lh_xprofile_forms
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('LH_Xprofile_forms_plugin')) {


class LH_Xprofile_forms_plugin {

    private static $instance;

    static function return_plugin_namespace(){

        return 'lh_xprofile_forms';

    }

    static function handle_bp_edit(){
    
    // Explode the posted field IDs into an array so we know which
		// fields have been submitted.
		$posted_field_ids = wp_parse_id_list( $_POST['field_ids'] );
		
		
        $is_required      = array();

		// Loop through the posted fields formatting any datebox values then validate the field.
		foreach ( (array) $posted_field_ids as $field_id ) {
		    
		    	bp_xprofile_maybe_format_datebox_post_data( $field_id );

			$is_required[ $field_id ] = xprofile_check_is_required_field( $field_id ) && ! bp_current_user_can( 'bp_moderate' );
			if ( $is_required[$field_id] && empty( $_POST['field_' . $field_id] ) ) {
				$errors = true;
			}
			
		}	
			
				// There are errors.
		if ( !empty( $errors ) ) {
			bp_core_add_message( __( 'Please make sure you fill in all required fields in this profile field group before saving.', 'buddypress' ), 'error' );

		// No errors.
		} else {
		    
		    // Reset the errors var.
			$errors = false;

			// Now we've checked for required fields, lets save the values.
			$old_values = $new_values = array();
			foreach ( (array) $posted_field_ids as $field_id ) {

				// Certain types of fields (checkboxes, multiselects) may come through empty. Save them as an empty array so that they don't get overwritten by the default on the next edit.
				$value = isset( $_POST['field_' . $field_id] ) ? $_POST['field_' . $field_id] : '';


				// Save the old and new values. They will be
				// passed to the filter and used to determine
				// whether an activity item should be posted.
				$old_values[ $field_id ] = array(
					'value'      => xprofile_get_field_data( $field_id, bp_displayed_user_id() ),
					'visibility' => xprofile_get_field_visibility_level( $field_id, bp_displayed_user_id() ),
				);
				// Update the field data.
				$field_updated = xprofile_set_field_data( $field_id, get_current_user_id(), $value, $is_required[ $field_id ] );
				$value         = xprofile_get_field_data( $field_id, get_current_user_id() );
				

				$new_values[ $field_id ] = array(
					'value'      => $value,
					'visibility' => xprofile_get_field_visibility_level( $field_id, bp_displayed_user_id() ),
				);

				if ( ! $field_updated ) {
					$errors = true;
					
				
				} else {

					/**
					 * Fires on each iteration of an XProfile field being saved with no error.
					 *
					 * @since 1.1.0
					 *
					 * @param int    $field_id ID of the field that was saved.
					 * @param string $value    Value that was saved to the field.
					 */
					do_action( 'xprofile_profile_field_data_updated', $field_id, $value );
				}
			}
			
		}
    
        
        /**
			 * Fires after all XProfile fields have been saved for the current profile.
			 *
			 * @since 1.0.0
			 *
			 * @param int   $value            Displayed user ID.
			 * @param array $posted_field_ids Array of field IDs that were edited.
			 * @param bool  $errors           Whether or not any errors occurred.
			 * @param array $old_values       Array of original values before updated.
			 * @param array $new_values       Array of newly saved values after update.
			 */
			do_action( 'xprofile_updated_profile', bp_displayed_user_id(), $posted_field_ids, $errors, $old_values, $new_values );

			// Set the feedback messages.
			if ( !empty( $errors ) ) {
				bp_core_add_message( __( 'There was a problem updating some of your profile information. Please try again.', 'buddypress' ), 'error' );
			} else {
			    
			   
				bp_core_add_message( __( 'Changes saved.', 'buddypress' ) );
				
				
				
				if (isset($_POST['redirect_url']) and parse_url($_POST['redirect_url'])){
				    
				    wp_redirect( $_POST['redirect_url'] );
                    exit;
				    
				    
				}
				
	
			}
    
    
    

}



public function lh_xprofile_form_output($atts) {

    // define attributes and their defaults
    extract( shortcode_atts( array (
        'group_id' => 1,
        'submit' => 'Save Changes',
        'redirect_url' => false
    ), $atts ) );
    
    
    $return_string = '';
    
    
    
    if (!is_user_logged_in()){
        
       $return_string .= __( 'In order to access this form please log in', self::return_plugin_namespace() ); 
        
    } else {
    
    
    
       
       
        ob_start();
        
      bp_core_render_message();     
do_action( 'bp_before_profile_edit_content' );
        
        
if ( bp_has_profile( 'user_id='.get_current_user_id().'&profile_group_id='.$group_id.'&hide_empty_fields=0') ){
    
    	while ( bp_profile_groups() ) {
    	
    	bp_the_profile_group(); 
    
    
 	do_action( 'bp_before_profile_field_content' );
    	
    	?>

<form action="" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug(); ?>">
    
    
    
    <?php
    
 do_action( 'bp_before_profile_loop_content' );    

    while ( bp_profile_fields() ) {
        
        bp_the_profile_field();
        
        
        ?>
        <div<?php bp_field_css_class( 'editfield' ); ?>>
        <fieldset>
        
        <?php
        
        
       $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
       
       
        
        
     $raw_properties = array('user_id' => get_current_user_id() );
							
							$field_type->edit_field_html($raw_properties);
							
							?>
							</fieldset>
							</div>
							<?php
        
    }
    
    ?>
<input id="lh_xprofile_form-submit" name="lh_xprofile_form-submit" value="<?php echo $submit; ?>" type="submit">
    
    <?php if (isset($redirect_url) and !empty($redirect_url) and parse_url($redirect_url)){ ?>
    
    	<input type="hidden" name="redirect_url" id="redirect_url" value="<?php echo $redirect_url; ?>" />
    
    <?php } ?>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_field_ids(); ?>" />

<input type="hidden" name="<?php echo self::return_plugin_namespace()."-frontend-nonce"; ?>" id="<?php echo self::return_plugin_namespace()."-frontend-nonce"; ?>" value="<?php echo wp_create_nonce(self::return_plugin_namespace()."-frontend-nonce"); ?>" />



</form>
    
    
    
    <?php
    
    	
    	
    	}
    
    
}
     
        
        $return_string .= ob_get_contents();
        ob_end_clean();
        
        
    }
        return $return_string;
    
    
}



    function register_shortcodes(){
    
        add_shortcode('lh_xprofile_form', array($this,"lh_xprofile_form_output"));
    
    }



    public function handle_form_processing(){
        
        if ( !empty($_POST[self::return_plugin_namespace().'-frontend-nonce' ]) and wp_verify_nonce( $_POST[self::return_plugin_namespace().'-frontend-nonce' ], self::return_plugin_namespace().'-frontend-nonce')) {
            
            self::handle_bp_edit();
            
        }
        
    }



    public function plugin_init(){
    
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 
        
        
        //add the shortcodes  
        add_action( 'init', array($this,'register_shortcodes'));
        
        
        //Handle the form processing
        add_action( 'wp', array($this,'handle_form_processing'));
    
    }

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
     
    public static function get_instance(){
        
        if (null === self::$instance) {
            
            self::$instance = new self();
            
        }
 
        return self::$instance;
        
    }


    public function __construct() {
    
        //run whatever on plugins loaded (currently just translations)
        add_action( 'bp_loaded', array($this,'plugin_init'));
    
    }


}

$lh_xprofile_forms_instance = LH_Xprofile_forms_plugin::get_instance();


}


?>