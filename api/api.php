<?php
error_reporting(0);
require('../config/config.php');
require('../database/database.php');

session_start();


global $appUrl;
$appUrl=$app_url;

function registration($request) {
    global $appUrl;
    $data = json_decode($request, true);
    $response = array();

    if (
        isset($data['first_name']) && !empty($data['first_name']) &&
        isset($data['last_name']) && !empty($data['last_name']) &&
        isset($data['email']) && !empty($data['email']) &&
        isset($data['password']) && !empty($data['password']) &&
        isset($data['confirm_password']) && !empty($data['confirm_password'])
    ) {
        $already_insert = getRows("SELECT * FROM users WHERE email='".$data['email']."'");

        if (count($already_insert) !== 0) {
            $response['status'] = 201;
            $response['message'] = 'This email address is already registered';
        } else {
            $md5Password = md5($data['password']);
            $query = 'INSERT INTO users (id, fname, lname, email, password, STATUS, ROLE, createdAt, updatedAt) VALUES ("", "'.$data['first_name'].'", "'.$data['last_name'].'", "'.$data['email'].'", "'.$md5Password.'", "pending", "2", NOW(), NOW())';
            $insert_user = executeInsertQuery($query);
            if ($insert_user) {
                $message=$appUrl.'?action=verify&UID='.$insert_user;
                $response['status'] = 200;
                $response['message'] = 'Registration successful';
                $response['verify_link'] = $message;
                // Need Mail Server Ceridancial
                // send_mail($data['email'],"Email Verification",$message);
            } else {
                $response['status'] = 500;
                $response['message'] = 'Failed to insert data into the database';
            }
        }
    } else {
        $response['status'] = 401;
        $response['message'] = 'Required parameters are missing or empty';
    }

    return json_encode($response);
}



function email_verify($id) {
    global $appUrl;

    $response = array();
    $query='UPDATE users SET status="active" ,updatedAt=NOW() where id='.$id;
    $update = executeQuery($query);

 
     if ($update) {
              header("Location: " . $appUrl . "gateway/login.php");
            } else {
                header("Location: " . $appUrl . "gateway/404.php");
            }


}


function login($request) {
    global $appUrl;
    $data = json_decode($request, true);
    $response = array();

    if (
        isset($data['email']) && !empty($data['email']) &&
        isset($data['password']) && !empty($data['password']) 
    ) {
        $user = getRows("SELECT * FROM users WHERE email='".$data['email']."'");

        if (count($user) !== 0) {
            if($user[0]['status']=='active'){
                $md5Password = md5($data['password']);
                if($md5Password===$user[0]['password']){
                    $response['status'] = 200;
                    $response['data'] = $user[0];
                    $response['message'] = 'Login Successfully';
                }else{
                    $response['status'] = 201;
                    $response['message'] = 'Invalid Password!.';
                }
            
            }else{
                $response['status'] = 201;
                $response['message'] = 'Please activate your account through email.'; 
            }
        } else {
            $response['status'] = 201;
            $response['message'] = 'User Not Found!';
        }
    } else {
        $response['status'] = 401;
        $response['message'] = 'Required parameters are missing or empty';
    }

    return json_encode($response);
}



function registervoterid($request) {

    $data = json_decode($request, true);
    $response = array();

    if (
        isset($data['first_name']) && !empty($data['first_name']) &&
        isset($data['last_name']) && !empty($data['last_name']) &&
        isset($data['email']) && !empty($data['email']) &&
        isset($data['dob']) && !empty($data['dob']) &&
        isset($data['mobile']) && !empty($data['mobile']) &&
        isset($data['district']) && !empty($data['district']) &&
        isset($data['address']) && !empty($data['address']) &&
        isset($data['taluk']) && !empty($data['taluk']) &&
        isset($data['state']) && !empty($data['state']) &&
        isset($data['voterid']) && !empty($data['voterid'])
    ) {
        $already_insert = getRows("SELECT * FROM voter_data WHERE email='".$data['email']."'");

        if (count($already_insert) !== 0) {
            $response['status'] = 201;
            $response['message'] = 'Voter already registered';
        } else {
            if (!isValidMobileNumber($data['mobile'])) {
                $response['status'] = 400;
                $response['message'] = 'Invalid mobile number format';
            } else {
            $query = 'INSERT INTO voter_data (id, first_name, gender, last_name, dob, mobile, email, district, address, taluk, state, voterid, createdAt, updatedAt) VALUES ("", "'.$data['first_name'].'", "'.$data['gender'].'", "'.$data['last_name'].'", "'.$data['dob'].'", "'.$data['mobile'].'", "'.$data['email'].'", "'.$data['district'].'", "'.$data['address'].'", "'.$data['taluk'].'", "'.$data['state'].'", "'.$data['voterid'].'", NOW(), NOW())';
            
            $insert_user = executeInsertQuery($query);
            
            if ($insert_user) {
                $message = 'Registration successful Done Your Id:'.$data['voterid'];
                $response['status'] = 200;
                $response['message'] = 'Registration successful';
                $response['verify_link'] = $message;
                // Need Mail Server Ceridancial
                // send_mail($data['email'],"Email Verification",$message);
            } else {
                $response['status'] = 500;
                $response['message'] = 'Failed to insert data into the database';
            }
        }
        }
    } else {
        $response['status'] = 401;
        $response['message'] = 'Required parameters are missing or empty';
    }

    return json_encode($response);
}


function isValidMobileNumber($mobileNumber) {
    $pattern = '/^[0-9]{10}$/';
    return preg_match($pattern, $mobileNumber) === 1;
}



function checkuserstatus($request) {

    $data = json_decode($request, true);
    $response = array();

    if (
        isset($data['email']) && !empty($data['email'])
    ) {
        $user= getRows("SELECT * FROM voter_data WHERE email='".$data['email']."'");

        if (count($user) === 0) {
            $response['status'] = 401;
            $response['data'] = [];
            $response['message'] = 'User Not Found';
        } else {           
                $response['status'] = 200;                
                $response['data'] = $user;
                $response['message'] = 'User data.';           
        }
        
    } else {
        $response['status'] = 401;
        $response['data'] = [];
        $response['message'] = 'Required parameters are missing or empty';
    }

    return json_encode($response);
}

function getDashboardData() {
    $response = array();

    $data = array();
        $voter_data= getRows("SELECT * FROM voter_data");
        $user= getRows("SELECT * FROM users");
        $data['TotalVoters']=COUNT($voter_data);
        $data['TodayRegister']=COUNT($user);
        $data['AttendCount']=COUNT($voter_data);  

                $response['status'] = 200;                
                $response['data'] = $data;
                $response['message'] = 'User data.';          
   

    return json_encode($response);
}


function getReport() {
    $response = array();
        $voter_data= getRows("SELECT
        first_name,
        last_name,
        gender,
        dob,
        mobile,
        email,
        district,
        address,
        taluk,
        state,
        voterid
      FROM voter_data");

        if ($voter_data) {
            $response['status'] = 200;                
            $response['data'] = $voter_data;
            $response['message'] = 'Report Data.';
        } else {
            $response['status'] = 401;                
            $response['data'] = [];
            $response['message'] = 'Data Not Found!';
        }                         
   

    return json_encode($response);
}


// Send Mail Function
// function send_mail($to,$subject,$message) {
//     require 'path/to/PHPMailer.php';
//     require 'path/to/SMTP.php'; 
//     require 'path/to/Exception.php'; 
    
//     use PHPMailer\PHPMailer\PHPMailer;
//     use PHPMailer\PHPMailer\SMTP;
//     use PHPMailer\PHPMailer\Exception;
//     $mail = new PHPMailer(true);
//     try {
//         $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
//         $mail->isSMTP(); 
//         $mail->Host = 'smtp.example.com'; 
//         $mail->SMTPAuth = true; 
//         $mail->Username = 'your_username'; 
//         $mail->Password = 'your_password'; 
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    
//         $mail->setFrom('from@example.com', 'Sender Name');
//         $mail->addAddress($to, $to);
//         $mail->isHTML(true); 
//         $mail->Subject = $subject;
//         $mail->Body = $message;
    
//         $mail->send();
//         echo 'Message has been sent';
//     } catch (Exception $e) {
//         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     }
  
//     }


?>
