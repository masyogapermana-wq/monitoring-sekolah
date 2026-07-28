<?php
$path = __DIR__ . '/images/logosekolah.jpg';
echo "File ada? " . (file_exists($path) ? 'YA' : 'TIDAK') . "<br>";
if (file_exists($path)) {
    echo "Ukuran file: " . filesize($path) . " bytes<br>";
    echo "Mime asli: " . mime_content_type($path) . "<br>";
    $data = base64_encode(file_get_contents($path));
    echo '<img src="data:image/jpeg;base64,' . $data . '" width="100">';
}
