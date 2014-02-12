<?php

/**
 * Description of NotificationGridFieldConfig
 *
 */
class NotificationGridFieldConfig extends GridFieldConfig {
	
	
	/**
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {
		$this->addComponent(new NotificationColumns());
	}
}
