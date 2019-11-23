<?php


require_once('./conn.php');

function printMessage($msg, $redirect){
    echo '<script>';
    echo  "alert('$msg');";
    echo  "window.location='$redirect'";
    echo '</script>';
}

function renderDeleteBtn($id){
    return  
        "<div class = 'delete-comment'>
            <button type='submit' class='btn btn-primary px-3 py-0' data-id=$id>刪除</button>
        </div>";
}

function renderEditBtn($id){
    return  
        "<div class = 'edit-comment'>
            <form action='./update.php' method='GET'>
                <input type='hidden' name='id' value=$id>
                    <button type='submit' class='btn btn-info  px-3 py-0'>更新</button>
            </form>
        </div>";
      }
?>

