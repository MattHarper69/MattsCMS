
	current_div_name = "NoModSet";
	current_mod_id = 0;
	current_mod_type_id = 0;
	content_has_changed = 0;
	new_content = ''; 
	old_content = ''; 
	drag_mod_mode = 0;
	text_html_mode = "text";
	//FontColour = 'black';


//	=================================================================================
//				Misc. Functions				=========================================
//	=================================================================================	

	function countInstances(str, word) 
	{
		var substrings = str.split(word);
		
		return substrings.length - 1;	
	}

	function CloseConfirmPanels()
	{
		$("#CMS_ConfirmDeletePage").hide();
		$(".CMS_ConfirmDeleteMod").hide();
		$(".CMS_ConfirmMovMod").hide();	
	}	
	
	function GetHighlightedText()
	{           
		if (window.getSelection) {  // all browsers, except IE before version 9
			var selectionRange = window.getSelection ();                                        
			return  selectionRange.toString ();
		} 
		else {
			if (document.selection.type == 'None') {
				alert ("No content wass selected");
				return  '';
			}
			else {
				var textRange = document.selection.createRange ();
				return textRange.text;
			}
		}	
	}
	
//	=================================================================================



//	Create and Read Cookies
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(";");
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==" ") c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}


//================================================================

//	Play a sound
function PlaySound(soundObj) 
{   
	var sound = document.getElementById(soundObj);   
	sound.Play(); 
} 


/* 
	function OpenCloseNextDiv()
	{
		//$(this).hide();
		$(this).parent().next().show(400);

		//$(".HideAtStart").show(400);	//	testing
		alert('testing')
	}

	function CloseThisPanel()
	{
		//$(this).parent().hide(400);
		$(".HideAtStart").hide(400);	//	testing
	
	}
	
 */

//	=================================================================================


$(document).ready(function()
{

	//	Show then Hide Update Success Msg	
	$(".UpdateMsgDiv" ).show(600).delay( 6000 ).hide(400);		
	
	//	=================================================================================
	//		Open / Close   Next / This Panel
	//	=================================================================================
	
	$(".OpenCloseNextDiv").click(	
		function() 
		{
			$(this).parent().next().toggle(400);
		}
	)
	
	$(".CloseThisPanel").click(	
		function() 
		{
			$(this).parent().hide(400);
			CloseConfirmPanels();
			$(".ConfirmDeleteButton").show();
					
		}
	)	

	$(".ConfirmDeleteButton").click(	
		function() 
		{
			$(this).parent().next().toggle(400);
			$(this).hide();
		}
	)


	
	
	//	=================================================================================
	//		misc. Slide-out Panels
	//	=================================================================================	
	//	show
	$(".SlideDownShow").show("slide", { direction: "up" }, 400);

			
	
	//	hide (cancel)
	$(".SlideDownShow .Cancel").click(function()
	{
		$(".SlideDownShow").hide("slide", { direction: "up" }, 400)
	});	

	//	=================================================================================		
	//		Drag Panel
	//	=================================================================================	
	
	$("#CMS_Panel").draggable(
	{ 	 
		 containment: "document"
		,handle: "#CMS_PanelTitleBar"
		,opacity: 0.5
		,appendTo: "body"
		 
		,stop: function (event, ui) 
		{ 
			 createCookie("CMSposX", ui.position.left, 10); 
			 createCookie("CMSposY", ui.position.top, 10); 
		} 
		
	});	
	
	//	=================================================================================
	//		 Panel Window Display Settings	=====================================
	//	=================================================================================


	//	Cookie: "CMSwinState" - 0 = normal - draggable
	//							1 = normal - Pinned
	//							2 = Maximized
	//							3 = Minimized

	
	if (readCookie("CMSwinState") < 1)
	{		
		EnableDrag()		
	}

	if (readCookie("CMSwinState") == 1)
	{	
		DisableDrag()
	}	

	if (readCookie("CMSwinState") == 2)
	{	
		MaximizePanel()
	}	

	if (readCookie("CMSwinState") == 3)
	{	
		MinimizePanel()
	}		
	
	

	
	//	Close all hidden Panels and Buttons by Default at start
	
	$(".CMS_EditHTMLPanel" ).hide();	
	$(".CMS_WysiwygToolbar").hide();	
	$(".CMS_EditModToolBar").hide();
	$(".CMS_ModConfigPanel").hide();
	$(".CMS_ModUploadAndLinkPanel").hide();
	$("#CMS_PageOptionsPanel").hide();
	$("#CMS_PageNavOptionsPanel").hide();	
	//$("#CMS_SyncOptionsPanel").hide();
	//$("#CMS_PageSecurityOptionsPanel").hide();
	$(".CMS_AddPageOptionsPanel").hide();
	$(".CMS_ClonePageOptionsPanel").hide();
	$("#CMS_ConfirmDeletePage").hide();
	$(".CMS_ConfirmDeleteMod").hide();
	$(".CMS_ConfirmMovMod").hide();

	$(".HideAtStart").hide();
	
	$(".CMS_Button_ModConfigClose").hide();	
	$(".CMS_Button_ModUploadAndLinkClose").hide();	
	$(".CMS_Button_ModInfoClose").hide();
	$(".CMS_Button_OpenTEXTPanel").hide();
	$(".CMS_Button_SaveModHtml").hide();
	$(".CMS_Button_ClosePageOptionsPanel").hide();
	$(".CMS_Button_ClosePageNavOptionsPanel").hide();	
	//$(".CMS_Button_CloseSyncOptionsPanel").hide();
	//$(".CMS_Button_ClosePageSecurityOptionsPanel").hide();
	$(".CMS_Button_CloseAddPageOptionsPanel").hide();
	$(".CMS_Button_CloseClonePageOptionsPanel").hide();	
	$(".CMS_Button_DragModStop").hide();
	
	$(".EditDivModDisplay").children().hide();
	//$(".EditDivModDisplay").css("position", "absolute");
	$(".EditDivModDisplay").css("position", "relative");
	$(".EditDivModDisplay").css("width", "16px");
	$(".EditDivModDisplay").css("height", "16px");
	$(".EditDivModDisplay").css("background-color", "#00ffff");
	$(".EditDivModDisplay").css("background-image", "url(/images_misc/icon_alert_16x16.png)");
	$(".EditDivModDisplay").css("background-repeat", "no-repeat");
	$(".EditDivModDisplay").css("background-position", "0 0");
	
	$(".InActiveModDisplay").children().hide();
	//$(".InActiveModDisplay").css("position", "absolute");
	$(".InActiveModDisplay").css("position", "relative");	
	$(".InActiveModDisplay").css("width", "16px");
	$(".InActiveModDisplay").css("height", "16px");
	$(".InActiveModDisplay").css("background-color", "#00ffff");
	$(".InActiveModDisplay").css("background-image", "url(/images_misc/icon_alert_16x16.png)");	
	$(".EditDivModDisplay").css("background-repeat", "no-repeat");
	$(".EditDivModDisplay").css("background-position", "0 0");
/* 	
	//	Need to give some space to prevent multiple adjacent hidden mod icons from being hidded
	$(".InActiveModDisplay").hover
	(
		function()
		{
			$(".InActiveModDisplay").css("position", "relative")
			$(".InActiveModDisplay").css("margin", "-10px")		
		}
		,
		function()
		{		
			$(".InActiveModDisplay").css("position", "absolute")
			$(".InActiveModDisplay").css("margin-top", "0")		
		}
	);
	 */
	//	=================================================================================

  	
	//	Hilight Editable Mod areas when hovering over them
  	$(".HoverShow").hover
	(

        function () 
		{		
			$(this).css("outline", EditHilightStyle);
		}
 		,
        function () 
		{			
			if($(this).attr("id") != current_div_name)
			{
				$(this).css("outline", "none");	
			}			

		}	 
		
	); 



	
	//	=================================================================================
	//		 Show (Sometimes) HIDDEN Mod on Hover	=====================================
	//	=================================================================================
	//	Show Hidden Mods when hovering over
  	$(".InActiveModDisplay").hover
	(

        function () 
		{

			$(this).css("width", "48px");
			$(this).css("height", "24px");
			
			$(this).next().next().css("outline", EditHilightStyle);
			

			
			$(this).children().show();
			

		}
  		,
        function () 
		{
			
			$(this).css("width", "16px");
			$(this).css("height", "16px");

			$(this).next().next().css("outline", "none");
			
			$(this).children().hide();
			

		}	
		
	); 	
	
	//	Show Start Div Mods and highlite them when hovering over
	$(".EditDivModDisplay").hover
	(

        function () 
		{

			ExistingOutline = $(this).parent("div").css("outline");
		
			$(this).css("width", "240px");
			$(this).css("height", "48px");

			$(this).css("background-color", "transparent");
			$(this).css("background-image", "none");		
			$(this).parent("div").css("outline", EditHilightStyle);
			
			$(this).children("p").show();

		}
		
  		,
		function () 
		{

			$(this).css("width", "16px");
			$(this).css("height", "16px");

			$(this).css("background-color", "#00ffff");
			$(this).css("background-image", "url(/images_misc/icon_alert_16x16.png)");

				
			if($(this).parent("div").attr("id") != current_div_name)
			{
				$(this).parent("div").css("outline", ExistingOutline);			
			}


			$(this).children("p").hide();

		}	
		
	); 	

	//	=================================================================================	
	
	//	need to Save Mod data when Locking or Activating/Deactivating
	//		OR
	//	Do confirm Save content when delete / lock / unlock / active /de-activating
	//	(still under construction)

	 
	$(".CMS_Button_ModUpdate").click(function()
	{
		//alert("mod id=" + current_mod_id);
		SaveModDataText(current_mod_id, true);

	});
	
	//	=================================================================================
	
	//	Check if content has changed
	$(".CMS_Button_ModConfigOpen").click(function(){
	
		if (content_has_changed != 0)
		{
			alert ("You have made changes to the content on this page.\n\n"
					+ "You should save these changes before continuing.\n\n"
		
				//	+ "To do this:\n\nClose the 'Configure this Module' window and click the 'Save' icon\n\n"
				);
		}
	
	});


	//	=================================================================================
	//			TAB NAV for PAGE options Panels		=========================================
	//	=================================================================================
	//$( '#PageGeneralSettingsTabs div:not(:first)' ).hide();
	  
	$("#PageGeneralSettingsTabNav li").click(function(e) 
	{
		$("#PageGeneralSettingsTabs div.AdminFormTabPanel").hide();
		$("#PageGeneralSettingsTabNav .current").removeClass("current");
		$(this).addClass("current");
		
		var clicked = $(this).find("a:first").attr("href");
		$("#PageGeneralSettingsTabs " + clicked).show();
		//	need to show mini layout
		$(".CMS_MiniPageLayout").show();
		e.preventDefault();
	
	});	
	
	$("#PageNavSettingsTabNav li").click(function(e) 
	{
		$("#PageNavSettingsTabs div").hide();
		$("#PageNavSettingsTabNav .current").removeClass("current");
		$(this).addClass("current");
		
		var clicked = $(this).find("a:first").attr("href");
		$("#PageNavSettingsTabs " + clicked).show();
		e.preventDefault();
	});	
	
	//	=================================================================================
	//			TAB NAV for Clone PAGE options Panels		=========================================
	//	=================================================================================
	

		//	set first tab open as default
		$( "#ClonePageTabs div:not(:first)" ).hide();
		$( "#TabPanel_Clone_1").show();
		$( "#ClonePageTabNav .current").removeClass("current");
		$( "#OpenTabPanel_Clone_1").addClass("current");

		
	$("#ClonePageTabNav li").click(function(e) 
	{
		$("#ClonePageTabs div.AdminFormTabPanel").hide();
		$("#ClonePageTabNav .current").removeClass("current");
		$(this).addClass("current");
		
		var clicked = $(this).find("a:first").attr("href");
		$("#ClonePageTabs " + clicked).show();
		e.preventDefault();
	
	});		
	
	
	//	=================================================================================
	//			TAB NAV for MOD CONFIG options Panels		=========================================
	//	=================================================================================

	$("#ModConfigSettingsTabs div.AdminFormTabPanel").hide();
	$("#ModConfigSettingsTabs div.OpenFirst").show();  
	$(".TabPanelNavLinks").eq(0).addClass('current');
	
	$("#ConfigModOptionsTabNav li").click(function(e) 
	{
		$("#ModConfigSettingsTabs div.AdminFormTabPanel").hide();
		$("#ConfigModOptionsTabNav .current").removeClass("current");
		$(this).addClass('current');
		
		var clicked = $(this).find('a:first').attr('href');
		$("#ModConfigSettingsTabs " + clicked).show();
		e.preventDefault();
	}); 


	//	=================================================================================
	//		 TAB NAV for Generic CONFIG Panels 	=====================================
	//	=================================================================================	

	$("#TabNavConfigMod li").click(function(e) 
	{
		$("#TabNavConfigMod_Tabs div.AdminFormTabPanel").hide();
		$("#TabNavConfigMod .current").removeClass("current");
		$(this).addClass("current");
		
		var clicked = $(this).find("a:first").attr("href");
		$("#TabNavConfigMod_Tabs " + clicked).show();
		e.preventDefault();
		
		//	populate hidden input with tab ID to send to update page
		var name = $(this).attr("name");
		$('#TabNavPanel_ID_Post').val(name)
		
	});
	
	
	
	
	
	
	//	=================================================================================
	//		Check if "Requires Log-in" and "include in siteMap" Checked		=================================
	//	=================================================================================	
	if (!$('#PageOptionsRequiresLogin').is(':checked') ) 
	{
		$("#UpdatePageOptionsAccessCode").hide();
	}
	
	if (!$('#PageOptionsIncludeInSiteMap').is(':checked') ) 
	{
		$("#UpdatePageOptionsPriority").hide();
	}
	
	
//	=================================================================================
//		 Sortable Items 	=====================================
//	=================================================================================		
		
	$( ".sortableItems" ).sortable(
	{
		 opacity: 0.6
		,stop: function(){

		
			var ItemPosArray = $(".sortableItems").sortable("toArray");
			$('#ItemPosArray').attr('value', ItemPosArray)
//alert(ItemPosArray);
		}
		,revert: true
	});	
	
	$(".sortableItems").css("cursor", "move");
	

	
//	=================================================================================
//		 Maximize and Restore Colorbox Window Size 	=====================================
//	=================================================================================	
	
	$(".MaximizeWindow").click(function()
	{
		    var x = '100%';     
			var y = '100%';      
			parent.$.colorbox.resize({width:y, height:x});
			$(".MaximizeWindow" ).hide();
			$(".RestoreWindow" ).show();
			
	});

	$(".RestoreWindow").click(function()
	{
		    var x = '85%';     
			var y = '85%';      
			parent.$.colorbox.resize({width:y, height:x}); 
			$(".MaximizeWindow" ).show();
			$(".RestoreWindow" ).hide();
	});	


	//	=================================================================================
	//		Form Validation							=====================================
	//	=================================================================================	
	
	$('.ValidateNumbersOnly').keyup(function () 
	{ 
		if (this.value != this.value.replace(/[^0-9]/g, '')) 
		{
			this.value = this.value.replace(/[^0-9]/g, '');
		}
	});
	
	
});	//	END Document Ready


//	=================================================================================
//		 Panel Window Display Option Functions 	=====================================
//	=================================================================================
	
	//	PIN TOOLBAR
	function DisableDrag()
	{
		$("#CMS_Panel").draggable("disable");
		$(".CMS_Button_PinPanel").hide(); 
		$(".CMS_Button_UnPinPanel").show();
		$(".CMS_Button_RestorePanel").hide();
		$("#CMS_PanelTitleBar").css("cursor", "default");
		createCookie("CMSwinState", 1, 10); 
	}

	//	Un-PIN TOOLBAR
	function EnableDrag()
	{
		$("#CMS_Panel").draggable("enable"); 
		$(".CMS_Button_UnPinPanel").hide(); 
		$(".CMS_Button_PinPanel").show();
		$(".CMS_Button_RestorePanel").hide();
		$("#CMS_PanelTitleBar").css("cursor", "move");	
		createCookie("CMSwinState", 0, 10);  	
	}
	
	//	Maximize TOOLBAR	
	function MaximizePanel()
	{
		DisableDrag();
		$(".CMS_Button_UnPinPanel").hide();
		$(".CMS_Button_MaximizePanel").hide();
		$(".CMS_Button_MinimizePanel").show();
		$(".CMS_Button_RestorePanel").show();
		
		$(".CMS_ToolBar").show();
/* 	  
		$("#CMS_Panel").css("left", "0");
		$("#CMS_Panel").css("top", "0"); 
		$("#CMS_Panel").css("width", "100%");
		$("#CMS_Panel").css("height", "100%"); 
		$("#CMS_Panel").css("border", "none");
*/
		$("#CMS_Panel").animate(
			{
				left: "0px"
				,top: "0px"
				,width: "100%"
				,height: "100%"
					
			},200
		);
		
		$("#CMS_Panel").css("border", "none");
		
		createCookie("CMSwinState", 2, 10);  
	}

	//	Minimize TOOLBAR	
	function MinimizePanel()
	{
		DisableDrag();
		$(".CMS_Button_UnPinPanel").hide();
		$(".CMS_Button_MinimizePanel").hide();
		$(".CMS_Button_MaximizePanel").show();
		$(".CMS_Button_RestorePanel").show();
		
		ClosePageOptionsPanel();
		ClosePageNavOptionsPanel();
		CloseEditModPanel( current_mod_id, current_div_name );
		$(".CMS_ToolBar").hide();
/* 		
		$("#CMS_Panel").css("left", "0");
		$("#CMS_Panel").css("top", "auto");
		$("#CMS_Panel").css("bottom", "0"); 	  
		$("#CMS_Panel").css("width", "400px");
		$("#CMS_Panel").css("height", "24px"); 
		$("#CMS_Panel").css("border", "none");
 */
		$("#CMS_Panel").animate(
			{
				 width: "400px"
				,height: "24px"			
			},200
		);
		$("#CMS_Panel").css("left", "0");
		$("#CMS_Panel").css("top", "auto");		
		$("#CMS_Panel").css("bottom", "0"); 
		$("#CMS_Panel").css("border", "none");
		
		createCookie("CMSwinState", 3, 10);  
	}

	//	Restore TOOLBAR	
	function RestorePanel()
	{
		EnableDrag();
		$(".CMS_Button_MinimizePanel").show();
		$(".CMS_Button_MaximizePanel").show();
		$(".CMS_Button_RestorePanel").hide();

		ClosePageOptionsPanel();
		ClosePageNavOptionsPanel();
		//CloseEditModPanel( current_mod_id, current_div_name );
		$(".CMS_ToolBar").show();

		$("#CMS_Panel").css("left", readCookie("CMSposX") + "px");
		$("#CMS_Panel").css("top", readCookie("CMSposY") + "px"); 		
		$("#CMS_Panel").css("width", "auto");
		$("#CMS_Panel").css("height", "auto"); 
/* 

		$("#CMS_Panel").animate(
			{		
				 left: readCookie("CMSposX") + "px"
				,top: readCookie("CMSposY") + "px"	
				,width: "auto"
				,height: "auto"

			},200
		);	
*/		
		$("#CMS_Panel").css("bottom", "auto");
		
		$("#CMS_Panel").css("border-top", "solid #eeeeee 3px");
		$("#CMS_Panel").css("border-right", "solid #777777 3px");
		$("#CMS_Panel").css("border-bottom", "solid #000000 3px");
		$("#CMS_Panel").css("border-left", "solid #cccccc 3px"); 
		
		createCookie("CMSwinState", 0, 10);  
	}

	
	
	

//	=================================================================================
//		Test if Content(Editable) has changed		=================================
//	=================================================================================	
	function TextContentChangedFocus(div_name)
	{
		old_content = $("#" + div_name + " span").html();
	}


	function TextContentChangedBlur(div_name)
	{
		new_content = $("#" + div_name + " span").html();
		if (new_content.replace(" contentEditable=true", "") != old_content.replace("contentEditable=true", ""))
		{
			content_has_changed = div_name;


			//alert ("content has changed for: " + content_has_changed);

		}
		
	}	


//	=================================================================================
//				Mod Selection				=========================================
//	=================================================================================

	function selectMod2Edit( mod_type_id, mod_id, div_name, edit_enabled, locked )
	{
			
		//	Hide All Config Panels
		ClosePageOptionsPanel();
		ClosePageNavOptionsPanel();

		CloseConfirmPanels();

		//if (mod_id != current_mod_id && drag_mod_mode != 1)
		if 
		(
				drag_mod_mode != 1
			&&	mod_id != current_mod_id
			||	div_name != current_div_name
		)
		{

			//	place code in correct DOM
			$( "#CMS_EditModToolBar_" + mod_id ).appendTo($( "#CMS_ToolBarWrapper" ));	
			$( "#CMS_ModConfigPanel_" + mod_id ).appendTo($( "#CMS_ToolBarWrapper" ));
			
			//may not need this - removing code preserve wyswig changes when moving to other mods	
			//	load textarea (edit HTML panel) with data
			var HTMLcontent = jQuery.trim($("#" + div_name + " span").html());
					
			if (HTMLcontent != null)
			{
				HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
				HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
			}
			
			$("#CMS_EditHTMLPanel_" + mod_id + " textarea" ).val(HTMLcontent);
		
		
			//	reset Nav Tab for Mod Config
			$("#ModConfigSettingsTabs div.AdminFormTabPanel").hide();
			$("#ModConfigSettingsTabs div.OpenFirst").show();  
			$(".TabPanelNavLinks").eq(0).addClass('current');		
		
		//=============================
	
		//	Clean-up Previous selected Mod:
		
			//	remove high-lite
			$("#" + current_div_name).css("outline", "none");
			//	make un-editable		
			$("#" + current_div_name + " span").attr({contentEditable: "false"});

			//	remove ToolBars
			$("#CMS_EditModToolBar_" + current_mod_id).hide(200);			
			$("#CMS_WysiwygToolbar").hide();
			$("#CMS_ModConfigPanel_" + current_mod_id).hide();
			
			//	Close any previous open Mod Config Panels
			CloseModConfigPanel( current_mod_id );

			//	update data and Close the Edit HTML Panel - only if open and swiching mods
			if (text_html_mode == "html" && mod_id != current_mod_id)
			{			
				CloseEditHTML(current_mod_type_id, current_mod_id, current_div_name);	
			}
			
			
			
		//---------------------------------------------------------------------------	
			
			//	This Mod clicked...
	
			//	high-lite
			$("#" + div_name).css("outline", EditHilightStyle);	
			
			//	make editable if not locked or in HTML mode
			if (locked != 1 && text_html_mode != "html")
			{			
				$("#" + div_name + " span").attr({contentEditable: "true"});
				
				
				
				if (edit_enabled > 0)	
				{
					$("#CMS_WysiwygToolbar").animate({ 
					  opacity: "show",
					  height: "show"
					}, 400);

				}
				
			}
			
			//	show Locked icon if mod is locked
			if (locked == 1)
			{
				$("#" + div_name).css({
					 "background-image": "url(/images_misc/icon_lock_16x16.png)"
					,"background-repeat": "no-repeat"
					,"background-position": "16px 0"
				});
				
			}

			$("#CMS_EditModToolBar_" + mod_id).animate({ 
			  opacity: "show",
			  height: "show"
			}, 400);
			
			if (text_html_mode == "html")
			{
				$( "#CMS_Button_OpenHTMLPanel_" + mod_id ).hide();
			}

			current_div_name = div_name;
			current_mod_id = mod_id;
			current_mod_type_id = mod_type_id;

			
		};	
		
	};

//	=================================================================================
//				Open Mod Config Panel				=================================
//	=================================================================================

	function OpenModConfigPanel( mod_id )
	{
		$(".CMS_EditHTMLPanel" ).hide();	
		//$(".CMS_WysiwygToolbar").hide();
		$("#CMS_ConfirmDeletePage").hide();
		$(".CMS_ConfirmDeleteMod").hide();
		$(".CMS_ConfirmMovMod").hide();
		
		$("#CMS_ModConfigPanel_" + mod_id).show(400);

		$(".CMS_Button_ModConfigOpen").hide();
		$(".CMS_Button_ModConfigClose").show();
		$(".CMS_Button_ModInfoOpen").hide();
		$(".CMS_Button_ModInfoClose").show();

		$(".CMS_Button_OpenHTMLPanel").hide();
		
		$("#CMS_Panel").css("width", CMSPanelWindowWidth);
				
	}
	
	function CloseModConfigPanel( mod_id )
	{
		$("#CMS_ModConfigPanel_" + mod_id).hide(400);

		$(".CMS_Button_ModConfigClose").hide();
		$(".CMS_Button_ModConfigOpen").show();
		$(".CMS_Button_ModInfoClose").hide();
		$(".CMS_Button_ModInfoOpen").show();

		$(".CMS_Button_OpenHTMLPanel").show();
		
		$("#CMS_Panel").css("width", "auto");
				
	}	

//	=================================================================================
//				Open Upload and Link Panel				=================================
//	=================================================================================

	function OpenModUploadAndLinkPanel( mod_id )
	{
		$(".CMS_EditHTMLPanel" ).hide();	
		$(".CMS_WysiwygToolbar").hide();
		$("#CMS_ConfirmDeletePage").hide();
		$(".CMS_ConfirmDeleteMod").hide();
		$(".CMS_ConfirmMovMod").hide();
		
		$("#CMS_ModUploadAndLinkPanel_" + mod_id).show(400);

		$(".CMS_Button_ModUploadAndLinkOpen").hide();
		$(".CMS_Button_ModUploadAndLinkClose").show();
		$(".CMS_Button_ModUploadAndLinkOpen").hide();
		$(".CMS_Button_ModUploadAndLinkClose").show();

		$(".CMS_Button_OpenHTMLPanel").hide();
		
		$("#CMS_Panel").css("width", CMSPanelWindowWidth);
				
	}
	
	function CloseModUploadAndLinkPanel( mod_id )
	{
		$("#CMS_ModUploadAndLinkPanel_" + mod_id).hide(400);

		$(".CMS_Button_ModUploadAndLinkClose").hide();
		$(".CMS_Button_ModUploadAndLinkOpen").show();
		$(".CMS_Button_ModUploadAndLinkClose").hide();
		$(".CMS_Button_ModUploadAndLinkOpen").show();

		$(".CMS_WysiwygToolbar").show();
		$(".CMS_Button_OpenHTMLPanel").show();
		
		$("#CMS_Panel").css("width", "auto");
				
	}
	
//	=================================================================================
//				TEXT / HTML Switch				=====================================
//	=================================================================================	

	//	Show Edit HTML panel
	function openEditHTML(mod_type_id, mod_id, div_name)
	{
		
		text_html_mode = "html";
		
		CloseConfirmPanels();

		//	Close any previous open Mod Config Panels
		CloseModConfigPanel( mod_id );
		CloseModUploadAndLinkPanel( mod_id );
		$( "#CMS_WysiwygToolbar" ).hide(400);
		
		//	show correct HTML / TEXT SWITCH button
		$( "#CMS_Button_OpenTEXTPanel_" + mod_id ).show();
		$( "#CMS_Button_OpenHTMLPanel_" + mod_id ).hide();

		//	show correct HTML / TEXT SAVE button
		$( "#CMS_Button_SaveModHtml_" + mod_id ).show();
		$( "#CMS_Button_SaveModText_" + mod_id ).hide();	

		$(".CMS_Button_ModConfigOpen").hide();
		$(".CMS_Button_ModUploadAndLinkOpen").hide();
	
		//	For Table Mods:
		if (mod_type_id == 38)
		{


			//	Get html content from editible span and trim for FF
			
			$(".TableEditArea").each
			(
				function()
				{
					//	Get wysiwyg html
					var HTMLcontent = jQuery.trim($(this).html());
					
					//	Clean-up HTML
					if (HTMLcontent != null)
					{
						HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
						HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
					}
					
					//	load textarea with html content
					var TextArea = $(this).next().children();
					//alert (TextArea)
					$(TextArea).val(HTMLcontent);

					//	get dimentions of editable span and use for textarea
					var EditTextAreaWidth = $(this).parent().width();
					
					//	make some room in textbox to edit.....
					if (EditTextAreaWidth < 30)	
					{
						EditTextAreaWidth = 30;
						$(TextArea).css("overflow", "hidden" );
					}
					$(TextArea).css("width", EditTextAreaWidth );

					var EditTextAreaHeight = $(this).parent().height();	
					if (EditTextAreaHeight < 25)	{EditTextAreaHeight = 25;}
					$(TextArea).css("height", EditTextAreaHeight );				
					
				}
			);

			//	close wysiwig
			$(".TableEditArea").animate({ 
				opacity: "hide",
				height: "hide"
			}, 400);			
			
			//	open textbox
			$(".CMS_EditHTMLTable" ).animate({ 
				opacity: "show",
				height: "show"
			}, 400);		
		}
		
		//	For Text and Heading Mods:
		else
		{
			//	Get html content from editible span and trim for FF
			var HTMLcontent = jQuery.trim($("#" + div_name + " span").html());
			
			if (HTMLcontent != null)
			{
				HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
				HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
			}
			
			//	load textarea with html content
			$("#CMS_EditHTMLPanel_" + mod_id + " textarea" ).val(HTMLcontent);
			
			//	get dimentions of editable span and use for textarea
			var EditTextAreaWidth = $("#" + div_name ).width();	
			$("#CMS_EditHTMLPanel_" + mod_id + " textarea" ).css("width", EditTextAreaWidth );

			var EditTextAreaHeight= $("#" + div_name ).height();	
			$("#CMS_EditHTMLPanel_" + mod_id + " textarea" ).css("height", EditTextAreaHeight );

			//	close wysiwig
			$("#" + div_name).animate({ 
				opacity: "hide",
				height: "hide"
			}, 400);	
			
			//	open textbox
			$("#CMS_EditHTMLPanel_" + mod_id ).animate({ 
				opacity: "show",
				height: "show"
			}, 400);
		
			$("#CMS_EditHTMLPanel_" + mod_id ).css("outline", EditHilightStyle);			
		}


		
	};


	//	Hide Edit HTML panel
	function CloseEditHTML(mod_type_id, mod_id, div_name)
	{
				
		text_html_mode = "text";
		
		CloseConfirmPanels();

		$( "#CMS_WysiwygToolbar" ).show(400);

		//	show correct HTML / TEXT SWITCH button	
		$( "#CMS_Button_OpenTEXTPanel_" + mod_id ).hide();
		$( "#CMS_Button_OpenHTMLPanel_" + mod_id ).show();

		//	show correct HTML / TEXT SAVE button
		$( "#CMS_Button_SaveModHtml_" + mod_id ).hide();
		$( "#CMS_Button_SaveModText_" + mod_id ).show();

		$(".CMS_Button_ModConfigOpen").show();
		$(".CMS_Button_ModUploadAndLinkOpen").show();	
		
		//	For Table Mods:
		if (mod_type_id == 38)
		{
				
			//	need to save html textarea data before switching to TEXT mode
			$(".TableEditHtmlTextArea").each
			(
			
				function()
				{
					//	Get wysiwyg html
					var HTMLcontent = $(this).val();

					//	Clean-up HTML
					if (HTMLcontent != null)
					{
						HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
						HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
					}

					$(this).parent().prev().html(HTMLcontent);

			
					
				}
			);			
			
			//	close textbox
			$(".TableEditArea").animate({ 
				opacity: "show",
				height: "show"
			}, 400);			
			
			//	open wysiwig
			$(".CMS_EditHTMLTable" ).animate({ 
				opacity: "hide",
				height: "hide"
			}, 400);		
		}
		
		//	For Text and Heading Mods:
		else
		{
		
			//	need to save html textarea data before switching to TEXT mode
			var HTMLcontent = $("#CMS_EditHTMLPanel_" + mod_id + " textarea" ).val();

			if (HTMLcontent != null)
			{
				HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
				HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
			}
			
			$("#" + div_name + " span").html(HTMLcontent);
			
			$( "#CMS_EditHTMLPanel_" + mod_id ).animate({ 
				 opacity: "hide"
				,height: "hide"
			}, 400);

				
			$( "#" + div_name ).animate({ 
				 opacity: "show"
				,height: "show"
			}, 400);
		}
		

		
	};	

//	=================================================================================	
//		Show / Hide In-Active Module		=========================================
//	=================================================================================

	function showHideInActiveMod(mod_id, div_name, edit_enabled)
	{
		
		if (readCookie("CMS_ShowInActive_" + mod_id) == 1)
		{			
			//	Hide Mod:
			
			$( "#" + div_name ).animate({ 
				 opacity: "hide"
				,height: "hide"
			}, 400);
						
			//$("#CMS_EditModToolBar_" + current_mod_id).hide();
			
			$( "#CMS_EditHTMLPanel_" + mod_id ).hide(400);
			$( "#CMS_EditModToolBar_" + mod_id ).hide(400);
			//$( "#CMS_WysiwygToolbar" ).hide(400);
			
			$( "#CMS_Button_OpenTEXTPanel_" + mod_id ).hide();
			$( "#CMS_Button_OpenHTMLPanel_" + mod_id ).show();
			
			
			$("#CMS_Button_showHideInActiveMod_" + mod_id + " img").attr({
			
											 src: "/images_misc/Button_show.gif"
											,alt: "Show"
			
			});
			

			$(this).val("On");

			createCookie("CMS_ShowInActive_" + mod_id, 0, 10);
			//current_div_name = "NoModSet";
			//current_mod_id = 0;
		}
		else
		{
			//	Show Mod:
			
			$( "#" + div_name ).animate({ 
				opacity: "show",
				height: "show"
			}, 400);
					
/* 			
			//	place code in correct DOM
			$( "#CMS_EditModToolBar_" + mod_id ).appendTo($( "#CMS_ToolBarWrapper" ));
			
			$("#CMS_EditModToolBar_" + mod_id).animate({ 
			  opacity: "show",
			  height: "show"
			}, 400);
			
			if (edit_enabled > 0)	
			{
				$("#CMS_WysiwygToolbar").animate({ 
				  opacity: "show",
				  height: "show"
				}, 400);

			}	
 */
			$("#CMS_Button_showHideInActiveMod_" + mod_id + " img").attr({
			
											 src: "/images_misc/Button_hide.gif"
											,alt: "Hide"
			
			});
			
			//selectMod2Edit( mod_type_id, mod_id, div_name, 0, 0 )

			createCookie("CMS_ShowInActive_" + mod_id, 1, 10);
			
			//current_div_name = "NoModSet";
			//current_mod_id = 0;
		}

		
		//$( "#CMS_WysiwygToolbar" ).hide();

		$( "InActiveModDisplay_" + mod_id ).css("height", "20px");
		$( "InActiveModDisplay_" + mod_id).css("border", "dashed #ff00ff 2px");		


		
	};


//	=================================================================================
//				Close Edit Mod Panel		=========================================
//	=================================================================================
	
	function CloseEditModPanel( mod_id, div_name )
	{
		
		CloseConfirmPanels();
		
		//	Clean-up Previous selected Mod:
		//	remove high-lite
		$("#" + current_div_name).css("outline", "none");
		//	make un-editable
		$("#" + current_div_name + " span").attr({contentEditable: "false"});
		
		$("#CMS_ModConfigPanel_" + mod_id).hide();
		$("#CMS_ModUploadAndLinkPanel_" + mod_id).hide();

		if (text_html_mode == "html")
		{	
			CloseEditHTML(current_mod_type_id, current_mod_id, div_name);	
		}

		//	remove ToolBars
		$("#CMS_EditModToolBar_" + current_mod_id).hide(200);			
		$("#CMS_WysiwygToolbar").hide(200);

		current_div_name = "NoModSet";
		current_mod_id = 0;
		
	}

//	=================================================================================
//				SAVE Mod Content			=========================================
//	=================================================================================

	function SaveTextModData()
	{		
		if (text_html_mode == "text")
		{
			SaveModDataText(current_mod_id, true);		
		}
		else
		{
			SaveModDataTextHtml(current_mod_id);		
		}
		
	};	
	
	
	function SaveModDataText(mod_id, reload)
	{
		//	get content from editable <tag>
		var HTMLcontent = $("#ModData_" + mod_id).html();
		
		if (HTMLcontent != null)
		{
			HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
			HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
		}
		
		//alert("id:" + mod_id + "\n\n data:\n" + HTMLcontent);	// for testing
		
		//	send to db and reload page
		UpdateDB (mod_id, HTMLcontent, reload);

		//alert("You will need to Refresh this page to see any updates for this page\n\n" + "( Hit the F5 key to do this )\n\n");
		//		OR neither
	};

	function SaveModDataTextHtml(mod_id)
	{
		//	get html content from <textarea>
		var HTMLcontent = $("#EditHtmlTextArea_" + mod_id).val();
		
		if (HTMLcontent != null)
		{
			HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
			HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars
			
		}
		
		//alert("id:" + mod_id + "\n\n data:\n" + HTMLcontent);	// for testing
	
		//	send to db and reload page
		UpdateDB (mod_id, HTMLcontent, true);
	
		//alert("You will need to Refresh this page to see any updates for this page\n\n" + "( Hit the F5 key to do this )\n\n");
		//		OR neither
		
	};

//	=================================================================================
//				Upload and Link File MARK CONTENT			=========================================
//	=================================================================================
	
	function ReplaceLinkText(mod_id)
	{		
		if (window.getSelection) 
		{  // all browsers, except IE before version 9

			var range = window.getSelection().getRangeAt(0);
			range.deleteContents(); 
			var repl = document.createTextNode("[linkMod_id_" + mod_id + "]"); 
			range.insertNode(repl);
		} 
		else 
		{
			if (document.selection.type == 'None') 
			{
				alert ("No content wass selected");
			}
			else 
			{
				document.selection.createRange().pasteHTML("[linkMod_id_" + mod_id + "]");		
			}
		}		
	}
	
	function AddHrefAndSave_text(mod_id)
	{		
		ReplaceLinkText(mod_id)
		SaveModDataText(mod_id, false);
		alert('Click OK to continue')
	};

	function AddHrefAndSave_table(mod_id)
	{					
		ReplaceLinkText(mod_id)
		SaveModDataTable(mod_id, false);
		alert('Click OK to continue')
	};	
	
/* 
	function SaveModDataListItems(mod_id)
	{
		//	get content from editable <tag>
		var HTMLcontentArray = new Array();
		
		$("#ListItems_" + mod_id + " .ListItems span").each(function(index)
		{
			var HTMLcontent = $(this).html();
			
			if (HTMLcontent != null)
			{
				HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
				HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
			}
			
			
			var LiId = $(this).parent().attr("id");
					
			var Split = LiId.split("_");
			LiId = Split[2];
			
			HTMLcontentArray[LiId] = HTMLcontent;
			//HTMLcontentArray[index] = HTMLcontent;
		

		});
		
		//alert("id:" + mod_id + "\n\n data:\n" + HTMLcontentArray);	// for testing
	
		//	send to db and reload page
		UpdateDB (mod_id, HTMLcontentArray, true);

		//alert("You will need to Refresh this page to see any updates for this page\n\n" + "( Hit the F5 key to do this )\n\n");
		//		OR neither
		
	};	
	 */
	function UpdateDB (mod_id, content, reload)
	{
	
	//alert("id:" + mod_id + "\n\n data:\n" + content);	// for testing
		$.ajax({            
			 url: "CMS/cms_update/cms_update_wysiwyg.php"           
			,type: "POST"            
			,data:
			{ 
				 mod_id: mod_id
				,content: content 
			}
			,success: function()
			{
				if (reload)
				{
					location.reload(true)					
				}

			}
			
		}); 
		
	}

	
	
	function CloseWysiwygToolbar()
	{
		$("#CMS_WysiwygToolbar").toggle(200);
	}
	
	
//	=================================================================================
//				OPEN / CLOSE PAGE options Panel		=========================================
//	=================================================================================

	function OpenPageOptionsPanel()
	{	
		
		CloseConfirmPanels();
		
		// do not open if Window minimized
		if (readCookie("CMSwinState") != 3)
		{
			CloseEditModPanel( current_mod_id, current_div_name );
			ClosePageNavOptionsPanel()
		
			if (readCookie("CMSwinState") == 2)
			{
				$("#CMS_Panel").css("width", "100%");
			}
			else
			{
				$("#CMS_Panel").css("width", CMSPanelWindowWidth);
				$("#CMS_Panel").css("height", "auto");
			}

			$( "#CMS_PageOptionsPanel" ).show(400);
			$( ".CMS_Button_OpenPageOptionsPanel" ).hide();
			$( ".CMS_Button_ClosePageOptionsPanel" ).show();	
		}

		//	set first tab open as default
		if (readCookie("CMS_OpenTab") < 1 || readCookie("CMS_OpenTab") > 5)
		{
			createCookie("CMS_OpenTab", 1, 10);
			$( "#PageGeneralSettingsTabs div:not(:first)" ).hide();
			$( "#TabPanel_1").show();
			$( "#PageGeneralSettingsTabNav .current").removeClass("current");
			$( "#OpenTabPanel_1").addClass("current");
		}
		
	}
	
	function ClosePageOptionsPanel()
	{	
		$( "#CMS_PageOptionsPanel" ).hide(400);
		$( ".CMS_Button_ClosePageOptionsPanel" ).hide();
		$( ".CMS_Button_OpenPageOptionsPanel" ).show();
			
		//	reset defult open tab if closing THIS panel
		if (readCookie("CMS_OpenTab") > 0 && readCookie("CMS_OpenTab") < 6)
		{		
			createCookie("CMS_OpenTab", 0, 10);
		} 

		
		if (readCookie("CMSwinState") == 2)
		{
			$("#CMS_Panel").css("width", "100%");
		}
		else
		{
			$("#CMS_Panel").css("width", "auto");
			$("#CMS_Panel").css("height", "auto");
		}
	}

		
	
//	=================================================================================
//				OPEN / CLOSE PAGE NAV options Panel		=========================================
//	=================================================================================

	function OpenPageNavOptionsPanel()
	{	
		
		CloseConfirmPanels();
		
		// do not open if Window minimized
		if (readCookie("CMSwinState") != 3)
		{		
			CloseEditModPanel( current_mod_id, current_div_name );
			ClosePageOptionsPanel();
			
			if (readCookie("CMSwinState") == 2)
			{
				$("#CMS_Panel").css("width", "100%");
			}
			else
			{
				$("#CMS_Panel").css("width", CMSPanelWindowWidth);
				$("#CMS_Panel").css("height", "auto");
			}
			
			$( "#CMS_PageNavOptionsPanel" ).show(400);
			$( ".CMS_Button_OpenPageNavOptionsPanel" ).hide();
			$( ".CMS_Button_ClosePageNavOptionsPanel" ).show();
		}
		
		//	set first tab open as default
		if (readCookie("CMS_OpenTab") < 6 || readCookie("CMS_OpenTab") > 8)
		{
			createCookie("CMS_OpenTab", 6, 10);
			$( "#PageNavSettingsTabs div:not(:first)" ).hide();	
			$( "#TabPanel_6").show();
			$( "#PageNavSettingsTabNav .current").removeClass("current");
			$( "#OpenTabPanel_6").addClass("current");
		}
		
		//$( "#PageNavSettingsTabs div:not(#TabPanel_" + readCookie("CMS_OpenTab") + ")").hide();
			
		
	}
	
	function ClosePageNavOptionsPanel()
	{	
		$( "#CMS_PageNavOptionsPanel" ).hide(400);
		$( ".CMS_Button_ClosePageNavOptionsPanel" ).hide();
		$( ".CMS_Button_OpenPageNavOptionsPanel" ).show();
		

		//	reset defult open tab if closing THIS panel
		if (readCookie("CMS_OpenTab") > 5 && readCookie("CMS_OpenTab") < 9)
		{		
			createCookie("CMS_OpenTab", 0, 10);
		}

		if (readCookie("CMSwinState") == 2)
		{
			$("#CMS_Panel").css("width", "100%");
		}
		else
		{
			$("#CMS_Panel").css("width", "auto");
			$("#CMS_Panel").css("height", "auto");
		}
	}
	

//	=================================================================================
//				WYSIWYG buttons		=========================================
//	=================================================================================	
	
	function Bold(){document.execCommand("bold",false,null)};
	function Italic(){document.execCommand("italic",false,null)};
	function Underline(){document.execCommand("underline",false,null)};
	function Strike(){document.execCommand("strikethrough",false,null)};
	function Subscript(){document.execCommand("subscript",false,null)};
	function Superscript(){document.execCommand("superscript",false,null)};
	function FontColour(FontColour){document.execCommand("forecolor",false, FontColour)};
	
	function FocusOnMod()
	{
		$("#ModData_" + current_mod_id).focus()
	}
//	=================================================================================
//				Set OPEN a Tab Panel		=========================================
//	=================================================================================

	function SetOpenTabPanel(tab_id)
	{
		createCookie("CMS_OpenTab", tab_id, 10);
	}
	
//	=================================================================================
//				Replace bad HTML			=========================================
//	=================================================================================
		
		function ReplaceTags(str) 
		{ 
			//	make tags Valid
			for (var i=0; i < replaceHTML.length; i++) 
			{ 
				str = str.replace(replaceHTML[i], withHTML[i]); 
			} 
			
					
			return str; 
		} 	

//	=================================================================================
//				HTML Char encode			=========================================
//	=================================================================================


    function HTMLCharEncode(str) 
	{

        var EndResult = '';
        for (i = 0; i < str.length; i++) 
		{
			var chr = str.charAt(i)
		  
			if (chr.charCodeAt(0) > 127)
			{
				chr = '&#' + chr.charCodeAt(0) + ';';
				//alert (chr);
			}
			
			
			EndResult += chr;
			
		}

        return EndResult;
    }

	


//	=================================================================================
//			Show / Hide - Page / Mod - Delete / Mov Confrim		=========
//	=================================================================================

	//	Delete Page
	function DeletePageConfirmOpen()
	{	
		$("#CMS_ConfirmDeletePage").show(400);
	}
	
	function DeletePageConfirmClose()
	{	
		$("#CMS_ConfirmDeletePage").hide(300);
	}
	
	//Delete Mod
	function DeleteModConfirmOpen()
	{	
		$(".CMS_ConfirmDeleteMod").show(400);
	}
	
	function DeleteModConfirmClose()
	{	
		$(".CMS_ConfirmDeleteMod").hide(300);
	}

	//	Move Mod
	function MoveModConfirmOpen(UpOrDown)
	{	
		$(".CMS_ConfirmMovMod").show(400);
		$("span.UpORDown").text(UpOrDown);
		$(".Button_ModUp").hide();
		$(".Button_ModDown").hide();
		$(".Button_Mod" + UpOrDown).show();
	}
	
	function MoveModConfirmClose()
	{	
		$(".CMS_ConfirmMovMod").hide(300);
	}
	
//	=================================================================================
//			Show / Hide Page Access Code settings when "Requires Log-in" CHECKED		=========
//	=================================================================================

	function ShowPageAccessCode()
	{	
		if ($('#PageOptionsRequiresLogin').is(":checked") ) 
		{
			$("#UpdatePageOptionsAccessCode").show();
		}
		
		else
		{
			$("#UpdatePageOptionsAccessCode").hide();
		}	
	}
	
//	=================================================================================
//			Show / Hide Page Site Map Priority settings when "Site Map" CHECKED		=========
//	=================================================================================

	function ShowPagePriority()
	{	
		if ($("#PageOptionsIncludeInSiteMap").is(":checked") ) 
		{
			$("#UpdatePageOptionsPriority").show();
		}
		
		else
		{
			$("#UpdatePageOptionsPriority").hide();
		}	
	}
	

//	=================================================================================
//		Drag a MOd to a new location	
//	=================================================================================
	
	function DragModStart()
	{
		
		//	Hide All Config Panels / toolbars / buttons
		ClosePageOptionsPanel();
		ClosePageNavOptionsPanel();
		CloseConfirmPanels();		
		$("#CMS_WysiwygToolbar").hide();
		$(".CMS_EditModToolBar").hide();
		
		$(".CMS_Button_DragModStart").hide();
		$(".CMS_Button_DragModStop").show();
		
		$("#CMS_DragModPanel").show(400);
		
		//	make un-editable
		$(".HoverShow span").attr({contentEditable: "false"});

		//	remove high-lite		
		$("#" + current_div_name).css("outline", "none");
		
		$( ".sortable" ).sortable(
		{
			 opacity: 0.6
			//,cursor: 'move'	//	not needed
			,revert: true
		});	
		
		//$(".sortable").sortable("enable");	//	need this to re-enable after being disabled below

		$(".HoverShow").css("cursor", "move");
		
		drag_mod_mode = 1;
		createCookie("CMS_drag_mod_mode", 1, 10);
	
	
	}	
	
	function DragModStop()	
	{
		$("#CMS_DragModPanel").hide(400);
		
		$(".CMS_Button_DragModStop").hide();
		$(".CMS_Button_DragModStart").show();
	
		//$(".sortable").sortable("disable");	//	not needed

		$(".sortable").sortable("destroy"); 

		$(".HoverShow").css("cursor", "default");
		drag_mod_mode = 0;
		current_div_name = "NoModSet";
		
		current_mod_id = 0;	
		createCookie("CMS_drag_mod_mode", 0, 10);
		
	}
	

	
    function SaveDragModPos()
	{
		var ModPosQryStr = '&';
		
		for (var i = 1; i<6; i++)
		{
			if ( $(".sortable" + i).length)
			{
				ModPosQryStr += $(".sortable" + i).sortable("serialize") + "&";
			}
		}
		

		ModPosQryStr += 'a=update_mod_pos'; 
		
		$.post("CMS/cms_update/cms_update_mod_settings.php", ModPosQryStr, function(theResponse)
		{ 									
			$(".UpdateMsgDiv").html(theResponse).hide();
			$(".UpdateMsgDiv" ).show(400).delay( 6000 ).hide(400);
		});  		
    }

	
	
	function getSelectionText() 
	{
		var text = "";
		if (window.getSelection) 
		{
			text = window.getSelection().toString();
		}
		
		else if (document.selection && document.selection.type != "Control") 
		{
			text = document.selection.createRange().text;
		}
		
		return text;
	}
	
		
