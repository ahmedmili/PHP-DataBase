<?php 
    // initialize variable
	$errors = ""; //  information message
	// initialisation of data
	$Name ="";
	$last_name = "";
	$CINN = "";
	$agee = "";

	$update = false;
	// server information
    $hostName = "localhost";
    $password = "";
    $username = "root" ;
    $data_name = "mydata";

	// connect to database
	$db = mysqli_connect($hostName , $username, $password , $data_name);

	// insert a quote if submit button is clicked
	if (isset($_POST['save'])) {
		// verification no empety values
		if (empty($_POST['fName'])) {
			$errors = "You must fill in the first name";
		}else if (empty($_POST['lName'])) {
			$errors = "You must fill in the last name";

        }else if (empty($_POST['age'])) {
			$errors = "You must fill in the age";
        }else if (empty($_POST['CIN'])) {
			$errors = "You must fill in the carte CIN";
        }
        
        else{ 
			// read date from inputes
			$fName = $_POST['fName'];
            $lName = $_POST['lName'];
            $age = $_POST['age'];
            $CIN = $_POST['CIN'];
				// insertion
				$sql = "INSERT INTO eleve(Fname,Lname,CIN,age) VALUES ('$fName','$lName','$CIN','$age' ) ";
				mysqli_query($db, $sql);
				header('location: index.php');
		}
		
	}


    // delete part
	if (isset($_GET['del_task'])) {
		$id = $_GET['del_task'];
		mysqli_query($db, "DELETE FROM eleve WHERE CIN=".$id);
		header('location: index.php');
	}


	// edit part
	if (isset($_GET['edit_task'])) {
		$id = $_GET['edit_task'];
		// select data frome database
		 $result = mysqli_query($db, "select * from eleve where CIN =".$id);
		// print data into the inputs
		$row = mysqli_fetch_array($result);
		$Name = $row["Fname"];
		$last_name = $row["Lname"];
		$CINN = $row["CIN"];
		$agee = $row["age"]; 
		$update = true; // necessairy to change button and thier function
			
	}

	if(isset($_POST["update"])){

		$fName = $_POST['fName'];
		$lName = $_POST['lName'];
		$age = $_POST['age'];
		$CIN= $_POST['CIN'];

		$update_data = "UPDATE eleve
		SET Fname='$fName',
			Lname='$lName',
			age = '$age'
		WHERE CIN=$CIN  ";
		mysqli_query($db,$update_data);
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>formulaire DB</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<div class="heading">
		<h2 style="font-style: 'Hervetica';">Gestionnair MySQL database</h2>
	</div>
	<form method="post" action="index.php" class="input_form">
		<!-- print notification -->
		<?php if (isset($errors)) { ?>
			<p><?php echo $errors; ?></p>
		<?php }; ?>
		<!-- form creation -->
		<input type="text"  class="task_input" name="fName" placeholder="first name" value="<?php echo $Name ;?>">
		<input type="text"  class="task_input" name="lName" placeholder="last name" value="<?php echo $last_name; ?>">
		<input type="text"  class="task_input" name="CIN" placeholder="CIN" value="<?php echo $CINN ;?>">
		<input type="text"  class="task_input" name="age" placeholder="age" value="<?php echo $agee ;?>" >
		<!-- creation and change button name  -->
		<?php if($update == false){ 	?>
		<button type="submit" name="save" class="add_btn" >Add</button>
		<?php } else{?>
			<button type="submit" name="update">update</button>
			<?php }?>
	</form>
<!-- table creation -->
<table>
	<thead>
		<tr>
			<th>N</th>
			<th>First Name</th>
			<th>Laset Name</th>
			<th> CIN</th>
			<th>Age</th>
		</tr>
	</thead>

	<tbody>
	<?php 
		// select all tasks if page is visited or refreshed
		$tasks = mysqli_query($db, "SELECT * FROM eleve");
		$i = 1;
         while ($row = mysqli_fetch_array($tasks)) { 
		?>
		<!-- insertion of data into HTML table -->
			<tr>
				<td class="task">> <?php echo $i; ?> </td>
				<td> <?php echo $row['Fname']; ?> </td>
                <td> <?php echo $row['Lname']; ?> </td>
                <td> <?php echo $row['CIN'] ?> </td>
                <td> <?php echo $row['age'] ?> </td>
                
				<td> 
				    <a class="delete" href="index.php?del_task=<?php echo $row['CIN']?>">Delete</a>  <!-- send data into link -->
				</td> 
				<td> 
				    <a href="index.php?edit_task=<?php echo $row['CIN']?>">edite</a> <!-- send data into link -->
				</td> 
			</tr>
		<?php $i++;  }?>	
	</tbody>
</table>

</body>
</html>