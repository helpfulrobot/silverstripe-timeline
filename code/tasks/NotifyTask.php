<?php

/**
 * Description of NotifyTask
 *
 */
class NotifyTask extends BuildTask {
	
	/**
	 * @var string $title Shown in the overview on the {@link TaskRunner} HTML or CLI interface. Should be short and concise, no HTML allowed.
	 */
	protected $title = 'Notify user';
	
	/**
	 * @var string $description Describe the implications the task has, and the changes it makes. Accepts HTML formatting.
	 */
	protected $description = 'Send a message to a user';
	
	/**
	 * 
	 * @param SS_HTTPRequest $request
	 */
	public function run($request) {
		$memberEmail = $request->requestVar('email');
		$message = trim($request->requestVar('message'));
		
		if(!$memberEmail) {
			echo 'Please provide an email, eg ?email=user@example.com'.PHP_EOL;
			exit(1);
		}
		
		$members = Member::get()->filter('Email', $memberEmail);
		if(!$members->count()) {
			echo 'Please provide an existing member email'.PHP_EOL;
			exit(1);
		}
		
		$member = $members->first();
		
		if(!$message) {
			echo 'Please provide a message, eg ?message=hello'.PHP_EOL;
			exit(1);
		}
		
		TimelineEvent::notify($member, $message);
		
		echo 'Member '.$member->Email.' has been notified'.PHP_EOL;
	}
}
