// Initialisation de la carte
const map = L.map('map').setView([48.8566, 2.3522], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap'
}).addTo(map);

// Icone personnalisee pour les zones
const zoneIcon = L.icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

// Affichage des zones sur la carte
const zones = window.zonesData || [];
const zonesList = document.getElementById('zones-list');
const bounds = [];

zones.forEach(function(zone) {
  if (zone.lat && zone.long) {
    const marker = L.marker([zone.lat, zone.long], { icon: zoneIcon })
      .addTo(map)
      .bindPopup('<strong>' + zone.name + '</strong><br>' + zone.spaceType);

    marker.on('click', function() {
      const url = window.zoneShowBaseUrl.replace('__ID__', zone.id);
      window.location.href = url;
    });

    bounds.push([zone.lat, zone.long]);
  }

  // Ajout dans la liste
  if (zonesList && zones.length > 0) {
    const item = document.createElement('a');
    item.href = window.zoneShowBaseUrl.replace('__ID__', zone.id);
    item.className = 'block p-2 bg-gray-50 rounded hover:bg-gray-100 text-gray-800';
    item.innerHTML = '<span class="font-medium">' + zone.name + '</span><span class="text-sm text-gray-500 ml-2">' + zone.surface + ' m²</span>';
    zonesList.appendChild(item);
  }
});

// Ajuster la vue pour voir toutes les zones
if (bounds.length > 0) {
  map.fitBounds(bounds, { padding: [50, 50] });
}

// Geolocalisation de l'utilisateur
if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(
    function (position) {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;

      // Si pas de zones, centrer sur l'utilisateur
      if (bounds.length === 0) {
        map.setView([lat, lng], 15);
      }

      // Marqueur utilisateur
      L.marker([lat, lng])
        .addTo(map)
        .bindPopup("Vous etes ici");
    },
    function (error) {
      console.error("Erreur de geolocalisation", error);
    }
  );
}
