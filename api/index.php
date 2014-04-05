<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/cards', 'getCards');
$app->get('/cards/:id',	'getCard');
$app->get('/cards/search/:query', 'findByTerm');
$app->post('/cards', 'addCard');
$app->put('/cards/:id', 'updateCard');
$app->delete('/cards/:id',	'deleteCard');

$app->run();

function getCards() {
	$sql = "select * FROM cards ORDER BY card_id ASC";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$cards = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"cards": ' . json_encode($cards) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getCard($id) {
	$sql = "SELECT * FROM cards WHERE card_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$card = $stmt->fetchObject();
		$db = null;
		echo json_encode($card);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function addCard() {
	error_log('addCard\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$card = json_decode($request->getBody());
	$sql = "INSERT INTO cards (deck_id, term, definition) VALUES (:deck_id, :term, :definition)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("deck_id", $card->deck_id);
		$stmt->bindParam("term", $card->term);
		$stmt->bindParam("definition", $card->definition);
		$stmt->execute();
		$card->card_id = $db->lastInsertId();
		$db = null;
		echo json_encode($card);
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function updateCard($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$card = json_decode($body);
	$sql = "UPDATE cards SET deck_id=:deck_id, term=:term, definition=:definition WHERE card_id=:card_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("deck_id", $card->deck_id);
		$stmt->bindParam("term", $card->term);
		$stmt->bindParam("definition", $card->definition);
		$stmt->bindParam("card_id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($card);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function deleteCard($id) {
	$sql = "DELETE FROM cards WHERE card_id=:card_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("card_id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function findByTerm($query) {
	$sql = "SELECT * FROM cards WHERE UPPER(term) LIKE :query ORDER BY term";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$cards = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"cards": ' . json_encode($cards) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="dbuser";
	$dbpass="lpghr255!";
	$dbname="ergosphere_hivemind";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>
