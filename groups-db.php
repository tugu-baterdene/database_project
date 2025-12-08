<?php

function fetchUserGroups($user_id) {
    global $db;
    $query = "SELECT g.g_id, g.status, g.num_of_people, g.addr 
              FROM part_of p
              JOIN groups g ON p.g_id = g.g_id 
              WHERE p.comp_id = :user_id 
              LIMIT 1";     
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $group = $statement->fetch(PDO::FETCH_ASSOC);
    return $group ? [$group] : [];
}

function fetchGroupDetails($g_id) {
    global $db;
    $query = "SELECT * FROM groups WHERE g_id = :g_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':g_id', $g_id);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function fetchGroupMembers($g_id) {
    global $db;
    $query = "SELECT u.comp_id, u.stu_name, u.status 
              FROM users u 
              JOIN part_of p ON u.comp_id = p.comp_id 
              WHERE p.g_id = :g_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':g_id', $g_id);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function leaveGroup($user_id, $g_id) {
    global $db;
    try {
        $query = "DELETE FROM part_of WHERE comp_id = :user_id AND g_id = :g_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':user_id', $user_id);
        $statement->bindParam(':g_id', $g_id);
        $statement->execute();

        $query2 = "UPDATE groups SET num_of_people = num_of_people - 1 WHERE g_id = :g_id";
        $statement2 = $db->prepare($query2);
        $statement2->bindParam(':g_id', $g_id);
        $statement2->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function createGroupWithProperty($user_id, $status, $addr, $size, $type, $details) {
    global $db;

    try {
        $db->beginTransaction();

        $delQuery = "DELETE FROM part_of WHERE comp_id = :user_id";
        $stmtDel = $db->prepare($delQuery);
        $stmtDel->bindValue(':user_id', $user_id);
        $stmtDel->execute();

        if (!empty($addr)) {
            // Check if address already exists to avoid duplicates
            $checkQuery = "SELECT COUNT(*) FROM location WHERE addr = :addr";
            $stmtCheck = $db->prepare($checkQuery);
            $stmtCheck->bindValue(':addr', $addr);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if (!$exists) {
                $onOff = ($type === 'dorm') ? 'On' : 'Off';

                $locQuery = "INSERT INTO location (addr, bedroom, bathroom, on_off_grounds, price, extra_cost) 
                             VALUES (:addr, :bed, :bath, :on_off, :price, :extra)";
                $stmtLoc = $db->prepare($locQuery);
                $stmtLoc->bindValue(':addr', $addr);
                $stmtLoc->bindValue(':bed', $details['bedroom']);
                $stmtLoc->bindValue(':bath', $details['bathroom']);
                $stmtLoc->bindValue(':on_off', $onOff); 
                $stmtLoc->bindValue(':price', $details['price']);
                $stmtLoc->bindValue(':extra', 'None');
                $stmtLoc->execute();

                if ($type === 'apartment') {
                    $aptQuery = "INSERT INTO apartment (addr, elevator, num_of_floors, balcony, pets, smoking)
                                 VALUES (:addr, :elev, :floors, :balcony, :pets, :smoke)";
                    $stmtApt = $db->prepare($aptQuery);
                    $stmtApt->bindValue(':addr', $addr);
                    $stmtApt->bindValue(':elev', $details['elevator']);
                    $stmtApt->bindValue(':floors', $details['floors']);
                    $stmtApt->bindValue(':balcony', $details['balcony']);
                    $stmtApt->bindValue(':pets', $details['pets']);
                    $stmtApt->bindValue(':smoke', $details['smoking']);
                    $stmtApt->execute();
                } 
                elseif ($type === 'house') {
                    $houseQuery = "INSERT INTO house (addr, yard, stories, porch)
                                   VALUES (:addr, :yard, :stories, :porch)";
                    $stmtHouse = $db->prepare($houseQuery);
                    $stmtHouse->bindValue(':addr', $addr);
                    $stmtHouse->bindValue(':yard', $details['yard']);
                    $stmtHouse->bindValue(':stories', $details['stories']);
                    $stmtHouse->bindValue(':porch', $details['porch']);
                    $stmtHouse->execute();
                } 
                elseif ($type === 'dorm') {
                    $dormQuery = "INSERT INTO dorm (addr, style, single_double, kitchen)
                                  VALUES (:addr, :style, :sd, :kitchen)";
                    $stmtDorm = $db->prepare($dormQuery);
                    $stmtDorm->bindValue(':addr', $addr);
                    $stmtDorm->bindValue(':style', $details['style']);
                    $stmtDorm->bindValue(':sd', $details['single_double']);
                    $stmtDorm->bindValue(':kitchen', $details['kitchen']);
                    $stmtDorm->execute();
                }
            }
        }

        $grpQuery = "INSERT INTO groups (status, num_of_people, addr) VALUES (:status, :num, :addr)";
        $stmtGrp = $db->prepare($grpQuery);
        $stmtGrp->bindValue(':status', $status);
        $stmtGrp->bindValue(':num', $size);
        $stmtGrp->bindValue(':addr', $addr);
        $stmtGrp->execute();
        
        $new_g_id = $db->lastInsertId();

        $partQuery = "INSERT INTO part_of (comp_id, g_id) VALUES (:comp_id, :g_id)";
        $stmtPart = $db->prepare($partQuery);
        $stmtPart->bindValue(':comp_id', $user_id);
        $stmtPart->bindValue(':g_id', $new_g_id);
        $stmtPart->execute();

        $db->commit();
        return $new_g_id;

    } catch (Exception $e) {
        $db->rollBack();
        die("Database Error: " . $e->getMessage());
    }
}
?>