<?php
// An example to Query dataOwner datails from Profile service

require_once 'graphql-client.php';

$query = <<<'GRAPHQL'
query getDataOwner($id: String!) {
   getDataOwner(dataOwnerId: $id) {
    dataOwnerId
    dataOwnerName
    storageLocation
    createDateTime
  }
}
GRAPHQL;

$result = graphql_query('https://pbnblnr7xxxxxxxxxxxxxxxxx.appsync-api.ap-south-1.amazonaws.com/graphql', $query, ['id' => '222'], '<APPSYNC-API-KEY>');
print_r($result);
