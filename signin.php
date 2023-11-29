<?php include "templates/header.php"; ?>
    <h2>Sign in with your Username and Password</h2>
    <form method="post">
        <label for="username">User Name</label>
        <input type="text" name="username" id="username">
        <label for="password">password</label>
        <input type="password" name="password" id="password">
        <input type="submit" name="submit" value="Sign In">
    </form>
    <a href="index.php">Back to home</a>
<?php include "templates/footer.php"; ?>