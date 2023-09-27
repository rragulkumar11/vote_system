<?php
require('../config/config.php');
global $path;
$path=$log_path;


function generateUniqueId() {
    $currentDateTime = new DateTime();
    $formattedDateTime = $currentDateTime->format('YmdHis');
    $number = intval($formattedDateTime);    
    return $number;  
}

function getUserIP() {
    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }

    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $ip = filter_var($ip, FILTER_VALIDATE_IP);

    return $ip;
}


function put_log($message) {
    global $path;
    $userIP = getUserIP();
    $logDirectory = $path;


if (!is_dir($logDirectory)) {
    mkdir($logDirectory, 0755, true);
}

$uniqueId = generateUniqueId();
$currentDateTime = date('Y-m-d');
$logFile = $logDirectory . 'log_' . $currentDateTime . '.txt';
$customMessage = $message;
$currentTime = date('Y-m-d H:i:s');
$logEntry = "$currentTime [trace] $uniqueId [IP] $userIP :: $customMessage\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);


}



?>
