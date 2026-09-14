<?php
// Route the request to the appropriate page
$requestUri = $_SERVER['REQUEST_URI'];

// If the request is for the root URL ("/"), redirect to login.php
if ($requestUri === '/' || $requestUri === '') {
    header("Location: login");
    exit();
}

// If the request is for other pages, handle the routing as needed
// Example: You can use a switch or if-else to handle different pages/routes

// For example:
// if ($requestUri === 'dashboard') {
//     require 'dashboard';
//     exit();
// }

// If the request does not match any known routes, you can display a 404 page or redirect to the login page
// For example:
// header("HTTP/1.0 404 Not Found");
// require '404.php';
// exit();

// If using a PHP framework, the routing would be handled differently based on the framework's routing system.
// For example, Laravel uses routes defined in routes/web.php or routes/api.php.

// For this basic approach, you can redirect to login.php for all requests that do not match specific routes.
header("Location: login");
exit();
