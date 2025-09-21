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

if(!isset($_SESSION['uid'])){

	//header("location:../c2wadmin/signin.php");
}

if(isset($_POST['complete'])){
	
	$tnx = $_POST['tnx'];
	$moni = $_POST['moni'];
	$email = $_POST['email'];

  $link->autocommit(false);
		
  $sql1 = "UPDATE withdrawals SET status = 'completed' WHERE tnx = '$tnx'";
		
  $sql2= "SELECT * FROM withdrawals WHERE tranx = '$tnx'";
  $result2 = mysqli_query($link,$sql2);
  if(mysqli_num_rows($result2) > 0){
    $row = mysqli_fetch_assoc($result2);
    $row['status'];
    $mode = $row['mode'];
    $wal = $row['amount'];
    $tnx = $row['tranx'];
    $date1 = $row['date'];
  }
  
  $sqlus= "SELECT * FROM users WHERE email = '$email'";
  $resultus = mysqli_query($link,$sqlus);
  if(mysqli_num_rows($resultus) > 0){
    $rowus = mysqli_fetch_assoc($resultus);
    $fullname = $rowus['fullname'];
  }
 
  if(isset($row['status']) &&  $row['status'] == "completed"){
	
	  $msg = "Transaction already completed!";

  }else{
    // Notify the user via email
    $data = [
        "head" => "Withdrawal Confirmed",
        "elements" => [
          "Amount" => $moni,
          "User" => $fullname,
          "Address" => $wal,
          "Method" => $mode,
          "Transaction id" => $tnx,
          "Date" => $date1
        ]
    ];

    // Send an email address
    $mailing = new Mailing($email, $fullname, null, Func::email_config());
    $mailing->set_params((new EmailBody($data))->main(), "Withdrawal");
    if($mailing->send_mail()):
        $link->autocommit(true);

      // $body = w_email($usernamewtc, $moni, $wal, $mode, $tnx, $date1);
      
      // $mailing = new Mailing($email, $name);
      // $mailing->config();
      // $mailing->set_params($body, "Withdrawal Status");
      // if($mailing->send_mail()){
              
      if(mysqli_query($link, $sql1)):
        $msg = "Transaction approved successfully and investor is credited!";
        $name = "Redcoperate";
      endif;

    else:
          $msg = "transaction was not approved! ";
    endif;
  }
}



if(isset($_POST['delete'])){
    
  $tnx = $_POST['tnx'];
  
  $sql = "DELETE FROM wbtc WHERE tnx='$tnx'";

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
  <link rel="stylesheet" href="">
 
  
    
    



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
	

		 <h2 class="text-center">WITHDRAWAL MANAGEMENT</h2>
		  </br>

</br>
 <?php if($msg != "") echo "<div style='padding:20px 5px;background-color:#dce8f7;color:black; width: 90%; margin: auto;'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
         
<div class="col-md-12 col-sm-12 col-sx-12">
               <div class="table-responsive">
                     <table class="display"  id="example">



					<thead>

						<tr class="info">
						<th>Fullname</th>
						<th>Address</th>
						<th style="display:none;"></th>
						<th style="display:none;"></th>
						<th style="display:none;"></th>
			
							<th>Amount</th>
                            <th>Mode</th>
                             <th>Status</th>
							<th>Transaction ID</th>
							<th>Date</th>
                                <th>Action</th>
                                 <th>Action</th>
                                

						</tr>
					</thead>



					<tbody>
					<?php $sql= "SELECT * FROM withdrawals ORDER BY id DESC";
			  $result = mysqli_query($link,$sql);
			  if(mysqli_num_rows($result) > 0){
				  while($row = mysqli_fetch_assoc($result)){   
          $row['status'];

          // Fetch user details
          $selecting = $link->prepare("SELECT fullname, email FROM users WHERE id = ?");
          $selecting->bind_param('i', $row['user']);
          $selecting->execute();
          $selecting->bind_result($fullname, $email);
          $selecting->fetch();
   
if(isset($row['status']) &&  $row['status']== 'completed'){
	
	
	$sec = 'Completed &nbsp;&nbsp;<i style="background-color:green;color:#fff; font-size:20px;" class="fa  fa-check" ></i>';

}else{
$sec ='Pending &nbsp;&nbsp;<i class="fa  fa-refresh" style=" font-size:20px;color:red"></i>';

}


				  ?>

						<tr class="primary">
						<form action="withdraw.php" method="post">
						
                          <td><?= $fullname; ?></td>
                            <td><?php echo $row['address'];?></td>
						  
						  <td style="display:none;"><input type="hidden" name="email" value="<?= $email; ?>"> </td>
							<td style="display:none;"><input type="hidden" name="moni" value="<?php echo $row['amount'];?>"> </td>
							
							<td style="display:none;"><input type="hidden" name="tnx" value="<?php echo $row['tranx'];?>"> </td>
						  
							<td><?php echo $row['amount'];?></td>
							<td><?php echo strtoupper($row['mode']); ?></td>
							<td><?php echo $sec ;?></td>
							<td><?php echo $row['tranx'];?></td>
              
              <td><?php echo substr($row['date'], 0, 10); ?></td>
			  
                             <td><button class="btn btn-primary" type="submit" name="complete" ><span class="glyphicon glyphicon-check"> complete</span></button></td>
							
    <td><button type="submit" name="delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"> Delete</span></button></td>
</form>


						</tr>
					  <?php
 }
			  }
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

