<?php

/**
 * TimelineEventTest
 *
 */
class TimelineEventTest extends SapphireTest {
	
	/**
	 *
	 * @var string
	 */
	public static $fixture_file = 'TimelineEventTest.yml';
	
	/**
	 * 
	 */
	public function testClassExists() {
		$this->assertTrue(new TimelineEvent instanceof TimelineEvent);
	}
	
	/**
	 * 
	 */
	public function testNotifyNonExistingMemberReturnsFalse() {
		$member = new Member();
		$this->assertFalse(TimelineEvent::notify($member, 'this is a message'));
	}
	
	/**
	 * 
	 */
	public function testNotifyNotSavedMemberReturnsFalse() {
		$member = new Member();
		$member->Email = 'testuser@test.com';
		$this->assertFalse(TimelineEvent::notify($member, 'this is a message'));
	}
	
	/**
	 * 
	 */
	public function testGetUnreadTimelineEventCountForMemberIsZero() {
		$member = $this->objFromFixture('Member', 'reciever');
		$unread = TimelineEvent::get_unread($member);
		$this->assertEquals(0, $unread->count());
	}
	
	/**
	 * 
	 */
	public function testGetUnreadTimelineEventCountForMemberIsOne() {
		$member = $this->objFromFixture('Member', 'reciever');
		$notificationID = TimelineEvent::notify($member, 'this is a message');
		$this->assertNotEquals(false, $notificationID, 'Ensure that the notification got saved');
		
		$unread = TimelineEvent::get_unread($member);
		$this->assertEquals(1, $unread->count());
		$this->assertEquals('this is a message', $unread->first()->Message);
	}
	
	
	public function testMarkTimelineEventAsRead() {
		$member = $this->objFromFixture('Member', 'reciever');
		
		TimelineEvent::notify($member, 'Im message to u!');
		$unread = TimelineEvent::get_unread($member);
		
		$this->assertEquals(1, $unread->count());
		$unread->first()->markAsRead();
		$this->assertEquals(0, TimelineEvent::get_unread($member)->count());
	}
	
	/**
	 * 
	 */
	public function testMarkTimelineEventAsStarred() {
		$member = $this->objFromFixture('Member', 'reciever');
		TimelineEvent::notify($member, 'Im important message to u!');
		
		$this->assertEquals(0, TimelineEvent::get_starred($member)->count());
		
		$notifications = TimelineEvent::get_all($member);
		$this->assertEquals(1, $notifications->count());
		$notifications->first()->markAsStarred();
		
		$this->assertEquals(1, TimelineEvent::get_starred($member)->count());
		$notifications->first()->markAsNotStarred();
		$this->assertEquals(0, TimelineEvent::get_starred($member)->count());
	}
}
