<?php 
/*
 *	Made by Partydragen and Samerton, edited by relavis
 *  http://partydragen.com/
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
 * 
 *  Refactored by A248
 */

class PlayerReport {
	private $_db;
	
	// Construct class
	public function __construct($user = null) {
		$this->_db = DB::getInstance();
	}
	
	// Can the specified user view player report?
	public function canViewPlayerReport($user_id = null){
		if($user_id){
			$data = $this->_db->get('users', array('id', '=', $user_id));
			if($data->count()){
				$user_group = $data->results();
				$user_group = $user_group[0]->group_id;
				// Get whether the user can view player report from the groups table
				$data = $this->_db->get('groups', array('id', '=', $user_group));
				if($data->count()){
					$data = $data->results();
					if($data[0]->playerreport == 1){
						// Can view
						return true;
					}
				}
			}
		}
		return false;
	}
	
	// Can the specified user accept player report?
	public function canAcceptPlayerReport($user_id = null){
		if($user_id){
			$data = $this->_db->get('users', array('id', '=', $user_id));
			if($data->count()){
				$user_group = $data->results();
				$user_group = $user_group[0]->group_id;
				// Get whether the user can accept player report from the groups table
				$data = $this->_db->get('groups', array('id', '=', $user_group));
				if($data->count()){
					$data = $data->results();
					if($data[0]->accept_playerreport == 1){
						// Can view
						return true;
					}
				}
			}
		}
		return false;
	}
	
}
