<?php
function fetchUser($user_id)
{
	global $db;
	$query = "SELECT comp_id, stu_name, phone_number, passwd, school_year, major, bio FROM users WHERE comp_id = :user_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();

	$user = $stmt->fetch(PDO::FETCH_ASSOC); // $user now contains user's info
	return $user;
}

function getRequestById($user_id)  
{
	global $db;
    $query = "SELECT * FROM users WHERE comp_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id); // $reqId is from the parameter --> more safe
    $statement->execute();
	$results = $statement->fetch();
    $statement->closeCursor();
	
	return $results;

}

function updateUser($user_id, $stu_name, $phone_number, $school_year, $major, $bio)
{
	global $db; 
	$query = "UPDATE users 
			SET stu_name =:stu_name, 
				phone_number=:phone_number, 
				school_year=:school_year, 
				major=:major, 
				bio=:bio
				WHERE comp_id =:user_id";
    $statement = $db->prepare($query);
	$statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':stu_name', $stu_name); 
    $statement->bindValue(':phone_number', $phone_number); 
	$statement->bindValue(':school_year', $school_year); 
	$statement->bindValue(':major', $major); 
	$statement->bindValue(':bio', $bio); 
    $statement->execute();
    $statement->closeCursor();
}

function updateLogin($user_id, $stu_name, $phone_number, $passwd, $school_year, $major, $bio)
{
	global $db; 
	$query = "UPDATE users 
			SET stu_name =:stu_name, 
				phone_number=:phone_number, 
				passwd=:passwd,
				school_year=:school_year, 
				major=:major, 
				bio=:bio
				WHERE comp_id =:user_id";
    $statement = $db->prepare($query);
	$statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':stu_name', $stu_name); 
    $statement->bindValue(':phone_number', $phone_number); 
	$statement->bindValue(':passwd', $passwd);
	$statement->bindValue(':school_year', $school_year); 
	$statement->bindValue(':major', $major); 
	$statement->bindValue(':bio', $bio); 
    $statement->execute();
    $statement->closeCursor();
}

function updatePref()
{
    global $db;
	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();
	$results = $statement->fetchAll(); // fetch() gets only onw row; fetchAll() gets all rows
	$statement->closeCursor();
	return $results;
}

function getAllUsers()
{
    global $db;
	$query = "SELECT * FROM users";
	$statement = $db3->prepare($query);
	$statement->execute();
	$results = $statement->fetchAll(); // fetch() gets only onw row; fetchAll() gets all rows
	$statement->closeCursor();
	return $results;
}
?>
