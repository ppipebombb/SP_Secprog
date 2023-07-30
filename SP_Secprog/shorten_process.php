<?php
$host = 'localhost';
$username = '';
$password = '';
$dbname = 'url_shortener';


$mysqli = new mysqli($host,$username,$password,$dbname);

if($mysqli -> connect_errno){
    die("Failed to connect: ".$mysqli -> connect_error);
}


session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $longUrl = $_POST['long_url'];
    $slug = $_POST['slug'];


    $stmt = $mysqli->prepare("SELECT * FROM users WHERE slug = ?");
    if ($stmt) {
        $stmt->bind_param("s", $slug);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
        } else {
            echo "Error: " . $stmt->error;
            exit();
        }
        $stmt->close();
    } else {
        echo "Error: " . $mysqli->error;
        exit();
    }


    $stmt = $mysqli->prepare("INSERT INTO users (username, long_url, slug) VALUES (?, ?, ?)");
    if ($stmt) {
        $username = $_SESSION['username'];
        $stmt->bind_param("sss", $username, $longUrl, $slug);
        if ($stmt->execute()) {
            header("Location: redirect.php?slug=" . urlencode($slug));
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $mysqli->error;
    }
}
?>
