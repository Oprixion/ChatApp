<?php include "templates/header.php"; ?>

<div class="container">
  <div class="sidebar">
    <div class="search-bar-container">
      <input type="text" id="search-box" class="search-box" placeholder="Search by Username" oninput="searchUsernames()">
      <div id="results-container"></div>
      <div id="selected-username" class="selected-username"></div> 
    </div>
    
  </div>
  <div class="content">
    <div class="header">Header Content</div>

    <div class="main">
      Welcome User
    </div>

    <div class="message_container">
      <input type="text" name="message" placeholder="Enter message">
      <input type="submit" name="submit" value="Send">
    </div>  
  </div>
</div>
<script src="js/script.js"></script> 
<?php include "templates/footer.php"; ?>
