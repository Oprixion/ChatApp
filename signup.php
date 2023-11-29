<?php
if (isset($_POST['submit'])) {
    require "config.php";
    require "common.php";
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $new_user = array(
            "username" => $_POST['username'],
            "psw" => $_POST['password'],
        );
        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            "users",
            implode(", ", array_keys($new_user)),
            ":" . implode(", :", array_keys($new_user))
        );
        $statement = $connection->prepare($sql);
        $statement->execute($new_user);
    
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php if (isset($_POST['submit']) && $statement) { ?>
    <?php echo $_POST['username']; ?> successfully added.
<?php } ?>

<?php include "templates/header.php"; ?>
    <h2>Sign Up with your Username and Password</h2>
    <form method="post">
        <label for="username">User Name</label>
        <input type="text" name="username" id="username">
        <label for="password">password</label>
        <input type="password" name="password" id="password">
        <input type="submit" name="submit" value="Sign In">
    </form>
    <a href="index.php">Back to home</a>
<?php include "templates/footer.php"; ?>