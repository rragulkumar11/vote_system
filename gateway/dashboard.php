
<?php
error_reporting(0);
session_start();
require "./header.php";
require "./menu.php";
require "./function.php";
require "../database/database.php";
if (empty($_SESSION["user"])) {
    header("Location: " . $app_url . "route?action=login");
    exit();
}

$errorMessage = "";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url . "?action=getDashboardData");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
$response_data_cutnts = curl_exec($ch);
curl_close($ch);
$response_data_cutnts = json_decode($response_data_cutnts, true);
$response_data_cutnts = $response_data_cutnts["data"];

$payload = [
    "email" => $_SESSION["user"],
];

$payload_json = json_encode($payload);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url . "?action=checkuserstatus");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
$response_data = curl_exec($ch);
curl_close($ch);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $dob = $_POST["dob"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $taluk = $_POST["taluk"];
    $district = $_POST["district"];
    $state = $_POST["state"];
    if (
        !empty($first_name) &&
        !empty($last_name) &&
        !empty($dob) &&
        !empty($mobile) &&
        !empty($email) &&
        !empty($address) &&
        !empty($taluk) &&
        !empty($district) &&
        !empty($state)
    ) {
        $today = new DateTime();
        $birthday = new DateTime($dob);
        $age = $today->diff($birthday)->y;
        if ($age < 18) {
            $errorMessage = "You are under 18 , So Not register for voter id.";
        } else {
            $currentDateTime = date("Y-m-d H:i:s");
            $dataToHash = $district . $state . $currentDateTime;
            $uniqueID = md5($dataToHash);
            $data = [
                "first_name" => $first_name,
                "gender" => $gender,
                "last_name" => $last_name,
                "dob" => $dob,
                "mobile" => $mobile,
                "email" => $email,
                "district" => $district,
                "address" => $address,
                "taluk" => $taluk,
                "state" => $state,
                "voterid" => $uniqueID,
            ];

            $data_json = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url . "?action=registervoterid");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
            ]);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch);
            } else {
                $response = json_decode($response, true);
                if ($response["status"] == 200) {
                    header("Location: " . $_SERVER["PHP_SELF"]);
                    exit();
                } else {
                    $errorMessage = $response["message"];
                }
            }
            curl_close($ch);
        }
    } else {
        $errorMessage = "Mandatory field is missing.";
    }
}

if ($errorMessage !== "") {
    echo "<script>
        
        Swal.fire({
            title: 'Rregister Voter id',
            text: '" .
        $errorMessage .
        "',
            icon: 'warning', 
            confirmButtonText: 'OK'
        });
    </script>";
}
?>
    <style>
        .card {
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
        }
        .card-primary {
            background-color: #007BFF;
            color: white;
        }

        .card-success {
            background-color: #28A745;
            color: white;
        }

        .card-danger {
            background-color: #DC3545;
            color: white;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Voter ID Registration System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Voter Module</a>
                    </li>
                    <?php if ($_SESSION["role"] === "1") { ?>
                    <li class="nav-item">
                        <a class="nav-link" href=<?php echo $app_url .
                            "?action=report"; ?>>Reports</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href=<?php echo $app_url .
                            "?action=logout"; ?>>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-1">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Voters Count</h5> 
                        <h5 class="card-text"><?php echo $response_data_cutnts[
                            "TotalVoters"
                        ]; ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-success">
                    <div class="card-body">
                        <h5 class="card-title">Today Register Count</h5>
                        <h5 class="card-text"><?php echo $response_data_cutnts[
                            "TodayRegister"
                        ]; ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card card-danger">
                    <div class="card-body">
                        <h5 class="card-title"> Attend Count</h5>
                        <h5 class="card-text"><?php echo $response_data_cutnts[
                            "AttendCount"
                        ]; ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-1 mb-5">
    <div class="row justify-content-center">
        <?php
        $response_data = json_decode($response_data, true);
        if (COUNT($response_data["data"]) !== 0) { ?>
           <div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center">Voter Information</h5>
            <?php if (
                isset($response_data["data"]) &&
                is_array($response_data["data"]) &&
                count($response_data["data"]) > 0
            ) { ?>
            
                    <?php foreach ($response_data["data"] as $user) {
                        $newDateString = date(
                            "m/d/Y",
                            strtotime($user["dob"])
                        ); ?>
                        <div class="user-data">
                            <div class="row">
                            <div class="col-md-4">
                            <img src="../resource/image/vote.png"
                  class="img-fluid" alt="Sample image">
                                </div>
                                <div class="col-md-3">
                                    <p>First Name: <?php echo $user[
                                        "first_name"
                                    ]; ?></p>
                                    <p>Last Name: <?php echo $user[
                                        "last_name"
                                    ]; ?></p>
                                    <p>Gender: <?php echo $user[
                                        "gender"
                                    ]; ?></p>
                                    <p>Date of Birth: <?php echo $newDateString; ?></p>
                                    <p>Mobile: <?php echo $user[
                                        "mobile"
                                    ]; ?></p>
                                </div>
                                <div class="col-md-5">
                                    <p>Voter ID: <?php echo $user[
                                        "voterid"
                                    ]; ?></p>
                                    <p>Email: <?php echo $user["email"]; ?></p>
                                    <p>District: <?php echo $user[
                                        "district"
                                    ]; ?></p>
                                    <p>Address: <?php echo $user[
                                        "address"
                                    ]; ?></p>
                                    <p>Taluk: <?php echo $user["taluk"]; ?></p>
                                    <p>State: <?php echo $user["state"]; ?></p>
                                    
                                </div>
                            </div>
                        </div>
                    <?php
                    } ?>
                
            <?php } else { ?>
                <p>No data available.</p>
            <?php } ?>
        </div>
    </div>
</div>

<style>
    .custom-card {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }

    .custom-card-title {
        font-size: 18px;
        font-weight: bold;
    }

    .user-data {
        margin-top: 15px;
    }
</style>

                    <?php } else { ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Voter ID Registration </h5>
                    <form method="post" id="registrationForm">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value=<?php echo $_SESSION[
                                "fname"
                            ]; ?> required readonly>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value=<?php echo $_SESSION[
                                "lname"
                            ]; ?> required readonly>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth:</label>
                            <input type="date" class="form-control" id="dob" name="dob" required max="<?php echo date(
                                "Y-m-d"
                            ); ?>">
                        </div>
                        <div class="form-group">
                        <label>Gender:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="other" value="Other">
                            <label class="form-check-label" for="other">Other</label>
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="mobile">Mobile:</label>
                            <input type="tel" class="form-control" id="mobile" name="mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value=<?php echo $_SESSION[
                                "user"
                            ]; ?> required readonly>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="taluk">Taluk:</label>
                            <input type="text" class="form-control" id="taluk" name="taluk" required>
                        </div>
                        <div class="form-group">
                            <label for="district">District:</label>
                            <input type="text" class="form-control" id="district" name="district" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State:</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                        <button type="submit" class="btn btn-dark btn-block" onclick="submitForm(event)">Save</button>
                    </form></div>
            </div>
        </div>
      
            <div class="col-md-6">
        <img src="../resource/image/note.png"
                  class="img-fluid" alt="Sample image">
            </div>
        </div>
    </div>

    <?php }
        $page_footer = "yes";
        require "./footer.php";

?>
