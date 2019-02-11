/*global google,gmap_data*/
'use strict';

/*
 *  new_map
 *
 *  This function will render a Google Map onto the selected jQuery element
 *
 *  @type	function
 *  @date	8/11/2013
 *  @since	4.3.0
 *
 *  @param	$el (jQuery element)
 *  @return	n/a
 */

function new_map(node) {

    if (!node.getAttribute('data-gmap')) {
        // vars
        var args = {
            zoom: 15,
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        };
        
        var markers = node.querySelectorAll('.Gmap-marker');

        // create map
        var map = new google.maps.Map(node, args);
        map.setOptions({styles: get_styles()});

        node.setAttribute('data-gmap', map);

        // add a markers reference
        map.markers = [];

        // add markers
        for (var i = 0; i < markers.length; ++i) {
            add_marker(markers[i], map);
        }

        // center map
        center_map(map);

        // return
        return map;
    }
}

/*
 *  add_marker
 *
 *  This function will add a marker to the selected Google Map
 *
 *  @type	function
 *  @date	8/11/2013
 *  @since	4.3.0
 *
 *  @param	$marker (jQuery element)
 *  @param	map (Google Map object)
 *  @return	n/a
 */

function add_marker(node, map) {

    // var
    var latlng = new google.maps.LatLng(node.getAttribute('data-lat'), node.getAttribute('data-lng'));

    // create marker
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        // icon: $marker.data('icon')
        icon: {
            url: gmap_data.marker_path,
            anchor: new google.maps.Point(18, 56)
            // scaledSize: new google.maps.Size(30, 40)
        }
    });

    // add to array
    map.markers.push(marker);
    
    // map.markers.push(new google.maps.Marker({
    //     position: latlng,
    //     map: map
    // }));

    // if marker contains HTML, add it to an infoWindow
    if (node.getAttribute('data-content')) {
        // create info window
        var infowindow = new google.maps.InfoWindow({
            content: node.getAttribute('data-content')
        });

        // show info window when marker is clicked
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
    }

}

/*
 *  center_map
 *
 *  This function will center the map, showing all markers attached to this map
 *
 *  @type	function
 *  @date	8/11/2013
 *  @since	4.3.0
 *
 *  @param	map (Google Map object)
 *  @return	n/a
 */

function center_map(map) {

    // vars
    var bounds = new google.maps.LatLngBounds();

    // loop through all markers and create bounds
    for (var i = 0; i < map.markers.length; ++i) {
        bounds.extend(
            new google.maps.LatLng(map.markers[i].position.lat(), map.markers[i].position.lng())
        );
    }

    if (map.markers.length === 1) {
        // single marker, set center of map
        map.setCenter(bounds.getCenter());
        map.setZoom(17);
    }
    else {
        // fit to bounds
        map.fitBounds(bounds);
    }
}

function center_on(node, lat, lng, zoom) {
    var map = node.getAttribute('data-gmap');
    if (map) {
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(lat, lng));
        map.setCenter(bounds.getCenter());
        map.setZoom(zoom || 15);
    }
}

function resize(node) {
    var map = node.getAttribute('data-gmap');
    if (map) {
        google.maps.event.trigger(map, 'resize');
    }
}

function get_styles() {
    return [
        {
            "featureType": "all",
            "elementType": "all",
            "stylers": [
                {
                    "saturation": "25"
                },
                {
                    "hue": "#0078ff"
                },
                {
                    "lightness": "-7"
                }
            ]
        },
        {
            "featureType": "all",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "saturation": "10"
                },
                {
                    "lightness": "21"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "all",
            "stylers": [
                {
                    "saturation": -70
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "simplified"
                },
                {
                    "saturation": "-30"
                }
            ]
        }
    ];
}

init(document.querySelectorAll('.Gmap'));

function init(nodes) {
    if (!nodes.length) {
        return;
    }
    
    var deferred = [];

    window[gmap_data.callback] = function () {
        for (var i = 0; i < deferred.length; ++i) {
            deferred[i].func(deferred[i].args);
        }
    };

    for (var i = 0; i < nodes.length; ++i) {
        (function (node) {

            function action(action) {
                switch (action) {
                    case 'center':
                        center_on(node, arguments[2], arguments[3]);
                        break;
                    case 'resize':
                        resize(node);
                        break;
                    default:
                        new_map(node);
                }
            }

            if (window.google === undefined) {
                node.map = function () {
                    deferred.push({
                        func: action,
                        args: arguments
                    });
                };
                deferred.push({
                    func: function () {
                        node.map = action;
                    }
                });
            }
            else {
                node.map = action;
            }

            // initialize map
            if (!node.getAttribute('data-noauto')) {
                node.map.call(node, 'init');
            }
        })(nodes[i]);
    }
}