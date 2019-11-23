<?php
    session_start();
    require_once('./conn.php');
    include_once('./utils.php');

    if(
    //要透過isset和empty來判斷是否為空值
    isset($_POST['username'])&& 
    isset($_POST['password'])&&
    isset($_POST['nickname'])&&
    !empty($_POST['username'])&& 
    !empty($_POST['password'])&&
    !empty($_POST['nickname']))
    {

        $sql = "INSERT INTO fan630_users (username,hashed_password,nickname) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $nickname = $_POST['nickname'];

        $stmt -> bind_param("sss",$username, $hashed_password, $nickname);

        /*發通行證*/
        if($stmt -> execute()){
            $_SESSION['username']=$username;
            header('Location:./index.php');
        }else{
            echo('帳號或是密碼錯誤!');
        }
    }


?>

<!DOCTYPE html>
 <html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.min.css">
    <title>註冊</title>
 </head>
 <body>
     <div id="form__wrapper" class="d-flex justify-content-center mt-3">
          <form action="./register.php" method="POST" class="form">
             <div class="form__row pt-3">請輸入自訂帳號:
                <input type="text" name='username'>
             </div>
             <div class="form__row">請輸入自訂密碼:
                 <input type="password" name='password'>
             </div>
             <div class="form__row">請輸入暱稱:
                 <input type="nickname" name='nickname'>
             </div class="form__row">
                 <input type="submit" class='btn btn-primary'>
            </form>
         </div>
</body>
 </html>