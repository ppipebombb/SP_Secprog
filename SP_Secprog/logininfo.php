<?php

$host = 'localhost';
$username = '';
$password = '';
$dbname = 'url_shortener';

//connect db
$mysqli = new mysqli($host,$username,$password,$dbname);

if($mysqli -> connect_errno){
    die("Failed to connect: ".$mysqli -> connect_error);
}

echo "Connected";

//input & login vars init
$loginUser = '';
$loginPass = '';
$loginMessage = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $loginUser = $_POST['username'];
    $loginPass = $_POST['password'];

    //fetch user details
    $stmt = $mysqli -> prepare("SELECT password FROM users WHERE username =?");

    if($stmt){
        $stmt -> bind_param("s", $loginUser);

        if($stmt -> execute()){
            $stmt -> bind_result($hashedPassword);

            if($stmt -> fetch()){

                //verify
                if(password_verify($loginPass, $hashedPassword)){
                    //pass=correct, start session
                    session_start();

                    $_SESSION['username'] = $loginUser;
                    header("Location: dashboard.php");

                    exit();
                }
                //if wrong
                else{
                    $loginMessage = "Incorrect username or password. Try again.";
                }
            
            } 
            else {
                $loginMessage = "Incorrect username or password.";
            }
        } 
        else{
            $loginMessage = "Error: ".$stmt -> error;
        }
        
    } 
    else{
        $loginMessage = "Error: ".$mysqli -> error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php echo $loginMessage; ?>
    <form method="POST" action="">
        <label for="username">Username: </label>
        <input type="TEXT" name="username" required>

        <br>
        <br>
        
        <label for="password">Password: </label>
        <input type="password" name="password" required>

        <br>
        <br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>