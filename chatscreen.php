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
    <div class="sidebar">
        <div id="welcomeMSG">Welcome, <?php echo htmlspecialchars($username); ?></div>
        <div class="search-bar-container">
            <input type="text" id="search-box" class="search-box" placeholder="Search by Username" oninput="searchUsernames()">
            <div id="results-container"></div>
            <ul id="selected-username" class="selected-username"></ul>
        </div>
    </div>
    <div class="content">
        <div class="header" id="chat-header"></div>
        <div id="chatBox" class="chat-box">
            <!-- Messages will be appended here -->
        </div>
        <input type="text" id="message" placeholder="Enter message">
        <input type="button" onclick="sendMessage()" value="Send">
    </div>
</div>

<script src="js/script.js"></script>
<script type="text/javascript">
    initializeWebSocket("<?php echo htmlspecialchars($username); ?>");
</script>

<?php include "templates/footer.php"; ?>

