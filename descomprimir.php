<?php
function descomprimirZip($archivo) {
    $zip = new ZipArchive;
    if ($zip->open($archivo) === TRUE) {
        $zip->extractTo('./'); // Extrae en la carpeta actual
        $zip->close();
        echo "✅ Archivo <strong>$archivo</strong> descomprimido correctamente.<br>";
    } else {
        echo "❌ No se pudo descomprimir <strong>$archivo</strong>.<br>";
    }
}

echo "<h3>Proceso de descompresión:</h3>";
descomprimirZip("Menu.zip");
descomprimirZip("Cuenta.zip");

echo "<br><strong>¡Proceso finalizado! Recuerda eliminar este archivo por seguridad.</strong>";
?>
