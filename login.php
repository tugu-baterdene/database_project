<?php 
require('connect-db.php');
require('sign_up-db.php');

$request_to_update = null;
?> 

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 	
{
	$user = verifyLogin($_POST['comp_id'], $_POST['passwd']);

		if ($user) {
			session_start(); // starts session as user and stores it
			$_SESSION['user_id'] = $_POST['comp_id'];
			echo "Welcome, " . $_SESSION['user_id'] . "!";
			header("Location: search.php");
			exit();
		} else {
			echo "Invalid ID or password.";
		}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roommate Connections – Login</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>  
  <div class="header">Roommate Connections</div>

  <div class="login-wrapper"> 
		<h1 class="login-title">LOG IN</h1> 
		<p class="signup-line"> Don't have an account? 
			<a href="sign_up.php">Sign In</a> 
		</p> 

  		<form method="post" class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">

			<label>Computing ID</label> 
			<input type='text' 
				id='comp_id' name='comp_id' required/>
			
			<label>Password</label> 
			<input type='password' class='form-control' id='passwd' name='passwd'
				value="<?php if ($request_to_update !=null) echo $request_to_update['passwd']; ?>" />

			<div class="login-button"> 
				<input type="submit" value="LOG IN" id="login" name="login" />    
			</div>
		</form> 
	</div>
</body>
</html>