
<?php
error_reporting(0);
require('./header.php');
require('../config/config.php');

session_start();
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
      $data = [
        'email' => $email,
        'password' => $password,
    ];
    
    $data_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url.'?action=singin');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }else{
      $response=json_decode($response,true);
      if($response['status']==200){

        $_SESSION['user'] = $response['data']['email'];
        $_SESSION['fname'] = $response['data']['fname'];
        $_SESSION['lname'] = $response['data']['lname'];
        $_SESSION['id'] = $response['data']['id'];
        $_SESSION['status'] = $response['data']['status'];
        $_SESSION['role'] = $response['data']['role'];
        header("Location: ".$app_url."gateway?action=dashboard");
        exit;
      }else{
        $errorMessage = $response['message'];
      }
    }
    curl_close($ch);
    
    } else {
        $errorMessage = "Both email and password are required.";
    }
}


?>

<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-2 order-lg-1">
                                <img src="../resource/image/vote_1.png" class="img-fluid" alt="Sample image">
                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-5 order-1 order-lg-2">
                                <h2 class="card-title text-center" style='margin-top:80px;'>Sign in</h2>
                                <form method='post' action="" id="loginForm">
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-block">Sign in</button>
                                </form>
                                <?php
                                    if (isset($errorMessage)) {
                                        echo "<p class='text-center mt-3'>$errorMessage</p>";
                                    }
                                ?>
                                <p class="text-center mt-3">Don't have an account? <a href=<?php echo $app_url."?action=register";?>>Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require('./footer.php');
?>
