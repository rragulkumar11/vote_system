<?php
require('../config/config.php');
require('../config/db.php');
error_reporting(0);

global $connection;

$connection = new mysqli($hostname, $username, $password, $database);

if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}

function getRows($query) {
    global $connection;
    $result = $connection->query($query);

    if (!$result) {
        die("Query failed: " . $connection->error);
    } else {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}

function executeQuery($query) {
    global $connection;
    $result = $connection->query($query);

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    $connection->close();

    return $result;
    
}


function executeInsertQuery($query) {
    global $connection;
    $result = $connection->query($query);

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    $lastInsertId = $connection->insert_id;

    $connection->close();

    return $lastInsertId;
}



?>
