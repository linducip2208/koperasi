<?php
// Close the file
$f = __DIR__ . '/../config/pseo-kecamatan.php';
file_put_contents($f, "    ],\n];\n", FILE_APPEND);
echo "File closed.\n";
