<?php
// An example to check whether graphql subscription is working or not
require dirname(__FILE__) . '/vendor/autoload.php';

$api_url = 'https://pbnblnr7xxxxxxxxxxxxxxxxx.appsync-api.ap-south-1.amazonaws.com/graphql'; // Appsync-API-URL
$api_key = '<APPSYNC-API-KEY>';

$wss_url = str_replace('https', 'wss', str_replace('appsync-api', 'appsync-realtime-api', $api_url));
$host = str_replace('https://', '', str_replace('/graphql', '', $api_url));

$authorization = ['host' => $host, 'x-api-key' => $api_key];
$api_header = json_encode($authorization);
$base64_api_header = base64_encode($api_header);

$appsync_url = "$wss_url?header=$base64_api_header&payload=e30=";

function uuid4()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$client = new WebSocket\Client($appsync_url, [
    'timeout' => 60, // 1 minute time out
    'headers' => ['sec-websocket-protocol' => 'graphql-ws'],
]);

// connection_init
$client->send(json_encode(["type" => "connection_init"]));
$connectionInitResponse = $client->receive();
$connectionInitArr = json_decode($connectionInitResponse, true);
print_r($connectionInitArr);

if ($connectionInitArr['type'] === 'connection_ack') {
    $query = json_encode([
        'query' => 'subscription onCreateTodo { onCreateTodo { __typename title description } }',
        // 'variables' => '{}',
    ]);

    $startReqArr = [
        'id' => uuid4(),
        'payload' => [
            'data' => $query,
            'extensions' => [
                'authorization' => $authorization,
            ],
        ],
        'type' => 'start',
    ];

    // echo json_encode($startReqArr);
    $client->send(json_encode($startReqArr));
    while (true) {
        try {
            $startResponse = $client->receive();
            $startResArr = json_decode($startResponse, true);
            print_r($startResArr);
            // Act on received message
            // Break while loop to stop listening
        } catch (Exception $e) {
            // Possibly log errors
            echo ($e->getMessage());
        }
    }
}
