<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'PUBLIC folder PHP works!',
    'time' => date('Y-m-d H:i:s')
]);
