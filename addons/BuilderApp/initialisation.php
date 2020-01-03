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

if(file_exists('addons/BuilderApp/' . $language . '.php'))
	require('addons/BuilderApp/' . $language . '.php');
else
	require('addons/BuilderApp/language.php');

if($user->isLoggedIn()){
	// Check cache for link location
	$c->setCache('builderapp');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}

	// Enabled, add links to navbar
	switch($link_location){
		case 'navbar':
			$navbar_array[] = array('builderapp' => $builderapp_language['builder_app_icon'] . $builderapp_language['builder_app']);
		break;
		
		case 'footer':
			$footer_nav_array['builderapp'] = $builderapp_language['builder_app_icon'] . $builderapp_language['builder_app'];
		break;
		
		case 'more':
			$nav_builderapp_object = new stdClass();
			$nav_builderapp_object->url = '/builderapp';
			$nav_builderapp_object->icon = $builderapp_language['builder_app_icon'];
			$nav_builderapp_object->title = $builderapp_language['builder_app'];
		
			$nav_more_dropdown[] = $nav_builderapp_object;
		break;
		
		case 'none':
		break;
		
		default:
			$navbar_array[] = array('builderapp' => $builderapp_language['builder_app_icon'] . $builderapp_language['builder_app']);
		break;
	}
	$custom_mod_sidebar['builderapp'] = array(
		'url' => '/mod/builderapp',
		'title' => $builderapp_language['builder_app']
	);
}