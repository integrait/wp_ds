jQuery(document).ready(function(event) {
    // First off, lets load our order items
    jQuery.ajax({
        url: ds_cart.ajaxurl,
        method: "POST",
        data: {
            action: 'load-dscart',
            nonce: ds_cart.ds_cart_nouce
        },   
        success: function(data){ 
            if(data.code == 1) 
                jQuery("div.widget_shopping_cart_content").html(data.order_items);
        }
    });
    
    jQuery(".variations_button").insertBefore('.single_add_to_cart_button');

    // Set Shipping dropdown to chosen
    jQuery('select#shipping_method_0').chosen();

    // Add new 'Add to Cart' button  
	jQuery(".single_add_to_cart_button")
		.after(jQuery('input#ds-addtocart'))
		.hide();

    // Set distributor key and price
    jQuery('.supplier_price').click(function(e){ 
        jQuery('#supplier_key').val( jQuery('.supplier_price:checked').data('supplier') );
        jQuery('#supplier_price').val( jQuery('.supplier_price:checked').val() );
    });

	// Add to Custom DS Cart
	jQuery('input#ds-addtocart').on('click', function(e){
		e.preventDefault(); 
                
        if(jQuery('input:radio:checked').length < 1){
            alert("Please select a Price!");
            return false;
        }    
            
        jQuery.ajax({
	        url: ds_cart.ajaxurl,
	        method: "POST",
	        data: {
	            action: 'add-to-dscart',
	            pID: jQuery('#supplier_key').data('pdt'),
	            price: jQuery('#supplier_price').val(),
	            qty: jQuery('input.qty').val(),
	            dist: jQuery('#supplier_key').val(),
	            nonce: ds_cart.ds_cart_nouce
            },  
            beforeSend: function(e){
                jQuery('input#ds-addtocart').val("Adding...");
            },
        	success: function(data){ 
                console.log(data);
                if(data.code == 1){ 
                    jQuery("span.dscart-items-number").html(data.count); // <<< Update Count 
                    jQuery('input#ds-addtocart').val("Added to Order Basket");
                }else{
                    jQuery('input#ds-addtocart').val("Add to Order Basket");
                    alert(data.message);
                } 
            }
        }); 

        return true;
    });

    // Update Order Basket
    jQuery('input.update_basket').on('click', function(e){ 
        e.preventDefault();

        jQuery(this).val("Updating..."); 

        // All Items in Basket
        var items = jQuery('tr.cart_table_item'),
        pdtid, qty, data = [];

        jQuery(items).each(function(i, jqO){
            pdtid = jQuery(jqO).find('.dscartitem').data('line');
            quantity = jQuery(jqO).find('input[name=dscart_'+pdtid+']').val();
            data.push({ pID: pdtid, qty: parseInt(quantity) });
        });

        jQuery.ajax({
            url: ds_cart.ajaxurl,
            method: "POST",
            data: {
                action: 'update-dscart',
                data: data, 
                nonce: ds_cart.ds_cart_nouce
            },
            success: function(data){   
                jQuery('input.update_basket').val("Updated");
                location.reload();
            }
        });    

        return false;
    });

    // Remove button click
    jQuery('td.dscartitem, a.dscartitem').on('click', function(e){ 
    	// Add Confirm Button here <<<<<
        var r = confirm("Are you sure?");
        if(r == 1){ 
            jQuery(this).parent().css({ 'background-color':'red', 'opacity':0.6 });
            jQuery.ajax({
                url: ds_cart.ajaxurl,
                method: "POST",
                data: {
                    action: 'remove-from-dscart',
                    dscartid: jQuery(this).data('dscartid'), 
                    nonce: ds_cart.ds_cart_nouce
                },
                success: function(data){  
                    location.reload();
                }
            });
        }else return false;   
    });

    // Remove button click - Mini-cart
    jQuery('li').delegate('a.remove_item#dscartitem', 'click',function(e){ 
        
        // Add Confirm Button here <<<<<
        jQuery(this).parent().css({ 'background-color':'red', 'opacity':0.6 });
        var self = this;
        alert("Remove this item");
        // jQuery.ajax({
        //     url: ds_cart.ajaxurl,
        //     method: "POST",
        //     data: {
        //         action: 'remove-from-dscart',
        //         dscartid: jQuery(this).data('dscartid'), 
        //         nonce: ds_cart.ds_cart_nouce
        //     },
        //     success: function(data){  
        //         self.parent()[0].remove();
        //     }
        // });   
    });	

    // Checkout Distributor items
    jQuery('input.checkout-button.move_to_cart').click(function(e){ 

     	var cart_n = jQuery('span.dscart-items-number').html();
    	
    	if(cart_n == "0") {
  		alert("No items to checkout");
		return false;
	}

        jQuery(this).val("Processing items...");

        // this.value = "Processing...";
     	jQuery.ajax({
	        url: ds_cart.ajaxurl,
	        method: "POST",
	        data: {
	            action: 'checkout-to-wc-cart',
	            supp_key: jQuery(this).data('supplier'), 
	            nonce: ds_cart.ds_cart_nouce
            },
            beforeSend: function(e){
                
            },
        	success: function(data){
            	console.log(data); 
            	if(data.code == 1){
                    jQuery('input.checkout-button.move_to_cart').val("Checkout this supplier →");
            		alert(data.message); 
                    window.location = ds_cart.home_url+"checkout/"; 
            	}else{
            		alert(data.message);
            	}
            }
        }); 
        jQuery(this).val("Checkout this supplier →");
    });

}); //end document.ready
 