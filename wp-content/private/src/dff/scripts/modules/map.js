/* eslint-disable no-unused-vars */
/* eslint-disable no-loop-func */
/* eslint-disable no-undef */
const createMapBlock = $map => {
  const locations = JSON.parse($map.dataset.locations.replace(/'/g, '"'));
  const zoom = parseInt($map.dataset.zoom, 10) || 15;

  const { google } = window;
  window.geocoder = new google.maps.Geocoder();
  const latlng = new google.maps.LatLng(53.3496, -6.3263);
  const mapOptions = {
    zoom,
    center: latlng,
  };

  const map = new google.maps.Map($map, mapOptions);

  let i = 0;

  const { geocoder } = window;

  // Create empty LatLngBounds object.
  const bounds = new google.maps.LatLngBounds();
  for (i = 0; i < locations.length; i += 1) {
    // Convert the location into an array.
    const infoWindowLocation = locations[i].split(',');

    // Split the array to get the first line and the rest separated.
    // First line will be the title. Note: muatation on infoWindowLocation. Desired.
    const infoWindowTitle = infoWindowLocation.shift();

    const infoWindowString = `
      <div class="poi-info-window gm-style">
        <div class="title full-width">${infoWindowTitle}</div>
        <div class="address">
          ${infoWindowLocation
            .map(line => `<div class="address-line full-width">${line}</div>`)
            .join('')}
        </div>
      </div>
    `;

    const infowindow = new google.maps.InfoWindow({
      content: infoWindowString,
    });

    geocoder.geocode({ address: locations[i] }, (results, status) => {
      if (status === google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);

        const marker = new google.maps.Marker({
          map,
          position: results[0].geometry.location,
        });

        marker.addListener('click', () => {
          infowindow.open(map, marker);
        });

        bounds.extend(marker.position);
        map.fitBounds(bounds);
      }
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  Array.from(document.querySelectorAll('.map')).forEach(createMapBlock);
});
