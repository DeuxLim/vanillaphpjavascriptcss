<?php
// Must be loaded initially
require __DIR__ . "/../src/utils/utilities.php";

$psr_4_class_mapping = [
    'App\\' => __DIR__ . '/../src/' // Namespace prefix => base directory
];

spl_autoload_register(function($class) use ($psr_4_class_mapping) {
    foreach ($psr_4_class_mapping as $prefix => $file_dir) {
        // Only continue if the class starts with the prefix
        if (strpos($class, $prefix) !== 0) {
            continue;
        }

        // Remove the prefix only from the start
        $relative_class = substr($class, strlen($prefix));

        // Convert namespace separators to directory separators
        $file_name = str_replace("\\", "/", $relative_class);

        // Build the full path
        $full_path = $file_dir . $file_name . ".php";

        if (file_exists($full_path)) {
            require $full_path;
        }
    }
});