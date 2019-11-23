<?php
    session_start();
    require_once('./conn.php');
    include_once('./pagination.php');
    include_once('./utils.php');

    $is_login = false;
    $error_message ='';

    /*確認是否有設定username*/
    if(!isset($_SESSION['username']))
    {
        $error_message='<h4 class="text-danger">請先完成註冊!</h4>';
    }
      else
    {
      $is_login=true;
      $username = $_SESSION['username'];
    }
    
    //主要的query
    $sql = "SELECT SQL_CALC_FOUND_ROWS c.content, c.created_at, c.username , c.id, u.nickname FROM fan630_comments as c LEFT JOIN fan630_users as u ON c.username = u.username 
    WHERE c.parent_id = ? ORDER BY created_at DESC LIMIT {$start}, {$perPage}";


    //為了要讓c.parent_id = 0, 因此透過$x 來賦值為0
    $x = 0;

    //主留言
    $stmt = $conn->prepare($sql);
    $stmt -> bind_param("s",$x);
    $stmt -> execute();
    $result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css?v=<?=time();?>">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function(){
            $('.msgboard__display').on('click','.delete-comment',function(e){
                if(!confirm("是否要刪除?")) return
                const id = $(e.target).attr('data-id')

                $.ajax({
                        method: 'POST',
                        url: './delete.php',
                        data: 
                           {id:id}
                    })
                .done(function(response) {
                    //這就是後面傳來的東西,把整組array稱呼為response. 
                    const msg =  JSON.parse(response)
                    // alert(msg.message)
                    $(e.target).closest('.container').hide(200)

                }).fail(function(response){
                    const msg =  JSON.parse(response)
                    alert(msg.message)
                })

            })

            $('form').submit(function(e){
               //e.preventDefault()
               const content = $(e.target).find('textarea[name="content"]').val()
               const nickname = $(e.target).find('input[name="nickname"]').val()
               const parentId = $(e.target).find('input[name="parent_id"]').val()


               //這邊是用來實現
               if(parentId === "0"){
                     e.preventDefault()
               }else{
                   return 
               }

               //不要用原本方法發送留言到後端,而是改用底下的ajax

               $.ajax({
                type: 'POST',
                url: './handle_add.php',
                data: {
                    content: content, 
                    parent_id: parentId,
                    nickname: nickname
                    }
                })
                .done(function(resp){
                    const res = JSON.parse(resp)
                    if(res.result === "success"){
                        $(".msgboard__display").prepend(`
                            <div class='container'>
                                <div class='msgboard__display__top'>
                                        <div class='sub_nickname'>暱稱:${res.username}</div>
                                        <div class='content'>留言內容:${content}</div>
                                        <div class='created_at'>Time:${res.createdTime}</div>
                                            <div class = 'form__link d-flex flex-nowrap'>
                                                <div class = 'delete-comment'>
                                                        <button type='submit' class='btn btn-primary px-3 py-0' data-id=${res.id}>刪除</button>
                                                </div>
                                                <div class = 'edit-comment'>
                                                        <form action='./update.php' method='GET'>
                                                                <input type='hidden' name='id' value=${res.id}>
                                                                <button type='submit' class='btn btn-primary px-3 py-0'>更新</button>
                                                        </form>
                                                </div>
                                            </div>
                                            <form class="subcomments" action="./handle_feedback.php" method="POST">
                                                <div class="text">
                                                    <input type="text" name = 'nickname'  placeholder="請輸入暱稱">
                                                </div>
                                                <div class = subcomment>
                                                    <textarea name="content" class="content" placeholder="請輸入留言"></textarea>
                                                </div>
                                                <div>
                                                    <input type="hidden" name = 'parent_id' value=${res.id} >
                                                </div>
                                                    <input type="submit" class="btn btn-light px-3 py-0">
                                            </form>   
                                </div>`
                                )
                                 $(e.target).find('textarea[name="content"]').val('');
                    }
                }).fail(function(response){
                    console.log(resp)
                })
            })
    })
    
    </script>
    
<title>會員留言板</title>
</head>
<body>
        <?php
            include_once('./navbar.php');
        ?>
    <div class="wrapper">
        <div class="container">
            <div class="warning">
                <h5>本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼</h5>
             </div>
            <?php               
                if($error_message !== ''){
                    echo $error_message;
                }else{
                    echo '<div class="welcome ">Welcome: </div>' . htmlspecialchars($username);
                }
            ?>
            
            <!-- 主留言表格 -->
            <div id="form__wrapper">
                <form action="./handle_add.php" method="POST" class="pt-4 pb-2 form">
                        <textarea name="content" class="content" placeholder="請輸入留言"></textarea>
                    <div>
                        <input type="hidden" name="nickname" value="0">
                    </div>
                    <div>
                        <input type="hidden" name="parent_id" value="0">
                    </div>
                    <?php
                        if($is_login){
                            echo "<input type='submit' class='btn btn-primary pt-1' value='送出'>";
                        }else{
                            echo "<input type='submit' class='btn btn-primary pt-1' value='請先登入' disabled>";
                        }
                    ?>
                </form>
            </div>
            <!-- 主留言表格 -->
                        
            <!-- message_content -->
            <div class="msgboard__display">
                    <?php                        
                        if($result->num_rows>0){
                          while($row = $result -> fetch_assoc()){
                            //登入狀態
                            if($is_login){
                    ?>
                             <div class='container'>
                                <div class='msgboard__display__top'>
                                    <div class='sub_nickname'>暱稱:<?=htmlspecialchars($row['nickname'],ENT_QUOTES,'utf-8');?></div>
                                    <div class='content'>留言內容:<?= htmlspecialchars($row['content'],ENT_QUOTES,'utf-8');?></div>
                                    <div class='created_at'>Time:<?=$row['created_at']?></div>
                                </div>

                                <div class='msgboard__display__bottom'>
                     <?php
                                    if($row['username']===$username){    
                    ?>
                                <div class = 'form__link d-flex flex-nowrap'>
                                    <?php echo renderDeleteBtn($row['id'])?>
                                    <?php echo renderEditBtn($row['id'])?>
                                </div>
                    <?php
                        }
                            //子留言
                                    $sql_sub = "SELECT c.content, c.username, c.id, c.nickname FROM fan630_comments as c LEFT JOIN fan630_users as u ON c.username = u.username 
                                    WHERE c.parent_id = ? ORDER BY created_at DESC";

                                    $stmt = $conn->prepare($sql_sub);
                                    $id = $row['id'];
                                    $stmt -> bind_param('s', $id);
                                    $stmt -> execute(); 
                                    $result_sub = $stmt -> get_result();

                            
                                    if($result_sub->num_rows>0){
                                        while($row_sub = $result_sub -> fetch_assoc()){
                                        //確認是否為原PO
                                        if($row['username'] == $row_sub['username']){
                    ?>
                                        <div class='msgboard__display__sub__same'>
                                            <div class='sub_nickname'>暱稱:<?=htmlspecialchars($row_sub['nickname'],ENT_QUOTES,'utf-8');?></div>
                                            <div class='content'>留言內容:<?= htmlspecialchars($row_sub['content'],ENT_QUOTES,'utf-8');?></div>
                                        </div>
                                <?php
                                        }else{
                                ?>  
                                        <!-- 透過class變更,來判斷是否子留言為原po -->
                                        <div class='msgboard__display__sub'>
                                            <div class='sub_nickname'>暱稱:<?=htmlspecialchars($row_sub['nickname'],ENT_QUOTES,'utf-8');?></div>
                                            <div class='content'>留言內容:<?= htmlspecialchars($row_sub['content'],ENT_QUOTES,'utf-8');?></div>
                                        </div>
                                <?php  
                                                        }
                                                    }
                                                }
                                ?>
                        <!-- msgboard__display__bottom的div -->
                        </div>
                    <!-- 子留言新增內容表格 -->
                      <form class="subcomments" action="./handle_feedback.php" method="POST">
                            <div class="text">
                                <input type="text" name = 'nickname'  placeholder="請輸入暱稱">
                            </div>
                            <div class = subcomment>
                                <textarea name="content" class="content" placeholder="請輸入留言"></textarea>
                            </div>
                            <div>
                                <input type="hidden" name = 'parent_id' value="<?php echo $row['id']?>" >
                            </div>
                            <div>
                                <input type="submit" class="btn btn-light px-3 py-0 mb-2">
                            </div>
                        </form>                         
                        
                    <!-- 對應主留言的container-->
                    </div>
                    


                    <?php
                    // 沒有登入的時候,編輯和刪除無法顯示
                    }else{
                    ?>
                            <div class='container'>
                                <div class='msgboard__display__top'>
                                    <div class='nickname'>暱稱:<?=htmlspecialchars($row['nickname'],ENT_QUOTES,'utf-8');?></div>
                                    <div class='content'>留言內容:<?= htmlspecialchars($row['content'],ENT_QUOTES,'utf-8');?></div>
                                    <div class='created_at'>Time:<?=$row['created_at']?></div>
                                </div>


                    <?php
                     $sql_sub = "SELECT SQL_CALC_FOUND_ROWS c.content, c.created_at, c.username , c.id, c.nickname FROM fan630_comments as c LEFT JOIN fan630_users as u ON c.username = u.username 
                            WHERE c.parent_id = ? ORDER BY created_at DESC";

                            $stmt = $conn->prepare($sql_sub);
                            $id = $row['id'];
                            $stmt -> bind_param('s', $id);
                            $stmt -> execute(); 
                            $result_sub = $stmt -> get_result();

                    //沒有登入的時候,新增留言無法顯示,子留言可以顯示
                            if($result_sub->num_rows>0){
                                while($row_sub = $result_sub -> fetch_assoc()){
                                    //確認是否為原PO
                                    if($row['username'] == $row_sub['username']){
                    ?>
                                        <div class='msgboard__display__sub__same'>
                                            <div class='sub_nickname'>暱稱:<?=htmlspecialchars($row_sub['nickname'],ENT_QUOTES,'utf-8');?></div>
                                            <div class='content'>留言內容:<?= htmlspecialchars($row_sub['content'],ENT_QUOTES,'utf-8');?></div>
                                        </div>
                    <?php             
                                }else{
                    ?>
                                        <div class='msgboard__display__sub'>
                                            <div class='sub_nickname'>暱稱:<?=htmlspecialchars($row_sub['nickname'],ENT_QUOTES,'utf-8');?></div>
                                            <div class='content'>留言內容:<?= htmlspecialchars($row_sub['content'],ENT_QUOTES,'utf-8');?></div>
                                        </div>
                    <?php                
                                    }
                                }
                            }
                        
                    ?>
                        </div>
                    <?php
                         }
                    ?>
                    <?php
                       }
                    }
                ?>
                <div class="pagination d-flex justify-content-center mt-3">
                    <nav aria-label="...">
                        <ul class="pagination pagination-md">
                            <?php for($x=1; $x<=$pages; $x++):?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $x;?>&perPage=<?php echo $perPage;?>" class="page-link">
                                        <?php echo $x; ?>
                                    </a>
                                </li>
                            <?php endfor;?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</body>
</html>