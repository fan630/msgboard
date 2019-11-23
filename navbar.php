<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=s, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <title>Document</title>
</head>
    <nav class="navbar navbar-expand-md navbar-light bg-light py-1">
        <a class="navbar-brand" href="./index.php">留言板</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
                if(!$is_login){
            ?>
            <ul class="navbar-nav">
                <li class="nav-item navbar__right__logout">
                    <a class="nav-link" href="./register.php">註冊<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./login.php">登入</a>
                </li>
            </ul>
            <?
            }else{
              ?>
                <ul class="navbar-nav">
                    <li class="nav-item navbar__right__login">
                        <a class="nav-link" href="./logout.php">登出</a>
                    </li>
                </ul>
              <?
            }
           ?>
        </div>
    </nav>
</html>