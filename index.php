<?php
/**
 * Router - Main Entry Point for "For You Gifts"
 * 
 * Handles all incoming requests and routes them to the appropriate
 * page or handler within the new directory structure.
 */

// Define Base Path
define('BASE_PATH', __DIR__);

// Load Global Configuration
require_once BASE_PATH . '/app/config/site.php';
require_once BASE_PATH . '/app/config/database.php';

// Get Request Path
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

// Normalize path by removing the script directory from the request URI
if ($scriptDir !== '/' && strpos($requestUri, $scriptDir) === 0) {
    $path = substr($requestUri, strlen($scriptDir));
} else {
    $path = $requestUri;
}

$path = trim($path, '/');

// Default Route
if ($path === '' || $path === 'index.php') {
    $path = 'home';
}

/**
 * Route Mapping
 * Map URL paths to actual file locations relative to BASE_PATH.
 * 
 * 'url-slug' => 'relative/path/to/file.php'
 */
$routes = [
    // Public Pages
    'home'              => 'pages/home.php',
    'about'             => 'pages/about.php',
    'about.php'         => 'pages/about.php', // Backward compatibility
    'projects.php'      => 'pages/products.php', // Backward compatibility / SEO
    'products'          => 'pages/products.php',
    'services'          => 'pages/services.php',
    'gallery'           => 'pages/gallery.php', // If exists
    'contact'           => 'pages/contact.php',
    'contact.php'       => 'pages/contact.php',
    'privacy'           => 'pages/privacy.php',
    'testimonials'      => 'pages/testimonials.php',
    'gift'              => 'pages/product-detail.php',
    'gift.php'          => 'pages/product-detail.php', // Backward compatibility
    
    // Handlers (Form Submissions)
    'contact-submit'    => 'app/handlers/contact.php',
];

// Dispatch
if (array_key_exists($path, $routes)) {
    $file = BASE_PATH . '/' . $routes[$path];
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Log error and show 404
        error_log("Router Error: File not found for route '$path' -> '$file'");
        http_response_code(404);
        require_once BASE_PATH . '/pages/404.php'; // Ensure 404 page exists
    }
} else {
    // 404 Not Found
    http_response_code(404);
    // Simple fallback if 404 page doesn't exist
    if (file_exists(BASE_PATH . '/pages/404.php')) {
        require_once BASE_PATH . '/pages/404.php';
    } else {
        echo "<h1>404 - Page Not Found</h1><p>The requested page could not be found.</p>";
    }
}
