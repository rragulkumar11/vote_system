<?php
require('./config/config.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';
header("Location: ".$app_url."gateway?action=".$action);
exit;
?>