<?php
// Archivo de enrutamiento para el servidor de desarrollo PHP
$request = $_SERVER['REQUEST_URI'];

// Remover parámetros de consulta
$request = strtok($request, '?');

// Si es la raíz, redirigir al frontend
if ($request === '/') {
    header('Location: /frontend/');
    exit;
}

// Si solicita /frontend/ sin archivo específico, servir index.html
if ($request === '/frontend/' || $request === '/frontend') {
    header('Content-Type: text/html');
    readfile(__DIR__ . '/frontend/index.html');
    exit;
}

// Si es un archivo estático, servirlo directamente
if (file_exists(__DIR__ . $request) && !is_dir(__DIR__ . $request)) {
    // Para archivos CSS, JS, HTML
    $ext = pathinfo($request, PATHINFO_EXTENSION);
    
    switch ($ext) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'html':
            header('Content-Type: text/html');
            break;
        case 'json':
            header('Content-Type: application/json');
            break;
        case 'php':
            // Ejecutar archivo PHP
            include __DIR__ . $request;
            exit;
    }
    
    readfile(__DIR__ . $request);
    exit;
}

// Si no existe el archivo, devolver 404
http_response_code(404);
echo "404 - Archivo no encontrado: " . htmlspecialchars($request);
?>