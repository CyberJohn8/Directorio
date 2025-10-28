// /js/auth-offline.js
// Requiere offline-db.js cargado antes
const BASE_URL = "https://cyberjohn.infinityfreeapp.com"; // ajusta si cambia

// Registro OFFLINE
async function manejarRegistroOffline(formEl) {
  const username = formEl.querySelector("#username").value.trim();
  const email = formEl.querySelector("#email").value.trim();
  const password = formEl.querySelector("#password").value;

  if (!username || !email || !password) {
    alert("Completa todos los campos para registrar offline.");
    return;
  }

  const passwordHash = await sha256Hex(password);

  // Guardar usuario local
  await dbGuardarUsuarioLocal({ username, email, passwordHash, rol: "usuario", origen: "offline" });

  // Encolar acción para sincronizar (guardamos password plano SOLO en cola, NO en usuarios)
  await dbAgregarPendiente({ tipo: "registro", username, email, passwordPlano: password });

  alert("Registro guardado offline. Se sincronizará cuando haya conexión.");
}

// Login OFFLINE
async function manejarLoginOffline(formEl) {
  const email = formEl.querySelector("#email").value.trim();
  const password = formEl.querySelector("#password").value;

  const user = await dbObtenerUsuarioLocal(email);
  if (!user) {
    alert("Usuario no encontrado en modo offline. Intenta de nuevo cuando tengas conexión.");
    return;
  }

  const passwordHash = await sha256Hex(password);
  if (user.passwordHash === passwordHash) {
    await dbSetSesionLocal({ email: user.email, username: user.username, rol: user.rol || "usuario" });
    // Opcional: encolar intento de login para sincronizar en servidor cuando haya conexión
    await dbAgregarPendiente({ tipo: "login", email, passwordPlano: password });
    // Redirigir a menú (modo invitado/autenticado local)
    location.href = "Menu/index.php";
  } else {
    alert("Contraseña incorrecta (modo offline).");
  }
}

// Envío ONLINE (mejora UX: si online, además guardamos local para permitir offline luego)
async function enviarRegistroOnline(formEl) {
  const data = new FormData(formEl);
  const plain = Object.fromEntries(data.entries());

  // Disparar envío normal
  const resp = await fetch("Registrarse.php", { method: "POST", body: new URLSearchParams(plain) });

  // Si el servidor respondió OK, guardamos copia local
  if (resp.ok) {
    const passwordHash = await sha256Hex(plain.password);
    await dbGuardarUsuarioLocal({ username: plain.username, email: plain.email, passwordHash, rol: "usuario", origen: "online" });
  }
  return resp;
}

async function enviarLoginOnline(formEl) {
  const data = new FormData(formEl);
  const plain = Object.fromEntries(data.entries());
  const resp = await fetch("Iniciar_Sesion.php", { method: "POST", body: new URLSearchParams(plain) });

  // Si login correcto, mantenemos sesión local también (para persistir offline)
  if (resp.redirected || resp.ok) {
    // Solo si el backend autentica y redirige (tu PHP hace header Location)
    // Guardamos copia local si ya existe el usuario
    const passwordHash = await sha256Hex(plain.password);
    const local = await dbObtenerUsuarioLocal(plain.email);
    if (!local) {
      await dbGuardarUsuarioLocal({ username: plain.email.split("@")[0], email: plain.email, passwordHash, rol: "usuario", origen: "online" });
    }
    await dbSetSesionLocal({ email: plain.email, username: (local?.username || plain.email.split("@")[0]), rol: (local?.rol || "usuario") });
  }
  return resp;
}

// Auto-sincronizar al volver online
window.addEventListener("online", () => {
  sincronizarPendientesConServidor(BASE_URL);
});

// Helper para “mejor envío” que decide según conexión
function prepararFormAuth(selectorForm, tipo) {
  const form = document.querySelector(selectorForm);
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    // Deja que el PHP procese si hay conexión (para respetar tu backend)
    if (navigator.onLine) {
      // Interceptamos para agregar mejoras y copia local
      e.preventDefault();
      try {
        const resp = tipo === "registro" ? await enviarRegistroOnline(form) : await enviarLoginOnline(form);
        // El backend normalmente redirige. Si no redirige, mostramos mensaje básico
        if (resp.redirected) {
          location.href = resp.url;
        } else {
          // intenta recargar para que se vea mensaje PHP
          location.reload();
        }
      } catch (err) {
        console.warn("Fallo online, intento offline:", err);
        if (tipo === "registro") await manejarRegistroOffline(form);
        else await manejarLoginOffline(form);
      }
    } else {
      // Sin conexión → 100% offline
      e.preventDefault();
      if (tipo === "registro") await manejarRegistroOffline(form);
      else await manejarLoginOffline(form);
    }
  });
}
