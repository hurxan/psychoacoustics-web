<?php
//Check if the file exists
$imagePath = '../files/wallpaper1.jpg';

header('Cache-Control: public, max-age=604800'); // Cache for 7 days
header('Content-Type: image/jpeg'); // Adjust MIME type
header('Content-Length: ' . filesize($imagePath)); // Optional: Improve efficiency

// Output the image
ob_clean(); // Clear output buffer
flush();    // Ensure headers are sent
readfile($imagePath);