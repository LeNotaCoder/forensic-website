<?php
$file = __DIR__ . '/counter.json';

// Create counter file if it doesn't exist
if (!file_exists($file)) {
    file_put_contents($file, json_encode(['count' => 1000000]));
}

// Read counter
$data = json_decode(file_get_contents($file), true);

// Increment
$data['count']++;

// Save back
file_put_contents($file, json_encode($data));

// Return JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
