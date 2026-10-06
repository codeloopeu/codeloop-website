<?php declare(strict_types = 1);

include_once('helpers/HttpHelper.php');
include_once('helpers/JsonHelper.php');
include_once('helpers/DatabaseHelper.php');
include_once('helpers/DomainHelper.php');

HttpHelper::checkIfPostOrDie();
$domain = DomainHelper::getDomainOrDie();
$json = JsonHelper::getJsonFromBodyOrDie();

$sessionId = pg_escape_string(@$json['sessionId']);
$eventDatetime = pg_escape_string(@$json['eventDatetime']);
$metadata = pg_escape_string(json_encode(@$json['metadata']));

$connection = DatabaseHelper::createDatabaseConnection();
pg_insert($connection, 'tracking', array('domain' => $domain, 'session_id' => $sessionId, 'event_datetime' => $eventDatetime, 'metadata' => $metadata));
pg_close($connection);

HttpHelper::setNoContentResponseStatusCode();
HttpHelper::addCorsHeaders();
