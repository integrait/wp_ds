jQuery(document).ready(function() {

    var drug_name = getParameterByName('s');

    jQuery.ajax({
        method: "GET",
        url: ds_cart_vars.ajaxurl,
        //url: "http://localhost/drugstoc_2/wp-admin/admin-ajax.php",
        dataType: "json",

        data: {
            action: 'get_Group_Distributor_json',
            drug_name: drug_name 
        },
        success: function(data) {
            console.log(data);
            //
            //alert(data); 
            var sn = 0; 

            jQuery("#group_search_result").append("<h4>Search Result Summary for " + drug_name + " </h4>");

            for (var i = 0; i < data.length; i++) { 

                total_result = data[i].total_result;
                distributor_slug = data[i].meta_key;
                distributor_name = data[i].Distributor;

                // json_email = data[i].user_email;
                // json_address = data[i].address;

                //$("#group_search_result").append("<div>hello world</div>");
 
                jQuery("#group_search_result").append("<div> Name : " + distributor_name + " Count : " + total_result + "</div>")
 
            } //end for

        } //end sucess handler

    }); //End Jquery Ajax
 
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}