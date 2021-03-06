<?php

/**
 * NotificationsWall
 * 
 * This is an admin controller that allows a user to see their notifications
 *
 */
class Timeline extends LeftAndMain {
	
	/**
	 *
	 * @var string
	 */
	private static $url_segment = 'timeline';
	
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
	private static $menu_title = 'Timeline';

	/**
	 *
	 * @var string
	 */
	private static $menu_icon = "timeline/images/notifications.png";

	/**
	 *
	 * @var string
	 */
	private static $tree_class = 'TimelineEvent';

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
	 * @return SS_HTTPResponse
	 */
	public function count() {
		$notifications = TimelineEvent::get_unread(Member::currentUser());
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
		
		$notifications = TimelineEvent::get_all(Member::currentUser());
		
		$gridField = new GridField(
			'TimelineEvents',
			'Timeline',
			$notifications,
			new TimelineConfig()
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
