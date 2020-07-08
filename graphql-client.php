<?php
// enable errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// function graphql_query(string $endpoint, string $query, array $variables = [], ?string $token = null): array {       // don't work with php5.6
function graphql_query($endpoint, $query, $variables = [], $token = null) {
    $headers = ['Content-Type: application/json', 'User-Agent: Minimal GraphQL client'];
    if (null !== $token) {
        // $headers[] = "Authorization: bearer $token";
        $headers[] = "x-api-key: $token";
    }

    $httpContentOptions = ['query' => $query];  //['query' => $query, 'variables' => $variables]
    if(count($variables) > 0) {
        $httpContentOptions['variables'] = $variables;
    }

    if (false === $data = @file_get_contents($endpoint, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode($httpContentOptions),
        ]
    ]))) {
        $error = error_get_last();
        throw new \ErrorException($error['message'], $error['type']);
    }

    return json_decode($data, true);
}