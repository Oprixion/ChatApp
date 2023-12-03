<?php include "templates/header.php"; ?>
<h1>Chatapp</h1>
<?php
    // Include your database configuration file
    require "config.php";

    $message = "";

    if (isset($_POST['submit'])) {
        try {
            // Connect to the database
            $connection = new PDO($dsn, $username, $password, $options);

            // Prepare and execute the query
            $sql = "SELECT * FROM users WHERE username = :username";
            
            $statement = $connection->prepare($sql);
            $statement->bindValue(':username', $_POST['username']);
            $statement->execute();

            $username = $statement->fetch(PDO::FETCH_ASSOC);

            // Check if user exists and password matches
            if ($username && password_verify($_POST['password'], $username['psw'])) {
                // Redirect to another page
                header("Location: chatscreen.php");
                exit;
            } else {
                // Display an error message
                $message = "Incorrect username or password";
            }

        } catch(PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }
?>

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