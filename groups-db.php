<?php
function fetchUserGroups($user_id)
{
	global $db;
	$query = "SELECT g_id, status FROM part_of WHERE comp_id = :user_id LIMIT 1";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':user_id', $user_id);
	$stmt->execute();

	$group = $stmt->fetch(PDO::FETCH_ASSOC);
	return $group ? [$group] : [];
}

function fetchAllGroups()
{
	global $db;
	$query = "SELECT g_id, status, num_of_people, addr FROM groups ORDER BY g_id ASC";
	$stmt = $db->prepare($query);
	$stmt->execute();

	$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $groups;
}

function fetchGroupMembers($g_id)
{
	global $db;
	$query = "SELECT comp_id, stu_name, status FROM users NATURAL JOIN part_of WHERE g_id = :g_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':g_id', $g_id);
	$stmt->execute();

	$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $members;
}

function fetchGroupDetails($g_id)
{
	global $db;
	$query = "SELECT * FROM groups WHERE g_id = :g_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':g_id', $g_id);
	$stmt->execute();

	$group = $stmt->fetch(PDO::FETCH_ASSOC);
	return $group;
}

function countGroupMembers($g_id)
{
	global $db;
	$query = "SELECT COUNT(*) as count FROM part_of WHERE g_id = :g_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':g_id', $g_id);
	$stmt->execute();

	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result['count'];
}

function fetchGroupLocation($g_id)
{
	global $db;
	$query = "SELECT addr FROM groups WHERE g_id = :g_id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':g_id', $g_id);
	$stmt->execute();

	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result ? $result['addr'] : null;
}

function createGroup($user_id, $group_status = 'Searching')
{
	global $db;
	try {
		// First, remove user from any existing group
		$query0 = "DELETE FROM part_of WHERE comp_id = :user_id";
		$stmt0 = $db->prepare($query0);
		$stmt0->bindParam(':user_id', $user_id);
		$stmt0->execute();

		// Create the new group with initial status and num_of_people = 1
		$query = "INSERT INTO groups (status, num_of_people) VALUES (:status, 1)";
		$stmt = $db->prepare($query);
		$stmt->bindValue(':status', $group_status);
		$stmt->execute();

		// Get the newly created group ID
		$g_id = $db->lastInsertId();

		// Add the creator as a member with 'owner' status
		$query2 = "INSERT INTO part_of (comp_id, g_id, status) VALUES (:comp_id, :g_id, 'owner')";
		$stmt2 = $db->prepare($query2);
		$stmt2->bindParam(':comp_id', $user_id);
		$stmt2->bindParam(':g_id', $g_id);
		$stmt2->execute();

		return $g_id;
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		return false;
	}
}

function leaveGroup($user_id, $g_id)
{
	global $db;
	try {
		$query = "DELETE FROM part_of WHERE comp_id = :user_id AND g_id = :g_id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':g_id', $g_id);
		$stmt->execute();
		return true;
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		return false;
	}
}

function updateGroupStatus($g_id, $status)
{
	global $db;
	try {
		$query = "UPDATE groups SET status = :status WHERE g_id = :g_id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(':status', $status);
		$stmt->bindParam(':g_id', $g_id);
		$stmt->execute();
		return true;
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		return false;
	}
}

function updateGroupNumPeople($g_id, $num_people)
{
	global $db;
	try {
		$query = "UPDATE groups SET num_of_people = :num_of_people WHERE g_id = :g_id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(':num_of_people', $num_people);
		$stmt->bindParam(':g_id', $g_id);
		$stmt->execute();
		return true;
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		return false;
	}
}
?>
