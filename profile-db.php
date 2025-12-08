<?php
function fetchUser($user_id)
{
    $db = getDB();
    $query = "SELECT comp_id, stu_name, phone_number, passwd, school_year, major, bio, status 
              FROM users 
              WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $user;
}

function fetchPref($user_id)
{
    $db = getDB();
    $query = "SELECT * FROM preferences WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $pref = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $pref;
}

function fetchGroup($user_id)
{
    $db = getDB();
    $query = "SELECT g_id FROM part_of WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();

    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $group;
}

function fetchNumGroupmates($g_id)
{
    $db = getDB();
    $query = "SELECT COUNT(*) FROM part_of WHERE g_id = :g_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':g_id', $g_id);
    $stmt->execute();

    $group_num = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $group_num;
}

function fetchGroupMax($g_id)
{
    $db = getDB(); 

    // FIXED: Your WHERE used :user_id but you bound :g_id.
    $query = "
        SELECT g.num_of_people
        FROM part_of p 
        JOIN groups g ON p.g_id = g.g_id
        WHERE p.g_id = :g_id
    ";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':g_id', $g_id);
    $stmt->execute();

    $group_max = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $group_max;
}

function fetchLocation($user_id)
{
    $db = getDB(); 

    $query = "
        SELECT l.addr, l.bedroom, l.bathroom, l.on_off_grounds, l.price, l.extra_cost
        FROM part_of p
        JOIN groups g ON p.g_id = g.g_id
        JOIN location l ON g.addr = l.addr
        WHERE p.comp_id = :user_id
    ";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();

    $location = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $location;
}

function fetchStatus($user_id)
{
    $db = getDB(); 
    $query = "SELECT g_id, status 
              FROM users 
              NATURAL JOIN part_of 
              NATURAL JOIN groups 
              WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $status = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $status;
}

function getRequestById($user_id)  
{
    $db = getDB();
    $query = "SELECT * FROM users WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $results;
}

function getPrefById($user_id)  
{
    $db = getDB(); 
    $query = "SELECT * FROM preferences WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $results;
}

function updateUser($user_id, $stu_name, $phone_number, $school_year, $major, $bio)
{
    $db = getDB();
    $query = "UPDATE users 
              SET stu_name = :stu_name, 
                  phone_number = :phone_number, 
                  school_year = :school_year, 
                  major = :major, 
                  bio = :bio
              WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':stu_name', $stu_name);
    $stmt->bindValue(':phone_number', $phone_number);
    $stmt->bindValue(':school_year', $school_year);
    $stmt->bindValue(':major', $major);
    $stmt->bindValue(':bio', $bio);
    $stmt->execute();
    $stmt->closeCursor();
}

function updateLogin($user_id, $passwd)
{
    $db = getDB(); 
    $query = "UPDATE users 
              SET passwd = :passwd
              WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':passwd', $passwd);
    $stmt->execute();
    $stmt->closeCursor();
}

function updatePref($user_id, $on_off, $sleep, $num_roommates, $drinking, $smoking, $pets, $budget)
{
    $db = getDB();
    $query = "UPDATE preferences
              SET on_off_grounds = :on_off,
                  sleeping = :sleep,
                  num_of_roommates = :num_roommates,
                  drinking = :drinking,
                  smoking = :smoking,
                  pets = :pets,
                  budget = :budget
              WHERE comp_id = :user_id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':on_off', $on_off);
    $stmt->bindValue(':sleep', $sleep);
    $stmt->bindValue(':num_roommates', $num_roommates);
    $stmt->bindValue(':drinking', $drinking);
    $stmt->bindValue(':smoking', $smoking);
    $stmt->bindValue(':pets', $pets);
    $stmt->bindValue(':budget', $budget);
    $stmt->execute();
    $stmt->closeCursor();
}

function addToGroup($comp_id, $g_id) 
{
    $db = getDB(); 
    $query = "INSERT INTO part_of
              SET comp_id = :comp_id,
                  g_id = :g_id";

    try {
        $stmt = $db->prepare($query);
        $stmt->bindValue(':comp_id', $comp_id);
        $stmt->bindValue(':g_id', $g_id);
        $stmt->execute();
        $stmt->closeCursor();
    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
