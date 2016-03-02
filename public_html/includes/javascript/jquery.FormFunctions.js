//	Auto Expand TextAreas
//	usage:	'onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"'

	var maxrows=10;

	function AutoResize(box) 
	{
		var txt=box.value;
		var cols=box.cols;
		var arrtxt=txt.split('\n');
		var rows=arrtxt.length;
		for (i=0;i<arrtxt.length;i++)
		  rows+=parseInt(arrtxt[i].length/cols);
		if (rows>box.maxrows)
			box.rows=box.maxrows;
		else
			box.rows=rows;
	}


$(document).ready(function() 
{
	//	Drag Expand Areas	-	Used with:	" jquery.textarearesizer.js"  --> ( "jquery-latest.js" also required)
	//	usage:	 class = "resizable"
	$('div.resizable:not(.processed)').TextAreaResizer();
	$('textarea.resizable:not(.processed)').TextAreaResizer();
	$('fieldset.resizable:not(.processed)').TextAreaResizer();
	


//	Hilight Inputs on Focus



	$('input[type="text"],textarea').focus(function() {
		$(this).addClass("FocusField");

    });
    $('input[type="text"],textarea').blur(function() {
    	$(this).removeClass("FocusField");

    });



	//	Check all check-boxes - OLD VERSION - replace:

	$('.check_all:checkbox').change(function() 
	{
		var group = ':checkbox[name=' + $(this).attr('name') + ']';
		$(group).attr('checked', $(this).attr('checked'));
	});


	//	New Version
	$(".CheckAll").change(function() 
	{
		var Classes = $(this).attr("class");
		var Class = Classes.split(" ")[1];
		$("." + Class).prop("checked", $(this).is(":checked"));	
	});

	
	//	stop multiple clicks on submit CC form
	//	(NOT IN USE atm))
	var submitCount = 0;    
	
	function ClickOnce()
	{        
		if (submitCount== 0) 
		{            
			//submit form            
			submitCount ++;            
			return true;        
		}        
		else 
		{            
			alert("Transaction is in progress.");            
			return false;        
		}    
	}; 
	
});
