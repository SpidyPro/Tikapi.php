<?php

$link = isset($_GET['link']) ? $_GET['link'] : '';
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';

if (empty($link) || empty($quantity)) {
    $missingParams = [];
    if (empty($link)) $missingParams[] = 'link';
    if (empty($quantity)) $missingParams[] = 'quantity';
    
    $result = [
        'status' => 'error',
        'message' => 'Missing parameter(s): ' . implode(', ', $missingParams)
    ];
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

$url = 'https://alvi0.xyz/tiktok/';
$data = [
    'link' => $link,
    'quantity' => $quantity
];

$headers = [
    'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Accept-Encoding: gzip, deflate, br, zstd',
    'Content-Type: application/x-www-form-urlencoded',
    'cache-control: max-age=0',
    'sec-ch-ua: "Android WebView";v="129", "Not=A?Brand";v="8", "Chromium";v="129"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'origin: https://alvi0.xyz',
    'upgrade-insecure-requests: 1',
    'x-requested-with: mark.via.gp',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: navigate',
    'sec-fetch-user: ?1',
    'sec-fetch-dest: document',
    'referer: https://alvi0.xyz/tiktok/',
    'accept-language: en-GB,en-US;q=0.9,en;q=0.8',
    'priority: u=0, i'
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $result = [
        'status' => 'error',
        'message' => curl_error($ch)
    ];
} else {
    if (preg_match('/Order successfully placed\. Order ID: \d+/', $response, $matches)) {
        $result = [
            'status' => 'success',
            'message' => $matches[0]
        ];
    } else {
        $result = [
            'status' => 'error',
            'message' => 'Order message not found'
        ];
    }
}

curl_close($ch);

header('Content-Type: application/json');
echo json_encode($result);
