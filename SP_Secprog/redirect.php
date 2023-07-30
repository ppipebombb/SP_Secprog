<?php
$host = 'localhost';
$username = '';
$password = '';
$dbname = 'databasequiz';

$mysqli = new mysqli($host,$username,$password,$dbname);

if($mysqli -> connect_errno){
    die("Failed to connect: ".$mysqli -> connect_error);
}


if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    $stmt = $mysqli->prepare("SELECT long_url FROM users WHERE slug = ?");
    if ($stmt) {
        $stmt->bind_param("s", $slug);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $longUrl = $row['long_url'];

                
                $updateStmt = $mysqli->prepare("UPDATE users SET visit_count = visit_count + 1 WHERE slug = ?");
                if ($updateStmt) {
                    $updateStmt->bind_param("s", $slug);
                    $updateStmt->execute();
                    $updateStmt->close();
                }

  
                header("Location: " . $longUrl);
                exit();
            } else {
                echo "URL not found.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Slug parameter not provided.";
}
?>
