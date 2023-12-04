function searchUsernames() {
    var searchTerm = document.getElementById('search-box').value;
    var resultsContainer = document.getElementById('results-container');
    var selectedUser = document.getElementById('selected-username');
  
    // Check if the search term is at least 3 characters
    if (searchTerm.length < 3) {
      resultsContainer.innerHTML = '';
      return; // Exit the function if the term is too short
    }
  
    // Make an AJAX call to the PHP script
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var users = JSON.parse(this.responseText);
        resultsContainer.innerHTML = ''; // Clear previous results
  
        users.forEach(function(user) {
          var div = document.createElement('div');
          var userItem = document.createElement("li");
          div.textContent = user.username;

          div.onclick = function() {
            document.getElementById('search-box').value = user.username; // Set the input value to the username clicked
            userItem.textContent = user.username;

            userItem.addEventListener('click', function() {
              startChat(user.username);
            });
          
            selectedUser.appendChild(userItem); // Display the selected username in the sidebar
            
            resultsContainer.innerHTML = ''; // Clear the results
          };
          resultsContainer.appendChild(div);
        });
      }
    };
    xhr.open("GET", "search.php?term=" + encodeURIComponent(searchTerm), true);
    xhr.send();
  }
  
// Global variable to store the selected user's username
var selectedUser = null;

function startChat(name) {
    var chatHeader = document.getElementById('chat-header');
    chatHeader.textContent = "Chatting with " + name;
    selectedUser = name; // Update the selectedUser variable
}
// Global variable for the current user's username

var currentUser;

// Function to initialize WebSocket connection with dynamic username
function initializeWebSocket(username) {
    currentUser = username;
    var socket = new WebSocket('ws://localhost:8080');

    socket.onopen = function(event) {
        // Send the correct username to the server
        var usernameMessage = JSON.stringify({ username: username });
        socket.send(usernameMessage);
    };

    socket.onerror = function(error) {
        console.error('WebSocket error:', error);
    };

    socket.onmessage = function(event) {
      var data = JSON.parse(event.data);
      if (data.username && data.message) {
          // Assuming you have a div with id 'chatBox' to display messages
          var chatBox = document.getElementById('chatBox');
          var messageElement = document.createElement('div');
          messageElement.textContent = data.username + ": " + data.message;
          chatBox.appendChild(messageElement);
      }
  };
  
  

    // Function to send a message
    window.sendMessage = function() {
        var messageInput = document.getElementById('message'); // Assuming input field for message
        var message = messageInput.value;

        var messageObject = {
            username: currentUser, // Use the global username variable
            message: message
        };

        console.log('Sending message:', messageObject);
        socket.send(JSON.stringify(messageObject));
    };
}

// Function to display messages
function displayMessage(username, message) {
    var chatBox = document.getElementById('chatBox'); // Assuming you have a div with id 'chatBox'
    var messageElement = document.createElement('div');
    messageElement.textContent = username + ": " + message;
    chatBox.appendChild(messageElement);
}

// Rest of your searchUsernames and startChat functions...


