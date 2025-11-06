<?php
function addUsers($comp_id, $phone_number, $name, $passwd)
{
	global $db;
	$query = "INSERT INTO sign_up SET comp_id=:comp_id, phone_number=:phone_number, name=:name, passwd=:passwd";
	try {
    	$statement = $db->prepare($query);
		$statement->bindValue(':comp_id', $comp_id); 
		$statement->bindValue(':phone_number', $phone_number); 
		$statement->bindValue(':name', $name); 
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