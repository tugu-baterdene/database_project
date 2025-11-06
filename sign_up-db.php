<?php
function addUsers($computing_id, $email, $first_name, $last_name, $passwd)
{
	global $db;
	$query = "INSERT INTO sign_up SET computing_id=:computing_id, email=:email, first_name=:first_name, last_name=:last_name, passwd=:passwd";
	try {
    	$statement = $db->prepare($query);
		$statement->bindValue(':computing_id', $computing_id); 
		$statement->bindValue(':email', $email); 
		$statement->bindValue(':first_name', $first_name); 
		$statement->bindValue(':last_name', $last_name);
		$statement->bindValue(':passwd', $passwd);
    	$statement->execute();
    	$statement->closeCursor();
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

}
?>