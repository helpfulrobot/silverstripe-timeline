<?php

/**
 * Description of Notification
 *
 */
class Notification extends DataObject {
	
	/**
	 *
	 * @var array
	 */
	private static $db = array(
		'Message' => 'HTMLText',
		'Read' => 'Boolean(0)',
		'Starred' => 'Boolean(0)'
	);
	
	/**
	 *
	 * @var array
	 */
	private static $has_one = array(
		'Member' => 'Member'
	);
	
	/**
	 *
	 * @var array
	 */
	private static $summary_fields = array(
		'Message.RAW',
		'Created'
	);
	
	/**
	 *
	 * @var string
	 */
	private static $default_sort = 'Created DESC';
	
	/**
	 * 
	 * @param Member $member
	 * @return bool
	 */
	public function canView($member = null) {
		if(!$member) {
			$member = Member::currentUser();
		}
		if(!$member) {
			return false;
		}
		return ($this->MemberID == $member->ID);
	}
	/**
	 * 
	 * @param Member $member
	 * @param type $message
	 * @return int - the new Notification primary id
	 */
	public static function notify(Member $member, $message) {
		if(!$member->ID) {
			return false;
		}
		if(!Member::get()->byID($member->ID)) {
			return false;
		}
		$notification = new Notification();
		$notification->MemberID = $member->ID;
		$notification->Message = $message;
		$id = $notification->write();
		return $id;
	}

	/**
	 * Short hand for getting all notifications for a member
	 * 
	 * @param Member $member
	 * @return type
	 */
	public static function get_unread(Member $member) {
		return Notification::get()->filter(array(
			'MemberID' => $member->ID,
			'Read' => 0
		));
	}
	
	/**
	 * Short hand for getting all notifications for a member
	 * 
	 * @param Member $member
	 * @return type
	 */
	public static function get_starred(Member $member) {
		return Notification::get()->filter(array(
			'MemberID' => $member->ID,
			'Starred' => 1
		));
	}
	
	/**
	 * 
	 * @param Member $member
	 */
	public static function get_all(Member $member) {
		return Notification::get()->filter(array(
			'MemberID' => $member->ID
		));
	}
	
	/**
	 * 
	 */
	public function markAsRead() {
		$this->Read = 1;
		$this->write();
	}
	
	/**
	 * 
	 */
	public function markAsStarred() {
		$this->Starred = 1;
		$this->write();
	}
	
	/**
	 * 
	 */
	public function markAsNotStarred() {
		$this->Starred = 0;
		$this->write();
	}
}
