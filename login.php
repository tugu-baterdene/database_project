<?php 
require_once('connect-db.php');
require_once('sign_up-db.php');
$error = '';
?> 

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hashedPasswd = getPasswd($_POST['comp_id']);
    if (password_verify($_POST['passwd'], $hashedPasswd)) {
        session_start(); 
		$_SESSION['user_id'] = $_POST['comp_id'];
		header("Location: profile.php");
		exit();
    } else {
        echo("Invalid Password, Try Again");
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
            <a href="sign_up.php">Sign Up</a> 
        </p> 

        <form method="post" class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">

            <label>Computing ID</label> 
            <input type='text' 
                id='comp_id' name='comp_id' required/>
            
            <label>Password</label> 
            <input type='password' class='form-control' id='passwd' name='passwd' required/>

            <div class="login-button"> 
                <input type="submit" value="LOG IN" id="login" name="login" />    
            </div>
        </form> 
    </div>
</body>
</html>