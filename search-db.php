<?php
function searchUsers($filters) {
    $db = getDB();

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
