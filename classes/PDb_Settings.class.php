<?php
/**
 * plugin settings class for participants-database plugin
 *
 * this uses the generic plugin settings class to build the settings specific to
 * the plugin
 */
class PDb_Settings extends Plugin_Settings {

  function __construct() {

    $this->WP_setting = Participants_Db::$participants_db_options ;

    // define the settings sections
    // no need to worry about the namespace, it will be prefixed
    $this->sections = array(
                            'main'   => __('General Settings', 'participants-database' ),
                            'signup' => __('Signup Form Settings', 'participants-database' ),
                            'record' => __('Record Form Settings', 'participants-database' ),
                            'list'   => __('List Display Settings', 'participants-database' ),
                            );

    self::$section_description = array(
      'record' => __( 'Settings for the [pdb_record] shortcode, which is used to show a user-editable form on the website.', 'participants-database' ),
      'list' => __( 'Settings for the [pdb_list] shortcode, which is used to show a list of records from the database.', 'participants-database' ),
      'signup' => __( 'Settings for the [pdb_signup] shortcode, which is used to show a signup or registration form on the website.', 'participants-database' ),
    );


    // run the parent class initialization to finish setting up the class 
    parent::__construct( __CLASS__, $this->WP_setting, $this->sections );

    $this->submit_button = __('Save Plugin Settings', 'participants-database' );
    
    // define the individual settings
    $this->_define_settings();

    // now that the settings have been defined, finish setting
    // up the plugin settings
    $this->initialize();

  }

  /**
   * defines the individual settings for the plugin
   *
   * @return null
   */
  private function _define_settings() {
		
	/******************************************************
	 *
	 *   general settings
	 *
	 ******************************************************/

    $this->plugin_settings[] = array(
        'name'       => 'image_upload_location',
        'title'      => __('File Upload Location', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'        => 'text',
          'help_text'   => __("this defines where the uploaded files will go, relative to the WordPress root.<br />Don't put it in the plugin folder, the images and files could get deleted when the plugin is updated.", 'participants-database' ),
          'value'       => Participants_Db::$uploads_path,
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'image_upload_limit',
        'title'      =>__('Image Upload Limit', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'        =>'dropdown',
          'help_text'   => __('the maximum allowed file size for an uploaded image', 'participants-database' ),
          'value'       => '100K',
					'options'     => array( '10K'=>10,'20K'=>20,'50K'=>50,'100K'=>100,'150K'=>150,'250K'=>250,'500K'=>500, '750K'=>750 ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       => 'default_image',
        'title'      => __('Default Image', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'        => 'text',
          'help_text'   => __("Path (relative to WP root) of an image file to show if no image has been defined for an image field. Leave blank for no default image.", 'participants-database' ),
          'value'       => 'wp-content/plugins/'.'participants-database'.'/ui/no-image.png',
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'image_link',
        'title'      =>__('Link Image to Fullsize', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('place a link to the full-size image on images. This link will work with most "lightbox" plugins.', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'use_plugin_css',
        'title'      =>__('Use the Plugin CSS', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('use the plugin\'s CSS to style the output of shortcodes', 'participants-database' ),
          'value'       => 1,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'make_links',
        'title'      =>__('Make Links Clickable', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('if a "text-line" field looks like a link (begins with "http" or is an email address) make it clickable', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'email_protect',
        'title'      =>__('Protect Email Addresses', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('protect email addresses in text-line fields with Javascript', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );
		
    $this->plugin_settings[] = array(
        'name'       =>'empty_field_message',
        'title'      =>__('Missing Field Error Message', 'participants-database' ),
        'group'      =>'main',
        'options'    =>array(
          'type'       =>'text',
          'help_text'  => __('the message shown when a field is required, but left empty (the %s is replaced by the name of the field)', 'participants-database' ),
          'value'      => __('The %s field is required.', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'invalid_field_message',
        'title'      =>__('Invalid Field Error Message', 'participants-database' ),
        'group'      =>'main',
        'options'    =>array(
          'type'       =>'text',
          'help_text'  => __("the message shown when a field's value does not pass the validation test", 'participants-database' ),
          'value'      => __('The %s field appears to be incorrect.', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'nonmatching_field_message',
        'title'      =>__('Non-Matching Field Error Message', 'participants-database' ),
        'group'      =>'main',
        'options'    =>array(
          'type'       =>'text',
          'help_text'  => __("the message shown when a field's value does match the value of another field", 'participants-database' ),
          'value'      => __('The %s field must match.', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'field_error_style',
        'title'      =>__('Field Error Style', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'        =>'text',
          'help_text'   => __('the CSS style applied to an input or text field that is missing or has not passed validation', 'participants-database' ),
          'value'       => __('border: 1px solid red', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'mark_required_fields',
        'title'      =>__('Mark Required Fields', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('mark the title of required fields?', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'required_field_marker',
        'title'      =>__('Required Field Marker', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'       => 'text-area',
          'help_text'  => __('html added to field title for required fields if selected above (the %s is replaced by the title of the field)', 'participants-database' ),
          'value'      => '%s<span class="reqd">*</span>',
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'rich_text_editor',
        'title'      =>__('Use Rich Text Editor', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('enable the rich text editor on "Rich Text" fields (works only for logged-in WP users, and "Text Area" fields will remain plain text)', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'html_email',
        'title'      =>__('Send HTML Email', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('use rich text in plugin emails? If you turn this off, be sure to remove all HTML tags from the email body settings for the plugin.', 'participants-database' ),
          'value'       => 1,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'strict_dates',
        'title'      =>__('Strict Date Format', 'participants-database' ),
        'group'      =>'main',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => sprintf( __('This forces date inputs to be interpreted strictly according to the date format setting of the site. You should tell your users what format you are expecting them to use. This also applies to date values used in [pdb_list] shortcode filters. The date with your setting looks like this: %s %s', 'participants-database' ), date(get_option('date_format')), ( Participants_Db::php_version() >= 5.3 ? '' : '<strong>(Your current version of PHP does not support this setting.)</strong>' ) ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );

    $this->plugin_settings[] = array(
        'name'       =>'record_edit_capability',
        'title'      =>__('Record Edit Access Level', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'        =>'dropdown',
          'help_text'   => __('sets the user access level for adding, editing and listing records. (fields management and plugin settings always require admin level access)', 'participants-database' ),
          'value'       => 'edit_others_posts',
					'options'     => array( __('Author')=>'edit_posts',__('Editor')=>'edit_others_posts',__('Admin')=>'manage_options' ),
          )
        );
		

    $this->plugin_settings[] = array(
        'name'       =>'admin_default_sort',
        'title'      =>__('Admin List Default Sort', 'participants-database' ),
        'group'      =>'main',
        'options'    =>array
          (
          'type'       =>'dropdown',
					'value'      => 'date_updated',
          'help_text'  => __('The record list shown in the admin section will be sorted by this field by default. (Field must be checked "sortable.")', 'participants-database' ),
          'options'    => $this->_get_sort_columns(),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'admin_default_sort_order',
        'title'      =>__('Admin List Default Sort Order', 'participants-database' ),
        'group'      => 'main',
        'options'    =>array(
          'type'        =>'dropdown',
          'help_text'   => __('Sets the default order of the record list in the admin.', 'participants-database' ),
          'value'       => 'desc',
					'options'     => array( __('Ascending', 'participants-database' )=>'asc',__('Descending', 'participants-database' )=>'desc' ),
          )
        );


		/******************************************************
		 *
		 *   list display settings
		 *
		 ******************************************************/
		 
    $this->plugin_settings[] = array(
        'name'       =>'list_limit',
        'title'      => __('Records per Page', 'participants-database' ),
        'group'      =>'list',
        'options'    =>array(
          'type'        =>'text',
          'help_text'   => __('the number of records to show on each page', 'participants-database' ),
          'attributes'  =>array( 'style'=>'width:40px' ),
          'value'       =>10,
          ),
        );
		
    $this->plugin_settings[] = array(
        'name'       =>'single_record_link_field',
        'title'      =>__('Single Record Link Field', 'participants-database' ),
        'group'      =>'list',
        'options'    =>array
          (
          'type'       =>'dropdown',
          'help_text'  => __('select the field on which to put a link to the [pdb_single] shortcode. Leave blank or set to "none" for no link.', 'participants-database' ),
          'options'    => $this->_get_display_columns(),
          )
        );
		
    $this->plugin_settings[] = array(
        'name'       =>'single_record_page',
        'title'      =>__('Single Record Page', 'participants-database' ),
        'group'      =>'list',
        'options'    =>array
          (
				  'attributes' => array( 'other' => 'Post ID' ),
          'type'       =>'dropdown-other',
          'help_text'  => __('this is the page where the [pdb_single] shortcode is located. If you want to assign a post or custom post type, enter the post ID in the "other" box.', 'participants-database' ),
          'options'    => $this->_get_pagelist(),
          )
        );
		
    $this->plugin_settings[] = array(
        'name'       =>'strict_search',
        'title'      =>__('Strict User Searching', 'participants-database' ),
        'group'      =>'list',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('When checked, the frontend list search must match the whole field exactly. If unchecked, the search will match if the search term is found in part of the field. Searches are not case-sensitive either way.', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );
    $this->plugin_settings[] = array(
        'name'       =>'ajax_search',
        'title'      =>__('Enable AJAX Search Functionality', 'participants-database' ),
        'group'      =>'list',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __("This enables list search results that are updated without reloading the page. It requires Javascript, but search will still work if Javascript is disabled in the user's browser.", 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          ),
        );
		
		/******************************************************
		 *
		 *   signup form settings
		 *
		 ******************************************************/
		
    $this->plugin_settings[] = array(
        'name'       =>'signup_button_text',
        'title'      =>__('Signup Button Text', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('text shown on the button to sign up', 'participants-database' ),
          'value'       => _x('Sign Up','the text on a button to submit a signup form', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'signup_thanks_page',
        'title'      =>__('Signup Thanks Page', 'participants-database' ),
        'group'      =>'signup',
        'options'    =>array
					(
          'type'       =>'dropdown-other',
          'help_text'  => __('after they singup, send them to this page for a thank you message. This page is where you put the [pdb_signup_thanks] shortcode, but you don&#39;t have to do that if you have them go back to the same page. You can also use a Post ID for posts and custom post types.', 'participants-database' ),
					'options'    => $this->_get_pagelist( true ),
					'attributes' => array( 'other' => 'Post ID' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       => 'send_signup_receipt_email',
        'title'      => __('Send Signup Response Email', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('Send a receipt email to people who sign up', 'participants-database' ),
          'value'       => 1,
          'options'     => array( 1, 0 ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'receipt_from_address',
        'title'      =>__('Signup Email From Address', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('the "From" address on signup receipt emails. If the recipient hits "reply", their reply will go to this address', 'participants-database' ),
          'value'       => get_bloginfo( 'admin_email' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'receipt_from_name',
        'title'      =>__('Signup Email From Name', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('the "From" name on signup receipt emails.', 'participants-database' ),
          'value'       => get_bloginfo( 'name' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'signup_receipt_email_subject',
        'title'      =>__('Signup Response Email Subject', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('subject line for the signup response email; placeholder tags can be used (see below)', 'participants-database' ),
          'value'       => sprintf( __("You've just signed up on %s", 'participants-database' ), get_bloginfo('name') ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'signup_receipt_email_body',
        'title'      =>__('Signup Response Email', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text-area',
          'help_text'   => __('Body of the email a visitor gets when they sign up. It includes a link ([record_link]) back to their record so they can fill it out. Can include HTML, placeholders:[first_name],[last_name],[email],[record_link]. You can only use placeholders for fields that are present in the signup form, including hidden fields.', 'participants-database' ),
					/* translators: the %s will be the name of the website */
          'value'       =>sprintf( __('<p>Thank you, [first_name], for signing up with %s.</p><p>You may complete your registration with additional information or update your information by visiting this private link at any time: <a href="[record_link]">[record_link]</a>.</p>', 'participants-database' ),get_bloginfo('name') ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'signup_thanks',
        'title'      =>__('Signup Thanks Message', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text-area',
          'help_text'   => __('Note to display on the web page after someone has submitted a signup form. Can include HTML and placeholders (see above)', 'participants-database' ),
          'value'       =>__('<p>Thank you, [first_name] for signing up!</p><p>You will receive an email acknowledgement shortly. You may complete your registration with additional information or update your information by visiting the link provided in the email.</p>', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       => 'send_signup_notify_email',
        'title'      => __('Send Signup Notification Email', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('Send an email notification that a signup has occurred.', 'participants-database' ),
          'value'       => 1,
          'options'     => array( 1, 0 ),
          )
        );


    $this->plugin_settings[] = array(
        'name'       =>'email_signup_notify_addresses',
        'title'      =>__('Signup Notification Recipients', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('comma-separated list of email addresses to send signup notifications to', 'participants-database' ),
          'value'       => get_bloginfo( 'admin_email' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'email_signup_notify_subject',
        'title'      =>__('Signup Notification Email Subject', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('subject of the notification email; placeholder tags can be used (see above)', 'participants-database' ),
					/* translators: the %s will be the name of the website */
          'value'       => sprintf( __('New signup on %s', 'participants-database' ), get_bloginfo('name') ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'email_signup_notify_body',
        'title'      =>__('Signup Notification Email'),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text-area',
          'help_text'   => __('notification email body. The [admin_record_link] tag will supply the URL for editing the record in the admin.'),
          'value'       => __('<p>A new signup has been submitted</p><ul><li>Name: [first_name] [last_name]</li><li>Email: [email]</li></ul><p>Edit this new record here: <a href="[admin_record_link]">[admin_record_link]</a></p>'),
          )
        );
		
    $this->plugin_settings[] = array(
        'name'       =>'unique_field',
        'title'      =>__('Duplicate Record Check Field', 'participants-database' ),
        'group'      =>'signup',
        'options'    =>array
          (
          'type'       =>'dropdown',
          'help_text'  => __('when a signup is submitted or CSV record is imported, this field is checked for a duplicate', 'participants-database' ),
          'options'    => array_merge( $this->_get_display_columns(), array( 'Record ID' => 'id' ) ),
					'value'      => 'email',
          )
        );
    $this->plugin_settings[] = array(
        'name'       =>'unique_email',
        'title'      => __('Duplicate Record Preference', 'participants-database' ),
        'group'      =>'signup',
        'options'    => array
          (
          'type'        => 'dropdown',
          'help_text'   => __('when the submission matches the Duplicate Record Check Field of an existing record. This also applies to importing records from a CSV file.', 'participants-database' ),
          'value'       => 1,
          'options'     => array(
																 'Create a new record with the submission' => 0,
																 'Overwrite matching record with new data' => 1,
																 'Show a validation error message' => 2,
																 ),
          ),
        );
		
    $this->plugin_settings[] = array(
        'name'       =>'duplicate_field_message',
        'title'      =>__('Duplicate Record Error Message', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array(
          'type'        =>'text-area',
          'help_text'   => __('If "Show a validation error message" is selected above, this message will be shown if a signup is made with a "check field" that matches an existing record.', 'participants-database' ),
          'value'       =>__('A record with that %s already exists. Please choose another.', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       => 'signup_show_group_descriptions',
        'title'      => __('Show Field Groups', 'participants-database' ),
        'group'      => 'signup',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('Show groups and group descriptions in the signup form.', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          )
        );

		/******************************************************
		 *
		 *   record form settings
		 *
		 ******************************************************/

    $this->plugin_settings[] = array(
        'name'       =>'registration_page',
        'title'      =>__('Participant Record Page', 'participants-database' ),
        'group'      =>'record',
        'options'    =>array
					(
          'type'       =>'dropdown-other',
          'help_text'  => __('The page where your participant record ([pdb_record] shortcode) is displayed. You can use a Post ID for posts and custom post types.', 'participants-database' ),
					'options'    => $this->_get_pagelist(),
					'attributes' => array( 'other' => 'Post ID' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'save_changes_label',
        'title'      =>__('Save Changes Label', 'participants-database' ),
        'group'      =>'record',
        'options'    =>array
					(
          'type'       =>'text',
          'help_text'  => __('label for the save changes button on the record form', 'participants-database' ),
					'value'			 => __('Save Your Changes', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'save_changes_button',
        'title'      =>__('Save Button Text', 'participants-database' ),
        'group'      =>'record',
        'options'    =>array
					(
          'type'       =>'text',
          'help_text'  => __('text on the "save" button', 'participants-database' ),
					'value'			 => _x('Save','a label for a button to save a form', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       => 'show_group_descriptions',
        'title'      => __('Show Group Descriptions', 'participants-database' ),
        'group'      => 'record',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('Show the group description under each group title in the record form.', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'record_updated_message',
        'title'      =>__('Record Updated Message', 'participants-database' ),
        'group'      =>'record',
        'options'    =>array(
          'type'       =>'text',
          'help_text'  => __("the message shown when a record form has been successfully submitted", 'participants-database' ),
          'value'      => __('Your information has been updated:', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       => 'send_record_update_notify_email',
        'title'      => __('Send Record Form Update Notification Email', 'participants-database' ),
        'group'      => 'record',
        'options'    => array
          (
          'type'        => 'checkbox',
          'help_text'   => __('Send an email notification that a record has been updated. These will be sent to the email addresses listed in the "Signup Notification Recipients" setting.', 'participants-database' ),
          'value'       => 0,
          'options'     => array( 1, 0 ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'record_update_email_subject',
        'title'      =>__('Record Update Email Subject', 'participants-database' ),
        'group'      => 'record',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('subject line for the record update notification email; placeholders can be used.', 'participants-database' ),
          'value'       => sprintf( __("A record has just been updated on %s", 'participants-database' ), get_bloginfo('name') ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'record_update_email_body',
        'title'      =>__('Record Update Notification Email', 'participants-database' ),
        'group'      => 'record',
        'options'    => array(
          'type'        =>'text-area',
          'help_text'   => __('Body of the the email sent when a user updates their record. Any field from the form can be included by using a replacement code of the form: [field_name]. For instance: [last_name],[address],[email] etc. (The field name is under the "name" column on the "Manage Database Fields" page.)  Also available is [date] which will show the date and time of the update and [admin_record_link] tag for a link to edit the record in the admin.', 'participants-database' ),
          'value'       =>__('<p>The following record was updated on [date]:</p><ul><li>Name: [first_name] [last_name]</li><li>Address: [address]</li><li>[city], [state], [country]</li><li>Phone: [phone]</li><li>Email: [email]</li></ul><p>Edit this record <a href="[admin_record_link]">here.</a></p>', 'participants-database' ),
          )
        );

    $this->plugin_settings[] = array(
        'name'       =>'no_record_error_message',
        'title'      =>__('Record Not Found Error Message', 'participants-database' ),
        'group'      => 'record',
        'options'    => array(
          'type'        =>'text',
          'help_text'   => __('message to show if the record page was accessed without a valid identifier. Leave this empty if you want nothing at all to show.', 'participants-database' ),
          'value'       => sprintf( __("No record was found.", 'participants-database' ), get_bloginfo('name') ),
          )
        );

  }
	
	private function _get_pagelist( $with_none = false ) {
		
		$pagelist = array();
		
		if ( $with_none ) $pagelist[ __('Same Page', 'participants-database' ) ] = 'none' ;
		
		$pages = get_pages( array() );
		
		foreach( $pages as $page ) {
			
			$pagelist[ $page->post_title ] = $page->ID;
		
		}
		
		/*
		 * if you wish to include posts in the list of pages where the shortcode can be found, uncomment this block of code
		 */
		/*
		
		$posts = get_posts( array( 'numberposts' => -1 ) );
		
		foreach( $posts as $post ) {
			
			$pagelist[ $post->post_title ] = $post->ID;
		
		}
		*/
		
		return $pagelist;
		
	}

	private function _get_display_columns() {

    $columnlist = array(  __('None', 'participants-database' ) => 'none' );

    $columns = Participants_Db::get_column_atts( 'frontend' );

    foreach( $columns as $column ) {

      if ( in_array( $column->form_element, array( 'text-line', 'image-upload' ) ) ) $columnlist[ $column->title ] = $column->name;

    }

    return $columnlist;

  }

	private function _get_sort_columns() {

    $columnlist = array();

    $columns = Participants_Db::get_column_atts( 'sortable' );

    foreach( $columns as $column ) {

      $columnlist[ $column->title ] = $column->name;

    }

    return $columnlist;

  }

  /**
   * displays a settings page form using the WP Settings API
   *
   * this function is called by the plugin on it's settings page
   *
   * @return null
   */
  public function show_settings_form() {
    ?>
    <div class="wrap participants_db settings-class">
      <h2><?php echo Participants_Db::$plugin_title?> <?php _e('Settings', 'participants-database' )?></h2>
      <form action="options.php" method="post" >
        <div class="ui-tabs">
          <ul class="ui-tabs-nav">
          <?php foreach ( $this->sections as $id => $title ) printf('<li><a href="#%s">%s</a></li>',Participants_Db::make_anchor( $id ), $title ); ?>
          </ul>
          <?php
  
          settings_fields( $this->WP_setting );
  
          do_settings_sections( $this->settings_page );
					
					?>
        </div>
          
          <?php
  
          $args = array(
                        'type'  => 'submit',
                        'class' => $this->submit_class,
                        'value' => $this->submit_button,
                        'name'  => 'submit',
                        );
  
          printf( $this->submit_wrap, FormElement::get_element( $args ) );
  
          ?>
      </form>

    </div>
    <?php

  }
	
	
	/**
	 * displays a section subheader
	 *
	 * note: the header is displayed by WP; this is only what would go under that
	 */
	public function options_section( $section ) {
		
		$name = Participants_db::make_anchor( end( explode( '_',$section['id'] ) ) );
		
    printf('<a id="%1$s" name="%1$s" class="%2$s" ></a>', $name, Participants_Db::$css_prefix.'anchor' );
    
    if ( isset( self::$section_description[$name] ) ) printf( '<div class="section-description" ><h4>%s</h4></div>', self::$section_description[$name] );
  
 
	
	}

}
?>