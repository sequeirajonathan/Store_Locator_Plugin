<?php
/*
Plugin Name: Store Locator Plugin
Plugin URI: 
Description: Displays a list of stores using the google maps API
Version: 1.0.0
Author: Jonathan Sequeira
Author URI: http://www.vet2dev.com
 */

function wp_store_locator_menu()
{
    add_options_page(
        'Store Locator Plugin',
        'Store Locator Settings',
        'manage_options',
        'store_locator_plugin',
        'wpstore_locator_get_profile'
    );
}
add_action('admin_menu', 'wp_store_locator_menu');


function wpstore_locator_get_profile()
{
  
    echo ('
    <style> 
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    
    .btn {
      border: 1px solid transparent;
      border-radius: .25rem;
      cursor: pointer;
      display: inline-block;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      padding: .375rem 1rem;
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;
    }
    
    .btn-reset {
      background-color: #0275d8;
      border-color: #0275d8;
      color: #fff;
    }
    .btn-reset:hover {
      background-color: #025aa5;
    }
    
    body .container {
      margin: 0 auto;
      width: 100%;
      /* Small devices (tablets, >= 768px and <= 991px) */
      /* Medium devices (desktops, >= 992px and <= 1100px) */
      /* Large devices (large desktops, 1200px and up) */
    }
    @media (min-width: 768px) and (max-width: 991px) {
      body .container {
        width: 100%;
      }
    }
    @media (min-width: 992px) and (max-width: 1199px) {
      body .container {
        width: 100%;
      }
    }
    @media (min-width: 1200px) {
      body .container {
        width: 900px;
      }
    }
    body .container .title {
      text-align: center;
    }
    body .container .row {
      margin: 0 auto;
      text-align: center;
      width: 100%;
    }
    body .container .row .input {
      display: inline-block;
    }
    body .container .row .input .places-input {
      border-radius: 4px;
      font-size: 0.85em;
      margin: 10px 0;
      padding: 5px;
      width: 200px;
    }
    body .container .row .control {
      display: inline-block;
    }
    body .container #map {
      display: block;
      height: 500px;
      margin: 0 auto;
      width: 100%;
      /* Small devices (tablets, >= 768px and <= 991px) */
      /* Medium devices (desktops, >= 992px and <= 1100px) */
      /* Large devices (large desktops, 1200px and up) */
    }
    @media (min-width: 768px) and (max-width: 991px) {
      body .container #map {
        width: 100%;
      }
    }
    @media (min-width: 992px) and (max-width: 1199px) {
      body .container #map {
        width: 100%;
      }
    }
    @media (min-width: 1200px) {
      body .container #map {
        width: 900px;
      }
    }
  
    </style>

');

    ?>

<div class="container">
  <h1 class="title">Search Store Locations</h1>
  <div class="row">
    <div class="input">
      <input type="text" class="places-input" placeholder="Search box" />
    </div>
    <div class="control">
      <button type="button" class="btn btn-reset">Reset</button>
    </div>
  </div>
  <div id="map"></div>
</div>




<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4tCdx8Sem52e8lzJNlbQy_1n8cof4Pzg&libraries=places&callback=initMap"
  type="text/javascript"></script>

<script>
function initMap() {
    var input = document.querySelector(".places-input");
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: 25.7735,
      lng: -80.3218
    },
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  var markers = [];
  var resetBtn = document.querySelector(".btn-reset");
  var searchBox = new google.maps.places.SearchBox(input);

  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  searchBox.addListener("places_changed", function(e) {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(
        new google.maps.Marker({
          map: map,
          icon: icon,
          title: place.name,
          position: place.geometry.location
        })
      );

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });

    map.fitBounds(bounds);
  });

  resetBtn.addEventListener("click", function(e) {
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    map = new google.maps.Map(document.getElementById('map'), {
      center: {
        lat: 41.850033,
        lng: -87.6500523
      },
      zoom: 3,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    input.value = "";
  }, false);

  google.maps.event.addDomListener(window, "resize", function() {
    var center = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(center);
  });
}
</script>

    <?php
  
}
add_action('wp_enqueue_scripts', 'wpstore_locator_get_profile');