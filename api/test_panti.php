<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'message' => 'Vercel PHP Runtime is WORKING',
    'time' => date('Y-m-d H:i:s')
]);
