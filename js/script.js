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
    var chatbox = document.createElement('div');
    chatbox.id = name;
    chatHeader.textContent = "Chatting with " + name;
    selectedUser = name;
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
          displayMessage(data.username, data.message, 'receiver');
      }
    };
  
  

    window.sendMessage = function() {
      var messageInput = document.getElementById('message');
      var message = messageInput.value;
      if (selectedUser) {
          var messageObject = {
              sender: currentUser,
              recipient: selectedUser,
              message: message
          };
          console.log('Sending message:', messageObject);
          socket.send(JSON.stringify(messageObject));
          // Display the message on the sender's side as well
          displayMessage(currentUser, message, "sender");

          // Clear the input field after sending the message
          messageInput.value = '';
      }
    };
    
}

// Function to display messages
function displayMessage(username, message, user) {
  var chatBox = document.getElementById('chatbox');
  var messageContainer = document.createElement('div');
  var messageBox = document.createElement('div');
  var nameBox = document.createElement('p');

  if (user == "sender") {
      messageContainer.id = 'sending';
  } else {
      messageContainer.id = 'receiving';
  }

  messageBox.textContent = message;
  nameBox.textContent = username;

  messageContainer.appendChild(nameBox);
  messageContainer.appendChild(messageBox);
  
  chatBox.appendChild(messageContainer);

  // Automatically scroll to the bottom for every message
  chatBox.scrollTop = chatBox.scrollHeight;
}








