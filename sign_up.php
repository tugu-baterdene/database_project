<?php 
require('connect-db.php');
require('sign_up-db.php');

$request_to_update = null;
?> 

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 	
{
	if (!empty($_POST['addBtn'])) 
	{
		addUsers($_POST['comp_id'], $_POST['stu_name'], $_POST['phone_number'], $_POST['passwd']);
		header("Location: profile.php");
		exit();
	}
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Roommate Connections – Sign In</title>
    <link rel="stylesheet" href="sign_up.css">
</head>

<body>  
  <div class="header">Roommate Connections</div>

  <div class="signin-wrapper"> 
		<h1 class="signin-title">SIGN IN</h1> 
		<p class="login-line"> Have an account? 
			<a href="login.php">Log in</a> 
		</p> 

  		<form method="post" class="signin-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">

			<label>Computing ID</label> 
			<input type='text' 
				id='comp_id' name='comp_id' required/>
			
			<label>Password</label> 
			<input type='password' class='form-control' id='passwd' name='passwd'
				value="<?php if ($request_to_update !=null) echo $request_to_update['passwd']; ?>" />

			<label>First and Last Name</label> 
			<input type='text' class='form-control' id='stu_name' name='stu_name' 
				value="<?php if ($request_to_update !=null) echo $request_to_update['stu_name']; ?>" />

			<label>Phone Number</label> 
			<input type='text' class='form-control' id='phone_number' name='phone_number'
				value="<?php if ($request_to_update !=null) echo $request_to_update['phone_number']; ?>" />

			<div class="signin-button"> 
				<input type="submit" value="CREATE ACCOUNT" id="addBtn" name="addBtn" title="Add a User" />    
			</div>
		</form> 
	</div>
</body>
</html>