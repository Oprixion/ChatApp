<?php 
session_start(); // Start the session

include "templates/header.php"; 

// Check if the session variable for user is set
if(isset($_SESSION['user'])) {
    $username = $_SESSION['user']; // Fetch the username from the session
} else {
    header("Location: signin.php"); // Redirect to signin.php if session is not set
    exit;
}

?>


<div class="container">
    <div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      <h1 class='title'>ChatAPP</h1>
      <div class="search-bar-container">
          <input type="text" id="search-box" class="search-box" placeholder="Search by Username" oninput="searchUsernames()">
          <div id="results-container"></div>
          <ul id="selected-username" class="selected-username"></ul>
      </div>
      <a href = "logout.php" >Logout</a>
    </div>

    <div id="chat" class="content">
        <button class="openbtn" onclick="openNav()">&#9776;</button>
        <div class="header" id="chat-header"></div>
        <div class = "chat-container" id = "chatContainer">
            <div id="drawer" class="emoji-drawer hidden"> 
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😀</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😃</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😄</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😁</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😆</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😆</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😅</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">😂</div>
                <div class="emoji" onclick="addEmoji(this.innerHTML)">🤣</div>
            </div>
            <!--Message goes here--> 
        </div>
        

        <div class = "message-container">
            <input type="text" id="message" placeholder="Enter message">
            
            <button class="toggle-emoji" onclick="toggleEmojiDrawer()">☺</button>
            
            <input id="send-btn" type="button" onclick="sendMessage()" value="Send">
        </div>
    </div>
</div>

<script src="js/script.js"></script>
<script type="text/javascript">
    initializeWebSocket("<?php echo htmlspecialchars($username); ?>");
</script>

<?php include "templates/footer.php"; ?>

