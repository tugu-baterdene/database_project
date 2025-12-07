<?php
function addUsers($comp_id, $stu_name, $phone_number, $passwd)
{
	global $db; 
	$query = "INSERT INTO users
          SET comp_id = :comp_id,
              stu_name = :stu_name,
              phone_number = :phone_number,
              passwd = :passwd";
    // $hashed = password_hash($passwd, PASSWORD_DEFAULT);

	try {
		$statement = $db->prepare($query);
        $statement->bindValue(':comp_id', $comp_id); 
        $statement->bindValue(':stu_name', $stu_name); 
        $statement->bindValue(':phone_number', $phone_number); 
        $statement->bindValue(':passwd', $hashed);
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

function addPref($comp_id)
{
	global $db; 
	$query = "INSERT INTO preferences
          SET comp_id = :comp_id";
	try {
		$statement = $db->prepare($query);
        $statement->bindValue(':comp_id', $comp_id); 
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

/*
function getPasswd($comp_id)
{
    global $db;
    $query = "SELECT passwd FROM users WHERE comp_id = :comp_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':comp_id', $comp_id);
    $statement->execute();
    $passwd = $statement->fetch(PDO::FETCH_ASSOC); // fetch associative array
    $statement->closeCursor();
    return $passwd; // return the string or false if not found
}
	*/

function getPasswd($comp_id)
{
    global $db;
    $query = "SELECT passwd FROM users WHERE comp_id = :comp_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':comp_id', $comp_id);
    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC); // fetch associative array
    $statement->closeCursor();

    return $row ? $row['passwd'] : false; // return just the password string or false if not found
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
