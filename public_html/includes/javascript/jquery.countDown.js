
jQuery.fn.countdown = function(options) 
{

	if(!options) options = '()';
	if(jQuery(this).length == 0) return false;
	var obj = this;	


	if(options.seconds < 0 || options.seconds == 'undefined')
	{
		if(options.callback) eval(options.callback);
		return null;
	}


	window.setTimeout(
		function() {
			jQuery(obj).html(String(options.seconds));
			--options.seconds;
			jQuery(obj).countdown(options);
			
			//	turn red under a min
			if (options.seconds < 60)
			{			
				$('.countdown').addClass('WarningMSG');
			}
			
			//	beep @ 1min, 30sec under 10sec
			if (options.seconds == 60 || options.seconds == 30 ||options.seconds < 10)
			{
				
				PlaySound('WarningBeep');
				//alert ( 'you will be logged-out in less than: ' + options.seconds + 'seconds');	

			}

			
		}
		, 1000
	);	


    return this;
}
