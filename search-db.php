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

function searchUsers($name_filter, $year_filter, $major_filter, $status_filter)
{
    global $db;

    $query = "SELECT * FROM users WHERE 1=1";

    if (!empty($name_filter)) {
        $query .= " AND stu_name LIKE :name_filter";
    }

    if (!empty($year_filter)) {
        $query .= " AND school_year = :year_filter";
    }

    if (!empty($major_filter)) {
        $query .= " AND major LIKE :major_filter";
    }

    if (!empty($status_filter)) {
        $query .= " AND status = :status_filter";
    }

    $statement = $db->prepare($query);

    if (!empty($name_filter)) {
        $statement->bindValue(':name_filter', '%'. $name_filter .'%');
    }
    if (!empty($year_filter)) {
        $statement->bindValue(':year_filter', $year_filter);
    }
    if (!empty($major_filter)) {
        $statement->bindValue(':major_filter', '%'. $major_filter .'%');
    }
    if (!empty($status_filter)) {
        $statement->bindValue(':status_filter', $status_filter);
    }

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}
?>
