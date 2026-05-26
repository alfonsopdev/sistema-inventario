<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? '';

if ($codigo === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Código patrimonial no enviado']);
  exit;
}

// Modificar la ip y apuntar al servidor local que tiene el ocsinventory
//$apiUrl = "http://192.168.185.17:8000/api/activos/" . urlencode($codigo);

$apiUrl = "http://192.168.1.24:8000/api/activos/" . urlencode($codigo);


// cURL
$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al conectar con API OCS']);
  curl_close($ch);
  exit;
}

curl_close($ch);

// Pasar tal cual la respuesta
http_response_code($httpCode);
echo $response;
