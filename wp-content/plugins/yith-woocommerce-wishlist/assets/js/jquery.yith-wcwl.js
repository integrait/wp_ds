jQuery(document).ready(function(a){a(document).on("click",".add_to_wishlist",function(){var b=yith_wcwl_plugin_ajax_web_url,d={add_to_wishlist:a(this).data("product-id"),product_type:a(this).data("product-type"),action:"add_to_wishlist"};call_ajax_add_to_wishlist(a(this),b,d);return!1})});
function call_ajax_add_to_wishlist(a,b,d){var c=a.parents(".yith-wcwl-add-to-wishlist");c.find(".ajax-loading").css("visibility","visible");jQuery.ajax({type:"POST",url:b,data:"product_id="+jQuery(".cart #product_id").val()+"&"+jQuery.param(d),success:function(a){var b=jQuery("#yith-wcwl-popup-message");c.find(".ajax-loading").css("visibility","hidden");response_arr=a.split("##");jQuery("#yith-wcwl-message").html(response_arr[1]);b.css("margin-left","-"+jQuery(b).width()+"px").fadeIn();window.setTimeout(function(){b.fadeOut()},
    2E3);"true"==jQuery.trim(response_arr[0])?(c.find(".yith-wcwl-add-button").css("display","none").removeClass("hide show").addClass("hide"),c.find(".yith-wcwl-wishlistexistsbrowse").css("display","none").removeClass("hide show").addClass("hide"),c.find(".yith-wcwl-wishlistaddedbrowse").css("display","block").removeClass("hide show").addClass("show")):("exists"==jQuery.trim(response_arr[0])?(c.find(".yith-wcwl-add-button").css("display","none").removeClass("hide show").addClass("hide"),c.find(".yith-wcwl-wishlistexistsbrowse").css("display",
    "block").removeClass("hide show").addClass("show")):(c.find(".yith-wcwl-add-button").css("display","block").removeClass("hide show").addClass("show"),c.find(".yith-wcwl-wishlistexistsbrowse").css("display","none").removeClass("hide show").addClass("hide")),c.find(".yith-wcwl-wishlistaddedbrowse").css("display","none").removeClass("hide show").addClass("hide"));jQuery("body").trigger("added_to_wishlist")}})}
function remove_item_from_wishlist(a,b){jQuery("#yith-wcwl-message").html("&nbsp;");jQuery(".wishlist_table").css("opacity","0.4");jQuery.ajax({type:"POST",url:a,data:{action:"remove_from_wishlist"},success:function(a){jQuery(".wishlist_table").css("opacity","1");jQuery("#"+b).remove();arr=a.split("#");jQuery("#yith-wcwl-message").html(arr[0]);jQuery(".cart").append('<tr><td colspan="6"><center>'+arr[0]+"</center></td></tr>")}})}
function add_tocart_from_wishlist(a){jQuery("#yith-wcwl-message").html("&nbsp;");jQuery.ajax({type:"GET",url:a,success:function(a){jQuery("#yith-wcwl-message").html(a)}})}function check_for_stock(a,b,d){if("out-of-stock"==b)return alert(yith_wcwl_l10n.out_of_stock),!1;location.href="true"==d?a+"&redirect_to_cart=true":a};