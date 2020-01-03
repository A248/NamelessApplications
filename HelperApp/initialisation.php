<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  License: MIT
 *
 *  Updated by Samerton
 *
 *  Refactored by A248
 *
 */

// Initialise the addon
// We've already checked to see if it's enabled

// Check language
$c->setCache('languagecache');
$language = $c->retrieve('language');

if(file_exists('addons/HelperApp/' . $language . '.php'))
	require('addons/HelperApp/' . $language . '.php');
else
	require('addons/HelperApp/language.php');

if($user->isLoggedIn()){
	// Check cache for link location
	$c->setCache('helperapp');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}

	// Enabled, add links to navbar
	switch($link_location){
		case 'navbar':
			$navbar_array[] = array('helperapp' => $helperapp_language['helper_app_icon'] . $helperapp_language['helper_app']);
		break;
		
		case 'footer':
			$footer_nav_array['helperapp'] = $helperapp_language['helper_app_icon'] . $helperapp_language['helper_app'];
		break;
		
		case 'more':
			$nav_helperapp_object = new stdClass();
			$nav_helperapp_object->url = '/helperapp';
			$nav_helperapp_object->icon = $helperapp_language['helper_app_icon'];
			$nav_helperapp_object->title = $helperapp_language['helper_app'];
		
			$nav_more_dropdown[] = $nav_helperapp_object;
		break;
		
		case 'none':
		break;
		
		default:
			$navbar_array[] = array('helperapp' => $helperapp_language['helper_app_icon'] . $helperapp_language['helper_app']);
		break;
	}
	$custom_mod_sidebar['helperapp'] = array(
		'url' => '/mod/helperapp',
		'title' => $helperapp_language['helper_app']
	);
}