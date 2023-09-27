<?php
error_reporting(0);
require "../config/config.php";
require "../api/api.php";
require "../gateway/function.php";


$action = isset($_GET["action"]) ? $_GET["action"] : "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request = file_get_contents("php://input");
}

$response = "";

switch ($action) {
    case "login":
        header("Location: " . $app_url . "gateway/login.php");
        break;

    case "dashboard":
        header("Location: " . $app_url . "gateway/dashboard.php");
        break;

    case "register":
        header("Location: " . $app_url . "gateway/register.php");
        break;
    case "report":
        header("Location: " . $app_url . "gateway/report.php");
        break;
    case "logout":
        session_destroy();
        header("Location: " . $app_url . "gateway/login.php");
        break;
    case "singup":
        put_log("Registration Request => " . $request);
        $response = registration($request);
        put_log("Registration Response => " . $response);
        break;
    case "verify":
        $id = isset($_GET["UID"]) ? $_GET["UID"] : "";
         put_log("Email Verify Request => UID :" . $id);
         email_verify($id);
         break;
    case "singin":
         put_log("Login Request => " . $request);
         $response = login($request);
         put_log("Login Response => " . $response);
         break;
    case "registervoterid":
         put_log("Voter ID Registration Request => " . $request);
         $response = registervoterid($request);
         put_log("Voter ID Registration Response => " . $response);
         break;
    case "checkuserstatus":
         put_log("Voter ID Registration User Status Request => " . $request);
         $response = checkuserstatus($request);
         put_log("Voter ID Registration User Status Response => " . $response);
         break;
    case "getDashboardData":
        $response = getDashboardData($request);
        put_log("Get dashboard count Response => " . $response);
        break;
    case "getReport":
       $response = getReport($request);
        put_log("Get Report Response => " . $response);
        break;

    default:
        header("Location: " . $app_url . "gateway/404.php");
        break;
}
if ($response !== "") {
    header("Content-Type: application/json");
    echo $response;
}
?>
