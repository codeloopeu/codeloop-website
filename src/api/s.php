<?php declare(strict_types = 1);

include_once('helpers/HttpHelper.php');
include_once('helpers/JsonHelper.php');
include_once('helpers/DatabaseHelper.php');
include_once('helpers/DomainHelper.php');

HttpHelper::checkIfPostOrDie();
$domain = DomainHelper::getDomainOrDie();
$json = JsonHelper::getJsonFromBodyOrDie();

$rawClientId = @$json['ref'];

$sessionId = pg_escape_string(@$json['sessionId']);
$browserId = pg_escape_string(@$json['browserId']);
$clientId = $rawClientId ? pg_escape_string($rawClientId) : $rawClientId;
$eventDatetime = pg_escape_string(@$json['eventDatetime']);
$metadata = pg_escape_string(json_encode(@$json['metadata']));

$connection = DatabaseHelper::createDatabaseConnection();
pg_insert($connection, 'sessions', array('domain' => $domain, 'id' => $sessionId, 'client_id' => $clientId, 'browser_id' => $browserId , 'event_datetime' => $eventDatetime, 'metadata' => $metadata));
pg_close($connection);

HttpHelper::setNoContentResponseStatusCode();
HttpHelper::addCorsHeaders();
