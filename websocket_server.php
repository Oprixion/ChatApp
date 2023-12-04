<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Attach the connection to the clients storage
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn) {
        // Detach the connection from the clients storage
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Received a message: $msg\n";
        $data = json_decode($msg, true);
    
        // Check and set username only if it's not already set
        if (!isset($from->username) && isset($data['username'])) {
            $from->username = $data['username'];
            echo "Username set to: " . $from->username . " for connection {$from->resourceId}\n";
            return; // Exit the function after setting the username
        }
    
        // Handle sending messages to specific recipient
        if (isset($data['sender'], $data['recipient'], $data['message'])) {
            $targetUsername = $data['recipient'];
            $broadcastMessage = json_encode([
                'username' => $data['sender'],
                'message' => $data['message']
            ]);
    
            foreach ($this->clients as $client) {
                if (isset($client->username) && $client->username === $targetUsername) {
                    echo "Sending message to " . $client->username . "\n";
                    $client->send($broadcastMessage);
                    break; // Stop the loop once the intended recipient is found
                }
            }
        }
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "Server started on port 8080\n";

$server->run();
