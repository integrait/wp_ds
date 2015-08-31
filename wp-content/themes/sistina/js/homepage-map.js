var user_id = 0;

jQuery(document).ready(function(){
  setTimeout(show_user_statistics, 5000);  
});

window.google_map_status = "";

function show_user_statistics(){

  jQuery('.count').each(function () {
      jQuery(this).prop('Counter',0).animate({
          Counter: jQuery(this).text()
      }, {
          duration: 12000,
          easing: 'swing',
          step: function (now) {
              jQuery(this).text(Math.ceil(now));
          }
      });
  });
}


var personal_home_page_type = "";

function Initializer () {
      
        var asyncUrl = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOt9IYHpYN22m7alw_HKi5y5WBgu57p4s&v=3.exp&sensor=true&callback=googleMapsInitialized';
        var gMapsLoader = jQuery.getScript(asyncUrl);
    } 


function draw_map(user_type){

  personal_home_page_type = user_type;

    Initializer();
  }


    var scope = {
        center: {
             // lat: 6.605511, 
             // lon: 3.344349
             lat: 6.465862,
             lon: 3.364509
        }
    };     
   
    window.googleMapsInitialized = function(){
      var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);
      var mapOptions = {
        zoom: 12,
        center: myLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl: true,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: false,
        streetViewControl: false,
        navigationControl: true,
        disableDefaultUI: true,
        overviewMapControl: true,
        scrollwheel: false
      };


      google_map_status = "loaded";

      scope.map = new google.maps.Map(jQuery('div.show_homepage_map')[0], mapOptions);

      ds_base_location = new google.maps.Marker({
          position: myLatLng,
          map: scope.map,
          title: 'My Location',
          //icon: 'wp-content/themes/sistina/images/map_marker/marker_pink.png'
          icon: 'img/u-marker.png'
      });



      google.maps.event.addDomListener(jQuery('div.show_homepage_map'), 'mousedown', function(e) {
        e.preventDefault();
        return false;
      });



      var infowindowx = new google.maps.InfoWindow({
       content: '<div class="flxmap-infowin"><div class="flxmap-marker-title">DrugStoc</div><div><div style="width:300px"><img style="float:right;" src="http://drugstoc.biz/wp-content/uploads/2014/10/splash-logo-beta.png" width="80px"><div><b style="color:#2883f9">(Base Location)</b><br>13, Gbajobi street, Ikeja,<br>Lagos<b><br>email</b>: info@drugstoc.biz<br></div></div></div></div>'
        
       });



      google.maps.event.addListener(ds_base_location, 'click', function() {
        infowindowx.open(scope.map, ds_base_location);
      });




        //do ajax loop

         jQuery.ajax({
                    method : "GET",
                    url: ds_cart_vars.ajaxurl,
                    //url: "http://localhost/drugstoc_2/wp-admin/admin-ajax.php",
                    dataType : "json",

                    data: {
                        action: 'getHomepageMap_json',
                        my_user_type: personal_home_page_type
                        
                    },
                    success: function(data){
                        console.log(data);

                      var sn = 0 ;




                 


                       for (var i=0;i<data.length; i++){
                    
                            
                        json_institution = data[i].institution;
                         json_state = data[i].state;
                        json_email = data[i].user_email;
                        json_address = data[i].address;

                        json_user_type = data[i].user_type;

                
                        gmap_cord = data[i].gmap_coords;


                        var arr_gmap_cord = gmap_cord.split(',');


                        scope.center.lat = arr_gmap_cord[0];
                        scope.center.lon = arr_gmap_cord[1];

                        

                             //appender = "";
          
                            //  if(personal_home_page_type ==  "All"){

                            //   console.log(json_user_type);
                            //   alert('grunt');

                            // //   appender = "";
                            // // }else{
                            // //   appender = "../";
                            // }
                            // 
                            // 
                              
                               //sContent =  '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="#">' + json_institution + '</a></div><div><div style="width:300px"><div><b style="color:#2883f9">(Drugstoc Verified)</b><br>' + json_address +'<br> '+ json_state +'<b><br>email</b>: '+ json_email +'<br></div></div></div></div>';

                                 sContent =  '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="#">' + json_institution + '</a></div><div><div style="width:300px"></div></div></div>';

                              if(json_user_type == "Distributor"){
                                
                                map_icon = ds_cart_vars.home_url+'wp-content/themes/sistina/images/map_marker/ico_distributor.png';

                                // console.log("Map ICON " + map_icon);

                              }else if(json_user_type == "Manufacturer"){

                                map_icon = ds_cart_vars.home_url+'wp-content/themes/sistina/images/map_marker/ico_manufacturer.png';

                              }else if(json_user_type == "Importer"){

                                map_icon = ds_cart_vars.home_url+'wp-content/themes/sistina/images/map_marker/ico_importer.png';

                              }else if(json_user_type == "Wholesale"){

                                map_icon = ds_cart_vars.home_url+'wp-content/themes/sistina/images/map_marker/ico_distributor.png';

                              }else if(json_user_type == "Pharmacy"){

                                map_icon = ds_cart_vars.home_url+'wp-content/themes/sistina/images/map_marker/ico_pharmacy.png';

                              }else{

                                map_icon = ds_cart_vars.home_url+'wp-content/themes/sistina/images/map_marker/marker_green.png';

                              }


                              var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);
                              
                              scope.myLocation = new google.maps.Marker({
                                position: myLatLng,
                                map: scope.map,
                                title: json_institution,
                                icon: map_icon,
                                info: sContent
                              });

                               
                             
                  var infowindow = new google.maps.InfoWindow({
                      content: '<a href="#" class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()"> ' + json_institution + '</a>'
                    });

                  google.maps.event.addListener(scope.myLocation, 'click', function() {
      
                infowindow.setContent(this.info);
                infowindow.open(scope.map,this);
                  });

                   scope.isLoadingGmaps = false;
                  scope.errorLoadingGmaps = false;  

                         
                         }//end for

                    }//end sucess handler

                });//End Jquery Ajax


        //End Ajax Looping


      scope.isLoadingGmaps = false;
      scope.errorLoadingGmaps = false;

    }    

    //Initializer();








function update_manufacturer_meta(user_id, coordinates){

jQuery.ajax({
                    method : "GET",
                    url: ds_cart_vars.ajaxurl,
                    //url: "http://localhost/drugstoc_2/wp-admin/admin-ajax.php",
                    //dataType : "json",

                    data: {
                        action: 'updateDistributorCordinates_json',
                        user_id: user_id,
                        coordinates: coordinates
                        
                    },
                    success: function(data){
                        console.log(data);

                    }//end sucess handler

                });//End Jquery Ajax



}// end update distributor

    function  get_cordinates_from_address(user_id,address){
         var geocoder = new google.maps.Geocoder();
                          
                          //Start Reverse Geo coordinates
                          geocoder.geocode( { 'address': address}, function(results, status) {


                           if (status == google.maps.GeocoderStatus.OK) {
                              var latitude = results[0].geometry.location.lat();
                              var longitude = results[0].geometry.location.lng();

                              //console.log(user_id + " -- " + address + " -- " + latitude + " -- " + longitude);
                              
                              coordinates = latitude + " , " + longitude;
                              
                              update_manufacturer_meta(user_id, coordinates);

                            
                            } // end if

                           }); //end reverse geo cordinates
                         
              }//end det cordinates from address


//draw single map
function draw_single_map(sin_institution, sin_address,sin_state,sin_email, sin_lat, sin_lon, sin_user_type){


      
     


                        scope.center.lat = sin_lat;
                        scope.center.lon = sin_lon;

                        

                              
                               sContent =  '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="#">' + sin_institution + '</a></div><div><div style="width:300px"><div><b style="color:#2883f9">(Drugstoc Verified)</b><br>' + sin_address +'<br> '+ sin_state +'<b><br>email</b>: '+ sin_email +'<br></div></div></div></div>';

                              if(sin_user_type == "Distributor"){
                                
                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_distributor.png';

                              }else if(sin_user_type == "Manufacturer"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_manufacturer.png';

                              }else if(sin_user_type == "Importer"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_importer.png';

                              }else if(sin_user_type == "Wholesale"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_importer.png';

                              }else if(sin_user_type == "Pharmacy"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_pharmacy.png';

                              }else{

                                map_icon = 'wp-content/themes/sistina/images/map_marker/marker_green.png';

                              }

                                

                  
                            
                              var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);
                              
                              scope.myLocation = new google.maps.Marker({
                                position: myLatLng,
                                map: scope.map,
                                title: sin_institution,
                                icon: map_icon,
                                info: sContent
                              });

                               

                             
                  var infowindow = new google.maps.InfoWindow({
                      content: '<a href="#" class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()"> ' + sin_institution + '</a>'
                    });

                  google.maps.event.addListener(scope.myLocation, 'click', function() {
      
                infowindow.setContent(this.info);
                infowindow.open(scope.map,this);
                  });

                   scope.isLoadingGmaps = false;
                  scope.errorLoadingGmaps = false;  

}//end draw single map


      function draw_map_xxxx(user_type){

       

                  jQuery.ajax({
                    method : "GET",
                    url: ds_cart_vars.ajaxurl,
                    //url: "http://localhost/drugstoc_2/wp-admin/admin-ajax.php",
                    dataType : "json",

                    data: {
                        action: 'getHomepageMap_json',
                        my_user_type: user_type
                        
                    },
                    success: function(data){
                        console.log(data);

                       // alert('function is success' + data);

                      // if (data.length == 0) {
                      //   return false;
                      // }

                      var sn = 0 ;


                      //var infowindow = new google.maps.InfoWindow();


                       for (var i=0;i<data.length; i++){
                    
                            
                        json_institution = data[i].institution;
                         json_state = data[i].state;
                        json_email = data[i].user_email;
                        json_address = data[i].address;





                        json_user_type = data[i].user_type;

                
                        gmap_cord = data[i].gmap_coords;


                        var arr_gmap_cord = gmap_cord.split(',');


                        scope.center.lat = arr_gmap_cord[0];
                        scope.center.lon = arr_gmap_cord[1];

                        

                              
                               sContent =  '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="#">' + json_institution + '</a></div><div><div style="width:300px"><div><b style="color:#2883f9">(Drugstoc Verified)</b><br>' + json_address +'<br> '+ json_state +'<b><br>email</b>: '+ json_email +'<br></div></div></div></div>';

                              if(json_user_type == "Distributor"){
                                
                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_distributor.png';

                              }else if(json_user_type == "Manufacturer"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_manufacturer.png';

                              }else if(json_user_type == "Importer"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_importer.png';

                              }else if(json_user_type == "Wholesale"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_importer.png';

                              }else if(json_user_type == "Pharmacy"){

                                map_icon = 'wp-content/themes/sistina/images/map_marker/ico_pharmacy.png';

                              }else{

                                map_icon = 'wp-content/themes/sistina/images/map_marker/marker_green.png';

                              }


                              var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);
                              
                              scope.myLocation = new google.maps.Marker({
                                position: myLatLng,
                                map: scope.map,
                                title: json_institution,
                                icon: map_icon,
                                info: sContent
                              });

                               
                             
                  var infowindow = new google.maps.InfoWindow({
                      content: '<a href="#" class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()"> ' + json_institution + '</a>'
                    });

                  google.maps.event.addListener(scope.myLocation, 'click', function() {
      
                infowindow.setContent(this.info);
                infowindow.open(scope.map,this);
                  });

                   scope.isLoadingGmaps = false;
                  scope.errorLoadingGmaps = false;  

                         
                         }//end for

                    }//end sucess handler

                });//End Jquery Ajax

                }// end drawmap function
   // }
      


    // function Initializer () {
    //     //Google's url for async maps initialization accepting callback function
    //     var asyncUrl = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOt9IYHpYN22m7alw_HKi5y5WBgu57p4s&v=3.exp&sensor=true&callback=googleMapsInitialized';
    //     var gMapsLoader = jQuery.getScript(asyncUrl);

    // }





jQuery(document).on('click', '.open-tag-dialog-btn', function(e) {
        scope.tagPopOver();
});


