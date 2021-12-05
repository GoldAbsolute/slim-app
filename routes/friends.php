<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/friends/all', function(Request $request, Response $response) {
    $sql = "SELECT * FROM friends";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $friends = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        $response->getBody()->write(json_encode($friends));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->get('/friends/{id}', function(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT * FROM friends WHERE id = $id";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $friend = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;
        $response->getBody()->write(json_encode($friend));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->post('/friends/add', function(Request $request, Response $response, array $args) {
    $email = $request->getParam('email');
    $name_name = $request->getParam('name');
    $phone = $request->getParam('phone');
    $sql = "INSERT INTO friends (email,name,phone) VALUE (:email, :name_name, :phone)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name_name);
        $stmt->bindParam(':phone', $phone);

        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->delete('/friends/delete/{id}', function(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "DELETE FROM friends WHERE id = $id";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});