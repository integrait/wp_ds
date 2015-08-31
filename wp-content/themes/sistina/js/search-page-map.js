// Get the URL parameter for tab
    function getURLParameter(name) {
        return decodeURI((new RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ''])[1]);
    }


jQuery(document).ready(function(){

    var param = getURLParameter('s');
    //var param = "para";
      
      jQuery.ajax({
       // url:"http://localhost/drugstoc_2/wp-admin/admin-ajax.php",
        //url: "wp-content/themes/sistina/drugstoc/search-map.php?search_parameter="+param,
        //method: "GET",
        url: ds_cart_vars.ajaxurl,
        dataType : "json",

        data: {
            action: 'getSeacrhMap_json',
            search_parameter : param
        },
        success: function(data){
            console.log(data);
           // alert('function is success' + data);

           for (var i=0;i<data.length; i++){
            //alert(data[i].institution);

                gmap_cord = data[i].gmap_coords;
                //alert(gmap_cord);
                var arr_gmap_cord = gmap_cord.split(',');


                scope.center.lat = arr_gmap_cord[0];
                scope.center.lon = arr_gmap_cord[1];



                 var link_breaker = data[i].meta_value.split('_');
                 var distributor_link = document.location.origin+"/drugstoc_2/vendor/"+link_breaker[0]+"?ds="+param;



                //alert(scope.center.lon);
                sContent =  '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="'+ distributor_link +'">' + data[i].institution + '</a></div><div><div style="width:300px"><div><b style="color:#2883f9">(Drugstoc Verified)</b><br>' + data[i].billing_address_1 +'<br>Lagos<b><br>email</b>: '+ data[i].user_email +'<br></div></div></div></div>';

                var myLatLng = new google.maps.LatLng(arr_gmap_cord[0], arr_gmap_cord[1]);

                scope.myLocation = new google.maps.Marker({
                position: myLatLng,
                map: scope.map,
                title: data[i].institution,
                //icon: 'wp-content/themes/sistina/images/map_marker/marker_green.png',
                icon: 'wp-content/themes/sistina/images/map_marker/ico_distributor.png',
                info: sContent
                });


                 google.maps.event.addDomListener(jQuery('div.map-container'), 'mousedown', function(e) {
                  e.preventDefault();
                  return false;
                });// end jquery ajax



      // var infowindow = new google.maps.InfoWindow({
      //   content: '<button class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()"> ' + data[i].institution + '</button>'
      // });

          //r_content = "Institution is : " + data[i].institution;
                   
        

         //var infowindow = new google.maps.InfoWindow({ content: sContent });
         

           var infowindow = new google.maps.InfoWindow({
           content: '<a href="'+distributor_link+'" class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()"> ' + data[i].institution + '</a>'
           });

            google.maps.event.addListener(scope.myLocation, 'click', function() {
      
            infowindow.setContent(this.info);
            infowindow.open(scope.map,this);
           
            });



      //infowindow.open(scope.map, scope.myLocation);

      scope.isLoadingGmaps = false;
      scope.errorLoadingGmaps = false;

           }

        }
    });// end document.ready

    function Initializer () {
        //Google's url for async maps initialization accepting callback function
        var asyncUrl = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOt9IYHpYN22m7alw_HKi5y5WBgu57p4s&v=3.exp&sensor=true&callback=googleMapsInitialized';
            var gMapsLoader = jQuery.getScript(asyncUrl);

    }



      
          var scope = {
              center: {
                   lat: 6.605511,
                   lon: 3.344349
              }
          };    
        

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(success);
           //alert('Geo Location is supported');
        } else{


        }//end if navigation

        function success(position) {
          var lat = position.coords.latitude;
          var lon = position.coords.longitude;


              

                
                scope.center.lat= lat;
                scope.center.lon= lon;
        
          console.log("Geo lon : " + lon + " Scope Lon : " + scope.center.lon);        
               
        }// END SUCCESS FUNCTION

          


   


    window.googleMapsInitialized = function(){//window google map inintialiazes
      var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);
      var mapOptions = {
        zoom: 10,
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



      scope.map = new google.maps.Map(jQuery('div.map-container')[0], mapOptions);


      default_location = "DrugStoc Base";

    

      //scope.myLocation = new google.maps.Marker({
      ds_base_location = new google.maps.Marker({
          position: myLatLng,
          map: scope.map,
          title: default_location,
          //icon: 'wp-content/themes/sistina/images/map_marker/marker_pink.png'
          icon: 'img/u-marker.png'
      });



      google.maps.event.addDomListener(jQuery('div.map-container'), 'mousedown', function(e) {
        e.preventDefault();
        return false;
      });


      //infoWindow = new google.maps.InfoWindow({ content: sContent });


      var infowindow = new google.maps.InfoWindow({
        //content: '<button class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()">Drugstoc Base</button>'
        content : '<div class="flxmap-infowin"><div class="flxmap-marker-title">DrugStoc</div><div><div style="width:300px"><img style="float:right;" src="http://drugstoc.biz/wp-content/uploads/2014/10/splash-logo-beta.png" width="100px"><div><b style="color:#2883f9">(Base Location)</b><br>13, Gbajobi street, Ikeja,<br>Lagos<b><br>email</b>: info@drugstoc.biz<br></div></div></div></div>'
      });


      //infoWindowArray["{!default_location}"] = infowindow;


      google.maps.event.addListener(ds_base_location, 'click', function() {
         infowindow.open(scope.map,ds_base_location);
      });

      






      //infowindow.open(scope.map, scope.myLocation);

      scope.isLoadingGmaps = false;
      scope.errorLoadingGmaps = false;

    }// end google map initializer    

    Initializer();

});



jQuery(document).on('click', '.open-tag-dialog-btn', function(e) {
        scope.tagPopOver();
});


