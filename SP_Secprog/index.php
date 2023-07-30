<?php

$host = 'localhost';
$username = '';
$password = '';
$dbname = 'url_shortener';

//db connect
$mysqli = new mysqli($host,$username,$password,$dbname);

if($mysqli -> connect_errno){
    die("Failed to connect: ".$mysqli -> connect_error);
}

echo "Connected";

//input & regist vars init
$username = '';
$password = '';
$registrationMessage = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    //hashing
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    //insert user into db
    $stmt = $mysqli -> prepare("INSERT INTO users(username,password)VALUES (?,?)");

    if($stmt){
        $stmt -> bind_param("ss", $username,$hashedPassword);
        
        //regist successful
        if($stmt -> execute()){
            header("Location: logininfo.php");
            exit();
        }
        else{
            echo"Error: ".$stmt -> error;
        }
        $stmt -> close();
    }
    else{
        echo"Error: ".$mysqli -> error;
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <h1>Sign In</h1>

</head>
<body>
        <?php echo$registrationMessage;?>
    <form method="POST">
        <label for="username">Username: </label>
        <input type="TEXT" name="username" required>

        <br>

        <label for="password">Password: </label>
        <input type="password" name="password" required>

        <br>
        <br>

        <input type="submit" value="Sign In">

    </form>
</body>
</html>