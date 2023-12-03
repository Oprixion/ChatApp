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
          div.textContent = user.username;
          div.onclick = function() {
            document.getElementById('search-box').value = user.username; // Set the input value to the username clicked
            document.getElementById('selected-username').textContent = user.username; // Display the selected username in the sidebar
            resultsContainer.innerHTML = ''; // Clear the results
          };
          resultsContainer.appendChild(div);
        });
      }
    };
    xhr.open("GET", "search.php?term=" + encodeURIComponent(searchTerm), true);
    xhr.send();
  }
  