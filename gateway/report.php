
<?php
error_reporting(0);
session_start();
require('./header.php');
require('./menu.php');
require('./function.php');
require('../database/database.php');
if(empty($_SESSION['user'])){
    header("Location: ".$app_url."route?action=login");
    exit;
}


?>
<style>
  #voterTable thead {
    background-color: #333; 
    color: white; 
  }

  #exportButton {
    background-color: #333; 
    color: white; 
    border: none;
    padding: 10px 20px;
    cursor: pointer;
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
                    <li class="nav-item ">
                        <a class="nav-link" href=<?php echo $app_url."?action=dashboard";?>>Voter Module</a>
                    </li>
                    <?php if($_SESSION['role']==='1')
                    {
                        ?> 
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Reports</a>
                    </li>
                    <?php }?>
                    <li class="nav-item">
                        <a class="nav-link" href=<?php echo $app_url."?action=logout";?>>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class=" m-5">
        <h2>Voter Report</h2>
        <table id="voterTable" class="display" style="width:100%">
            <thead>
                <tr>
                     <th>Voter ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>District</th>
                    <th>Address</th>
                    <th>Taluk</th>
                    <th>State</th>
                    
                </tr>
            </thead>
        </table>
        <button id="exportButton">Export as Excel</button>
    </div>
    
    
    <script>
        $(document).ready(function() {
            var table = $('#voterTable').DataTable({
                ajax: "<?php echo $api_url.'?action=getReport'?>",
                columns: [
                    { data: 'voterid' },
                    { data: 'first_name' },
                    { data: 'last_name' },
                    { data: 'gender' },
                    { data: 'dob' },
                    { data: 'mobile' },
                    { data: 'email' },
                    { data: 'district' },
                    { data: 'address' },
                    { data: 'taluk' },
                    { data: 'state' }
                ]
                ,
        buttons: [
            'excelHtml5' 
        ]
            });

            $('#exportButton').click(function() {
                table.button('.buttons-excel').trigger();
            });
        });
    </script>

    <?php
    $page_footer='yes';
require('./footer.php');
?>

   
