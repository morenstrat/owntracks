(function ($, Drupal, drupalSettings) {
  var maps = drupalSettings.owntracks.maps;

  $.each(maps, function(d, data) {
    var map = L.map(data.id);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    if (data.track.length == 1) {
      map.setView(data.track[0], 13);
      L.marker(data.track[0]).addTo(map);
    }
    else {
      var polyline = L.polyline(data.track, {color: 'blue'}).addTo(map);
      map.fitBounds(polyline.getBounds());
    }

    data.processed = true;
  });
}(jQuery, Drupal, drupalSettings));
