<?php
$directorio = __DIR__ . "/conversacion";

if (!is_dir($directorio)) {
    die("❌ La carpeta conversacion NO existe en: " . $directorio);
}

echo "<h2>Archivos en conversacion:</h2><ul>";
foreach (scandir($directorio) as $archivo) {
    if ($archivo !== "." && $archivo !== "..") {
        echo "<li>" . htmlspecialchars($archivo) . "</li>";
    }
}
echo "</ul>";
