<?php
    include "index.php";
    $long_url = mysqli_real_escape_string($conn, $_POST['long_url']);
    if(!empty($long_url) && filter_var($long_url, FILTER_VALIDATE_URL)){
        $ran_url = substr(md5(microtime()), rand(0, 26), 5);
        $sql = mysqli_query($conn, "SELECT * FROM links WHERE slug = '{$ran_url}'");
        if(mysqli_num_rows($sql) > 0){
            echo "Something went wrong. Please try again!";
        }else{
            $sql2 = mysqli_query($conn, "INSERT INTO links (long_url, slug, visits) 
                                         VALUES ('{$long_url}', '{$ran_url}', '0')");
            if($sql2){
                $sql3 = mysqli_query($conn, "SELECT slug FROM url WHERE slug = '{$ran_url}'");
                if(mysqli_num_rows($sql3) > 0){
                    $slug = mysqli_fetch_assoc($sql3);
                    echo $slug['slug'];
                }
            }
        }
    }else{
        echo "$long_url - This is not a valid URL!";
    }
?>