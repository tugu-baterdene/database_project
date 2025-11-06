<?php 
require('connect-db.php');
require('sign_up-db.php');

$list_of_requests = getAllRequests();
// var_dump($list_of_requests);
?> 

<?php // command center
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (!empty($_POST['addBtn'])) 
	{
		addRequests($_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']);
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
            Requested date:
            <input type='text' class='form-control' 
                   id='requestedDate' name='requestedDate' 
                   placeholder='Format: yyyy-mm-dd' 
                   pattern="\d{4}-\d{1,2}-\d{1,2}" 
                   value="<?php if ($request_to_update !=null) echo $request_to_update['reqDate']; ?>" />
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Room Number:
            <input type='text' class='form-control' id='roomNo' name='roomNo' 
                   value="<?php if ($request_to_update !=null) echo $request_to_update['roomNumber']; ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested by: 
            <input type='text' class='form-control' id='requestedBy' name='requestedBy'
                   placeholder='Enter your name'
                   value="<?php if ($request_to_update !=null) echo $request_to_update['reqBy']; ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class="mb-3">
            Description of work/repair:
            <input type='text' class='form-control' id='requestDesc' name='requestDesc'
                   value="<?php if ($request_to_update !=null) echo $request_to_update['repairDesc']; ?>" />
        </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested Priority:
            <select class='form-select' id='priority_option' name='priority_option'>
              <option selected></option>
              <option value='high'
				<?php if ($request_to_update !=null && $request_to_update['reqPriority'] == 'high') 
					echo ' selected="selected"'?> 
				>
                High - Must be done within 24 hours</option>
              <option value='medium'
				<?php if ($request_to_update !=null && $request_to_update['reqPriority'] == 'medium') 
                    echo ' selected="selected"'?> 
 				>
                Medium - Within a week</option>
              <option value='low' 
				<?php if ($request_to_update !=null && $request_to_update['reqPriority'] == 'low') 
                    echo ' selected="selected"'?> 
				>
                Low - When you get a chance</option>
            </select>
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Submit a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid ">
      <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
           title="Update a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid">
        <input type="reset" value="Clear form" name="clearBtn" id="clearBtn" class="btn btn-secondary" />
      </div>      
    </div>  
    <div>
  </div>  

  <input type="hidden" name="reqId" value="<?php echo $_POST['reqId']; ?>" />
  </form>

</div>


<hr/>
<div class="container">
<h3>List of requests</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
  <tr style="background-color:#B0B0B0">
    <th width="30%"><b>ReqID</b></th>
    <th width="30%"><b>Date</b></th>        
    <th width="30%"><b>Room#</b></th> 
    <th width="30%"><b>By</b></th>
    <th width="30%"><b>Description</b></th>        
    <th width="30%"><b>Priority</b></th> 
    <th><b>Update?</b></th>
    <th><b>Delete?</b></th>
  </tr>
  </thead>
	<?php foreach ($list_of_requests as $req_info): ?>
  <tr>
	<td><?php echo $req_info['reqId']; ?> </td>
	<td><?php echo $req_info['reqDate']; ?> </td>
	<td><?php echo $req_info['roomNumber']; ?> </td>
	<td><?php echo $req_info['reqBy']; ?> </td>
	<td><?php echo $req_info['repairDesc']; ?> </td>
	<td><?php echo $req_info['reqPriority']; ?> </td>
	<td>
		<form action="request.php" method="post">
			<input type="submit" value="Update"
                 name="updateBtn" class="btn btn-primary"
                 title="Click to update the table"
            /> <!-- class is optional, only if you want to make your button more fancy and title is optional (will show message when cursor is moved above button) -->
            <input type="hidden" name="reqId"
                 value="<?php echo $req_info['reqId']; ?>"
            />
        </form>
	</td>
	<td>
		<form action="request.php" method="post"> <!-- more safe than "get"-->
			<input type="submit" value="Delete"
				name="deleteBtn" class="btn btn-danger"
				title="Click to delete this request"
			/> <!-- class is optional, only if you want to make your button more fancy and title is optional (will show message when cursor is moved above button) -->
			<input type="hidden" name="reqId"
				value="<?php echo $req_info['reqId']; ?>"
			/>
  		</form>
	</td>
  </tr>
  <?php endforeach; ?>
</table>
</div>   


<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>