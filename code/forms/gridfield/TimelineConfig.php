<?php

/**
 * TimelineConfig
 *
 */
class TimelineConfig extends GridFieldConfig {
	
	/**
	 * 
	 */
	public function __construct() {
		$this->addComponent(new TimelineColumns());
	}
}
