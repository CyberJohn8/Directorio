self.addEventListener("install", function (event) {
    self.skipWaiting();
  });
  
  self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    event.waitUntil(
      clients.openWindow("https://cyberjohn.infinityfreeapp.com/Menu/Eventos/")
    );
  });
  