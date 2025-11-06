<?php 
require('connect-db.php');
require('sign_up-db.php');

$list_of_requests = getAllRequests();
// var_dump($list_of_requests);
$request_to_update = null;
?> 

<?php // command center
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (!empty($_POST['addBtn'])) 
	{
		addUser($_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']);
		$list_of_requests = getAllRequests();
	}
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Tugu, Grace, Kundana, Santi">
  <meta name="description" content="Roommate Connection application for UVA students">
  <meta name="keywords" content="Roommates, UVA, Students">
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  
  
  <title>Roommate Connection</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="sign_up.css">  
</head>

<body>  
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Roommate Connection Sign Up</h2>
    </div>  
  </div>
  
  <!---------------->
<body>
	<?php include('header.php') ?>

  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Computing ID:
            <input type='text' class='form-control' 
                   id='comp_id' name='comp_id' 
                   pattern="\d{4}-\d{1,2}-\d{1,2}"
				   />
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Phone Number:
            <input type='text' class='form-control' id='phone_number' name='phone_number' 
				   pattern="\d{10}"
                   value="<?php if ($request_to_update !=null) echo $request_to_update['phone_number']; ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Name: 
            <input type='text' class='form-control' id='name' name='name'
                   value="<?php if ($request_to_update !=null) echo $request_to_update['name']; ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class="mb-3">
            Password:
            <input type='text' class='form-control' id='passwd' name='passwd'
                   value="<?php if ($request_to_update !=null) echo $request_to_update['passwd']; ?>" />
        </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Add a user" />                  
      </div>	    
    </div>  
    <div>
  </div>  

  <input type="hidden" name="comp_id" value="<?php echo $_POST['comp_id']; ?>" />
  </form>

</div>

<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>