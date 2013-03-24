$(document).ready(function(){ 
  $('[class^="easyA"]').submit(function() 
	{
			easyA($this);
			return false;
	});//eof 
}); 
	function easyA(form)
	{
		alert(form);
	}

   function doSubmit(action,pr_ID)
   {

		if(action == 1)//remove
		{
			//var id = da.attr("pr_ID");
			$.ajax({type: "POST",url: "action.php",data: "action=remove&pr_ID=" + pr_ID,	
			success: function(msg)
			{	
				if(msg != false)
				{
					if(msg != -1)
					{
						updateSub(pr_ID);
						displayD(msg);
					}
					else
					{
						location.reload();
					}//if emptied cart -- refresh
				}//eof if not false
				 

			} });//eof success function and ajax
		}//eof if
		if(action == 2)//update
		{
			//var form = "update"+pr_ID;
			//var qu = document.forms[form].elements[0].value;
			var quant = "#quantity"+pr_ID;
			var qu = $(quant).val();
			//var id = da.attr("pr_ID");
			$.ajax({type: "POST",url: "action.php",data: "action=update&pr_ID=" + pr_ID + "&q=" + qu,	
			success: function(msg)
			{	
				if(msg != false)
				{
				displayD(msg);
				}//eof if not false
			} });//eof success function and ajax
		}//eof if
   }//eof doSubmit

	function displayD(data)
	{
		if(data != "ERROR")
		{
			var dataArray = new Array();
			dataArray = data.split("~");
			var action = dataArray[0];
			var pr_ID = dataArray[1];
			var subtotal = dataArray[2];
			if(action == 2)var newItemPrice = dataArray[3];
			if(action == 1)//remove
			{
				
				//$("#messagewindow").animate({'top':'160px'},500); 
				$('#overlay').fadeIn('fast',function(){
				$("#infowindow").html("<a href=\"javascript: closeD()\" id=\"xbutton\"></a><p>your data has been removed</p> id="+ pr_ID);
				
					$('#infowindow').animate({'top':'160px'},500);
				});
				var form ="tbody#"+pr_ID;
				$(form).fadeOut('slow');
				$("#subtotal").html("$"+subtotal);
				 
				
				
			}//eof if
			if(action == 2)//update
			{
				$("#infowindow").html("<a href=\"javascript: closeD()\" id=\"xbutton\"></a><p>your data has been updated</p>"+ data);
				//$("#messagewindow").animate({'top':'160px'},500); 
				$('#overlay').fadeIn('fast',function(){
					$('#infowindow').animate({'top':'160px'},500);
				});
				 
				$("#subtotal").html("$"+subtotal);
				var form = "#price"+pr_ID;
				$(form).html(newItemPrice);
				
			}//eof if
		}//eof if not err
		else
		{
			$("#infowindow").html("<a href=\"javascript: closeD()\" id=\"xbutton\"></a><p>Error</p>"+ data);
				//$("#messagewindow").animate({'top':'160px'},500); 
				$('#overlay').fadeIn('fast',function(){
					$('#infowindow').animate({'top':'160px'},500);
				});
		}//eof else
   }//eof displayd
   function closeD()
   {
   $('#infowindow').animate({'top':'-700px'},500,function(){
	            $('#overlay').fadeOut('fast');
	        });
   }
   function updateSub(pr_ID)//update subtotal
   {
		var form = "#remove"+pr_ID+" [name=price]";
		var valu = $(form).val();
		$('[name=subtotal]')
   }//eof updatesub
  