<?php
session_start();
if($_GET['do'] == 'logout')
{
    unset($_SESSION['admin']);
    session_destroy();
} 
?> 
Вы авторизованы !!!