<?php
// function searchUsers($filters)
// {
//     global $db;

//     $query = "
//         SELECT 
//             users.*, 
//             preferences.budget, 
//             preferences.on_off_grounds, 
//             location.bedroom, 
//             location.bathroom
//         FROM users
//         LEFT JOIN preferences ON users.comp_id = preferences.comp_id
//         LEFT JOIN part_of ON users.comp_id = part_of.comp_id
//         LEFT JOIN groups ON part_of.g_id = groups.g_id
//         LEFT JOIN location ON groups.addr = location.addr
//         LEFT JOIN apartment ON apartment.addr = location.addr
//         LEFT JOIN house ON house.addr = location.addr
//         LEFT JOIN dorm ON dorm.addr = location.addr
//         WHERE 1=1
//     ";

//     $params = [];

//     if (!empty($filters['name'])) {
//         $query .= " AND users.stu_name LIKE :name";
//         $params[':name'] = "%{$filters['name']}%";
//     }

//     if (!empty($filters['year'])) {
//         $query .= " AND users.school_year = :year";
//         $params[':year'] = $filters['year'];
//     }

//     if (!empty($filters['major'])) {
//         $query .= " AND users.major LIKE :major";
//         $params[':major'] = "%{$filters['major']}%";
//     }

//     if (!empty($filters['status'])) {
//         $query .= " AND users.status = :status";
//         $params[':status'] = $filters['status'];
//     }

//     if (!empty($filters['on_off'])) {
//         $query .= " AND preferences.on_off_grounds = :on_off";
//         $params[':on_off'] = $filters['on_off'];
//     }

//     if (!empty($filters['budget'])) {
//         $query .= " AND preferences.budget <= :budget";
//         $params[':budget'] = $filters['budget'];
//     }

//     if (!empty($filters['drinking'])) {
//         $query .= " AND preferences.drinking = :drinking";
//         $params[':drinking'] = $filters['drinking'];
//     }

//     if (!empty($filters['smoking'])) {
//         $query .= " AND preferences.smoking = :smoking";
//         $params[':smoking'] = $filters['smoking'];
//     }

//     if (!empty($filters['pets'])) {
//         $query .= " AND preferences.pets = :pets";
//         $params[':pets'] = $filters['pets'];
//     }

//     if (!empty($filters['bedroom'])) {
//         $query .= " AND location.bedroom = :bedroom";
//         $params[':bedroom'] = $filters['bedroom'];
//     }

//     if (!empty($filters['bathroom'])) {
//         $query .= " AND location.bathroom = :bathroom";
//         $params[':bathroom'] = $filters['bathroom'];
//     }

//     if (!empty($filters['housing_type'])) {
//         if ($filters['housing_type'] === 'apartment')
//             $query .= " AND apartment.addr IS NOT NULL";
//         if ($filters['housing_type'] === 'house')
//             $query .= " AND house.addr IS NOT NULL";
//         if ($filters['housing_type'] === 'dorm')
//             $query .= " AND dorm.addr IS NOT NULL";
//     }

//     $stmt = $db->prepare($query);
//     $stmt->execute($params);
//     $results = $stmt->fetchAll();
//     $stmt->closeCursor();
//     return $results;
// }

function searchUsers($filters) {
    global $db;

    $stmt = $db->prepare("CALL search_users_proc(:name, :year, :major, :status, :on_off, :budget, :drinking, :smoking, :pets)");

    $stmt->bindValue(':name', !empty($filters['name']) ? $filters['name'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':year', !empty($filters['year']) ? $filters['year'] : null, PDO::PARAM_INT);
    $stmt->bindValue(':major', !empty($filters['major']) ? $filters['major'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':status', !empty($filters['status']) ? $filters['status'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':on_off', !empty($filters['on_off']) ? $filters['on_off'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':budget', !empty($filters['budget']) ? $filters['budget'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':drinking', !empty($filters['drinking']) ? $filters['drinking'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':smoking', !empty($filters['smoking']) ? $filters['smoking'] : null, PDO::PARAM_STR);
    $stmt->bindValue(':pets', !empty($filters['pets']) ? $filters['pets'] : null, PDO::PARAM_STR);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $results;
}

?>
