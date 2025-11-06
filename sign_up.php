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