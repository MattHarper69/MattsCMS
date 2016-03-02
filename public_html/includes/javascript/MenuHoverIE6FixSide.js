//	Used for "dropdown" / "Slide-out" CSS menus WHERE IE6 or earlier

MenuHover = function() 
{
	var SideLis = document.getElementById("MenuSide").getElementsByTagName("LI");
	for (var i = 0; i < SideLis.length; i++) 
	{
		SideLis[i].onmouseover=function() 
		{
			this.className+=" over";
		}
		SideLis[i].onmouseout=function() 
		{
			this.className=this.className.replace(new RegExp(" over\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", MenuHover);