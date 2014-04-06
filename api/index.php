<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/cards', 'getCards');
$app->get('/cards/:id',	'getCard');
$app->get('/cards/search/:query', 'findCardByTerm');
$app->post('/cards', 'addCard');
$app->put('/cards/:id', 'updateCard');
$app->delete('/cards/:id',	'deleteCard');

$app->get('/topics', 'getTopics');
$app->get('/topics/:id',	'getTopic');
$app->get('/topics/:id/decks',	'getDecksByTopic');
$app->get('/topics/search/:query', 'findTopicByName');
$app->post('/topics', 'addTopic');
$app->put('/topics/:id', 'updateTopic');
$app->delete('/topics/:id',	'deleteTopic');

$app->get('/decks', 'getDecks');
$app->get('/decks/:id',	'getDeck');
$app->get('/decks/:id/cards',	'getCardsByDeck');




$app->run();

function getCards() {
	$sql = "SELECT * FROM cards ORDER BY card_id ASC";
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

function findCardByTerm($query) {
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

function getTopics() {
	$sql = "SELECT * FROM topics ORDER BY topic_name ASC";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$topics = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"topics": ' . json_encode($topics) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getTopic($id) {
	$sql = "SELECT * FROM topics WHERE topic_id=:topic_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("topic_id", $id);
		$stmt->execute();
		$topic = $stmt->fetchObject();
		$db = null;
		echo json_encode($topic);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function findTopicByName($query) {
	$sql = "SELECT * FROM topics WHERE UPPER(topic_name) LIKE :query ORDER BY topic_name";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$topics = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"topics": ' . json_encode($topics) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function addTopic() {
	error_log('addTopic\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$topic = json_decode($request->getBody());
	$sql = "INSERT INTO topics (topic_name, topic_description) VALUES (:topic_name, :topic_description)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("topic_name", $topic->topic_name);
		$stmt->bindParam("topic_description", $topic->topic_description);
		$stmt->execute();
		$topic->topic_id = $db->lastInsertId();
		$db = null;
		echo json_encode($topic);
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function updateTopic($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$topic = json_decode($body);
	$sql = "UPDATE topics SET topic_name=:topic_name, topic_description=:topic_description WHERE topic_id=:topic_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("topic_id", $id);
		$stmt->bindParam("topic_name", $topic->topic_name);
		$stmt->bindParam("topic_description", $topic->topic_description);
		$stmt->execute();
		$db = null;
		echo json_encode($topic);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function deleteTopic($id) {
	$sql = "DELETE FROM topics WHERE topic_id=:topic_id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("topic_id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getDecksByTopic($id) {
	$sql = "SELECT * FROM decks WHERE topic_id=:topic_id ORDER BY deck_name ASC";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("topic_id", $id);
		$stmt->execute();
		$decks = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"decks": ' . json_encode($decks) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getDecks() {
	$sql = "SELECT * FROM decks ORDER BY deck_name ASC";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$decks = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"decks": ' . json_encode($decks) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getDeck($id) {
	$sql = "SELECT * FROM decks WHERE deck_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$deck = $stmt->fetchObject();
		$db = null;
		echo json_encode($deck);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getCardsByDeck($id) {
	$sql = "SELECT * FROM cards WHERE deck_id=:deck_id ORDER BY card_id ASC";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("deck_id", $id);
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
