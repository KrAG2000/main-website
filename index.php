<?php
/**
 * PHP Router for Serving Static HTML Files with Clean URLs
 *
 * This script acts as a front controller for PHP's built-in development server (`php -S`).
 * It intercepts all incoming requests and maps clean URLs (e.g., /about-us)
 * to their corresponding .html files (e.g., about-us.html).
 *
 * Usage:
 * 1. Save this file as `router.php` (or `index.php`) in the root of your project.
 * 2. Ensure all your .html files listed below are in the same directory as this router.
 * 3. Start the PHP development server from your terminal in that directory:
 * `php -S localhost:8080 router.php`
 * 4. Access your pages using clean URLs, e.g., `http://localhost:8080/about-us`
 */

// Get the requested URI path from the server
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading/trailing slashes and decode the URL for consistent matching.
// This ensures that '/about-us/' and 'about-us' are treated the same as 'about-us'.
$requestPath = trim(urldecode($requestUri), '/');

// Define a mapping of clean URLs to actual HTML file names.
// This array explicitly lists all the HTML files from your image.
$routes = [
    ''               => 'index.html',
    'about-us'       => 'about-us.html',
    'blogs'          => 'blogs.html',
    'contact-us'     => 'contact-us.html',
    'forms'          => 'forms.html',
    'form1'          => 'form1.html',
    'form2'          => 'form2.html',
    'faqs'           => 'faqs.html',
    'features'       => 'features.html',
    'pp-tc'          => 'pp-tc.html',  
    'pricing'        => 'pricing.html',
    'sign-in_out'    => 'sign-in_out.html',
    'solutions'      => 'solutions.html',
    'testimonials'   => 'testimonials.html',
];

// Check if the requested path exists as a key in our routes mapping
if (array_key_exists($requestPath, $routes)) {
    $filePath = $routes[$requestPath];

    // Check if the actual HTML file exists on disk
    if (file_exists($filePath)) {
        // Set content type header to ensure browser renders HTML correctly
        header('Content-Type: text/html; charset=utf-8');
        // Include the HTML file. This will send its content to the browser.
        include $filePath;
    } else {
        // If the HTML file mapped in $routes is not found, it's a server-side 404
        handleNotFound('File not found on server: ' . $filePath);
    }
} else {
    // If the requested path is not in our defined routes,
    // check if it directly corresponds to an existing .html file (e.g., if someone types .html)
    // or if it's a static asset like style.css.

    // First, check for direct file access (e.g., style.css or if .html is typed directly)
    if (file_exists($requestPath)) {
        // Serve the file directly. PHP's built-in server can handle static files.
        // It tries to serve files directly first before handing off to the router.
        // This 'if' block is mostly a fallback/demonstration, as `php -S` usually handles this.
        $extension = pathinfo($requestPath, PATHINFO_EXTENSION);
        $contentType = 'text/plain'; // Default
        switch ($extension) {
            case 'html':
                $contentType = 'text/html';
                break;
            case 'css':
                $contentType = 'text/css';
                break;
            case 'js':
                $contentType = 'application/javascript';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            case 'jpg':
            case 'jpeg':
                $contentType = 'image/jpeg';
                break;
            case 'gif':
                $contentType = 'image/gif';
                break;
            // Add more MIME types as needed
        }
        header('Content-Type: ' . $contentType . '; charset=utf-8');
        readfile($requestPath);
    } else {
        // No route matched, and no direct file found
        handleNotFound('Page not found: ' . $requestUri);
    }
}

/**
 * Handles a 404 Not Found error.
 * Sets the HTTP response code and includes a custom 404 page.
 * @param string $message An optional message for logging or display.
 */
function handleNotFound(string $message = 'Page not found.') {
    http_response_code(404);
    error_log("404 Error: " . $message); // Log the error for debugging

    // You should create a '404.html' file in your project root for this to work
    if (file_exists('404.html')) {
        include '404.html';
    } else {
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f8f8f8; color: #333; }
        h1 { font-size: 3em; margin-bottom: 0.5em; }
        p { font-size: 1.2em; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>404 Not Found</h1>
    <p>The page you requested could not be found.</p>
    <p><a href="/">Go to Home Page</a></p>
</body>
</html>';
    }
    exit; // Stop script execution after handling the error
}
?>
