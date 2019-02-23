<?php
defined('MOODLE_INTERNAL') || die;

// if (is_siteadmin()) {
//     $settings = new admin_settingpage('local_systemsettings', 'Public Course Catalogue Page');
//     $settings->add(new admin_setting_configtext('local_systemsettings/masterfeatures_'.$modname, 'Master Feature: '.ucfirst($modfullname),''));
//     $ADMIN->add('localplugins', $settings);
// }

// Ensure the configurations for this site are set
if ( $hassiteconfig ){
 
	// Create the new settings page
	// - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
	// $settings will be NULL
	$settings = new admin_settingpage( 'local_systemsettings', 'Your Settings Page Title' );
 
	// Create 
	$ADMIN->add( 'localplugins', $settings );
 
	// Add a setting field to the settings for this page
	$settings->add( new admin_setting_configtext('systemsettings_supportname','Support Name','', ''));
    $settings->add( new admin_setting_configtext('systemsettings_email','Support Email','', ''));
    $settings->add( new admin_setting_configtext('systemsettings_contactnumber','Support Contact Number','', ''));


}