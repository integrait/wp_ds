var user_id = 0;

window.google_map_status = "";

function Initializer() {
    var asyncUrl = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOt9IYHpYN22m7alw_HKi5y5WBgu57p4s&v=3.exp&sensor=true&callback=googleMapsInitializedSingleMap';
    var gMapsLoader = jQuery.getScript(asyncUrl);
}


var single_object = {};

function draw_single_map(sin_institution, sin_address, sin_state, sin_email, sin_lat, sin_lon, sin_user_type) {

    single_object.sin_institution = sin_institution;
    single_object.sin_address = sin_address;
    single_object.sin_state = sin_state;
    single_object.sin_email = sin_email;
    single_object.sin_lat = sin_lat;
    single_object.sin_lon = sin_lon;
    single_object.sin_user_type = sin_user_type;

    Initializer();
}

var scope = {
    center: {
        lat: 6.605511,
        lon: 3.344349
    }
};

window.googleMapsInitializedSingleMap = function() {

    var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);
    var mapOptions = {
        zoom: 8,
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

    scope.map = new google.maps.Map(jQuery('div.show_homepage_map')[0], mapOptions);

    ds_base_location = new google.maps.Marker({
        position: myLatLng,
        map: scope.map,
        title: 'My Location',
        //icon: '../../wp-content/themes/sistina/images/map_marker/marker_pink.png'
        icon: 'img/u-marker.png'
    });

    console.log(myLatLng);
 
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
 
    scope.center.lat = single_object.sin_lat;
    scope.center.lon = single_object.sin_lon;
 
    sContent = '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="#">' + single_object.sin_institution + '</a></div><div><div style="width:300px"><div><b style="color:#2883f9">(Drugstoc Verified)</b><br>' + single_object.sin_address + '<br> ' + single_object.sin_state + '<b><br>email</b>: ' + single_object.sin_email + '<br></div></div></div></div>';

    if (single_object.sin_user_type == "Distributor") {

        map_icon = '../../wp-content/themes/sistina/images/map_marker/ico_distributor.png';

    } else if (single_object.sin_user_type == "Manufacturer") {

        map_icon = '../../wp-content/themes/sistina/images/map_marker/ico_manufacturer.png';

    } else if (single_object.sin_user_type == "Importer") {

        map_icon = '../../wp-content/themes/sistina/images/map_marker/ico_importer.png';

    } else if (single_object.sin_user_type == "Wholesale") {

        map_icon = '../../wp-content/themes/sistina/images/map_marker/ico_importer.png';

    } else if (single_object.sin_user_type == "Pharmacy") {

        map_icon = '../../wp-content/themes/sistina/images/map_marker/ico_pharmacy.png';

    } else {

        map_icon = '../../wp-content/themes/sistina/images/map_marker/marker_green.png';

    }
 
    var myLatLng = new google.maps.LatLng(scope.center.lat, scope.center.lon);

    console.log(myLatLng);

    scope.myLocation = new google.maps.Marker({
        position: myLatLng,
        map: scope.map,
        title: single_object.sin_institution,
        icon: map_icon,
        info: sContent
    });
 
    var infowindow = new google.maps.InfoWindow({
        content: '<a href="#" class="button button-positive open-tag-dialog-btn button-clear button-small" ng-click="tagPopOver()"> ' + single_object.sin_institution + '</a>'
    });

    google.maps.event.addListener(scope.myLocation, 'click', function() {

        infowindow.setContent(this.info);
        infowindow.open(scope.map, this);
    });
 
    scope.isLoadingGmaps = false;
    scope.errorLoadingGmaps = false;

}

//Initializer();
 
function draw_single_mapxx(sin_institution, sin_address, sin_state, sin_email, sin_lat, sin_lon, sin_user_type) {

    scope.center.lat = sin_lat;
    scope.center.lon = sin_lon;

 
    sContent = '<div class="flxmap-infowin"><div class="flxmap-marker-title"><a href="#">' + sin_institution + '</a></div><div><div style="width:300px"><div><b style="color:#2883f9">(Drugstoc Verified)</b><br>' + sin_address + '<br> ' + sin_state + '<b><br>email</b>: ' + sin_email + '<br></div></div></div></div>';

    if (sin_user_type == "Distributor") {

        map_icon = 'wp-content/themes/sistina/images/map_marker/ico_distributor.png';

    } else if (sin_user_type == "Manufacturer") {

        map_icon = 'wp-content/themes/sistina/images/map_marker/ico_manufacturer.png';

    } else if (sin_user_type == "Importer") {

        map_icon = 'wp-content/themes/sistina/images/map_marker/ico_importer.png';

    } else if (sin_user_type == "Wholesale") {

        map_icon = 'wp-content/themes/sistina/images/map_marker/ico_importer.png';

    } else if (sin_user_type == "Pharmacy") {

        map_icon = 'wp-content/themes/sistina/images/map_marker/ico_pharmacy.png';

    } else {

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
        infowindow.open(scope.map, this);
    });

    scope.isLoadingGmaps = false;
    scope.errorLoadingGmaps = false;

} //end draw single map
 
jQuery(document).on('click', '.open-tag-dialog-btn', function(e) {
    scope.tagPopOver();
});