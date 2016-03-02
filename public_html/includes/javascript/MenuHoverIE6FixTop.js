//	Used for "dropdown" / "Slide-out" CSS menus WHERE IE6 or earlier

MenuHover = function() 
{
	var TopLis = document.getElementById("MenuTop").getElementsByTagName("LI");
	for (var i = 0; i < TopLis.length; i++) 
	{
		TopLis[i].onmouseover=function() 
		{
			this.className+=" over";
		}
		TopLis[i].onmouseout=function() 
		{
			this.className=this.className.replace(new RegExp(" over\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", MenuHover);