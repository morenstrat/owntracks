(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.owntracks = {
    attach: function (context) {
      $('#owntracks-map').once().each(function() {
        var track = drupalSettings.owntracks.track;
        var map = L.map('owntracks-map');

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        if (track.length) {
          var polyline = L.polyline(track, {color: 'blue'}).addTo(map);
          map.fitBounds(polyline.getBounds());

          $.each(track, function (i, e) {
            L.marker(e).addTo(map);
          });
        }
        else {
          map.setView([51.4833333, 7.2166667], 2);
        }
      });
    }
  };

}(jQuery, Drupal, drupalSettings));
