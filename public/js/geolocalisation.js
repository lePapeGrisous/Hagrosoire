  // Initialisation par défaut (au cas où l'utilisateur refuse)
  const map = L.map('map').setView([48.8566, 2.3522], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // Géolocalisation
  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // Centrer la carte
        map.setView([lat, lng], 15);

        // Marqueur utilisateur
        L.marker([lat, lng])
          .addTo(map)
          .bindPopup("📍 Vous êtes ici")
          .openPopup();
      },
      function (error) {
        console.error("Erreur de géolocalisation", error);
        alert("Impossible de récupérer votre position.");
      }
    );
  } else {
    alert("La géolocalisation n'est pas supportée par ce navigateur.");
  }
