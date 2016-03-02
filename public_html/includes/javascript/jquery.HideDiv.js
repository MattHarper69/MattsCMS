$(document).ready(function()
{

	// display the DOWN arrow when the page loads
	$('#PageGeneralSettingsHeading').addClass("OpenDiv");
		
	$('#PageGeneralSettingsHeading').click(function() 		
	{
		$('#PageGeneralSettings').animate( {'height':'toggle'}, 'slow', 'easeOutCirc');
  
		//	if class="OpenDiv" -> class="ClosedDiv" { ie: display the DOWN arrow on alternate clicks}
		$('#PageGeneralSettingsHeading').toggleClass("OpenDiv");
		//	if class="ClosedDiv" -> class="OpenDiv" { ie: display the UP arrow on alternate clicks}
		$('#PageGeneralSettingsHeading').toggleClass("CloseDiv");
  
	}); 
});

$(document).ready(function()
{

	// display the DOWN arrow when the page loads
	$('#PageLayoutHeading').addClass("OpenDiv");
		
	$('#PageLayoutHeading').click(function() 		
	{
		$('#PageLayout').animate( {'height':'toggle'}, 'slow', 'easeOutCirc');
  
		//	if class="OpenDiv" -> class="ClosedDiv" { ie: display the DOWN arrow on alternate clicks}
		$('#PageLayoutHeading').toggleClass("OpenDiv");
		//	if class="ClosedDiv" -> class="OpenDiv" { ie: display the UP arrow on alternate clicks}
		$('#PageLayoutHeading').toggleClass("CloseDiv");
  
	}); 
});