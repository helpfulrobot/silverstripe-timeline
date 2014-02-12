<?php

/**
 * Description of NotifyTask
 *
 */
class NotifyTask extends BuildTask {
	
	/**
	 * 
	 * @param SS_HTTPRequest $request
	 */
	public function run($request) {
		$memberEmail = $request->requestVar('email');
		$message = trim($request->requestVar('message'));
		
		if(!$memberEmail) {
			echo 'Please provide an email'.PHP_EOL;
			exit(1);
		}
		
		$members = Member::get()->filter('Email', $memberEmail);
		if(!$members->count()) {
			echo 'Please provide a valid email'.PHP_EOL;
			exit(1);
		}
		
		$member = $members->first();
		
		if(!$message) {
			echo 'Please provide a message'.PHP_EOL;
			exit(1);
		}
		
		Notification::notify($member, $message);
		
		echo 'Member '.$member->Email.' has been notified'.PHP_EOL;
	}
}
