<?php

function getDB() {
    static $db = null;   // reuse same connection during one request

    if ($db === null) {
        $username = 'rgp3qv'; 
        $password = 'iilwagfsS0S';
        $host = 'mysql01.cs.virginia.edu';
        $dbname = 'rgp3qv';
        $dsn = "mysql:host=$host;dbname=$dbname";

        try {
            $db = new PDO($dsn, $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "<p>Database connection error: " . $e->getMessage() . "</p>";
            exit;
        }
    }

    return $db;
}
?>