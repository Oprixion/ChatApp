<?php 
session_start(); // Start the session at the top
include "templates/header.php"; 

require "config.php";

$message = "";

if (isset($_POST['submit'])) {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM users WHERE username = :username";
    
    $statement = $connection->prepare($sql);
    $statement->bindValue(':username', $_POST['username']);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['psw'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: chatscreen.php");
        exit;
    } else {
        $message = "Incorrect username or password";
    }
}
?>

<h1>Chatapp</h1>
<h2>Sign in with your Username and Password</h2>
<form method="post">
    <label for="username">User Name</label>
    <input type="text" name="username" id="username">
    <label for="password">password</label>
    <input type="password" name="password" id="password">
    <input type="submit" name="submit" value="Sign In">
</form>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<a href="index.php">Back to home</a>
<?php include "templates/footer.php"; ?>