<?php

// written by GTusername1

session_start();
if (empty($_SESSION['UserID']) ){
    header("Location: public_search.php");
    die();
}else{
    header("Location: employee_search.php");
    die();
}
?>