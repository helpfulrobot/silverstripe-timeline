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
		'count'
	);
	
	/**
	 *
	 * @var string
	 */
	private static $menu_title = 'Notifications';

	/**
	 *
	 * @var string
	 */
	private static $menu_icon = "notification/images/notifications.png";

	/**
	 *
	 * @var string
	 */
	private static $tree_class = 'Notification';

	public function init() {
		parent::init();
		if(!$this->request->isAjax()) {
			Requirements::css('notification/css/notification-wall.css');
		}
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
		
		$gridField = new GridField(
			'Notifications',
			'Notifications',
			Notification::get_all(Member::currentUser()),
			new NotificationGridFieldConfig()
		);
		$fields->add($gridField);
		
		$form = CMSForm::create( 
				$this, "EditForm", $fields, new FieldList()
			)->setHTMLID('Form_EditForm');
		
		$form->setResponseNegotiator($this->getResponseNegotiator());
		$form->addExtraClass('cms-edit-form');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		$form->setAttribute('data-pjax-fragment', 'CurrentForm');
		
		
		return $form;
	}
	
	
}
