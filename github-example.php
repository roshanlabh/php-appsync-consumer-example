<?php
// An example to fetch info from githup's graphql API
require_once 'graphql-client.php';

$query = <<<'GRAPHQL'
query GetUser($user: String!) {
   user (login: $user) {
    name
    email
    repositoriesContributedTo {
      totalCount
    }
  }
}
GRAPHQL;

$result = graphql_query('https://api.github.com/graphql', $query, ['user' => '<github Username>'], '<github access key>');
print_r($result);
