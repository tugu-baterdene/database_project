<?php
function addUsers($comp_id, $stu_name, $phone_number, $passwd, $school_year, $major, $bio)
{
	global $db; 
	$query = "INSERT INTO users
          SET comp_id = :comp_id,
              stu_name = :stu_name,
              phone_number = :phone_number,
              passwd = :passwd,
              school_year = :school_year,
              major = :major,
              bio = :bio";
	try {
		$statement = $db->prepare($query);
        $statement->bindValue(':comp_id', $comp_id); 
        $statement->bindValue(':stu_name', $stu_name); 
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

function getAllUsers()
{
    global $db;
	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();
	$results = $statement->fetchAll(); // fetch() gets only onw row; fetchAll() gets all rows
	$statement->closeCursor();
	return $results;
}
?>
