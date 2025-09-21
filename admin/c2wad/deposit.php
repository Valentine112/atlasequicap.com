<?php
include "../../config/config1.php";
require '../../vendor/autoload.php';
include "../../config/db.php";

use Service\{
  Mailing,
  EmailBody,
  Func
};


$msg = "";

if(isset($_GET['referred'])){
	$referreds = $_GET['referred'];
}else{
	$referreds = '';
}

$crypto = "";
$crypto_abbr = "";

if(!isset($_SESSION['uid'])){
	header("location:../c2wadmin/signin.php");
}

if(isset($_POST['approve'])){
	
  $userId = $_POST['user'];
	$tnx = $_POST['tnx'];
	$usd = $_POST['usd'];
	$email = $_POST['email'];
	$date1 = Date("Y-m-d");
  $mode = $_POST['type'];
  $fullname = "";

  // Fetch user
  $data = [
    'email' => $email,
    '1' => '1',
    'needle' => '*',
    'table' => 'users'
  ];

  $user = Func::searchDb($link, $data, "AND");
  if(empty($user)) return $user;
	
  $modeBalance = $user['wallet'];
  $newBalance = $modeBalance + $usd;

  $sql1 = "UPDATE users SET wallet = '$newBalance' WHERE email='$email'";
		
  $sql2= "SELECT * FROM deposit WHERE tranx = '$tnx'";
  $result2 = mysqli_query($link,$sql2);
  if(mysqli_num_rows($result2) > 0){
    $row = mysqli_fetch_assoc($result2);
    $row['status'];
    $type_crypto = $row['mode'];
  }

  $sqlus= "SELECT * FROM users WHERE email = '$email'";
  $resultus = mysqli_query($link,$sqlus);
  if(mysqli_num_rows($resultus) > 0){
    $rowus = mysqli_fetch_assoc($resultus);
    $fuullname = $rowus['fullname'];
    $referred = $rowus['referred'];
  }
 $error = "";

  if(isset($row['status']) &&  $row['status']== "approved"){
    
    $msg = "Transaction already approved!";

  }else{
		$error = $email;
    //$link->autocommit(FALSE);
		if(mysqli_query($link, $sql1)){
        $sql1 = "UPDATE deposit SET status = 1  WHERE tranx = '$tnx'";
        if(mysqli_query($link, $sql1)):
            
            $percent = ($usd * (10 / 100));
            // Save the downline percent
            $inserting = $link->prepare("INSERT into referred (fullname, referred, amount, date) VALUES (?, ?, ?, ?)");
            
            $inserting->bind_param('ssis', $fullname, $referred, $percent, $date1);
            if($inserting->execute()):
                
                // Add downline percent to wallet
                $downlinePercent = $user['wallet'] + $percent;

                $updating = $link->prepare("UPDATE users SET wallet = ? WHERE refcode = ?");
                $updating->bind_param('is', $downlinePercent, $referred);
                if($updating->execute()):
                    $name = "Reynance";

                    // Notify the user via email
                    $data = [
                      "head" => "Deposit Confirmed",
                      "elements" => [
                        "Amount" => $usd,
                        "User" => $fullname,
                        "Method" => strtoupper($type_crypto),
                        "Transaction id" => $tnx,
                        "Date" => $date1
                      ]
                  ];

                  // Send an email address
                  $mailing = new Mailing($email, $fullname, null, Func::email_config());
                  $mailing->set_params((new EmailBody($data))->main(), "Deposit");
                  if($mailing->send_mail()):
                    $link->autocommit(TRUE);
                    $msg = "transaction approved successfully and investor is credited!";
                  endif;
                endif;
            endif;
        else:
          $msg = "transaction was not approved! ";
        endif;
		}
}
}


if(isset($_POST['delete'])) {
	
	$tnx = $_POST['tnx'];
	
$sql = "DELETE FROM deposit WHERE tranx='$tnx'";

if (mysqli_query($link, $sql)) {
    $msg = "Order deleted successfully!";
} else {
    $msg = "Order not deleted! ";
}
}

include 'header.php';

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
  

  <link rel="stylesheet" href=" https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.19/css/dataTables.jqueryui.min.css">
  <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/1.5.6/css/buttons.jqueryui.min.css">



  

  <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap.min.css">
  <link rel="stylesheet" href="css/deposit.css">
 
  
    
    



  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
 

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.jqueryui.min.js"></script>
   
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
  

     
 <div class="content-wrapper">
  


  <!-- Main content -->
  <section class="content">



   <style>
 
	
   </style>


<div style="width:100%">
  <div class="box box-default">
    <div class="box-header with-border">

	<div class="row">


		 <h2 class="text-center">INVESTORS DEPOSIT MANAGEMENT</h2>
		  </br>

</br>
 <?php if($msg != "") echo "<div style='padding:20px 5px;background-color:#dce8f7;color:black; width: 90%; margin: auto;'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
          </br>
		    </br>

<div class="col-md-12 col-sm-12 col-sx-12">
    <div class="table-responsive">
          <table class="display" style="width: 100%;" id="example">

					<thead>

						<tr class="info">
						<th>Email</th>
                        <th>Currency</th>
						<th style="display:none;"></th>
						<th style="display:none;"></th>
						<th style="display:none;"></th>
            <th style="display:none;"></th>
            <th style="display:none;"></th>
							<th>Amount</th>
                               <th>Status</th>
							 
							 <th>Transaction ID</th>
							 <th style="display:none;"></th>
							<th>Date</th>
                                <th>Action</th>
                                 <!--<th>Action</th>-->
                                  <th>Action</th>
								
                                

						</tr>
					</thead>


					<tbody>
					<?php $mssg = ""; $sql= "SELECT * FROM deposit ORDER BY id DESC";
                			  $result = mysqli_query($link,$sql);
                			  if(mysqli_num_rows($result) < 1):
                          echo "<tr class='primary'>
                                  <td colspan=8><h3 style='text-align:center;color: #000;'>No transaction here</h3></td>
                                </tr>";
                            //$mssg = "<h3>NO $crypto TRANSACTIONS HAS BEEN MADE</h3>";
                        else:
                				  while($row = mysqli_fetch_assoc($result)){  
                          // Fetch user details
                          $selecting = $link->prepare("SELECT fullname, email, referred FROM users WHERE id = ?");
                          $selecting->bind_param('i', $row['user']);
                          $selecting->execute();
                          $selecting->bind_result($fullname, $email, $referred);
                          $selecting->fetch();
                          $selecting->close();

                          if(isset($row['status']) &&  $row['status']== 1){
                            $sec = 'Approved &nbsp;&nbsp;<i style="background-color:green;color:#fff; font-size:20px;" class="fa  fa-check" ></i>';

                          }else{
                            $sec ='Pending &nbsp;&nbsp;<i class="fa  fa-refresh" style=" font-size:20px;color:red"></i>';

                          }


				  ?>

						<tr class="primary">
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?token='.strtolower($crypto_abbr); ?>" method="post">
							
                            <input type="hidden" name="currency" value="<?= $row['mode']; ?>">
							<td style="display:none;"><input type="hidden" name="email" value="<?php echo $email; ?>"> </td>
							<td style="display:none;"><input type="hidden" name="usd" value="<?php echo $row['amount']; ?>"> </td>
							
							<td style="display:none;"><input type="hidden" name="tnx" value="<?php echo $row['tranx'];?>"> </td>
							
							<td style="display:none;"><input type="hidden" name="type" value="<?php echo $row['mode'];?>"> </td>
							<td style="display:none;"><input type="hidden" name="user" value="<?php echo $row['user'];?>"> </td>
    							<td><?php echo $email;?></td>
                                <td><?= strtoupper($row['mode']); ?></td>
    							
    							<td><?php echo $row['amount']; ?></td>
    							<td><?php echo $sec ;?></td>
    							
    							<td><?php echo $row['tranx'];?></td>

              
                           <td style="display:none;"><input type="hidden" name="referred" value="<?php echo $row['referred']; ?>"> </td>
                			   <td><?= substr($row['date'], 0, 10); ?></td>
      
              <td><button class="btn btn-success" type="submit" name="approve">Approve</button></td>
                            
							<!--<td><button class="btn btn-info" type="submit" name="referrer">Pay Referrer</button></td>-->
							
							<td><button class="btn btn-danger" type="submit" name="delete">Delete</button></td>
							
   
</form>

						</tr>
					  <?php
}
      endif;
			  ?>
					</tbody>



				</table>
</div>
          </div>

		  </div>
          <!-- /top tiles -->

          </div>

                



    </body>
              </div>
            </div>


              </div>


          <br />







    </body>
              </div>
            </div>





          </section>

   </div>
  </div>
</div>


  </body>
</html>
    
<script>
$(document).ready(function() {
    var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
       
    } );
    

    table.buttons().container()
        .insertBefore( '#example_filter' );

        table.buttons().container()
        .appendTo( '#example_wrapper .col-sm-12:eq(0)' );
} );
</script>






<script>
$(document).ready(function () {
        $('#table')
                .dataTable({
                    "responsive": true,
                    
                });

				
    });



				</script>


