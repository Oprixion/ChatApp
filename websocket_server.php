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

    
        // Check if the connection has a username set
        if (!isset($from->username)) {
        // Check if the received data contains a username
            if (isset($data['username'])) {
                // Assign the username to the connection
                $from->username = $data['username'];
                echo "Username set to: " . $from->username . " for connection {$from->resourceId}\n";
            } else {
                echo "Username not provided for connection {$from->resourceId}\n";
                return; // If no username is provided, exit the function
            }
        }
    
        // Echo when a message is sent from 'snghiem'
        if ($from->username === 'snghiem') {
            echo "Message sent from snghiem: " . $data['message'] . "\n";
        }
    
        // Broadcast the message to all connected clients
        $broadcastMessage = json_encode([
            'username' => $from->username,
            'message' => isset($data['message']) ? $data['message'] : ''
        ]);
    
        foreach ($this->clients as $client) {
            // Check and log the username of each client
            if (isset($client->username)) {
                echo "Sending message to " . $client->username . "\n";
            } else {
                echo "A client without a username is connected\n";
            }
    
            $client->send($broadcastMessage);
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
