<?php
function addUsers($comp_id, $stu_name, $phone_number, $passwd)
{
	global $db; 
	$query = "INSERT INTO users
          SET comp_id = :comp_id,
              stu_name = :stu_name,
              phone_number = :phone_number,
              passwd = :passwd";
	try {
		$statement = $db->prepare($query);
        $statement->bindValue(':comp_id', $comp_id); 
        $statement->bindValue(':stu_name', $stu_name); 
        $statement->bindValue(':phone_number', $phone_number); 
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

function verifyLogin($comp_id, $passwd)
{
	global $db;
	$query = "SELECT * FROM users 
              WHERE comp_id = :comp_id 
              AND passwd = :passwd";

    $statement = $db->prepare($query);
    $statement->bindValue(':comp_id', $comp_id);
    $statement->bindValue(':passwd', $passwd);
    $statement->execute();

    // Fetch user if exists
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $result;
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
