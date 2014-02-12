<?php

/**
 * Description of NotificationTest
 *
 */
class NotificationTest extends SapphireTest {
	
	/**
	 *
	 * @var string
	 */
	public static $fixture_file = 'NotificationTest.yml';
	
	/**
	 * 
	 */
	public function testClassExists() {
		$this->assertTrue(new Notification instanceof Notification);
	}
	
	/**
	 * 
	 */
	public function testNotifyNonExistingMemberReturnsFalse() {
		$member = new Member();
		$this->assertFalse(Notification::notify($member, 'this is a message'));
	}
	
	/**
	 * 
	 */
	public function testNotifyNotSavedMemberReturnsFalse() {
		$member = new Member();
		$member->Email = 'testuser@test.com';
		$this->assertFalse(Notification::notify($member, 'this is a message'));
	}
	
	/**
	 * 
	 */
	public function testGetUnreadNotificationCountForMemberIsZero() {
		$member = $this->objFromFixture('Member', 'reciever');
		$unread = Notification::get_unread($member);
		$this->assertEquals(0, $unread->count());
	}
	
	/**
	 * 
	 */
	public function testGetUnreadNotificationCountForMemberIsOne() {
		$member = $this->objFromFixture('Member', 'reciever');
		$notificationID = Notification::notify($member, 'this is a message');
		$this->assertNotEquals(false, $notificationID, 'Ensure that the notification got saved');
		
		$unread = Notification::get_unread($member);
		$this->assertEquals(1, $unread->count());
		$this->assertEquals('this is a message', $unread->first()->Message);
	}
	
	
	public function testMarkNotificationAsRead() {
		$member = $this->objFromFixture('Member', 'reciever');
		
		Notification::notify($member, 'Im message to u!');
		$unread = Notification::get_unread($member);
		
		$this->assertEquals(1, $unread->count());
		$unread->first()->markAsRead();
		$this->assertEquals(0, Notification::get_unread($member)->count());
	}
	
	/**
	 * 
	 */
	public function testMarkNotificationAsStarred() {
		$member = $this->objFromFixture('Member', 'reciever');
		Notification::notify($member, 'Im important message to u!');
		
		$this->assertEquals(0, Notification::get_starred($member)->count());
		
		$notifications = Notification::get_all($member);
		$this->assertEquals(1, $notifications->count());
		$notifications->first()->markAsStarred();
		
		$this->assertEquals(1, Notification::get_starred($member)->count());
		$notifications->first()->markAsNotStarred();
		$this->assertEquals(0, Notification::get_starred($member)->count());
	}
}
