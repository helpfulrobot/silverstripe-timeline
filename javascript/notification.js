/*jslint browser: true, nomen: true,  white: true */ /*global  $, jQuery*/

jQuery(function($) {
	"use strict";
	
	$.entwine('ss', function($) {
		
		$('.cms-logo-header').entwine({
			/** 
			 * Add a notification box underneath the 
			 */
			onmatch: function() {
				var notificationBox = '<div class="cms-notification-status"><a href="/admin/notifications"><span class="count">0</span> notifications</a></div>';
				this.after(notificationBox);
			},
		});
		
		
		$('.cms-notification-status a').entwine({
			
			setCount: function(data) {
				this.children('.count').html(data.count);
			},
			
			/**
			 * Variable: PingIntervalSeconds
			 * (Number) Interval in which /notification/count will be checked
			 */
			PingIntervalSeconds: 5,
			
			/**
			 * 
			 * Sets up the pinging
			 */
			onmatch: function() {
				var self = this;
				// first get the latest count
				jQuery.ajax({
					url: 'admin/notifications/count',
					global: false,
					type: 'POST',
					dataType: "json",
				}).done(function(data) {
					self.setCount(data);
				});
				// then setup the automatic pinging
				this._setupPinging();
			},
			
			/**
			 * 
			 * 
			 */
			_setupPinging: function() {
				
				var self = this;
				
				// setup pinging for notification count
				setInterval(function(){
					jQuery.ajax({
						url: 'admin/notifications/count',
						global: false,
						type: 'POST',
						dataType: "json",
					}).done(function(data) {
						self.setCount(data);
					});
				}, this.getPingIntervalSeconds() * 1000);
			}
		});
	});
});
