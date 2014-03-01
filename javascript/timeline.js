/*jslint browser: true, nomen: true,  white: true */ /*global  $, jQuery*/

jQuery(function($) {
	"use strict";
	
	$.entwine('ss', function($) {
		
		$('#Menu-Timeline span.text').entwine({
			/** 
			 * Add a notification box underneath the 
			 */
			onmatch: function() {
				var notificationBox = ' <span class="cms-notification-status"><span class="count"></span></span>';
				this.append(notificationBox);
			},
		});
		
		
		$('.cms-notification-status').entwine({
			
			setCount: function(data) {
				if(typeof data.count === "undefined")  {
					return;
				}
				
				if(data.count > 0) {
					this.children('.count').html('(' + data.count + ')');
				}
				
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
					url: 'admin/timeline/count',
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
						url: 'admin/timeline/count',
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
