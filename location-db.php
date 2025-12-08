<?php

function fetchGroup($user_id)
{
    $db = getDB(); 
    $query = "SELECT g_id FROM users NATURAL JOIN groups WHERE comp_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();

    $group = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $group;
}

function fetchLandlord($name, $contact)
{
    $db = getDB(); 
    $query = "SELECT l_id FROM landlords WHERE name = :name AND contact = :contact";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':contact', $contact);
    $statement->execute();

    $location = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $location;
}

function addLocation($addr, $bedroom, $bathroom, $on_off_grounds, $price, $extra_cost)
{
    $db = getDB(); 
    $query = "INSERT INTO location
              SET addr = :addr,
                  bedroom = :bedroom,
                  bathroom = :bathroom,
                  on_off_grounds = :on_off_grounds,
                  price = :price,
                  extra_cost = :extra_cost";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':addr', $addr);
        $statement->bindValue(':bedroom', $bedroom);
        $statement->bindValue(':bathroom', $bathroom);
        $statement->bindValue(':on_off_grounds', $on_off_grounds);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':extra_cost', $extra_cost);
        $statement->execute();
        $statement->closeCursor();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function addApt($addr, $elevator, $num_of_floors, $balcony, $pets, $smoking)
{
    $db = getDB(); 
    $query = "INSERT INTO apartment
              SET addr = :addr,
                  elevator = :elevator,
                  num_of_floors = :num_of_floors,
                  balcony = :balcony,
                  pets = :pets,
                  smoking = :smoking";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':addr', $addr);
        $statement->bindValue(':elevator', $elevator);
        $statement->bindValue(':num_of_floors', $num_of_floors);
        $statement->bindValue(':balcony', $balcony);
        $statement->bindValue(':pets', $pets);
        $statement->bindValue(':smoking', $smoking);
        $statement->execute();
        $statement->closeCursor();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function addHouse($addr, $yard, $stories, $porch)
{
    $db = getDB(); 
    $query = "INSERT INTO house
              SET addr = :addr,
                  yard = :yard,
                  stories = :stories,
                  porch = :porch";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':addr', $addr);
        $statement->bindValue(':yard', $yard);
        $statement->bindValue(':stories', $stories);
        $statement->bindValue(':porch', $porch);
        $statement->execute();
        $statement->closeCursor();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function addDorm($addr, $style, $single_double, $kitchen)
{
    $db = getDB(); 
    $query = "INSERT INTO dorm
              SET addr = :addr,
                  style = :style,
                  single_double = :single_double,
                  kitchen = :kitchen";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':addr', $addr);
        $statement->bindValue(':style', $style);
        $statement->bindValue(':single_double', $single_double);
        $statement->bindValue(':kitchen', $kitchen);
        $statement->execute();
        $statement->closeCursor();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function addLandlord($name, $contact) 
{
    $db = getDB(); 
    $query = "INSERT INTO landlords
              SET name = :name,
                  contact = :contact";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':contact', $contact);
        $statement->execute();
        $statement->closeCursor();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function addOwns($l_id, $addr) 
{
    $db = getDB(); 
    $query = "INSERT INTO owns 
              SET l_id = :l_id,
                  addr = :addr";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':l_id', $l_id);
        $statement->bindValue(':addr', $addr);
        $statement->execute();
        $statement->closeCursor();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function checkAddr($addr)
{
    $db = getDB(); 
    $query = "SELECT addr FROM location WHERE addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':addr', $addr);
    $statement->execute();

    $exists = $statement->fetchColumn() !== false;
    $statement->closeCursor();
    return $exists;
}

function checkLandlord($name, $contact)
{
    $db = getDB(); 
    $query = "SELECT l_id FROM landlords WHERE name = :name AND contact = :contact";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':contact', $contact);
    $statement->execute();

    $exists = $statement->fetchColumn() !== false;
    $statement->closeCursor();
    return $exists;
}

function checkOwns($addr)
{
    $db = getDB(); 
    $query = "SELECT l_id FROM owns WHERE addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':addr', $addr);
    $statement->execute();

    $curr_lid = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $curr_lid;
}

function updateLocation($addr, $bedroom, $bathroom, $on_off_grounds, $price, $extra_cost)
{
    $db = getDB(); 
    $query = "UPDATE location
              SET bedroom = :bedroom,
                  bathroom = :bathroom,
                  on_off_grounds = :on_off_grounds,
                  price = :price,
                  extra_cost = :extra_cost
              WHERE addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':addr', $addr);
    $statement->bindValue(':bedroom', $bedroom);
    $statement->bindValue(':bathroom', $bathroom);
    $statement->bindValue(':on_off_grounds', $on_off_grounds);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':extra_cost', $extra_cost);
    $statement->execute();
    $statement->closeCursor();
}

function updateApt($addr, $elevator, $num_of_floors, $balcony, $pets, $smoking)
{
    $db = getDB(); 
    $query = "UPDATE apartment
              SET elevator = :elevator,
                  num_of_floors = :num_of_floors,
                  balcony = :balcony,
                  pets = :pets,
                  smoking = :smoking
              WHERE addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':addr', $addr);
    $statement->bindValue(':elevator', $elevator);
    $statement->bindValue(':num_of_floors', $num_of_floors);
    $statement->bindValue(':balcony', $balcony);
    $statement->bindValue(':pets', $pets);
    $statement->bindValue(':smoking', $smoking);
    $statement->execute();
    $statement->closeCursor();
}

function updateHouse($addr, $yard, $stories, $porch)
{
    $db = getDB(); 
    $query = "UPDATE house
              SET yard = :yard,
                  stories = :stories,
                  porch = :porch
              WHERE addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':addr', $addr);
    $statement->bindValue(':yard', $yard);
    $statement->bindValue(':stories', $stories);
    $statement->bindValue(':porch', $porch);
    $statement->execute();
    $statement->closeCursor();
}

function updateDorm($addr, $style, $single_double, $kitchen)
{
    $db = getDB(); 
    $query = "UPDATE dorm
              SET style = :style,
                  single_double = :single_double,
                  kitchen = :kitchen
              WHERE addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':addr', $addr);
    $statement->bindValue(':style', $style);
    $statement->bindValue(':single_double', $single_double);
    $statement->bindValue(':kitchen', $kitchen);
    $statement->execute();
    $statement->closeCursor();
}

function updateGroup($g_id, $addr)
{
    $db = getDB(); 
    $query = "UPDATE groups
              SET addr = :addr
              WHERE g_id = :g_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':g_id', $g_id);
    $statement->bindValue(':addr', $addr);
    $statement->execute();
    $statement->closeCursor();
}

function removeOwns($l_id, $addr)
{
    $db = getDB(); 
    $query = "DELETE FROM owns WHERE l_id = :l_id AND addr = :addr";
    $statement = $db->prepare($query);
    $statement->bindValue(':l_id', $l_id);
    $statement->bindValue(':addr', $addr);
    $statement->execute();
    $statement->closeCursor();
}
?>
