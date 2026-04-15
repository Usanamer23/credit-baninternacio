<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$archivo = 'estado.txt';

// Si el archivo no existe, lo creamos con un formato de lista vacío
if (!file_exists($archivo)) {
    file_put_contents($archivo, json_encode([]));
    chmod($archivo, 0777);
}

$metodo = $_SERVER['REQUEST_METHOD'];

// LISTAR O CONSULTAR (GET)
if ($metodo === 'GET') {
    $data = json_decode(file_get_contents($archivo), true);
    if (isset($_GET['accion']) && $_GET['accion'] === 'listar') {
        echo json_encode($data); // Devuelve todos los usuarios al admin
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];
        echo json_encode(['estado' => $data[$id] ?? 'espera']); // Devuelve estado a un usuario
    }
    exit;
}

// ACTUALIZAR O REGISTRAR (POST)
if ($metodo === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id_usuario'])) {
        $id = $input['id_usuario'];
        $estado = $input['estado'] ?? 'espera';
        
        $data = json_decode(file_get_contents($archivo), true);
        $data[$id] = $estado;
        
        file_put_contents($archivo, json_encode($data));
        echo json_encode(['res' => 'ok']);
    }
    exit;
}
?>