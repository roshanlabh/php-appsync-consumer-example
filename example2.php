<?php
// An example of dataOwner Mutation of Appsych service

require_once 'graphql-client.php';

$query = <<<'GRAPHQL'
mutation addDataOwner{
  addDataOwner(data: {
    dataOwnerId: "333"
    storageLocation: "ap-south-1"
    dataOwnerName: "Test Hotel 333"
  }){
    dataOwnerId
    dataOwnerName
    storageLocation
    createDateTime
  }
}
GRAPHQL;

$result = graphql_query('https://pbnblnr7xxxxxxxxxxxxxxxxx.appsync-api.ap-south-1.amazonaws.com/graphql', $query, [], '<APPSYNC-API-KEY>');
print_r($result);
