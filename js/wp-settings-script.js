jQuery(document).ready( function($) {
	
	ShowWordpressDetails();
	ShowThemeDetails();
	ShowMySQLDetails();
	ShowPhpDetails();
  ShowPluginDetails();

    //add method call here for checkbox 

	$("#chkWordpress").click( function() {
		ShowWordpressDetails();
    checkAll();
	});
    $("#chkThemeDetails").click( function() {
		ShowThemeDetails();
    checkAll();
	});
		// click chkMySQLDetails
$("#chkMySQLDetails").click( function() {
		ShowMySQLDetails();
    checkAll();
	});

$("#chkPhpDetails").click( function() {
		ShowPhpDetails();
    checkAll();
	});


$("#chkPluginDetails").click( function() {
    ShowPluginDetails();
    checkAll();
  });

$("#chkShowHide").click( function() {
    ShowHideAllDetails( $('#chkShowHide').prop('checked'));
    checkAll();
  });



//add click event handler here
	
	//ShowWordpressDetails
   function ShowWordpressDetails()
   {
	//alert($("#wpd:checked").length);
     if($("#chkWordpress:checked").length>0)
   {
	    $("#wordpressDetails").show();
   }
   else
   {
	    $("#wordpressDetails").hide();
   }
   

   }
   //

   //ShowThemeDetails
    function ShowThemeDetails()
   {
	//alert($("#chkThemeDetails:checked").length);
     if($("#chkThemeDetails:checked").length>0)
   {
	    $("#themeDetails").show();
   }
   else
   {
	    $("#themeDetails").hide();
   }
   

   }
   //ShowThemeDetails

    //ShowMySQLDetails
   function ShowMySQLDetails()
   {
	//alert($("#chkThemeDetails:checked").length);
     if($("#chkMySQLDetails:checked").length>0)
   {
	    $("#mySQLDetails").show();
   }
   else
   {
	    $("#mySQLDetails").hide();
   }
   }
   //

  //ShowPhpDetails
   function ShowPhpDetails()
   {
	//alert($("#chkThemeDetails:checked").length);
     if($("#chkPhpDetails:checked").length>0)
   {
	    $("#PhpDetails").show();
   }
   else
   {
	    $("#PhpDetails").hide();
   }
   }



     //ShowPluginDetails
   function ShowPluginDetails()
   {
  //alert($("#chkThemeDetails:checked").length);
     if($("#chkPluginDetails:checked").length>0)
   {
      $("#PluginDetails").show();
   }
   else
   {
      $("#PluginDetails").hide();
   }
   }



   function ShowHideAllDetails(chk)
   {
     $("#chkPhpDetails").prop('checked', chk);
     $("#chkWordpress").prop('checked', chk);
     $("#chkThemeDetails").prop('checked', chk);
     $("#chkMySQLDetails").prop('checked', chk);
     $("#chkPluginDetails").prop('checked', chk);

     ShowWordpressDetails();
     ShowThemeDetails();
     ShowPhpDetails();
     ShowMySQLDetails();
     ShowPluginDetails();
   }

   function checkAll()
   {
    var checkedAll=$('#chkPhpDetails,#chkWordpress,#chkThemeDetails,#chkMySQLDetails,#chkPluginDetails').filter(':checked').length;

    if(checkedAll==5)
    {
      $('#chkShowHide').prop('checked', true);
    }
    else
    {
     $('#chkShowHide').prop('checked', false);
    }
   // alert($("#chkShowHide"));
   }
   //add method here 

});