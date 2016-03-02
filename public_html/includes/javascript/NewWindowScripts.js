/*  this is used to open Link in a New window and replaces: 'target="_blank" ' */

function externalLinks() 
{ 
	if (!document.getElementsByTagName) return; 
	
	var anchors = document.getElementsByTagName("a"); 
	for (var i=0; i<anchors.length; i++) 
	{ 
		var anchor = anchors[i]; 
		if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "external")
		
	    anchor.target = "_blank"; 
	} 

} 

window.onload = externalLinks;


/*  OPen New Window of specified size */

function openWindow(link,w,h)
{
	var win = 'width='+w+',height='+h+',status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0';
	NewWin = window.open(link,'NewWin',win);
				
}
			