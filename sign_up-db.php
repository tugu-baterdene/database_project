<?php
function addUsers($comp_id, $stu_name, $phone_number, $passwd, $school_year, $major, $bio)
{
	global $db;
	$query = "INSERT INTO sign_up SET comp_id=:comp_id, stu_name=:stu_name, phone_number=:phone_number, passwd=:passwd, school_year=:school_year, major=:major, bio=:bio";
	try {
    	$statement = $db->prepare($query);
		$statement->bindValue(':comp_id', $comp_id); 
		$statement->bindValue(':name', $name); 
		$statement->bindValue(':phone_number', $phone_number); 
		$statement->bindValue(':passwd', $passwd);
		$statement->bindValue(':school_year', $school_year);
		$statement->bindValue(':major', $major);
		$statement->bindValue(':bio', $bio);
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