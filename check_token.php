<?php

    session_start();
    include_once('./conn.php');

    if(!isset($_COOKIE['PHPSESSID'])&& !empty($_COOKIE['PHPSESSID'])){
          header('Location:./login.php');
        }else{
           $username = $_SESSION['username'];
        }
?>