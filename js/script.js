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
    sessionStorage.setItem('username', username);
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
  var chatBox = document.getElementById('chatBox');
  var messageContainer = document.createElement('div')
  var messageBox = document.createElement('div');
  var nameBox = document.createElement('p');

  if (user == "sender"){
    messageContainer.id = 'sending';
    messageBox.textContent = message;
    nameBox.textContent = username;

    messageContainer.appendChild(nameBox);
    messageContainer.appendChild(messageBox);


    chatBox.appendChild(messageContainer);
  } else{
    messageContainer.id = 'receiving';
    messageBox.textContent = message;
    nameBox.textContent = username;

    messageContainer.appendChild(nameBox);
    messageContainer.appendChild(messageBox);


    chatBox.appendChild(messageContainer);
  }
  
}

/* Set the width of the sidebar to 20% and the left margin of the page content to 20% */
/* In order to test for responsiveness, you must click the toggle sidebar button again each time you change the resolution */
function openNav() {
  var screenWidth = window.innerWidth || document.documentElement.clientWidth;

  console.log("Screen Width:", screenWidth);

  var mySidebar = document.getElementById("mySidebar");
  var main = document.getElementById("main");

  if (screenWidth <= 768) {
    mySidebar.style.width = "40%";
    main.style.marginLeft = "0";
  } else if (screenWidth > 768 && screenWidth < 1089){
    mySidebar.style.width = "30%";
    main.style.marginLeft = "30%";
  } else {
    mySidebar.style.width = "20%";
    main.style.marginLeft = "20%";
  }
}




/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("chat").style.marginLeft = "0";
}

function addEmoji(emoji) {
  let inputEle = document.getElementById('message');
  
  message.value += emoji;
}

function toggleEmojiDrawer() {
  let drawer = document.getElementById('drawer');
  
  if (drawer.classList.contains('hidden')) {
    drawer.classList.remove('hidden');
  } else {
    drawer.classList.add('hidden');
  }
}