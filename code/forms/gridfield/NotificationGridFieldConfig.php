<?php

/**
 * NotificationGridFieldConfig
 *
 */
class NotificationGridFieldConfig extends GridFieldConfig {
	
	/**
	 * 
	 */
	public function __construct() {
		$this->addComponent(new NotificationColumns());
	}
}
