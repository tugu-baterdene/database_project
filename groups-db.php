<?php

function fetchUserGroups($user_id) {
    $db = getDB(); 
    $query = "SELECT g.g_id, g.status, g.num_of_people, g.addr 
              FROM part_of p
              JOIN groups g ON p.g_id = g.g_id 
              WHERE p.comp_id = :user_id 
              LIMIT 1";     
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $group = $statement->fetch(PDO::FETCH_ASSOC);
	$statement->closeCursor();
    return $group ? [$group] : [];
}

function fetchGroupDetails($g_id) {
    $db = getDB(); 
    $query = "SELECT * FROM groups WHERE g_id = :g_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':g_id', $g_id);
    $statement->execute();
	$group_details = $statement->fetch(PDO::FETCH_ASSOC);
	$statement->closeCursor();
    return $group_details;
}

function fetchGroupMembers($g_id) {
    $db = getDB(); 
    $query = "SELECT u.comp_id, u.stu_name, u.status 
              FROM users u 
              JOIN part_of p ON u.comp_id = p.comp_id 
              WHERE p.g_id = :g_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':g_id', $g_id);
    $statement->execute();
	$group_mem = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->closeCursor();
    return $group_mem;
}

function leaveGroup($user_id, $g_id) {
    $db = getDB(); 
    try {
        $query = "DELETE FROM part_of WHERE comp_id = :user_id AND g_id = :g_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':user_id', $user_id);
        $statement->bindParam(':g_id', $g_id);
        $statement->execute();

        $qGroup = "SELECT num_of_people, status FROM groups WHERE g_id = :g_id";
        $stmtG = $db->prepare($qGroup);
        $stmtG->bindParam(':g_id', $g_id);
        $stmtG->execute();
        $groupData = $stmtG->fetch(PDO::FETCH_ASSOC);

        $qCount = "SELECT COUNT(*) FROM part_of WHERE g_id = :g_id";
        $stmtC = $db->prepare($qCount);
        $stmtC->bindParam(':g_id', $g_id);
        $stmtC->execute();
        $currentCount = $stmtC->fetchColumn();

        if ($groupData && $groupData['status'] === 'Closed' && $currentCount < $groupData['num_of_people']) {
            $updateQ = "UPDATE groups SET status = 'Searching' WHERE g_id = :g_id";
            $stmtUp = $db->prepare($updateQ);
            $stmtUp->bindParam(':g_id', $g_id);
            $stmtUp->execute();
        }

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateGroupStatus($g_id, $status) {
    $db = getDB(); 
    $query = "UPDATE groups SET status = :status WHERE g_id = :g_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':status', $status);
    $statement->bindValue(':g_id', $g_id);
    $statement->execute();
	$statement->closeCursor();
}

function createGroupWithProperty($user_id, $status, $addr, $size, $type, $details, $landlord_name, $landlord_email) {
    $db = getDB(); 

    try {
        $db->beginTransaction();

        $delQuery = "DELETE FROM part_of WHERE comp_id = :user_id";
        $stmtDel = $db->prepare($delQuery);
        $stmtDel->bindValue(':user_id', $user_id);
        $stmtDel->execute();

        if (!empty($landlord_name)) {
            $landQuery = "INSERT INTO landlords (name, contact) VALUES (:name, :contact)";
            $stmtLand = $db->prepare($landQuery);
            $stmtLand->bindValue(':name', $landlord_name);
            $stmtLand->bindValue(':contact', $landlord_email);
            $stmtLand->execute();
        }

        if (!empty($addr)) {
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