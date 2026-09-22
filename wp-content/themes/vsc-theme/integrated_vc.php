<?php

/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 11/10/2017
 * Time: 10:55
 */
function vsc_map()
{
    $map = '<div id="map1" style="width: 100%;height:430px;min-height: 100%;"></div>';
    ?>
    <script>

        addedMap = false;
        jQuery(document).scroll(function () {
            if (!addedMap && jQuery(document).scrollTop() > 100) {
                addedMap = true;
                jQuery(function () {
                    var points = new google.maps.LatLng(45.50769580633233, -73.56435486050708);
                    var styles = [
                        {
                            "stylers": [
                                {"visibility": "on"},
                                {"hue": "#F1ECE9"}
                            ]
                        }
                    ];

                    var mapOptions1 = {
                        scrollwheel: false,
                        // How zoomed in you want the map to start at (always required)
                        zoom: 17,
                        center: points, // New York
                        // This is where you would paste any style found on Snazzy Maps.
                        styles: styles
                    };
                    // Create the Google Map using our element and options defined above
                    var map1 = new google.maps.Map(document.getElementById("map1"), mapOptions1);
                    var rectangle = new google.maps.Rectangle();

                    // Let's also add a marker while we're at it
                    var marker = new google.maps.Marker({
                        position: points,
                        map: map1,
                        icon: '/wp-content/themes/vsc-theme/css/img/icon-map.svg',
                        url: 'https://www.google.com/maps/place/Complexe+Desjardins/@45.5075266,-73.5669083,17z/data=!3m1!5s0x4cc91a45a1cf65b7:0x3310f1891dd11e56!4m14!1m7!3m6!1s0x4cc91a4fca1ae933:0x9e1c26554e8a2355!2sComplexe+Desjardins!8m2!3d45.5075229!4d-73.5643334!16zL20vMDR2NTJo!3m5!1s0x4cc91a4fca1ae933:0x9e1c26554e8a2355!8m2!3d45.5075229!4d-73.5643334!16zL20vMDR2NTJo?entry=ttu'
                    });
                    marker.setAnimation(google.maps.Animation.BOUNCE);

                    marker.addListener('mouseover', function () {
                        marker.setAnimation(null);
                    });
                    marker.addListener('mouseout', function () {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    });
                    google.maps.event.addListener(marker, 'click', function () {
                        window.location.href = this.url;
                    });
                });
            }
            document.getElementById("year").innerHTML = new Date().getFullYear();
        });
    </script>
    <?php return $map;
}

add_shortcode('vsc_map', 'vsc_map');
