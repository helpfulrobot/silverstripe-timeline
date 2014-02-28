<?php

/**
 * NotificationsWall
 * 
 * This is an admin controller that allows a user to see their notifications
 *
 */
class NotificationsWall extends LeftAndMain {
	
	/**
	 *
	 * @var string
	 */
	private static $url_segment = 'notifications';
	
	/**
	 *
	 * @var array
	 */
	private static $allowed_actions = array(
		'count',
		'index'
	);
	
	/**
	 *
	 * @var string
	 */
	private static $menu_title = 'Wall';

	/**
	 *
	 * @var string
	 */
	private static $menu_icon = "timeline/images/notifications.png";

	/**
	 *
	 * @var string
	 */
	private static $tree_class = 'Notification';

	public function init() {
		parent::init();
		if(!$this->request->isAjax()) {
			Requirements::css('timeline/css/notification-wall.css');
		}
	}
	
	/**
	 * Everyone should be able to see their notifications
	 * 
	 * @param Member $member
	 * @return boolean
	 */
	public function canView($member = null) {
		return true;
	}
	
	/**
	 * Returns the unread count in a JSONobject
	 * 
	 * @return SS_HTTPRequest
	 */
	public function count() {
		$notifications = Notification::get_unread(Member::currentUser());
		$response = new SS_HTTPResponse(
			json_encode(array('count' => $notifications->count())), 
			200
		);
		$response->addHeader('Content-Type', 'application/json');
		return $response;
	}
	
	/**
	 * 
	 * @param int $id
	 * @param FieldList $fields
	 * @return Form
	 */
	public function getEditForm($id = null, $fields = null) {
		
		$fields = new FieldList();
		
		$notifications = Notification::get_all(Member::currentUser());
		
		$gridField = new GridField(
			'Notifications',
			'Notifications',
			$notifications,
			new NotificationGridFieldConfig()
		);
		
		$fields->add($gridField);
		
		$form = CMSForm::create($this, "EditForm", $fields, new FieldList())->setHTMLID('Form_EditForm');
		
		$form->setResponseNegotiator($this->getResponseNegotiator());
		$form->addExtraClass('cms-edit-form');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		$form->setAttribute('data-pjax-fragment', 'CurrentForm');
		
		return $form;
	}
}
