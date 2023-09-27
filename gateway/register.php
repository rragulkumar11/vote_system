<?php
require "./header.php"; 
require "../config/config.php";
error_reporting(0);
?>
<section class="vh-100" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

              <h2 class="card-title text-center">Sign up</h2>
                        <form method='post'  id="registrationForm">
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-dark btn-block" onclick="submitForm(event)">Sign up</button>
                        </form>
                        <p class="text-center mt-3">Go to Sign in ? <a href=<?php echo $app_url."?action=login";?>>Sign in</a></p>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                <img src="../resource/image/vote.png"
                  class="img-fluid" alt="Sample image">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function submitForm(event) {
    event.preventDefault();
    var first_name= $('#first_name').val();
    var last_name= $('#last_name').val();
    var  email= $('#email').val();
    var    password= $('#password').val();
    var  confirm_password= $('#confirm_password').val();

if(first_name!='' && last_name!='' && email!='' && password!='' && confirm_password!=''){

       if(password===confirm_password){
    var formData = {
        first_name: first_name,
        last_name: last_name,
        email: email,
        password:password,
        confirm_password: confirm_password
    };

    
  

    jQuery.ajax({
    type: 'POST',
    url: "<?php echo $api_url.'?action=singup'?>",
    contentType: 'application/json',
    data: JSON.stringify(formData), 
    success: function(response) {
        if (response.status==200) {
            $('#registrationForm')[0].reset();
            $('#errorMessage').text(''); 
            Swal.fire({
                icon: 'success',
                title: 'Registration OK',
                text: 'Registration successful!',
            });
        } else{
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: response.message,
            });
        }
    },
    error: function() {
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            text: 'An error occurred during registration.',
        });
    }
});


            
  }else{
    Swal.fire({
                    icon: 'info',
                    title: 'Registration Failed',
                    text: 'Password and Confirm Password do not match.'
                });
  }
  }
  else{
    Swal.fire({
                    icon: 'info',
                    title: 'Registration Failed',
                    text: 'Mandatory field is missing',
                });
  }

}
</script>

<?php require "./footer.php";
?>
