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
const zonesListDesktop = document.getElementById('zones-list-desktop');
const zonesListMobile = document.getElementById('zones-list-mobile');
const bounds = [];

// Fonction pour creer un item de liste
function createZoneListItem(zone, isMobile) {
  const item = document.createElement('a');
  item.href = window.zoneShowBaseUrl.replace('__ID__', zone.id);
  item.className = isMobile
    ? 'block p-3 bg-stone-800/50 rounded-xl hover:bg-stone-700/50 transition-colors'
    : 'block p-3 bg-stone-700/30 rounded-xl hover:bg-stone-700/50 transition-colors';
  item.innerHTML = '<div class="flex items-center gap-3">' +
    '<div class="w-8 h-8 bg-emerald-600/30 rounded-lg flex items-center justify-center">' +
    '<svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>' +
    '</div>' +
    '<div class="flex-1">' +
    '<span class="font-medium text-white block">' + zone.name + '</span>' +
    '<span class="text-xs text-stone-400">' + zone.surface + ' m² - ' + (zone.spaceType || 'Non defini') + '</span>' +
    '</div></div>';
  return item;
}

zones.forEach(function(zone) {
  if (zone.lat && zone.long) {
    const marker = L.marker([zone.lat, zone.long], { icon: zoneIcon })
      .addTo(map)
      .bindPopup('<div class="text-center"><strong class="text-emerald-700">' + zone.name + '</strong><br><span class="text-sm text-gray-600">' + (zone.spaceType || '') + '</span><br><span class="text-xs text-gray-500">' + zone.surface + ' m²</span></div>');

    marker.on('click', function() {
      const url = window.zoneShowBaseUrl.replace('__ID__', zone.id);
      window.location.href = url;
    });

    bounds.push([zone.lat, zone.long]);
  }

  // Ajout dans les listes desktop et mobile
  if (zonesListDesktop && zones.length > 0) {
    zonesListDesktop.appendChild(createZoneListItem(zone, false));
  }
  if (zonesListMobile && zones.length > 0) {
    zonesListMobile.appendChild(createZoneListItem(zone, true));
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
