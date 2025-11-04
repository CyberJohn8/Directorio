<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**/
$servername = "sql204.infinityfree.com";
$username = "if0_39714112";
$password = "MWgk9nZD6H0RIl";
$database = "if0_39714112_directorio_asambleas";/*/

$servername = "localhost";
$username = "root";
$password = "";
$database = "directorio";/**/

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
if (empty($estado)) {
    die("Por favor, especifica un estado en la URL. Ej: estado.php?estado=Zulia");
}

$nombreEstado = strtolower(str_replace(
    ['á','é','í','ó','ú','ñ',' '],
    ['a','e','i','o','u','n','_'],
    $estado
));
$mapa_path = "IMG/" . $nombreEstado . ".png";
if (!file_exists($mapa_path)) $mapa_path = "IMG/Default.png";

// Obtener todas las iglesias del estado
$sql = "SELECT * FROM iglesias WHERE estado = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $estado);
$stmt->execute();
$result = $stmt->get_result();

$datos_iglesias = [];
while ($row = $result->fetch_assoc()) $datos_iglesias[] = $row;

echo "<script>console.log('Cantidad de iglesias encontradas: " . count($datos_iglesias) . "');</script>";






$mapa_estados = [

    '' => [
        'img' => 'IMG/estados/Default.png'
    ],

    'Amazonas' => [
        'img' => 'IMG/estados/Amazonas.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Puerto Ayacucho', 'asamblea' => 'Puerto Ayacucho'],
        ]
    ],

    'Anzoátegui' => [
        'img' => 'IMG/estados/Anzoátegui.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - EL TIGRITO', 'asamblea' => 'EL TIGRITO'],
            ['texto' => '2', 'leyenda' => '2 - Puerto la Cruz', 'asamblea' => 'Puerto la Cruz'],
            ['texto' => '3', 'leyenda' => '3 - CIUDAD ORINOCO (SOLEDAD)', 'asamblea' => 'CIUDAD ORINOCO (SOLEDAD)'],
        ]
    ],

    'Apure' => [
        'img' => 'IMG/estados/Apure.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Bario Antonio José de Sucre', 'asamblea' => 'Bario Antonio José de Sucre'],
            ['texto' => '2', 'leyenda' => '2 - El Negro', 'asamblea' => 'El Negro'],
            ['texto' => '3', 'leyenda' => '3 - Elorza', 'asamblea' => 'Elorza'],
            ['texto' => '4', 'leyenda' => '4 - Guasdualito', 'asamblea' => 'Guasdualito'],
            ['texto' => '5', 'leyenda' => '5 - San Fernando', 'asamblea' => 'San Fernando'],
        ]
    ],

    'Aragua' => [
        'img' => 'IMG/estados/Aragua.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - BARRIO SAN CARLOS', 'asamblea' => 'BARRIO SAN CARLOS'],
            ['texto' => '2', 'leyenda' => '2 - COROMOTO', 'asamblea' => 'COROMOTO'],
            ['texto' => '3', 'leyenda' => '3 - DIEZ DE DICIEMBRE', 'asamblea' => 'DIEZ DE DICIEMBRE'],
            ['texto' => '4', 'leyenda' => '4 - Carmen de Cura', 'asamblea' => 'Carmen de Cura'],
            ['texto' => '5', 'leyenda' => '5 - LA PICA', 'asamblea' => 'LA PICA'],
            ['texto' => '6', 'leyenda' => '6 - PALO NEGRO, CENTRO', 'asamblea' => 'PALO NEGRO, CENTRO'],
            ['texto' => '7', 'leyenda' => '7 - PAYA', 'asamblea' => 'PAYA'],
            ['texto' => '8', 'leyenda' => '8 - SAN CASIMIRO', 'asamblea' => 'SAN CASIMIRO'],
            ['texto' => '9', 'leyenda' => '9 - San Mateo', 'asamblea' => 'San Mateo'],
            ['texto' => '10', 'leyenda' => '10 - Santa Rita', 'asamblea' => 'Santa Rita'],
            ['texto' => '11', 'leyenda' => '11 - Zuata', 'asamblea' => 'Zuata'],
        ]
    ],

    'Barinas' => [
        'img' => 'IMG/estados/Barinas.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Barrancas', 'asamblea' => 'Barrancas'],
            ['texto' => '2', 'leyenda' => '2 - Barinas', 'asamblea' => 'Barinas'],
            ['texto' => '3', 'leyenda' => '3 - Barinitas', 'asamblea' => 'Barinitas'],
            ['texto' => '4', 'leyenda' => '4 - Guamito', 'asamblea' => 'Guamito'],
            ['texto' => '5', 'leyenda' => '5 - Los Rastrojos', 'asamblea' => 'Los Rastrojos'],
        ]
    ],

    'Bolívar' => [
        'img' => 'IMG/estados/Bolívar.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Caicara del Orinoco', 'asamblea' => 'Caicara del Orinoco'],
            ['texto' => '2', 'leyenda' => '2 - CIUDAD BOLÍVAR - CUYUNÍ', 'asamblea' => 'CIUDAD BOLÍVAR - CUYUNÍ'],
            ['texto' => '3', 'leyenda' => '3 - LA SABANITA', 'asamblea' => 'LA SABANITA'],
            ['texto' => '4', 'leyenda' => '4 - Puerto Ordaz', 'asamblea' => 'Puerto Ordaz'],
            ['texto' => '5', 'leyenda' => '5 - San Félix', 'asamblea' => 'San Félix'],
            ['texto' => '6', 'leyenda' => '6 - SANTA ELENA DE UAIRÉN', 'asamblea' => 'SANTA ELENA DE UAIRÉN'],
            ['texto' => '7', 'leyenda' => '7 - TUMEREMO', 'asamblea' => 'TUMEREMO'],
            ['texto' => '8', 'leyenda' => '8 - Santa Rosa del Buey', 'asamblea' => 'Santa Rosa del Buey'],
        ]
    ],

    'Carabobo' => [
        'img' => 'IMG/estados/Carabobo.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Alpargatón', 'asamblea' => 'Alpargatón'],
            ['texto' => '2', 'leyenda' => '2 - Bejuma', 'asamblea' => 'Bejuma'],
            ['texto' => '3', 'leyenda' => '3 - Boqueron', 'asamblea' => 'Boqueron'],
            ['texto' => '4', 'leyenda' => '4 - Campo Carabobo', 'asamblea' => 'Campo Carabobo'],
            ['texto' => '5', 'leyenda' => '5 - Canoabo', 'asamblea' => 'Canoabo'],
            ['texto' => '6', 'leyenda' => '6 - Chirgua', 'asamblea' => 'Chirgua'],
            ['texto' => '7', 'leyenda' => '7 - Guacara', 'asamblea' => 'Guacara'],
            ['texto' => '8', 'leyenda' => '8 - Güigüe', 'asamblea' => 'Güigüe'],
            ['texto' => '9', 'leyenda' => '9 - Juaniquero', 'asamblea' => 'Juaniquero'],
            ['texto' => '10', 'leyenda' => '10 - La Compañía', 'asamblea' => 'La Compañía'],
            ['texto' => '11', 'leyenda' => '11 - La Jobera', 'asamblea' => 'La Jobera'],
            ['texto' => '12', 'leyenda' => '12 - La Lagunita', 'asamblea' => 'La Lagunita'],
            ['texto' => '13', 'leyenda' => '13 - LA SABANA - CANOABO', 'asamblea' => 'LA SABANA - CANOABO'],
            ['texto' => '14', 'leyenda' => '14 - Las Trincheras', 'asamblea' => 'Las Trincheras'],
            ['texto' => '15', 'leyenda' => '15 - Los Caracaros', 'asamblea' => 'Los Caracaros'],
            ['texto' => '16', 'leyenda' => '16 - Mariara', 'asamblea' => 'Mariara'],
            ['texto' => '17', 'leyenda' => '17 - PRIMAVERA', 'asamblea' => 'PRIMAVERA'],
            ['texto' => '18', 'leyenda' => '18 - San Joaquin', 'asamblea' => 'San Joaquin'],
            ['texto' => '19', 'leyenda' => '19 - San Pablo', 'asamblea' => 'San Pablo'],
            ['texto' => '20', 'leyenda' => '20 - Tocuyito', 'asamblea' => 'Tocuyito'],
            
            ['texto' => '21', 'leyenda' => '21 - Morón', 'asamblea' => 'Morón', 'tipo' => 'redirigir'],
            ['texto' => '22', 'leyenda' => '22 - Puerto Cabello,San Esteban', 'asamblea' => 'Puerto Cabello,San Esteban', 'tipo' => 'redirigir'],
            ['texto' => '23', 'leyenda' => '23 - Valencia', 'asamblea' => 'Valencia', 'tipo' => 'redirigir'],
        ]
    ],

    'Lara' => [
        'img' => 'IMG/estados/Lara.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - CALLE 23', 'asamblea' => 'CALLE 23'],
            ['texto' => '2', 'leyenda' => '2 - Alto de Pueblo Nuevo', 'asamblea' => 'Alto de Pueblo Nuevo'],
            ['texto' => '3', 'leyenda' => '3 - Carora', 'asamblea' => 'Carora'],
            ['texto' => '4', 'leyenda' => '4 - Cauderales', 'asamblea' => 'Cauderales'],
            ['texto' => '5', 'leyenda' => '5 - Duaca', 'asamblea' => 'Duaca'],
            ['texto' => '6', 'leyenda' => '6 - El Carmen', 'asamblea' => 'El Carmen'],
            ['texto' => '7', 'leyenda' => '7 - La Montañita', 'asamblea' => 'La Montañita'],
            ['texto' => '8', 'leyenda' => '8 - La Reluciente', 'asamblea' => 'La Reluciente'],
            ['texto' => '9', 'leyenda' => '9 - La Represa', 'asamblea' => 'La Represa'],
            ['texto' => '10', 'leyenda' => '10 - Moroturo', 'asamblea' => 'Moroturo'],
            ['texto' => '11', 'leyenda' => '11 - San Jacinto', 'asamblea' => 'San Jacinto'],
        ]
    ],

    'Cojedes' => [
        'img' => 'IMG/estados/Cojedes.png',  // la ruta de la imagen del mapa si la tienes
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Buenos Aires', 'asamblea' => 'Buenos Aires'],
            ['texto' => '2', 'leyenda' => '2 - Barrio Ezequiel Zamora', 'asamblea' => 'Barrio Ezequiel Zamora'],
            ['texto' => '3', 'leyenda' => '3 - Barrio Nuevo', 'asamblea' => 'Barrio Nuevo'],
            ['texto' => '4', 'leyenda' => '4 - El Baul', 'asamblea' => 'El Baul'],
            ['texto' => '5', 'leyenda' => '5 - El Muertico', 'asamblea' => 'El Muertico'],
            ['texto' => '6', 'leyenda' => '6 - El Penitente', 'asamblea' => 'El Penitente'],
            ['texto' => '7', 'leyenda' => '7 - Genareño', 'asamblea' => 'Genareño'],
            ['texto' => '8', 'leyenda' => '8 - La Chorrera', 'asamblea' => 'La Chorrera'],
            ['texto' => '9', 'leyenda' => '9 - Las Vegas', 'asamblea' => 'Las Vegas'],
            ['texto' => '10', 'leyenda' => '10 - Los Colorados', 'asamblea' => 'Los Colorados'],
            ['texto' => '11', 'leyenda' => '11 - Manrique', 'asamblea' => 'Manrique'],
            ['texto' => '12', 'leyenda' => '12 - Puente Onoto', 'asamblea' => 'Puente Onoto'],
            ['texto' => '13', 'leyenda' => '13 - San Carlos', 'asamblea' => 'San Carlos'],
            ['texto' => '14', 'leyenda' => '14 - Tinaco', 'asamblea' => 'Tinaco'],
            ['texto' => '15', 'leyenda' => '15 - Tinaquillo', 'asamblea' => 'Tinaquillo'],
        ],
    ],

    'Delta Amacuro' => [
        'img' => 'IMG/estados/Delta Amacuro.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Tucupita', 'asamblea' => 'Tucupita'],
        ],
    ],

    'Distrito Capital' => [
        'img' => 'IMG/estados/Distrito Capital.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - El Cementerio', 'asamblea' => 'El Cementerio'],
            ['texto' => '2', 'leyenda' => '2 - El Valle', 'asamblea' => 'El Valle'],
            ['texto' => '3', 'leyenda' => '3 - Las Adjuntas', 'asamblea' => 'Las Adjuntas'],
            ['texto' => '4', 'leyenda' => '4 - La Vega', 'asamblea' => 'La Vega'],
            ['texto' => '5', 'leyenda' => '5 - Los Flores', 'asamblea' => 'Los Flores'],
        ],
    ],

    'Falcón' => [
        'img' => 'IMG/estados/Falcón.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Buena Vista', 'asamblea' => 'Buena Vista'],
            ['texto' => '2', 'leyenda' => '2 - Chichiriviche', 'asamblea' => 'Chichiriviche'],
            ['texto' => '3', 'leyenda' => '3 - Churuguara', 'asamblea' => 'Churuguara'],
            ['texto' => '4', 'leyenda' => '4 - Coro', 'asamblea' => 'Coro'],
            ['texto' => '5', 'leyenda' => '5 - Dabajuro', 'asamblea' => 'Dabajuro'],
            ['texto' => '6', 'leyenda' => '6 - El Mene', 'asamblea' => 'El Mene'],
            ['texto' => '7', 'leyenda' => '7 - Jacura, Pueblo Nuevo', 'asamblea' => 'Jacura, Pueblo Nuevo'],
            ['texto' => '8', 'leyenda' => '8 - La Guacharaca', 'asamblea' => 'La Guacharaca'],
            ['texto' => '9', 'leyenda' => '9 - Mirimire', 'asamblea' => 'Mirimire'],
            ['texto' => '10', 'leyenda' => '10 - Palma Sola', 'asamblea' => 'Palma Sola'],
            ['texto' => '11', 'leyenda' => '11 - Puerto Cumarebo', 'asamblea' => 'Puerto Cumarebo'],
            ['texto' => '12', 'leyenda' => '12 - Punto Fijo', 'asamblea' => 'Punto Fijo'],
            ['texto' => '13', 'leyenda' => '13 - Santa Cruz de Bucaral', 'asamblea' => 'Santa Cruz de Bucaral'],
            ['texto' => '14', 'leyenda' => '14 - Sector Unión', 'asamblea' => 'Sector Unión'],
            ['texto' => '15', 'leyenda' => '15 - Tocópero', 'asamblea' => 'Tocópero'],
            ['texto' => '16', 'leyenda' => '16 - Tucacas', 'asamblea' => 'Tucacas'],
            ['texto' => '17', 'leyenda' => '17 - Yaracal', 'asamblea' => 'Yaracal'],
        ],
    ],

    'Guarico' => [
        'img' => 'IMG/estados/Guarico.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Altagracia de Orituco', 'asamblea' => 'Altagracia de Orituco'],
            ['texto' => '2', 'leyenda' => '2 - San Juan de los Morros', 'asamblea' => 'San Juan de los Morros'],
            ['texto' => '3', 'leyenda' => '3 - Tigüigüe', 'asamblea' => 'Tigüigüe'],
            ['texto' => '4', 'leyenda' => '4 - Valle de la Pascua', 'asamblea' => 'Valle de la Pascua'],
            ['texto' => '5', 'leyenda' => '5 - Zaraza', 'asamblea' => 'Zaraza'],
        ],
    ],

    'Mérida' => [
        'img' => 'IMG/estados/Mérida.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - El Vigia', 'asamblea' => 'El Vigia'],
            ['texto' => '2', 'leyenda' => '2 - Chamita', 'asamblea' => 'CHAMITA'],
            ['texto' => '3', 'leyenda' => '3 - Jají', 'asamblea' => 'Jají'],
            ['texto' => '4', 'leyenda' => '4 - Los Próceres', 'asamblea' => 'Los Próceres'],
        ],
    ],

    'Miranda' => [
        'img' => 'IMG/estados/Miranda.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Caucagua', 'asamblea' => 'Caucagua'],
            ['texto' => '2', 'leyenda' => '2 - Charallave', 'asamblea' => 'Charallave'],
            ['texto' => '3', 'leyenda' => '3 - Guarenas', 'asamblea' => 'Guarenas'],
            ['texto' => '4', 'leyenda' => '4 - Guatire', 'asamblea' => 'Guatire'],
            ['texto' => '5', 'leyenda' => '5 - La Mata', 'asamblea' => 'La Mata'],
            ['texto' => '6', 'leyenda' => '6 - Los Teques', 'asamblea' => 'Los Teques'],
            ['texto' => '7', 'leyenda' => '7 - Ocumare del Tuy', 'asamblea' => 'Ocumare del Tuy'],
            ['texto' => '8', 'leyenda' => '8 - Santa Lucía', 'asamblea' => 'Santa Lucía'],
            ['texto' => '9', 'leyenda' => '9 - Petare', 'asamblea' => 'Petare'],
            ['texto' => '*', 'leyenda' => '* - Distrito Capital', 'asamblea' => 'Distrito Capital'], // Esta referencia apunta a Distrito Capital
        ],
    ],

    'Monagas' => [
        'img' => 'IMG/estados/Monagas.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Maturin', 'asamblea' => 'Maturin'],
        ],
    ],

    'Nueva Esparta' => [
        'img' => 'IMG/estados/Nueva Esparta.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - PORLAMAR - BARRIO ACHÍPANO', 'asamblea' => 'PORLAMAR - BARRIO ACHÍPANO'],
        ],
    ],

    'Portuguesa' => [
        'img' => 'IMG/estados/Portuguesa.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Acarigua', 'asamblea' => 'Acarigua'],
            ['texto' => '2', 'leyenda' => '2 - Biscucuy', 'asamblea' => 'Biscucuy'],
            ['texto' => '3', 'leyenda' => '3 - Corozo Largo', 'asamblea' => 'Corozo Largo'],
            ['texto' => '4', 'leyenda' => '4 - Guanare', 'asamblea' => 'Guanare'],
            ['texto' => '5', 'leyenda' => '5 - Guanarito', 'asamblea' => 'Guanarito'],
            ['texto' => '6', 'leyenda' => '6 - Los Puertos de Payara', 'asamblea' => 'Los Puertos de Payara'],
            ['texto' => '7', 'leyenda' => '7 - San José de las Majaguas', 'asamblea' => 'San José de las Majaguas'],
            ['texto' => '8', 'leyenda' => '8 - San Rafael de Onoto', 'asamblea' => 'San Rafael de Onoto'],
            ['texto' => '9', 'leyenda' => '9 - Tapa de Piedra', 'asamblea' => 'Tapa de Piedra'],
        ],
    ],

    'Sucre' => [
        'img' => 'IMG/estados/Sucre.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Carúpano', 'asamblea' => 'Carúpano'],
            ['texto' => '2', 'leyenda' => '2 - El Mango', 'asamblea' => 'El Mango'],
            ['texto' => '3', 'leyenda' => '3 - Los Altos de Sucre', 'asamblea' => 'Los Altos de Sucre'],
            ['texto' => '4', 'leyenda' => '4 - San Pedrito', 'asamblea' => 'San Pedrito'],
            ['texto' => '5', 'leyenda' => '5 - Santa Fe', 'asamblea' => 'Santa Fe'],
            ['texto' => '6', 'leyenda' => '6 - Zurita', 'asamblea' => 'Zurita'],
        ],
    ],

    'Táchira' => [
        'img' => 'IMG/estados/Táchira.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - San Antonio', 'asamblea' => 'San Antonio'],
            ['texto' => '2', 'leyenda' => '2 - San Cristobal', 'asamblea' => 'San Cristobal'],
        ],
    ],

    'Trujillo' => [
        'img' => 'IMG/estados/Trujillo.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Batatal', 'asamblea' => 'Batatal'],
            ['texto' => '2', 'leyenda' => '2 - El Cenizo', 'asamblea' => 'El Cenizo'],
            ['texto' => '3', 'leyenda' => '3 - Sabana de Mendoza', 'asamblea' => 'Sabana de Mendoza'],
            ['texto' => '4', 'leyenda' => '4 - Valera', 'asamblea' => 'Valera'],
        ],
    ],

    'Vargas' => [
        'img' => 'IMG/estados/Vargas.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Catia la Mar', 'asamblea' => 'Catia la Mar'],
        ],
    ],

    'Yaracuy' => [
        'img' => 'IMG/estados/Yaracuy.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Albarico', 'asamblea' => 'Albarico'],
            ['texto' => '2', 'leyenda' => '2 - Aroa', 'asamblea' => 'Aroa'],
            ['texto' => '3', 'leyenda' => '3 - Carabobo', 'asamblea' => 'Carabobo'],
            ['texto' => '4', 'leyenda' => '4 - Chivacoa', 'asamblea' => 'Chivacoa'],
            ['texto' => '5', 'leyenda' => '5 - El Guayabo', 'asamblea' => 'El Guayabo'],
            ['texto' => '6', 'leyenda' => '6 - Escondido', 'asamblea' => 'Escondido'],
            ['texto' => '7', 'leyenda' => '7 - La Candelaria', 'asamblea' => 'La Candelaria'],
            ['texto' => '8', 'leyenda' => '8 - La Independencia', 'asamblea' => 'La Independencia'],
            ['texto' => '9', 'leyenda' => '9 - La Ocho', 'asamblea' => 'La Ocho'],
            ['texto' => '10', 'leyenda' => '10 - La Raya', 'asamblea' => 'La Raya'],
            ['texto' => '11', 'leyenda' => '11 - Marín', 'asamblea' => 'Marín'],
            ['texto' => '12', 'leyenda' => '12 - Nirgua', 'asamblea' => 'Nirgua'],
            ['texto' => '13', 'leyenda' => '13 - Obonte', 'asamblea' => 'Obonte'],
            ['texto' => '14', 'leyenda' => '14 - Salom', 'asamblea' => 'Salom'],
            ['texto' => '15', 'leyenda' => '15 - San Felipe', 'asamblea' => 'San Felipe'],
            ['texto' => '16', 'leyenda' => '16 - San José de Carúpano', 'asamblea' => 'San José de Carúpano'],
            ['texto' => '17', 'leyenda' => '17 - San Mateo de Nirgua', 'asamblea' => 'San Mateo de Nirgua'],
            ['texto' => '18', 'leyenda' => '18 - Temerla', 'asamblea' => 'Temerla'],
            ['texto' => '19', 'leyenda' => '19 - Yaritagua', 'asamblea' => 'Yaritagua'],
        ],
    ],

    'Zulia' => [
        'img' => 'IMG/estados/Zulia.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Cabimas', 'asamblea' => 'Cabimas'],
            ['texto' => '2', 'leyenda' => '2 - Cuatricentenario', 'asamblea' => 'Cuatricentenario'],
            ['texto' => '3', 'leyenda' => '3 - El Progreso', 'asamblea' => 'El Progreso'],
            ['texto' => '4', 'leyenda' => '4 - La Repelona', 'asamblea' => 'La Repelona'],
            ['texto' => '5', 'leyenda' => '5 - La Villa del Rosario (Perijá)', 'asamblea' => 'La Villa del Rosario (Perijá)'],
            ['texto' => '6', 'leyenda' => '6 - Los Jobitos', 'asamblea' => 'Los Jobitos'],
            ['texto' => '7', 'leyenda' => '7 - San Francisco', 'asamblea' => 'San Francisco'],
            ['texto' => '8', 'leyenda' => '8 - Zipayare', 'asamblea' => 'Zipayare'],
        ]
    ],

    'Frontera Colombia' => [
        'img' => 'IMG/estados/Frontera Colombia.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - CALI – MARIANO RAMOS', 'asamblea' => 'CALI – MARIANO RAMOS'],
            ['texto' => '2', 'leyenda' => '2 - Cartagena', 'asamblea' => 'Cartagena'],
            ['texto' => '3', 'leyenda' => '3 - Caucasia Antioquia', 'asamblea' => 'Caucasia Antioquia'],
            ['texto' => '4', 'leyenda' => '4 - Macaján', 'asamblea' => 'Macaján'],
            ['texto' => '5', 'leyenda' => '5 - La Sierpe', 'asamblea' => 'La Sierpe'],
            ['texto' => '6', 'leyenda' => '6 - Manguitos', 'asamblea' => 'Manguitos'],
            ['texto' => '7', 'leyenda' => '7 - Manizales', 'asamblea' => 'Manizales'],
            ['texto' => '8', 'leyenda' => '8 - Manuela Beltrán - Barranquilla', 'asamblea' => 'Manuela Beltrán - Barranquilla'],
            ['texto' => '9', 'leyenda' => '9 - Medellín', 'asamblea' => 'Medellín'],
            ['texto' => '10', 'leyenda' => '10 - Pasto', 'asamblea' => 'Pasto'],
            ['texto' => '11', 'leyenda' => '11 - SINCELEJO – SANTA MARÍA', 'asamblea' => 'SINCELEJO – SANTA MARÍA'],
            ['texto' => '12', 'leyenda' => '12 - Tesoro', 'asamblea' => 'Tesoro'],
            ['texto' => '13', 'leyenda' => '13 - Soacha', 'asamblea' => 'Soacha'],
            ['texto' => '14', 'leyenda' => '14 - Valledupar', 'asamblea' => 'Valledupar'],
            ['texto' => '15', 'leyenda' => '15 - VILLA DEL ROSARIO – CUCUTA', 'asamblea' => 'VILLA DEL ROSARIO – CUCUTA'],
            ['texto' => '16', 'leyenda' => '16 - Villavicencio', 'asamblea' => 'Villavicencio'],
        ],
    ],


];
?>


<!DOCTYPE >
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="/Menu/iconos/icon2-8.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asambleas en <?php echo htmlspecialchars($estado); ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Estado_Dinamico.css">

    <style>
        /*==================/DISEÑO PARA CELULARES 768PX/==================*/
        /* RESPONSIVE */
        @media (max-width: 768px) {
            /*==================/MENÚ LATERAL/==================*/
            .menu-nav {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 55px;
                display: flex;
                flex-direction: row;
                justify-content: space-between; /* ← Cambiado para mover extremos */    
                align-items: center;
                padding: 0 0;
                z-index: 1001;

                border-radius: 0 0 20px 20px;
            }

            .icon-btn {
                width: auto;
                height: 80%;
            }

            .icon-btn img {
                width: 32px;
                height: 32px;
                /*filter: invert(1);/**/
            }

            .solo-pc {
                display: none !important;
            }





            /*==================/MENÚ EMERGENTE OCULTO/==================*/
            /* Panel emergente */
            .sidebar {
                left: -300px;
                width: 250px;
                height: 100%;
                background-color: #637983/*transparent/*#263C3EA6*/;
                color: #EAE4D5;
                padding: 20px;
            }

            .sidebar.active {
                left: 0px; /* aparece justo al lado del menú de íconos */
            }

            .sidebar h2 {
                font-family: 'Oleo Script', cursive;
                color: #EAE4D5;
                
                font-weight: normal;
                font-size: 26px;
            }

            .sidebar a {
                color: #EAE4D5;
                border-radius: 0px;
                color: #EAE4D5;        
                font-weight: normal;
                font-size: 16px;

                border-bottom: 1px solid #263C3E;
            }

            .sidebar a:hover {
                background-color: #2a3e42;
            }



            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 1002;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .overlay.active {
                opacity: 1;
                pointer-events: auto;
            }

            .sidebar .close-btn {    
                border-radius: 45px 15px 45px 15px;
                background-color: #A2B0BE;
                color: #192E2F;
            }

            .sidebar .close-btn:hover {
                transform: scale(1.25);
                background-color: #192E2F;
                color: #A2B0BE;
            }
        }







        /*==================/DISEÑO PARA CELULARES 280PX/==================*/
        /* RESPONSIVE */
        @media (max-width: 280px) {
            /*==================/MENÚ LATERAL/==================*/
            /*==================/MENÚ LATERAL/==================*/
            .menu-nav {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 55px;
                display: flex;
                flex-direction: row;
                justify-content: space-between; /* ← Cambiado para mover extremos */    
                align-items: center;
                padding: 0 0;
                z-index: 1001;

                border-radius: 0 10px 0 10px;
            }

            .icon-btn {
                width: auto;
                height: 80%;
            }

            .icon-btn img {
                width: 32px;
                height: 32px;
                /*filter: invert(1);/**/
            }

            .solo-pc {
                display: none !important;
            }





            /*==================/MENÚ EMERGENTE OCULTO/==================*/
            /* Panel emergente */
            .sidebar {
                left: -300px;
                width: 150px;
                height: 100%;
                background-color: #637983/*transparent/*#263C3EA6*/;
                color: #EAE4D5;
                padding: 20px;
            }

            .sidebar.active {
                left: 0px; /* aparece justo al lado del menú de íconos */
            }

            .sidebar h2 {
                font-family: 'Oleo Script', cursive;
                color: #EAE4D5;
                
                font-weight: normal;
                font-size: 26px;
            }

            .sidebar a {
                color: #EAE4D5;
                border-radius: 0px;
                color: #EAE4D5;        
                font-weight: normal;
                font-size: 16px;

                border-bottom: 1px solid #263C3E;
            }

            .sidebar a:hover {
                background-color: #2a3e42;
            }



            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 1002;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .overlay.active {
                opacity: 1;
                pointer-events: auto;
            }

            .sidebar .close-btn {    
                border-radius: 45px 15px 45px 15px;
                background-color: #A2B0BE;
                color: #192E2F;
            }

            .sidebar .close-btn:hover {
                transform: scale(1.25);
                background-color: #192E2F;
                color: #A2B0BE;
            }
        }

        

        @media only screen and (max-width: 768px) {
            /*==================/CUERPO/==================*/
            body {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;

                
                background-image: url('iconos/Fondo_Mapa_Tlf.png'); /* tu imagen */
                padding-top: 20%; /* para que el menú no tape el contenido */
            } 

            .content {
                margin-top: auto;
                padding: 20px;

                
                overflow-y: scroll; /* Permite desplazamiento vertical */
                scrollbar-width: none;     /* Firefox */
                -ms-overflow-style: none;  /* Internet Explorer 10+ */
            }

            .content::-webkit-scrollbar {
                display: none; /* Chrome, Safari y Opera */
            }

            /* ENCABEZADO */
            header {
                margin-left: 0px;
                margin-bottom: -40px;
            }

            header h1 {
                font-size: 26px;
                border-radius: 8px;
            }

            header .return-button {
                display: none !important;
            }

            /* MAPA */
            .mapa-container {
                position: relative;
                width: 95vw;
                height: auto;
                margin: 10px auto;

                
                border: 3px solid #ccc;
                border-radius: 10px;

                
                max-width: 700px;
                max-height: 700px;
            }

            .mapa-container img .mapa-ciudad {
                width: 100%;
                height: auto;/**/
                display: block;
                border-radius: 10px;



                
                max-width: 500px;
                max-height: 500px;
            }

            /* Botones interactivos sobre el mapa */
            .ciudad-btn {
                position: absolute;
                border: none;
                border-radius: 5px;
                padding: 1.5px 3px;
                font-size: 12px;
                cursor: pointer;
                transition: background 0.3s;
            }

            /* LEYENDA */
            .leyenda-container {
                width: 95vw;
                max-height: 200px;
                overflow-y: auto;
                padding: 10px;
                margin: 10px auto;
                border-radius: 8px;
            }

            .leyenda-container ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .leyenda-container li {
                margin: 6px 0;
            }

            .leyenda-container button {
                width: 100%;
                border: none;
                padding: 5px;
                border-radius: 6px;
                font-size: 14px;
                text-align: left;
            }

            /* MODAL */
            /*Modal de detalles*/
            .modal-content {
                padding: 5px;
                width: 90%;
                max-height: 96%;
                margin: auto;
                border-radius: 12px;
                position: relative;
            }

            .modal-content h3 {
                font-size: 20px;
            }

            .modal-content p, li {
                font-size: 14px;
                margin: 5px;
            }

            .modal-content ul {
                list-style-type: none; /* Esto elimina los puntos/viñetas */
                /*padding: 0;           /* Opcional: Elimina el padding predeterminado */
                /*margin: 0;            /* Opcional: Elimina el margen predeterminado */
            }

            .btn-mapa {
                display: inline-block;
                padding: 5px 8px;
                text-decoration: none;
                font-weight: bold;
                border-radius: 8px;
            }


            /* Estilo para las obras con scroll */
            .obras-scroll {
                margin-top: 2px;
                padding: 6px 10px;
            }

            /* Lista de obras */
            .obras-scroll ul {
                list-style-type: disc;
                margin: 6px 0 6px 20px;
                padding: 0;
            }

            .obras-scroll li {
                margin-bottom: 2px;
                line-height: 1.4;
                text-align: left;
            }
        }














            /*====================/TELÉFONOS/====================*/
        @media only screen and (max-width: 600px) { /* Este punto de ruptura cubre la mayoría de los teléfonos */




            header h1 {
                font-size: 26px; /* Texto más pequeño para pantallas pequeñas */
                line-height: 1.2;
                padding: 0 10px;
                text-align: center; /* Centra el título */
                margin-bottom: 10px; /* Espacio debajo del título */
            }

            header .return-button {
                display: none !important;
            }

            /* MAPA */
            .mapa-container {
                position: relative;
                width: 90vw; /* Usa 'vw' para que se adapte al ancho de la pantalla */
                max-width: 500px; /* Un límite máximo para que no se vea gigantesco en pantallas grandes */
                height: auto;
                margin: 20px auto; /* Más margen para separar de otros elementos */
            }

            .mapa-container img.mapa-ciudad {
                display: block;
                border-radius: 8px; /* Ligeramente menos redondeado para pantallas pequeñas */
            }

            .mapa-container {
                max-width: 400px;
                max-height: 400px;
            }

            /* Botones interactivos sobre el mapa */
            .ciudad-btn {
                position: absolute;
                border: none;
                border-radius: 6px; /* Ligeramente más pequeño */
                padding: 2px 5px; /* Ajusta el padding para mayor comodidad al tocar */
                font-size: 9px; /* Fuente un poco más grande para legibilidad */
                cursor: pointer;
                transition: background 0.3s, transform 0.2s; /* Añade transición para el transform */
            }

            /* Asegúrate de que los botones tengan posiciones relativas a su contenedor */
            /* Aquí es donde tendrías que definir las posiciones individuales de cada botón (ej. top, left, right, bottom)
                usando porcentajes o unidades vw/vh para que se adapten al tamaño del mapa.
                Ejemplo:
                .estado-btn.btn-anzoategui { top: 10%; left: 20%; }
            */

            .estado-btn:hover { /* Cambiado de .estado-btn button:hover a .estado-btn:hover */
                transform: scale(1.1); /* Ligeramente más pequeño el efecto para móviles */
                background-color: #7a96a4; /* Un ligero cambio de color al pasar el ratón/tocar */
            }


            /* LEYENDA */
            .leyenda-container {
                width: 90vw; /* Ajusta el ancho para móviles */
                max-width: 400px; /* Un límite máximo para que no se vea gigantesco */
                max-height: 180px; /* Ajusta la altura máxima si es necesario */
                overflow-y: auto;
                background: rgba(99, 121, 131, 0.9); /* Ligeramente más opaco para mejor contraste */
                color: #EAE4D5;
                padding: 15px; /* Más padding para mejorar el tacto */
                margin: 20px auto;
                border-radius: 8px;
            }

            .leyenda-container ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .leyenda-container li {
                margin: 8px 0; /* Más espacio entre elementos de la lista */
            }

            .leyenda-container button {
                width: 100%;
                background-color: #A2B0BE;
                color: #2a3e42;
                border: none;
                padding: 12px; /* Más padding para ser más fácil de tocar */
                border-radius: 6px;
                font-size: 15px; /* Un poco más grande para legibilidad */
                text-align: left;
                box-sizing: border-box; /* Asegura que el padding no añada ancho extra */
            }
        }

        @media only screen and (max-width: 320px) {
            /*==================/CUERPO/==================*/
            body {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;

                
                background-image: url('iconos/Fondo_Mapa_Tlf.png'); /* tu imagen */
                padding-top: 20%; /* para que el menú no tape el contenido */
            } 

            .content {
                margin-top: auto;
                padding: 10px;
                overflow-y: scroll; /* Permite desplazamiento vertical */
                scrollbar-width: none;     /* Firefox */
                -ms-overflow-style: none;  /* Internet Explorer 10+ */
            }

            .content::-webkit-scrollbar {
                display: none; /* Chrome, Safari y Opera */
            }

            /* ENCABEZADO */
            header {
                flex-direction: column;
                padding: 10px;
                border-radius: 0;
            }

            header h1 {
                font-size: 22px;
                line-height: 1.3;
                padding: 0 10px;
            }

            header .return-button {
                display: none !important;
            }

            /* MAPA */
            .mapa-container {
                position: relative;
                width: 95vw;
                height: auto;
                margin: 10px auto;
            }

            .mapa-container img.mapa-ciudad {
                width: 100%;
                height: auto;
                display: block;
                border-radius: 10px;
            }

            .mapa-container {
                max-width: 280px;
                max-height: 280px;
            }

            .mapa-container img {
                max-width: 280px;
                max-height: 280px;
            }

            /* Botones interactivos sobre el mapa */
            .ciudad-btn {
                transform: translate(-50%, -50%);
                font-size: 8px;
                padding: 1px 3px;
            }
            /*
            .ciudad-btn button:hover {
                transform: scale(1.2);
            }/**/

            /* LEYENDA */
            .leyenda-container {
                width: 95vw;
                max-height: 200px;
                overflow-y: auto;
                padding: 10px;
                margin: 10px auto;
                border-radius: 8px;
            }

            .leyenda-container ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .leyenda-container li {
                margin: 6px 0;
            }

            .leyenda-container button {
                width: 100%;
                background-color: #A2B0BE;
                color: #2a3e42;
                border: none;
                padding: 10px;
                border-radius: 6px;
                font-size: 14px;
                text-align: left;
            }

            /* LEYENDA */
        }


        

        @media only screen and (max-width: 768px) {
            body {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column; /* NUEVO: asegura que el contenido fluya verticalmente */
                justify-content: flex-start; /* CAMBIO: alinea al tope */
                align-items: center;
                position: relative;
                
                background-image: url('iconos/Fondo_Mapa_Tlf.png');
                /* padding-top: 20%; */ /* ❌ Elimínalo o comenta esta línea */
            }

            .content {
                margin-top: 0; /* ✅ asegura que empiece desde el tope */
                padding: 20px;
                width: 100%;
                max-width: 100%;
                overflow-y: auto;
            }

            .content {
                margin-top: 60px; /* Ajusta según la altura de tu header */
            }

        }

        @media only screen and (max-width: 320px) {
            body {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column; /* NUEVO: asegura que el contenido fluya verticalmente */
                justify-content: flex-start; /* CAMBIO: alinea al tope */
                align-items: center;
                position: relative;
                
                background-image: url('iconos/Fondo_Mapa_Tlf.png');
                /* padding-top: 20%; */ /* ❌ Elimínalo o comenta esta línea */
            }

            .content {
                margin-top: 40px; /* ✅ asegura que empiece desde el tope */
                padding: 20px;
                width: 100%;
                max-width: 100%;
                overflow-y: auto;
            }

            .content header h1 {
                font-size: 30px;
                text-align: center;
            }

            header h1 {
                font-size: 30px;
                text-align: center;
            }
        }



        @media (max-width: 768px) {
            .mapa-contenedor-flex {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }

            .mapa-container {
                position: relative;
                width: 90%;
                max-width: 90vw;
                overflow: hidden;
            }

            .mapa-container img {
                width: 100%;
                height: auto;
                display: block;
                border-radius: 8px;
            }

            .ciudad-btn {
                transform: translate(-50%, -50%);
                font-size: 10px;
                padding: 1px 3px;
            }

            .leyenda-interactiva {
                width: 100%;
                margin-top: 20px;
                padding: 10px;
                box-sizing: border-box;
            }

            .leyenda-interactiva ul {
                padding-left: 0;
                list-style: none;
            }

            .leyenda-interactiva ul li {
                margin-bottom: 10px;
            }

            .leyenda-interactiva button {
                width: 100%;
                font-size: 14px;
                padding: 8px;
            }

            header h1 {
                font-size: 30px;
                text-align: center;
            }

            .return-button {
                position: absolute;
                left: 10px;
                top: 10px;
                z-index: 10;
            }
        }

        @media (max-width: 300px) {
            .mapa-contenedor-flex {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }

            .mapa-container {
                position: relative;
                width: 90vw;
                height: 90vw; /* 💡 mismo valor que el ancho */
                max-width: 300px;
                max-height: 300px;
                overflow: hidden;
                border-radius: 8px;
            }

            .mapa-container img {
                width: 100%;
                height: auto;
                object-fit: contain; /* o usa 'cover' si prefieres recorte */
                display: block;
                border-radius: 8px;
            }

            .ciudad-btn {
                transform: translate(-50%, -50%);
                font-size: 10px;
                padding: 1px 3px;
            }

            .leyenda-interactiva {
                width: 100%;
                margin-top: 20px;
                padding: 10px;
                box-sizing: border-box;
            }

            .leyenda-interactiva ul {
                padding-left: 0;
                list-style: none;
            }

            .leyenda-interactiva ul li {
                margin-bottom: 10px;
            }

            .leyenda-interactiva button {
                width: 100%;
                font-size: 14px;
                padding: 8px;
            }

            header h1 {
                font-size: 30px;
                text-align: center;
            }

            .return-button {
                position: absolute;
                left: 10px;
                top: 10px;
                z-index: 10;
            }
        }
    </style>





    <style>
        /* POSICIONES (ajústalas manualmente según tu mapa por estado) */
        /*--Zulia--*/
        .Zulia-1 { top: 42%; left: 60%; }
        .Zulia-2 { top: 34.5%; left: 55%; }
        .Zulia-3 { top: 35.5%; left: 57%; }
        .Zulia-4 { top: 29%; left: 54%; }
        .Zulia-5 { top: 47.5%; left: 44.5%; }
        .Zulia-6 { top: 33%; left: 59.5%; }
        .Zulia-7 { top: 38%; left: 55.5%; }
        .Zulia-8 { top: 48%; left: 67%; }

        @media only screen and (max-width: 768px) {
        .Zulia-1 { top: 42%; left: 65%; }
        .Zulia-2 { top: 34.5%; left: 56.5%; }
        .Zulia-3 { top: 35.5%; left: 60%; }
        .Zulia-4 { top: 29%; left: 56%; }
        .Zulia-5 { top: 47.5%; left: 42%; }
        .Zulia-6 { top: 33%; left: 64%; }
        .Zulia-7 { top: 38%; left: 57.5%; }
        .Zulia-8 { top: 48%; left: 75%; }
        }


        /*--Lara--*/
        .Lara-1  { top: 48%; left: 70%; }
        .Lara-2  { top: 51%; left: 66%; }
        .Lara-3  { top: 45%; left: 40%; }
        .Lara-4  { top: 8%;  left: 58%; }
        .Lara-5  { top: 34%; left: 78%; }
        .Lara-6  { top: 50%; left: 68%; }
        .Lara-7  { top: 10%; left: 62%; }
        .Lara-8  { top: 66%; left: 62%; }
        .Lara-9  { top: 57%; left: 49%; }
        .Lara-10 { top: 14%; left: 72%; }
        .Lara-11 { top: 44%; left: 69%; }

        @media only screen and (max-width: 768px) {
        .Lara-1  { top: 48%; left: 74%; }
        .Lara-2  { top: 51%; left: 70%; }
        .Lara-3  { top: 45%; left: 40%; }
        .Lara-4  { top: 8%;  left: 60%; }
        .Lara-5  { top: 34%; left: 84%; }
        .Lara-6  { top: 50%; left: 72%; }
        .Lara-7  { top: 10%; left: 66%; }
        .Lara-8  { top: 66%; left: 64%; }
        .Lara-9  { top: 57%; left: 49%; }
        .Lara-10 { top: 14%; left: 76%; }
        .Lara-11 { top: 44%; left: 73%; }
        }


        /*--Amazonas--*/
        .Amazonas-1 { top: 13%; left: 29.5%; }

        @media only screen and (max-width: 768px) {
        .Amazonas-1 { top: 14%; left: 20%; }
        }


        /*--Anzoátegui--*/
        .Anzoategui-1 { top: 49%; left: 49%; }
        .Anzoategui-2 { top: 7.5%; left: 38.5%; }
        .Anzoategui-3 { top: 74%; left: 70%; }

        @media only screen and (max-width: 768px) {
        .Anzoategui-1 { top: 49%; left: 49%; }
        .Anzoategui-2 { top: 7.5%; left: 36%; }
        .Anzoategui-3 { top: 75%; left: 74%; }
        }


        /*--Apure--*/
        .Apure-1 { top: 19%; left: 74.5%; }
        .Apure-2 { top: 25%; left: 75.5%; }
        .Apure-3 { top: 38%; left: 51%; }
        .Apure-4 { top: 33%; left: 34%; }
        .Apure-5 { top: 13%; left: 73.5%; }

        @media only screen and (max-width: 768px) {
        .Apure-1 { top: 25%; left: 80%; }
        .Apure-2 { top: 33%; left: 81%; }
        .Apure-3 { top: 54%; left: 50%; }
        .Apure-4 { top: 44%; left: 30%; }
        .Apure-5 { top: 17%; left: 79%; }
        }


        /*--Aragua--*/
        .Aragua-1  { top: 58%; left: 66%; }
        .Aragua-2  { top: 26%; left: 32%; }
        .Aragua-3  { top: 20%; left: 30%; }
        .Aragua-4  { top: 22%; left: 32%; }
        .Aragua-5  { top: 31%; left: 32%; }
        .Aragua-6  { top: 31%; left: 34%; }
        .Aragua-7  { top: 21%; left: 39%; }
        .Aragua-8  { top: 44%; left: 59%; }
        .Aragua-9  { top: 25%; left: 39%; }
        .Aragua-10 { top: 27%; left: 34%; }
        .Aragua-11 { top: 29%; left: 40%; }

        @media only screen and (max-width: 768px) {
        .Aragua-1  { top: 58%; left: 72%; }
        .Aragua-2  { top: 26%; left: 22%; }
        .Aragua-3  { top: 20%; left: 20%; }
        .Aragua-4  { top: 22%; left: 22%; }
        .Aragua-5  { top: 31%; left: 22%; }
        .Aragua-6  { top: 31%; left: 25%; }
        .Aragua-7  { top: 21%; left: 35%; }
        .Aragua-8  { top: 44%; left: 64%; }
        .Aragua-9  { top: 25%; left: 34%; }
        .Aragua-10 { top: 27%; left: 25%; }
        .Aragua-11 { top: 29%; left: 36%; }
        }


        /*--Barinas--*/
        .Barinas-1 { top: 21%; left: 43%; }
        .Barinas-2 { top: 30%; left: 42%; }
        .Barinas-3 { top: 20%; left: 36%; }
        .Barinas-4 { top: 34%; left: 40%; }
        .Barinas-5 { top: 28%; left: 44%; }

        @media only screen and (max-width: 768px) {
        .Barinas-1 { top: 21%; left: 41%; }
        .Barinas-2 { top: 32%; left: 40%; }
        .Barinas-3 { top: 20%; left: 34%; }
        .Barinas-4 { top: 36%; left: 38%; }
        .Barinas-5 { top: 30%; left: 42%; }
        }


        /*--Bolivar--*/
        .Bolivar-1 { top: 17%; left: 53%; }
        .Bolivar-2 { top: 12%; left: 53%; }
        .Bolivar-3 { top: 20%; left: 30%; }
        .Bolivar-4 { top: 6%;  left: 62%; }
        .Bolivar-5 { top: 8%;  left: 60%; }
        .Bolivar-6 { top: 78%; left: 74%; }
        .Bolivar-7 { top: 22%; left: 78%; }
        .Bolivar-8 { top: 10%; left: 67%; }

        @media only screen and (max-width: 768px) {
        .Bolivar-1 { top: 18%; left: 53%; }
        .Bolivar-2 { top: 12%; left: 53%; }
        .Bolivar-3 { top: 20%; left: 25%; }
        .Bolivar-4 { top: 6%;  left: 65%; }
        .Bolivar-5 { top: 8%;  left: 62%; }
        .Bolivar-6 { top: 78%; left: 80%; }
        .Bolivar-7 { top: 22%; left: 84%; }
        .Bolivar-8 { top: 10%; left: 70%; }
        }


        /*--Carabobo--*/
        .Carabobo-1  { top: 14%; left: 26%; }
        .Carabobo-2  { top: 56%; left: 28%; }
        .Carabobo-3  { top: 64%; left: 56%; }
        .Carabobo-4  { top: 74%; left: 34%; }
        .Carabobo-5  { top: 37%; left: 25%; }
        .Carabobo-6  { top: 54%; left: 33%; }
        .Carabobo-7  { top: 48%; left: 56%; }
        .Carabobo-8  { top: 68%; left: 64%; }
        .Carabobo-9  { top: 80%; left: 66%; }
        .Carabobo-10 { top: 45%; left: 58%; }
        .Carabobo-11 { top: 20%; left: 22%; }
        .Carabobo-12 { top: 68%; left: 36%; }
        .Carabobo-13 { top: 34%; left: 22%; }
        .Carabobo-14 { top: 36%; left: 37%; }
        .Carabobo-15 { top: 58%; left: 32%; }
        .Carabobo-16 { top: 39%; left: 71%; }
        .Carabobo-17 { top: 60%; left: 50%; }
        .Carabobo-18 { top: 42%; left: 63%; }
        .Carabobo-19 { top: 14%; left: 20%; }
        .Carabobo-20 { top: 58%; left: 38%; }


        .Carabobo-21 { top: 14%; left: 32%; }
        .Carabobo-22 { top: 18%; left: 48%; }
        .Carabobo-23 { top: 50%; left: 46%; }

        @media only screen and (max-width: 768px) {
            .Carabobo-1  { top: 14%; left: 20%; }
            .Carabobo-2  { top: 56%; left: 23%; }
            .Carabobo-3  { top: 64%; left: 58%; }
            .Carabobo-4  { top: 74%; left: 30%; }
            .Carabobo-5  { top: 37%; left: 20%; }
            .Carabobo-6  { top: 54%; left: 29%; }
            .Carabobo-7  { top: 48%; left: 58%; }
            .Carabobo-8  { top: 68%; left: 68%; }
            .Carabobo-9  { top: 80%; left: 70%; }
            .Carabobo-10 { top: 45%; left: 60%; }
            .Carabobo-11 { top: 20%; left: 16%; }
            .Carabobo-12 { top: 68%; left: 32%; }
            .Carabobo-13 { top: 34%; left: 16%; }
            .Carabobo-14 { top: 36%; left: 34%; }
            .Carabobo-15 { top: 58%; left: 27%; }
            .Carabobo-16 { top: 39%; left: 76%; }
            .Carabobo-17 { top: 60%; left: 50%; }
            .Carabobo-18 { top: 42%; left: 66%; }
            .Carabobo-19 { top: 14%; left: 14%; }
            .Carabobo-20 { top: 58%; left: 34%; }


            .Carabobo-21 { top: 14%; left: 26%; }
            .Carabobo-22 { top: 18%; left: 48%; }
            .Carabobo-23 { top: 50%; left: 45%; }
        }


        /*--Cojedes--*/
        .Cojedes-1  { top: 24%; left: 32.5%; }
        .Cojedes-2  { top: 32%; left: 44%; }
        .Cojedes-3  { top: 24%; left: 44%; }
        .Cojedes-4  { top: 16%; left: 58%; }
        .Cojedes-5  { top: 43%; left: 37.5%; }
        .Cojedes-6  { top: 34%; left: 35%; }
        .Cojedes-7  { top: 46.5%; left: 37%; }
        .Cojedes-8  { top: 37%; left: 32%; }
        .Cojedes-9  { top: 37%; left: 42%; }
        .Cojedes-10 { top: 27%; left: 42%; }
        .Cojedes-11 { top: 18%; left: 48%; }
        .Cojedes-12 { top: 30%; left: 30.5%; }
        .Cojedes-13 { top: 30%; left: 42%; }
        .Cojedes-14 { top: 28%; left: 49%; }
        .Cojedes-15 { top: 10%; left: 53%; }

        @media only screen and (max-width: 768px) {
        .Cojedes-1  { top: 24%; left: 23%; }
        .Cojedes-2  { top: 32%; left: 41%; }
        .Cojedes-3  { top: 24%; left: 41%; }
        .Cojedes-4  { top: 16%; left: 61%; }
        .Cojedes-5  { top: 43%; left: 31.5%; }
        .Cojedes-6  { top: 34%; left: 27%; }
        .Cojedes-7  { top: 46.5%; left: 30.5%; }
        .Cojedes-8  { top: 37%; left: 24%; }
        .Cojedes-9  { top: 37%; left: 38%; }
        .Cojedes-10 { top: 27%; left: 39%; }
        .Cojedes-11 { top: 18%; left: 47%; }
        .Cojedes-12 { top: 30%; left: 21%; }
        .Cojedes-13 { top: 30%; left: 39%; }
        .Cojedes-14 { top: 28%; left: 48%; }
        .Cojedes-15 { top: 10%; left: 54%; }
        }


        /*--Delta Amacuro--*/
        .Delta_Amacuro-1 { top: 43%; left: 30%; }

        @media only screen and (max-width: 768px) {
            .Delta_Amacuro-1 { top: 43%; left: 25%; }
        }


        /*--Falcon--*/
        .Falcon-1  { top: 16%; left: 45.5%; }
        .Falcon-2  { top: 66%; left: 83%; }
        .Falcon-3  { top: 70%; left: 54%; }
        .Falcon-4  { top: 43%; left: 48%; }
        .Falcon-5  { top: 60%; left: 28%; }
        .Falcon-6  { top: 57%; left: 80%; }
        .Falcon-7  { top: 56%; left: 73%; }
        .Falcon-8  { top: 57%; left: 75%; }
        .Falcon-9  { top: 52%; left: 74%; }
        .Falcon-10 { top: 31%; left: 51%; }
        .Falcon-11 { top: 40%; left: 60%; }
        .Falcon-12 { top: 29%; left: 40%; }
        .Falcon-13 { top: 70%; left: 63%; }
        .Falcon-14 { top: 24%; left: 41%; }
        .Falcon-15 { top: 38%; left: 64%; }
        .Falcon-16 { top: 73%; left: 83%; }
        .Falcon-17 { top: 62%; left: 79%; }

        @media only screen and (max-width: 768px) {
        .Falcon-1  { top: 16%; left: 44%; }
        .Falcon-2  { top: 66%; left: 89%; }
        .Falcon-3  { top: 70%; left: 54%; }
        .Falcon-4  { top: 43%; left: 48%; }
        .Falcon-5  { top: 60%; left: 23%; }
        .Falcon-6  { top: 57%; left: 86%; }
        .Falcon-7  { top: 56%; left: 78%; }
        .Falcon-8  { top: 57%; left: 80%; }
        .Falcon-9  { top: 52%; left: 79%; }
        .Falcon-10 { top: 31%; left: 51%; }
        .Falcon-11 { top: 40%; left: 62%; }
        .Falcon-12 { top: 29%; left: 37%; }
        .Falcon-13 { top: 70%; left: 66%; }
        .Falcon-14 { top: 24%; left: 38%; }
        .Falcon-15 { top: 38%; left: 67%; }
        .Falcon-16 { top: 73%; left: 89%; }
        .Falcon-17 { top: 62%; left: 85%; }
        }


        /*--Frontera Colombia--*/
        .Frontera_Colombia-1 { top: 22%; left: 38%; }
        .Frontera_Colombia-2 { top: 24%; left: 42%; }
        .Frontera_Colombia-3 { top: 26%; left: 35%; }
        .Frontera_Colombia-4 { top: 28%; left: 46%; }
        .Frontera_Colombia-5 { top: 30%; left: 40%; }
        .Frontera_Colombia-6 { top: 32%; left: 50%; }
        .Frontera_Colombia-7 { top: 34%; left: 44%; }
        .Frontera_Colombia-8 { top: 36%; left: 48%; }
        .Frontera_Colombia-9 { top: 38%; left: 39%; }
        .Frontera_Colombia-10 { top: 40%; left: 43%; }
        .Frontera_Colombia-11 { top: 42%; left: 45%; }
        .Frontera_Colombia-12 { top: 44%; left: 37%; }
        .Frontera_Colombia-13 { top: 46%; left: 49%; }
        .Frontera_Colombia-14 { top: 48%; left: 41%; }
        .Frontera_Colombia-15 { top: 50%; left: 47%; }
        .Frontera_Colombia-16 { top: 52%; left: 43%; }


        @media only screen and (max-width: 768px) {
        .Frontera_Colombia-1 { top: 56%; left: 30%; }
        .Frontera_Colombia-2 { top: 12%; left: 37%; }
        .Frontera_Colombia-3 { top: 30%; left: 35%; }
        .Frontera_Colombia-4 { top: 20%; left: 34%; }
        .Frontera_Colombia-5 { top: 26%; left: 42%; }
        .Frontera_Colombia-6 { top: 28%; left: 32%; }
        .Frontera_Colombia-7 { top: 44%; left: 31%; }
        .Frontera_Colombia-8 { top: 10%; left: 41%; }
        .Frontera_Colombia-9 { top: 40%; left: 31%; }
        .Frontera_Colombia-10 { top: 70%; left: 27%; }
        .Frontera_Colombia-11 { top: 22%; left: 37%; }
        .Frontera_Colombia-12 { top: 31.5%; left: 31%; }
        .Frontera_Colombia-13 { top: 46%; left: 42%; }
        .Frontera_Colombia-14 { top: 12%; left: 48%; }
        .Frontera_Colombia-15 { top: 30%; left: 50%; }
        .Frontera_Colombia-16 { top: 20%; left: 47%; }
        }

        /*--Guarico--*/
        .Guarico-1 { top: 12%; left: 52%; }
        .Guarico-2 { top: 8%;  left: 28%; }
        .Guarico-3 { top: 24%; left: 30%; }
        .Guarico-4 { top: 38%; left: 58%; }
        .Guarico-5 { top: 30%; left: 72%; }

        @media only screen and (max-width: 768px) {
        .Guarico-1 { top: 12%; left: 50%; }
        .Guarico-2 { top: 8%;  left: 22%; }
        .Guarico-3 { top: 24%; left: 26%; }
        .Guarico-4 { top: 38%; left: 60%; }
        .Guarico-5 { top: 30%; left: 76%; }
        }


        /*--Merida--*/
        .Merida-1 { top: 36%; left: 34%; }
        .Merida-2 { top: 40%; left: 53%; }
        .Merida-3 { top: 33%; left: 49%; }
        .Merida-4 { top: 36%; left: 53%; }

        @media only screen and (max-width: 768px) {
        .Merida-1 { top: 36%; left: 27%; }
        .Merida-2 { top: 40%; left: 54%; }
        .Merida-3 { top: 33%; left: 49%; }
        .Merida-4 { top: 36%; left: 54%; }
        }


        /*--Miranda--*/
        .Miranda-1  { top: 30%; left: 54%; }
        .Miranda-2  { top: 46%; left: 28%; }
        .Miranda-3  { top: 18%; left: 37%; }
        .Miranda-4  { top: 18%; left: 39%; }
        .Miranda-5  { top: 54%; left: 30%; }
        .Miranda-6  { top: 32%; left: 22%; }
        .Miranda-7  { top: 58%; left: 33%; }
        .Miranda-8  { top: 32%; left: 37%; }
        .Miranda-9  { top: 20%; left: 29%; }
        .Miranda-10 { top: 20%; left: 22%; }

        @media only screen and (max-width: 768px) {
        .Miranda-1  { top: 40%; left: 54%; }
        .Miranda-2  { top: 60%; left: 24%; }
        .Miranda-3  { top: 22%; left: 34%; }
        .Miranda-4  { top: 22%; left: 37%; }
        .Miranda-5  { top: 68%; left: 26%; }
        .Miranda-6  { top: 40%; left: 16%; }
        .Miranda-7  { top: 72%; left: 29%; }
        .Miranda-8  { top: 42%; left: 35%; }
        .Miranda-9  { top: 26%; left: 25%; }
        .Miranda-10 { top: 24%; left: 16%; }
        }


        /*--Monagas--*/
        .Monagas-1 { top: 28%; left: 49%; }

        @media only screen and (max-width: 768px) {
        .Monagas-1 { top: 28%; left: 49%; }
        }


        /*--Nueva Esparta--*/
        .Nueva_Esparta-1 { top: 48%; left: 75.5%; }

        @media only screen and (max-width: 768px) {
        .Nueva_Esparta-1 { top: 48%; left: 81%; }
        }


        /*--Portuguesa--*/
        .Portuguesa-1  { top: 20%; left: 57%; }
        .Portuguesa-2  { top: 30%; left: 29%; }
        .Portuguesa-3  { top: 65%; left: 51%; }
        .Portuguesa-4  { top: 50%; left: 30%; }
        .Portuguesa-5  { top: 66%; left: 56%; }
        .Portuguesa-6  { top: 22%; left: 60%; }
        .Portuguesa-7  { top: 25%; left: 65%; }
        .Portuguesa-8  { top: 12%; left: 61%; }
        .Portuguesa-9  { top: 16%; left: 58%; }

        @media only screen and (max-width: 768px) {
        .Portuguesa-1  { top: 20%; left: 60%; }
        .Portuguesa-2  { top: 30%; left: 20%; }
        .Portuguesa-3  { top: 65%; left: 51%; }
        .Portuguesa-4  { top: 50%; left: 21%; }
        .Portuguesa-5  { top: 66%; left: 58%; }
        .Portuguesa-6  { top: 22%; left: 64%; }
        .Portuguesa-7  { top: 25%; left: 72%; }
        .Portuguesa-8  { top: 12%; left: 66%; }
        .Portuguesa-9  { top: 16%; left: 61%; }
        }


        /*--Sucre--*/
        .Sucre-1 { top: 12%; left: 46%; }
        .Sucre-2 { top: 12%; left: 80%; }
        .Sucre-3 { top: 46%; left: 18%; }
        .Sucre-4 { top: 39%; left: 21%; }
        .Sucre-5 { top: 32%; left: 18%; }
        .Sucre-6 { top: 41%; left: 19%; }

        @media only screen and (max-width: 768px) {
        .Sucre-1 { top: 18%; left: 46%; }
        .Sucre-2 { top: 24%; left: 84%; }
        .Sucre-3 { top: 80%; left: 6%; }
        .Sucre-4 { top: 70%; left: 10%; }
        .Sucre-5 { top: 60%; left: 6%; }
        .Sucre-6 { top: 70%; left: 7%; }
        }


        /*--Tachira--*/
        .Tachira-1 { top: 69%; left: 26%; }
        .Tachira-2 { top: 70%; left: 36%; }

        @media only screen and (max-width: 768px) {
        .Tachira-1 { top: 69%; left: 15%; }
        .Tachira-2 { top: 70%; left: 30%; }
        }


        /*--Trujillo--*/
        .Trujillo-1 { top: 68%; left: 72%; }
        .Trujillo-2 { top: 46%; left: 41%; }
        .Trujillo-3 { top: 54%; left: 45%; }
        .Trujillo-4 { top: 66%; left: 46%; }

        @media only screen and (max-width: 768px) {
        .Trujillo-1 { top: 68%; left: 82%; }
        .Trujillo-2 { top: 46%; left: 36%; }
        .Trujillo-3 { top: 54%; left: 42%; }
        .Trujillo-4 { top: 66%; left: 44%; }
        }

        /*--Vargas--*/
        .Vargas-1 { top: 18%; left: 39.5%; }

        @media only screen and (max-width: 768px) {
        .Vargas-1 { top: 18%; left: 37.5%; }
        }


        /*--Yaracuy--*/
        .Yaracuy-1  { top: 34%; left: 56%; }
        .Yaracuy-2  { top: 26%; left: 42%; }
        .Yaracuy-3  { top: 17%; left: 51%; }
        .Yaracuy-4  { top: 63%; left: 44%; }
        .Yaracuy-5  { top: 30%; left: 68%; }
        .Yaracuy-6  { top: 39%; left: 71%; }
        .Yaracuy-7  { top: 50%; left: 71%; }
        .Yaracuy-8  { top: 46%; left: 46%; }
        .Yaracuy-9  { top: 62%; left: 40%; }
        .Yaracuy-10 { top: 32%; left: 71%; }
        .Yaracuy-11 { top: 36%; left: 54%; }
        .Yaracuy-12 { top: 70%; left: 63%; }
        .Yaracuy-13 { top: 49%; left: 47%; }
        .Yaracuy-14 { top: 62%; left: 67%; }
        .Yaracuy-15 { top: 38%; left: 52%; }
        .Yaracuy-16 { top: 40%; left: 50%; }
        .Yaracuy-17 { top: 76%; left: 60%; }
        .Yaracuy-18 { top: 49%; left: 68.5%; }
        .Yaracuy-19 { top: 69%; left: 22%; }

        @media only screen and (max-width: 768px) {
        .Yaracuy-1  { top: 34%; left: 57%; }
        .Yaracuy-2  { top: 26%; left: 40%; }
        .Yaracuy-3  { top: 17%; left: 51%; }
        .Yaracuy-4  { top: 63%; left: 42%; }
        .Yaracuy-5  { top: 30%; left: 72%; }
        .Yaracuy-6  { top: 39%; left: 79%; }
        .Yaracuy-7  { top: 50%; left: 79%; }
        .Yaracuy-8  { top: 46%; left: 44%; }
        .Yaracuy-9  { top: 62%; left: 36%; }
        .Yaracuy-10 { top: 32%; left: 78%; }
        .Yaracuy-11 { top: 36%; left: 54%; }
        .Yaracuy-12 { top: 70%; left: 68%; }
        .Yaracuy-13 { top: 49%; left: 45%; }
        .Yaracuy-14 { top: 62%; left: 73%; }
        .Yaracuy-15 { top: 39%; left: 51%; }
        .Yaracuy-16 { top: 42%; left: 49%; }
        .Yaracuy-17 { top: 76%; left: 64%; }
        .Yaracuy-18 { top: 49%; left: 75%; }
        .Yaracuy-19 { top: 69%; left: 12%; }
        }


        /*--Distrito Capital--*/
        .Distrito_Capital-1 { top: 40%; left: 56%; }
        .Distrito_Capital-2 { top: 44%; left: 58%; }
        .Distrito_Capital-3 { top: 60%; left: 38%; }
        .Distrito_Capital-4 { top: 48%; left: 48%; }
        .Distrito_Capital-5 { top: 28%; left: 50%; }

        /*.ciudad-Petare { top: 48%; left: 56%; }/**/

        @media only screen and (max-width: 768px) {
        .Distrito_Capital-1 { top: 40%; left: 56%; }
        .Distrito_Capital-2 { top: 44%; left: 59%; }
        .Distrito_Capital-3 { top: 60%; left: 36%; }
        .Distrito_Capital-4 { top: 48%; left: 48%; }
        .Distrito_Capital-5 { top: 28%; left: 50%; }

        /*.ciudad-Petare { top: 48%; left: 56%; }/**/
        }
    </style>

</head>
<body>

    <!-- Íconos visibles solo en escritorio -->
    <!-- CONTENEDOR DEL MENÚ -->
    <nav class="menu-nav">
        <button class="icon-btn btn-menu" onclick="toggleSidebarMenu()" title="Menú">
            <img src="iconos/Menu.png" alt="Menú">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://pwagacandsv.rf.gd/Menu/index.php'" title="Inicio">
            <img src="iconos/Inicio.png" alt="Inicio">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://pwagacandsv.rf.gd/Menu/Submenu.php'" title="Ubicación">
            <img src="iconos/ubicaciones.png" alt="Ubicación">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://pwagacandsv.rf.gd/Menu/Eventos/index.php'" title="Eventos">
            <img src="iconos/eventos.png" alt="Eventos">
        </button>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <button class="icon-btn solo-pc" onclick="location.href='https://pwagacandsv.rf.gd/Menu/Donaciones/index.php'" title="Donaciones">
                <img src="iconos/donation.png" alt="Donaciones">
            </button>
        <?php endif; ?>
        <button class="icon-btn solo-pc" onclick="location.href='https://pwagacandsv.rf.gd/Menu/Material/index.php'" title="Material Literario">
            <img src="iconos/material.png" alt="Material Literario">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://pwagacandsv.rf.gd/Menu/LiteraturaBiblica/index.php'" title="Biblia">
            <img src="iconos/Biblia.png" alt="Estudio Bíblico">
        </button>
        <button class="icon-btn btn-sesion" onclick="location.href='https://pwagacandsv.rf.gd/Menu/logout.php'" title="Cerrar Sesión">
            <img src="iconos/Sesion.png" alt="Cerrar Sesión">
        </button>
    </nav>


    <!-- Menú emergente (sidebar) para celular -->
    <div class="sidebar mobile-only" id="sidebarMenu">
        <h2>Menú</h2>
        <a href="https://pwagacandsv.rf.gd/Menu/index.php">Inicio</a>
        <a href="https://pwagacandsv.rf.gd/Menu/Submenu.php">Ubicación</a>
        <a href="https://pwagacandsv.rf.gd/Menu/Eventos/index.php">Eventos</a>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <a href="https://pwagacandsv.rf.gd/Menu/Donaciones/index.php">Donaciones</a>
        <?php endif; ?>
        <a href="https://pwagacandsv.rf.gd/Menu/Material/index.php">Material Literario</a>
        <a href="https://pwagacandsv.rf.gd/Menu/LiteraturaBiblica/index.php">Estudio Bíblico</a>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <a href="https://pwagacandsv.rf.gd/Cuenta/index.php">Gestionar Sesión</a>
        <?php endif; ?>
        

        <a href="https://pwagacandsv.rf.gd/Menu/Copiryt.php">Acerca de</a>

        <button class="close-btn" onclick="toggleSidebarMenu()">Cerrar</button>
    </div>

    <!-- Fondo oscuro -->
    <div class="overlay" id="overlay" onclick="toggleSidebarMenu()"></div>

    <script>
        function toggleSidebarMenu() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    </script>

    <div class="content">
        <header>
            <button onclick="history.back()" class="return-button" title="Volver"></button>
            <h1>Asambleas en <?php echo htmlspecialchars($estado); ?></h1>
        </header>



        <?php if (isset($mapa_estados[$estado])): ?>
            <div class="mapa-contenedor-flex">
                <!-- Contenedor del mapa -->
                <div class="mapa-container">
                    <img src="<?php echo htmlspecialchars($mapa_estados[$estado]['img']); ?>" alt="Mapa de <?php echo htmlspecialchars($estado); ?>">

                    <?php foreach ($mapa_estados[$estado]['botones'] as $index => $btn): ?>
                        <?php
                            $texto = isset($btn['texto']) ? htmlspecialchars($btn['texto']) : '?';
                            $leyenda = isset($btn['leyenda']) ? htmlspecialchars($btn['leyenda']) : '';
                            $asamblea = isset($btn['asamblea']) ? htmlspecialchars($btn['asamblea']) : '';
                            $tipo = isset($btn['tipo']) ? $btn['tipo'] : '';

                            $class_estado = str_replace(
                                [' ', 'á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ'], 
                                ['_', 'a','e','i','o','u','A','E','I','O','U','n','N'], 
                                $estado
                            );
                            $clase_css = $class_estado . '-' . ($index + 1);

                            // URL personalizada para Distrito Capital
                            if ($asamblea === 'Distrito Capital') {
                                $url = 'Estados.php?estado=Distrito Capital';
                            } else {
                                $url = 'ciudad.php?estado=' . urlencode($estado) . '&ciudad=' . urlencode($asamblea);
                            }
                        ?>

                        <?php if ($tipo === 'redirigir' || $asamblea === 'Distrito Capital'): ?>
                            <button 
                                class="ciudad-btn ciudad-<?php echo str_replace([' ', ',', 'ñ', 'Ñ'], ['','', 'n', 'N'], $asamblea); ?> <?php echo $clase_css; ?>" 
                                onclick="window.location.href='<?php echo $url; ?>'" 
                                title="<?php echo $leyenda; ?>">
                                <?php echo $texto; ?>
                            </button>
                        <?php else: ?>
                            <button 
                                class="ciudad-btn <?php echo $clase_css; ?>"
                                onclick="mostrarDetallesPorAsamblea('<?php echo $asamblea; ?>')" 
                                title="<?php echo $leyenda; ?>">
                                <?php echo $texto; ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Leyenda interactiva -->
                <div class="leyenda-interactiva">
                    <h3>Leyenda de <?php echo htmlspecialchars($estado); ?></h3>
                    <ul>
                        <?php foreach ($mapa_estados[$estado]['botones'] as $btn): ?>
                            <?php
                                $leyenda = isset($btn['leyenda']) ? htmlspecialchars($btn['leyenda']) : '';
                                $asamblea = isset($btn['asamblea']) ? htmlspecialchars($btn['asamblea']) : '';
                                $tipo = isset($btn['tipo']) ? $btn['tipo'] : '';

                                // URL personalizada para Distrito Capital
                                if ($asamblea === 'Distrito Capital') {
                                    $url = 'Estados.php?estado=Distrito Capital';
                                } else {
                                    $url = 'ciudad.php?estado=' . urlencode($estado) . '&ciudad=' . urlencode($asamblea);
                                }
                            ?>
                            <li>
                                <?php if ($tipo === 'redirigir' || $asamblea === 'Distrito Capital'): ?>
                                    <button onclick="window.location.href='<?php echo $url; ?>'">
                                        <?php echo $leyenda; ?>
                                    </button>
                                <?php else: ?>
                                    <button onclick="mostrarDetallesPorAsamblea('<?php echo $asamblea; ?>')">
                                        <?php echo $leyenda; ?>
                                    </button>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        <?php else: ?>
            <p>No hay datos disponibles para este estado.</p>
        <?php endif; ?>




            
    </div>

    <!-- MODAL -->
    <div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h3>Detalles de la Asamblea</h3>

        <p><strong>Asamblea:</strong> <span id="det-asamblea"></span></p>
        <p><strong>Número:</strong> <span id="det-numero"></span></p>
        <p><strong>Fecha de Fundación:</strong> <span id="det-Fehca_Fundacion"></span></p>
        <p><strong>Ciudad:</strong> <span id="det-ciudad"></span></p>
        <p><strong>Estado:</strong> <span id="det-estado"></span></p>
        <p><strong>Dirección:</strong> <span id="det-direccion"></span></p>

        <ul id="lista-horarios">
            <li><strong>Domingo:</strong> <span id="det-domingo"></span></li>
            <li><strong>Lunes:</strong> <span id="det-lunes"></span></li>
            <li><strong>Martes:</strong> <span id="det-martes"></span></li>
            <li><strong>Miércoles:</strong> <span id="det-miercoles"></span></li>
            <li><strong>Jueves:</strong> <span id="det-jueves"></span></li>
            <li><strong>Viernes:</strong> <span id="det-viernes"></span></li>
            <li><strong>Sábado:</strong> <span id="det-sabado"></span></li>
        </ul>

        <div id="wrap-obras">
        <p><strong>Obras:</strong></p>
        <div class="obras-scroll">
            <ul id="det-obras"></ul>
        </div>
        </div>

        <p><strong>Google Maps:</strong>
        <a id="det-mapa" href="#" target="_blank" class="btn-mapa">
            <i class="fas fa-map-marker-alt"></i> Ver ubicación
        </a>
        </p>
    </div>
    </div>

    <script>
        const iglesiasData = <?php
            $iglesiasPorAsamblea = [];
            foreach ($datos_iglesias as $row) {
                $clave = strtoupper(trim($row['asamblea']));
                $iglesiasPorAsamblea[$clave] = $row;
            }
            echo json_encode($iglesiasPorAsamblea, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        ?>;

        function esSinReunion(valor) {
        if (valor == null) return true;
        let s = String(valor)
            .replace(/<[^>]*>/g, "")
            .replace(/\u00A0/g, " ")
            .trim()
            .toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        s = s.replace(/^[\s\-–—_.:;]+|[\s\-–—_.:;]+$/g, "").replace(/\s+/g, " ").trim();
        const VACIOS = new Set(["", "sin reuniones", "sin reunion", "no hay", "no hay reuniones", "no aplica", "n/a", "na", "ninguna", "sin actividades"]);
        return VACIOS.has(s);
        }

        function mostrarDetalles(data) {
        document.getElementById("det-asamblea").textContent = data.asamblea || "";
        document.getElementById("det-numero").textContent = data.numero || "";
        document.getElementById("det-Fehca_Fundacion").textContent = data.Fehca_Fundacion || "";
        document.getElementById("det-ciudad").textContent = data.ciudad || "";
        document.getElementById("det-estado").textContent = data.estado || "";
        document.getElementById("det-direccion").textContent = data.direccion || "";

        // Horarios
        const dias = ["domingo","lunes","martes","miercoles","jueves","viernes","sabado"];
        dias.forEach(dia => {
            const span = document.getElementById(`det-${dia}`);
            if (!span) return;
            const li = span.closest("li");
            const valor = data[dia];

            if (esSinReunion(valor)) {
            span.textContent = "";
            if (li) li.style.display = "none";
            } else {
            span.textContent = String(valor).trim();
            if (li) li.style.display = "list-item";
            }
        });

        // Ocultar la lista si no hay días visibles
        const ul = document.getElementById("lista-horarios");
        if (ul) {
            const visible = Array.from(ul.querySelectorAll("li")).some(li => li.style.display !== "none");
            ul.style.display = visible ? "block" : "none";
        }

        // Mostrar obras con formato de lista y scroll
        const obrasContainer = document.getElementById("det-obras");
        const wrapObras = document.getElementById("wrap-obras");
        obrasContainer.innerHTML = "";

        if (data.obras && data.obras.trim() !== "" && data.obras.toLowerCase().trim() !== "sin obras que atender") {
            const obrasList = data.obras.trim().split(/\r?\n+/).filter(line => line.trim() !== "");
            if (obrasList.length > 0) {
            obrasList.forEach(linea => {
                const li = document.createElement("li");
                li.textContent = linea.trim();
                obrasContainer.appendChild(li);
            });
            wrapObras.style.display = "block";
            } else {
            wrapObras.style.display = "none";
            }
        } else {
            wrapObras.style.display = "none";
        }

        // Google Maps
        const mapaLink = document.getElementById("det-mapa");
        if (data.GoogleMaps && data.GoogleMaps.trim() !== "") {
            mapaLink.href = data.GoogleMaps.trim();
        } else {
            mapaLink.href = "#";
        }

        document.getElementById("modal").style.display = "block";
        }

        function cerrarModal() {
        document.getElementById("modal").style.display = "none";
        }

        function mostrarDetallesPorAsamblea(asamblea) {
        const clave = asamblea.trim().toUpperCase();
        const data = iglesiasData[clave];
        if (data) {
            mostrarDetalles(data);
        } else {
            console.error("No se encontró información para:", clave);
            alert("No se encontró información para la asamblea: " + asamblea);
        }
        }

        document.addEventListener("DOMContentLoaded", function () {
        const mapaLink = document.getElementById("det-mapa");
        mapaLink.addEventListener("click", function (e) {
            const url = mapaLink.getAttribute("href");
            if (!url || url === "#" || url.trim() === "") {
            e.preventDefault();
            alert("Enlace no disponible por ahora.\n\nSi lo tiene, favor hacerlo llegar al correo:\n\ndirectorioasambleas@gmail.com\n\nIndicando claramente el nombre de la asamblea y el estado en el que se encuentra.\n\nGracias de antemano.");
            }
        });
        });
    </script>

    <style>
        /* Estilo para las obras con scroll */
        .obras-scroll {
            max-height: 100px;        /* altura máxima antes de activar scroll */
            overflow-y: auto;         /* scroll vertical */
            margin-top: 6px;
            /*border: 1px solid #ccc;/** */
            border-radius: 6px;
            background: transparent;
            padding: 6px 10px;
        }

        /* Lista de obras */
        .obras-scroll ul {
            list-style-type: disc;
            margin: 6px 0 6px 20px;
            padding: 0;
        }

        .obras-scroll li {
            margin-bottom: 5px;
            line-height: 1.4;
            text-align: left;
        }

        /* Scrollbar personalizada */
        .obras-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .obras-scroll::-webkit-scrollbar-thumb {
            background: #aaa;
            border-radius: 10px;
        }

        .obras-scroll::-webkit-scrollbar-thumb:hover {
            background: #888;
        }

    </style>




</body>
</html>
<?php $conn->close(); ?>
