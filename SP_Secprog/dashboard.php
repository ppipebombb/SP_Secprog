<?php 
  include "php/index.php";
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'databasequiz';

//db connect
$mysqli = new mysqli($host,$username,$password,$dbname);

if($mysqli -> connect_errno){
    die("Failed to connect: ".$mysqli -> connect_error);
}
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$loginUser = $_SESSION['username'];
$shortUrl = array();

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $loginUser);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $shortUrl[] = $row;
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}


  $new_url = "";
  if(isset($_GET)){
    foreach($_GET as $key=>$val){
      $u = mysqli_real_escape_string($conn, $key);
      $new_url = str_replace('/', '', $u);
    }
      $sql = mysqli_query($conn, "SELECT long_url FROM links WHERE slug = '{$new_url}'");
      if(mysqli_num_rows($sql) > 0){
        $sql2 = mysqli_query($conn, "UPDATE links SET visits = visits + 1 WHERE slug = '{$new_url}'");
        if($sql2){
            $long_url = mysqli_fetch_assoc($sql);
            header("Location:".$long_url['long_url']);
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>URL Shortener</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
</head>
<body>
  <div class="wrapper">
    <form action="#" autocomplete="off">
      <input type="text" spellcheck="false" name="full_url" placeholder="Enter or paste a long url" required>
      <i class="url-icon uil uil-link"></i>
      <button>Shorten</button>
    </form>
    <?php
      $sql2 = mysqli_query($conn, "SELECT * FROM links ORDER BY id DESC");
      if(mysqli_num_rows($sql2) > 0){;
        ?>
          <div class="statistics">
            <?php
              $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM links");
              $res = mysqli_fetch_assoc($sql3);

              $sql4 = mysqli_query($conn, "SELECT clicks FROM links");
              $total = 0;
              while($count = mysqli_fetch_assoc($sql4)){
                $total = $count['visits'] + $total;
              }
            ?>
            <span>Total Links: <span><?php echo end($res) ?></span> & Total Clicks: <span><?php echo $total ?></span></span>
            <a href="php/deleting.php?delete=all">Clear All</a>
        </div>
        <div class="urls-area">
          <div class="title">
            <li>Shorten URL</li>
            <li>Original URL</li>
            <li>Clicks</li>
            <li>Action</li>
          </div>
          <?php
            while($row = mysqli_fetch_assoc($sql2)){
              ?>
                <div class="data">
                <li>
                  <a href="<?php echo $domain.$row['slug'] ?>" target="_blank">
                  <?php
                    if($domain.strlen($row['slug']) > 50){
                      echo $domain.substr($row['slug'], 0, 50) . '...';
                    }else{
                      echo $domain.$row['slug'];
                    }
                  ?>
                  </a>
                </li> 
                <li>
                  <?php
                    if(strlen($row['long_url']) > 60){
                      echo substr($row['long_url'], 0, 60) . '...';
                    }else{
                      echo $row['long_url'];
                    }
                  ?>
                </li> 
              </li>
                <li><?php echo $row['visits'] ?></li>
                <li><a href="php/deleting.php?id=<?php echo $row['slug'] ?>">Delete</a></li>
              </div>
              <?php
            }
          ?>
      </div>
        <?php
      }
    ?>
  </div>

  <div class="blur-effect"></div>
  <div class="popup-box">
  <div class="info-box">Your short link is ready. You can also edit your short link now but can't edit once you saved it.</div>
  <form action="#" autocomplete="off">
    <label>Edit your shortened url</label>
    <input type="text" class="shorten-url" spellcheck="false" required>
    <i class="copy-icon uil uil-copy-alt"></i>
    <button>Save</button>
  </form>
  </div>

  <script src="script.js"></script>

</body>
</html>

