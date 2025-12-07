<?php
function fetchUser($user_id)
{
	global $db;
	$query = "SELECT comp_id, stu_name, phone_number, passwd, school_year, major, status FROM users WHERE comp_id = :user_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();

	$user = $stmt->fetch(PDO::FETCH_ASSOC); // $user now contains user's info
	return $user;
}

function fetchPref($user_id)
{
	global $db;
	$query = "SELECT * FROM preferences WHERE comp_id = :user_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();

	$pref = $stmt->fetch(PDO::FETCH_ASSOC);
	return $pref;
}

function fetchLocation($user_id)
{
	// CHANGE SQL TO BE LOCATION, not LOCATIONS
	global $db;
	$query = "SELECT addr, bedroom, bathroom, on_off_grounds, price, extra_cost FROM users NATURAL JOIN part_of NATURAL JOIN groups NATURAL JOIN location WHERE comp_id = :user_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();

	$location = $stmt->fetch(PDO::FETCH_ASSOC);
	return $location;
}

function fetchStatus($user_id)
{
	// CHANGE SQL TO BE LOCATION, not LOCATIONS
	global $db;
	$query = "SELECT g_id, status FROM users NATURAL JOIN part_of NATURAL JOIN groups WHERE comp_id = :user_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();

	$status = $stmt->fetch(PDO::FETCH_ASSOC);
	return $status;
}

function getRequestById($user_id)  
{
	global $db;
    $query = "SELECT * FROM users WHERE comp_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
	$results = $statement->fetch();
    $statement->closeCursor();
	
	return $results;
}

function getPrefById($user_id)  
{
	global $db;
    $query = "SELECT * FROM preferences WHERE comp_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
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

function updateLogin($user_id, $passwd)
{
	global $db; 
	$query = "UPDATE users 
			SET passwd=:passwd
			WHERE comp_id =:user_id";
    $statement = $db->prepare($query);
	$statement->bindValue(':user_id', $user_id);
	$statement->bindValue(':passwd', $passwd);
    $statement->execute();
    $statement->closeCursor();
}

function updatePref($user_id, $on_off, $sleep, $num_roommates, $drinking, $smoking, $pets, $budget)
{
    global $db; 
	$query = "UPDATE preferences 
			SET on_off_grounds=:on_off, 
				sleeping=:sleep, 
				num_of_roommates=:num_roommates, 
				drinking=:drinking, 
				smoking=:smoking,
				pets=:pets,
				budget=:budget
				WHERE comp_id =:user_id";
    $statement = $db->prepare($query);
	$statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':on_off', $on_off); 
    $statement->bindValue(':sleep', $sleep); 
	$statement->bindValue(':num_roommates', $num_roommates); 
	$statement->bindValue(':drinking', $drinking); 
	$statement->bindValue(':smoking', $smoking); 
	$statement->bindValue(':pets', $pets); 
	$statement->bindValue(':budget', $budget); 
    $statement->execute();
    $statement->closeCursor();
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


function fetchLocation($user_id)
{
    global $db;

    
    $query = "SELECT * FROM location WHERE comp_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$location) {
        return null;
    }

    $addr = $location['addr'];

    
    $query = "SELECT * FROM apartment WHERE addr = :addr";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':addr', $addr);
    $stmt->execute();
    $apartment = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($apartment) {
        $location['type'] = 'apartment';
        $location['details'] = $apartment;
        return $location;
    }

    
    $query = "SELECT * FROM house WHERE addr = :addr";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':addr', $addr);
    $stmt->execute();
    $house = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($house) {
        $location['type'] = 'house';
        $location['details'] = $house;
        return $location;
    }

    
    $query = "SELECT * FROM dorm WHERE addr = :addr";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':addr', $addr);
    $stmt->execute();
    $dorm = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($dorm) {
        $location['type'] = 'dorm';
        $location['details'] = $dorm;
        return $location;
    }

    
    $location['type'] = 'unknown';
    return $location;
}


?>
