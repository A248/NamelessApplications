<?php 
/*
 *	Made by Partydragen, edited by relavis
 *  http://partydragen.com/
 *
 *  License: MIT
 *
 *  Updated by Samerton
 *
 *  Refactored by A248
 */

// Initialise the player report addon
// We've already checked to see if it's enabled

// Check language
$c->setCache('languagecache');
$language = $c->retrieve('language');

if(file_exists('addons/PlayerReport/' . $language . '.php'))
	require('addons/PlayerReport/' . $language . '.php');
else
	require('addons/PlayerReport/language.php');

if($user->isLoggedIn()){
	// Check cache for link location
	$c->setCache('playerreport');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}

	// Enabled, add links to navbar
	switch($link_location){
		case 'navbar':
			$navbar_array[] = array('playerreport' => $playerreport_language['player_report_icon'] . $playerreport_language['player_report']);
		break;
		
		case 'footer':
			$footer_nav_array['playerreport'] = $playerreport_language['player_report_icon'] . $playerreport_language['player_report'];
		break;
		
		case 'more':
			$nav_playerreport_object = new stdClass();
			$nav_playerreport_object->url = '/playerreport';
			$nav_playerreport_object->icon = $playerreport_language['player_report_icon'];
			$nav_playerreport_object->title = $playerreport_language['player_report'];
		
			$nav_more_dropdown[] = $nav_playerreport_object;
		break;
		
		case 'none':
		break;
		
		default:
			$navbar_array[] = array('playerreport' => $playerreport_language['player_report_icon'] . $playerreport_language['player_report']);
		break;
	}
	$custom_mod_sidebar['playerreport'] = array(
		'url' => '/mod/playerreport',
		'title' => $playerreport_language['player_report']
	);
}