		var editors = new Array();
		
		var tabs = new Array();
$(document).ready(function()
	{
		
			
		CodePress.run();
		$('iframe').each(function(){
			editors.push($(this));
			var height = $(window).height()-100+"px";
			var width = $(window).width()-1+"px";
			$(this).css('height',height);
			$(this).css('width',width);
			
		});//eof tab.each
		$('[rel^="tab"]').each(function(){
			tabs.push($(this));
		});//eof tab.each
		
			
			var x=0;
		for(x=0;x<editors.length;x++)
		{
			if(x == 0 )
				editors[x].css('left','0px');
			else
				editors[x].css('left','-9000px');
		}//eof for
			
		$('a[rel^="tab"]').click(function(){
			var index = $(this).attr('rel').substr(3);
			var x=0;
			for( x=0;x<editors.length;x++)
			{
				if(x == index )
				{
					editors[x].css('left','0px');
					tabs[x].attr("class",'CURRENT');
				}
				else
				{
					editors[x].css('left','-9000px');
					tabs[x].attr("class",'NOTCURR');
				}
				
			setCurrent();
			}//eof for 
				
		});
		setCurrent();
	});//eof .ready  
	function setCurrent()
	{
		$(".CURRENT").css('background-color','#CCC');
		$(".NOTCURR").each(function()
		{$(this).css('background-color','#999');
		});
	}
	function saveFile()
	{
		var x;
		var fdata;
		var fname = "";
		for(x=0;x<editors.length;x++)
		{
			if(tabs[x].attr('class') == "CURRENT")
			{
				fdata = eval("cp"+x).getCode();
				fname = tabs[x].attr('title');
				alert(fdata);
				alert(fname);
			}
		}
		if(fdata.length > 1)
		{
			$.ajax({type: "POST",url: "action.php",data: "action=save&data=" + fdata + "&fn="+fname,	
			success: function(msg)
			{	
				if(msg != false)
				{
					alert(msg);
				}//eof if not false
				 

			} });//eof success function and ajax
		}
	}
	function openFile()
	{
		
	}
	function closeFile()
	{
		
	}