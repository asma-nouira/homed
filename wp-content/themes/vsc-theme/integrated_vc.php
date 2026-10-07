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
                    var points = new google.maps.LatLng(45.4481339085689, -73.86111057570005);
                    var styles = [
                        {
                            "stylers": [
                                {"visibility": "on"},
                                {"hue": "#592C22"}
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
                        icon: '/wp-content/uploads/2026/10/Icon-map.svg',
                        url: 'https://www.google.com/maps/place/Clinique+Dentaire+HomeDental/@45.4479871,-73.8636211,17z/data=!3m1!4b1!4m6!3m5!1s0x4cc91b61e0dd3be9:0x59c4c05fdc939a49!8m2!3d45.4479834!4d-73.8610462!16s%2Fg%2F11g2zfnvnz?entry=ttu&g_ep=EgoyMDI2MTAwNC4wIKXMDSoASAFQAw%3D%3D'
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
