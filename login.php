<?php
session_start();
$users = 'admin';
$pass = '202cb962ac59075b964b07152d234b70';
 if($_POST['submit'])
{
    if($users == $_POST['user'] AND $pass == md5($_POST['pass']))
    {
        $_SESSION['admin'] = $users;
        header("Location: admin_index.php");
        exit();
    }
    else echo '<p>Логин или пароль неверны!</p>';
} 
?>  
<html>
<head>
	<title>Test</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="seylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <form method="post">
    <input type="text" name="user" class="login_input" placeholder="User"/> Username <br /> 
    <input type="password" name="pass" placeholder="Password" class="login_input"/> Password <br />
    <input type="submit" name="submit" value="Login" />
    </form> 
</body>
</html>