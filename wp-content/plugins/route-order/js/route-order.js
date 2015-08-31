jQuery(document).ready(function(event) {

 	// Set Datatable for orders
	jQuery('#ordertable').dataTable({

	    "bSort": true,  
	    "order": [[ 7, "desc" ]],
	    "bPaginate": true,
	    "bLengthChange": true,
	    "bFilter": true, 
	    "bInfo": true,
	    "bAutoWidth": true, 
	    "sDom": 'T<"panel-menu dt-panelmenu"lfr><"clearfix">tip',
	    "oTableTools": {
    	  "sSwfPath": "<a href='//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/swf/copy_csv_xls_pdf.swf' target='_blank' rel='nofollow'>http://cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/swf/copy_csv_xls_pdf.swf</a>",
	      "aButtons": [ 
	          "csv",
	          "xls",
	          {
	              "sExtends": "pdf",
	              "bFooter": true,
	              "sPdfMessage": "List of All Orders ",
	              "sPdfOrientation": "landscape"
	          },
	          "print"
	    ]}
	});  
	
	// Select All items in table
	jQuery(".select_all").click(function(){
		if(jQuery(this).prop("checked")){ 
			jQuery(".case").prop("checked",true);
		}else{ 
			jQuery(".case").prop("checked",false);	
		}
	});

	var globalVar = [];  
		 
	// Route order button on Order Detail Page > Back-end
	jQuery("#route1, #route2").on('click', function(e){
		e.preventDefault();

		var distributorName = jQuery.trim(jQuery("#myExternalSelect").val());

		if(distributorName == ""){
			alert('You must select a distributor to route this order to');
		 	return false;
		}
	 
		var ourTr = jQuery('tr.item .case:checked');

	    jQuery(ourTr).each(function(i, jqO) { 
	       	var data = jQuery.trim(jQuery("#customer_user option:selected").text());
	       	var data_ = data.split(" ");

			var innerJson = {
				"orderid":  jQuery(this).data('order'),
				"customer": "9999",
				"itemid": jQuery.trim(jQuery(jqO).parents('tr.item').data("itemId")),
				"name": jQuery.trim(jQuery(jqO).parents('tr.item').find('.name').text()),
				"qty": jQuery.trim(jQuery(jqO).parents('tr.item').find('.quantity').text()),
				"amount": jQuery.trim(jQuery(jqO).parents('tr.item').find('.line_cost').text()),
				"distributors": jQuery.trim(jQuery("#myExternalSelect").val()),
				"phonenumber": jQuery.trim(jQuery("#myExternalSelect option:selected").data("phonenumber")),
				"email": jQuery.trim(jQuery("#myExternalSelect option:selected").data("email"))
			};
			globalVar.push(innerJson);  
	   		jQuery(jqO).parents('tr.item').find('.dist').html(jQuery("#myExternalSelect").val());
	   	}); 

		// if(globalVar.length == 0){ 
		// 	alert('You must select at least one product to route this order');
		//  	return false;
		// }

		var data = globalVar;  

		jQuery.ajax({
		    type:'POST',
		    url: ds_route.ajaxurl,
		    data: { 
		    	action : 'route-order',
		    	nonce  : ds_route.ds_route_nouce,
		    	order  : jQuery(this).data('order'),//data,
		    	distributor: jQuery.trim(jQuery("#myExternalSelect option:selected").data('key'))
		    }, 
		    beforeSend: function(){
		    	jQuery("#orderitems").html("&nbsp;&nbsp;Routing Order Items...");
		    },
		    success: function(data) {
			    globalVar = []; 
			    jQuery('tr.item input[type=checkbox]').attr("checked",false);
			    // console.log(data.message); 
				    alert(data.message);
			    
			    if(data.code == 1){
			    	alert(data.message);
			    	// Push Order to Mobile App
			    	
			    	var pubnub = PUBNUB.init({
		                publish_key: 'pub-c-10336f2b-48f9-4da0-9556-a4be9539b821',
		                subscribe_key: 'sub-c-f45f8864-b627-11e4-b2c9-02ee2ddab7fe'
		            });

	                pubnub.subscribe({
	                    channel: "drugstoc",
	                    message: function (message, env, channel) {
		                    document.getElementById('text').innerHTML =
		                        "Message Received." + '<br>' +
		                        "Channel: " + channel + '<br>' +
		                        "Message: " + JSON.stringify(message) + '<br>' +
		                        "Raw Envelope: " + JSON.stringify(env) + '<br>'
	   	                    },
	                    connect: function(){
		                    pubnub.publish({
		                        channel: "drugstoc",
		                        message: jQuery(this).data('order'),
		                        callback: function (m) {
		                            console.log(m)
		                        }
	                    });
	                  } 
	               });
	               
			    }else{
			    	alert(data.message);
			    	console.log(data.message);
			    }
			    jQuery("#orderitems").html("&nbsp;&nbsp;Order Items");  
		    }
		});

		e.preventDefault();
	});
 
}); //end document.ready






 