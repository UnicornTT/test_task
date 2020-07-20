<?php

$db = mysqli_connect("localhost", "root", "root", "todo");
function defender_xss($arr)
{
    $filter = array("<", ">"); 
     foreach($arr as $num=>$xss)
     {
        $arr[$num]=str_replace ($filter, "|", $xss);
     }
       return $arr;
}
if(isset($_GET['edit']))
{
    $id = defender_xss($_GET['edit']);
}

if(isset($_POST['submit']))
{
    if(empty($_POST['task']))
    {
        $task_err = "You must fill in the task";
    }
    else
    {   
        $task = defender_xss($_POST['task']);
        mysqli_query($db, 'UPDATE tasks SET task = "'. $task .'" WHERE tasks.id ='. $id);
        mysqli_query($db, 'UPDATE tasks SET edited = "1" WHERE tasks.id ='. $id);
        if ( isset($_POST['status']) == true )
        {
            mysqli_query($db, 'UPDATE tasks SET done = "1" WHERE tasks.id ='. $id);
        }
        else
        {
            mysqli_query($db, 'UPDATE tasks SET done = "0" WHERE tasks.id ='. $id);
        }
        header('location: admin_index.php');
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="seylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <form method="post" action="function.php?edit=<?php echo $id ?>" class="input_form">
    <?php if (isset($task_err)) { ?>
        <p><?php echo $task_err; ?></p>
    <?php } ?>
		<input type="text" name="task" class="task_input" placeholder="Task">
        <input type="checkbox" name="status"/>
		<button type="submit" name="submit" id="add_edit_btn" class="add_btn">Save</button>
    </form>


</body>
</html>