<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <title>Marker Clustering</title>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body onload="initMap()">
<div id="map"></div>
<script type="text/javascript">
    let url = "http://45.32.112.173:84/app/log-data";
    var locations = [];


    function initMap() {
        $.ajax({
            url: url,
            type: "GET",
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                var rs = JSON.parse(result);
                if (rs['success']) {
                    locations = rs['data']['items'];
                    var infoWin = new google.maps.InfoWindow();
                    var markers = locations.map(function (location, i) {
                        var lat = locations[i]['latitude'];
                        var long = locations[i]['longitude'];

                        latlngset = new google.maps.LatLng(lat, long);
                        var marker = new google.maps.Marker({
                            position: latlngset
                        });
                        google.maps.event.addListener(marker, 'click', function (evt) {
                            infoWin.setContent(location.content);
                            infoWin.open(map, marker);
                        });
                        return marker;
                    });

                    // Add a marker clusterer to manage the markers.
                    var markerCluster = new MarkerClusterer(map, markers, {
                        imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                    });
                }
            },
            error: function (result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                return;
            }
        });//end jQuery.ajax
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: {
                lat: 11.61,
                lng: 108.04
            },
            mapTypeId: 'satellite'
        });


    }


    google.maps.event.addDomListener(window, "load", initMap);
</script>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR7YxhX_ugreNUsW_CbeHOaE45w7rObgw&libraries=places&sensor=false"></script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
</body>
</html>