<?php
header("Content-Type: application/javascript");
?>
// aquí va tu código JS del Service Worker




const CACHE_NAME = "geobiblia-cache-v4";

const BASE_URL = "https://cyberjohn.infinityfreeapp.com";

const urlsToCache = [
  `${BASE_URL}/`,
  `${BASE_URL}/index.php`,
  `${BASE_URL}/Formulario.css`,
  `${BASE_URL}/manifest.json`,
  `${BASE_URL}/service-worker.js`,
  `${BASE_URL}/Menu/iconos/icon2-8 1.png`,
  `${BASE_URL}/Menu/iconos/icon2-8 1.png`,
  `${BASE_URL}/Iniciar_Sesion.php`,
  `${BASE_URL}/Registrarse.php`,
  `${BASE_URL}/recuperar_contrasena.php`,
  `${BASE_URL}/Menu/index.php`,
  `${BASE_URL}/Ubicaciones/ubicaciones.css`,
  `${BASE_URL}/Ubicaciones/ubicaciones.js`,
  `${BASE_URL}/Ubicaciones/ubicaciones.php`,
  `${BASE_URL}/LiteraturaBiblica/literatura.css`,
  `${BASE_URL}/LiteraturaBiblica/literatura.js`,
  `${BASE_URL}/LiteraturaBiblica/literatura.php`,
  `${BASE_URL}/Eventos/eventos.css`,
  `${BASE_URL}/Eventos/eventos.js`,
  `${BASE_URL}/Eventos/eventos.php`,
  `${BASE_URL}/Material/material.css`,
  `${BASE_URL}/Material/material.js`,
  `${BASE_URL}/Material/material.php`,
  `${BASE_URL}/imagenes/mapa.png`,
  `${BASE_URL}/imagenes/fondo.jpg`,
  `${BASE_URL}/offline.html`
];

// Instalación con cacheo completo sin abortar si falla uno
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(async (cache) => {
      for (let url of urlsToCache) {
        try {
          await cache.add(url);
          console.log("Cacheado:", url);
        } catch (err) {
          console.warn("No se pudo cachear:", url, err);
        }
      }
    })
  );
  self.skipWaiting();
});

// Activación: limpiar caches antiguos
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch con soporte offline y actualización de icon2-8 1s
self.addEventListener("fetch", (event) => {
  let requestURL = new URL(event.request.url);

  // Si es la página raíz con parámetros (?i=1), usar cache de "/"
  if (requestURL.pathname === "/Geolocalizador/" && requestURL.search) {
    event.respondWith(caches.match(`${BASE_URL}/`));
    return;
  }

  event.respondWith(
    caches.match(event.request).then((response) => {
      if (response) return response;

      return fetch(event.request)
        .then((networkResponse) => {
          if (
            event.request.method === "GET" &&
            networkResponse.status === 200
          ) {
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, networkResponse.clone());
            });
          }
          return networkResponse;
        })
        .catch(() => {
          if (event.request.mode === "navigate") {
            return caches.match(`${BASE_URL}/offline.html`);
          }
        });
    })
  );
});
