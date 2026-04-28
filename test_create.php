<?php
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
list($header, $body) = explode("\r\n\r\n", $response, 2);
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
preg_match('/name="_token" value="(.*?)"/', $body, $token_match);
$token = $token_match[1];

// Login
$post_data = http_build_query([
    '_token' => $token,
    'email' => 'patient@mediluxe.com',
    'password' => 'password'
]);
$cookie_string = http_build_query($cookies, '', '; ');
$ch2 = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HEADER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch2, CURLOPT_COOKIE, $cookie_string);
$response2 = curl_exec($ch2);
list($header2, $body2) = explode("\r\n\r\n", $response2, 2);
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header2, $matches2);
foreach($matches2[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
$cookie_string2 = http_build_query($cookies, '', '; ');

// Fetch Create Page for CSRF
$ch3 = curl_init('http://127.0.0.1:8000/appointments/create');
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_HEADER, true);
curl_setopt($ch3, CURLOPT_COOKIE, $cookie_string2);
$response3 = curl_exec($ch3);
list($header3, $body3) = explode("\r\n\r\n", $response3, 2);
preg_match('/name="_token" value="(.*?)"/', $body3, $token_match3);
$token3 = $token_match3[1];

// Create Appointment
$post_data3 = http_build_query([
    '_token' => $token3,
    'service_id' => 1,
    'appointment_date' => date('Y-m-d\TH:i', strtotime('+1 day')),
    'notes' => 'Test appointment'
]);
$ch4 = curl_init('http://127.0.0.1:8000/appointments');
curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch4, CURLOPT_HEADER, true);
curl_setopt($ch4, CURLOPT_POST, true);
curl_setopt($ch4, CURLOPT_POSTFIELDS, $post_data3);
curl_setopt($ch4, CURLOPT_COOKIE, $cookie_string2);
$response4 = curl_exec($ch4);
list($header4, $body4) = explode("\r\n\r\n", $response4, 2);

echo "STATUS 4:\n$header4\n";
