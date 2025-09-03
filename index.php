<?php
// Simple fallback router - mainly for development
$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?'); // Remove query parameters

// If root, redirect to frontend
if ($request === '/') {
    header('Location: /frontend/');
    exit;
}

// Simple 404 for other unhandled requests
http_response_code(404);
echo "404 - Page not found";
?>