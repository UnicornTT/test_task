<?php 

    session_start();
    $errors = "";
    function defender_xss($arr){
        $filter = array("<", ">"); 
         foreach($arr as $num=>$xss){
            $arr[$num]=str_replace ($filter, "|", $xss);
         }
           return $arr;
    }

    if($_SESSION['admin'] == "admin")
    {
        header("Location: admin_index.php");    
        exit(); 
    }

	$db = mysqli_connect("localhost", "root", "root", "todo");

	if (isset($_POST['submit'])) {
        if(empty($_POST['name'])){
            $task_err = "You must fill in the name";
        }
        elseif(empty($_POST['e-mail'])){
            $task_err = "You must fill in the e-mail";
        }
        elseif(!filter_var($_POST['e-mail'], FILTER_VALIDATE_EMAIL)){
            $task_err = "Invalid e-mail";
        }
        elseif (empty($_POST['task'])) {
			$task_err = "You must fill in the task";
        }
        else{
            $name = defender_xss($_POST['name']);
            $email = defender_xss($_POST['e-mail']);
			$task = defender_xss($_POST['task']);
			$sql = "INSERT INTO `tasks` (`id`, `name`, `email`, `task`) VALUES (NULL, '$name', '$email', '$task');";
            mysqli_query($db, $sql);
			header('location: index.php');
		}
    }
    if (isset($_GET['del_task'])) 
    {
        $id = $_GET['del_task'];
    
        mysqli_query($db, "DELETE FROM tasks WHERE id=".$id);
        header('location: index.php?page=' . $_GET['page'] . '&sort=' . $_GET['sort']);
    }

    if (isset($_GET['sort']))
    {
        $sort = $_GET['sort'];
    }
    elseif(isset($_POST['sort']))
    {
        $sort = $_POST['sorted'];
    }
    else
    {
        $sort = id;
    }

    $mysqli = mysqli_connect('localhost', 'root', 'root', 'todo');

    $total_pages = $mysqli->query('SELECT COUNT(*) FROM tasks')->fetch_row()[0];

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

    $num_results_on_page = 3;

    if ($stmt = $mysqli->prepare('SELECT * FROM tasks ORDER BY '. $sort . ' LIMIT ?,?')) {
        $calc_page = ($page - 1) * $num_results_on_page;
        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
        $stmt->execute(); 
        $result = $stmt->get_result();
        $stmt->close();
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
    <a href="login.php">Login</a> 
    <form method="post" action="index.php" class="input_form">
    <?php if (isset($task_err)) { ?>
        <p><?php echo $task_err; ?></p>
    <?php } ?>
        <input type="text" name="name" class="task_input" placeholder="Name" />
        <input type="text" name="e-mail" class="task_input" placeholder="E-mail" />
		<input type="text" name="task" class="task_input" placeholder="Task">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
    </form>
    <form method="post" action="index.php" class="sort_form">
        <label for="tasks">Order by:</label>
        <select name="sorted">
            <option value=""></option>
            <option value="name">Name↓</option>
            <option value="name DESC">Name↑</option>
            <option value="email">E-mail↓</option>
            <option value="email DESC">E-mail↑</option>
            <option value="done">Status↓</option>
            <option value="done DESC">Status↑</option>
        </select>
        <button type="submit" name="sort">Sort</button>
    </form>
    <table>
	<thead>
		<tr>
            <th>Name</th>
            <th>E-mail</th>
			<th>Tasks</th>
			<th style="width: 60px;">Status</th>
		</tr>
	</thead>

	<tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
	<tr>
		<td><?php echo $row['name']; ?></td>
		<td><?php echo $row['email']; ?></td>
        <td><?php echo $row['task']; ?></td>
        <td class="delete"> <input type='checkbox' disabled <?php if($row['done'] == true):?> checked<?php endif ?>/></td>
	</tr>
	<?php endwhile; ?>	
	</tbody>
    </table>
    <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
<ul class="pagination">
	<?php if ($page > 1): ?>
	<li class="prev"><a href="index.php?page=<?php echo $page-1 ?>&sort=<?php echo $sort?>">Prev</a></li>
	<?php endif; ?>

	<?php if ($page > 3): ?>
	<li class="start"><a href="index.php?page=1&sort=<?php echo $sort?>">1</a></li>
	<li class="dots">...</li>
	<?php endif; ?>

	<?php if ($page-2 > 0): ?><li class="page"><a href="index.php?page=<?php echo $page-2 ?>&sort=<?php echo $sort?>"><?php echo $page-2 ?></a></li><?php endif; ?>
	<?php if ($page-1 > 0): ?><li class="page"><a href="index.php?page=<?php echo $page-1 ?>&sort=<?php echo $sort?>"><?php echo $page-1 ?></a></li><?php endif; ?>

	<li class="currentpage"><a href="index.php?page=<?php echo $page ?>&sort=<?php echo $sort?>"><?php echo $page ?></a></li>

	<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="index.php?page=<?php echo $page+1 ?>&sort=<?php echo $sort?>"><?php echo $page+1 ?></a></li><?php endif; ?>
	<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="index.php?page=<?php echo $page+2 ?>&sort=<?php echo $sort?>"><?php echo $page+2 ?></a></li><?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
	<li class="dots">...</li>
	<li class="end"><a href="index.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>&sort=<?php echo $sort?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
	<?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
	<li class="next"><a href="index.php?page=<?php echo $page+1 ?>&sort=<?php echo $sort?>">Next</a></li>
	<?php endif; ?>
</ul>
<?php endif; ?>

</body>
</html>