
<?php

session_start();


include "../../config/db.php";

$msg = "";
use PHPMailer\PHPMailer\PHPMailer;

if(isset($_SESSION['uid'])){
	



}
else{


	header("location:../c2wadmin/signin.php");
}

if(isset($_POST['mssg'])){


 $email = $_POST['email'];
   $title = $_POST['title'];
   $message = $_POST['message'];
   
    $msgid ='cabcdg19etsfjhdshdsh35678gwyjerehuhb/>()[]{}|\dTSGSAWQUJHDCSMNBVCBNRTPZXMCBVN1234567890';
            $msgid = str_shuffle($msgid);
             $msgid= substr($msgid,0, 10);


	 $sql = "INSERT INTO adminmessage (email, message, title, msgid) VALUES ('$email','$message','$title','$msgid')";
        if(mysqli_query($link, $sql)){
			
			$msg = "Message sent!";
		}else{
			
			$msg = "Message not sent!";
		}
}

    $selecting = $link->prepare("SELECT fname, lname, email, walletbalance, pname, package FROM users ORDER BY id ASC");
    $selecting->execute();
    $selecting->bind_result($fname, $lname, $user_email, $walletbalance, $pname, $package);
    $selecting->store_result();

include "header.php";


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



 <div class="content-wrapper">
  


  <!-- Main content -->
  <section class="content">



   




<div class="col-xs-12" style="float: none">
          <div class="box box-default">
            <div class="box-header with-border">

          <h4 align="center"><i class="fa fa-comment"></i> SEND PRIVATE MESSAGE</h4>
</br>


        
         

 
          <hr></hr>
          
        
          
            <div class="box-header with-border">
            
            <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
          </br>

     <form class="form-horizontal" action="inmessage.php" method="POST" >

           <legend>Private Message </legend>
		   
		  
 <div class="form-group">
        <input type="text" name="email" placeholder="Recipient Email"  class="form-control">
        </div>
     <div class="form-group">
        <input type="text" name="title" placeholder="Title"  class="form-control">
        </div>
		
       <div class="form-group">
        <textarea placeholder="write message here" name="message" class="form-control"></textarea>
      </div>

      
      
	  
	  <button style="" type="submit" class="btn btn-primary" name="mssg" > <i class="fa fa-send"></i>&nbsp; Send Meassage </button>

    </form>

<br><br>

    <div style="width:100%">
  <div class="box box-default">
    <div class="box-header with-border">

    <table class="display" style="width: 100%;" id="example">
      <thead>
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Balance</th>
            <th>Package name</th>
            <th>Package</th>
        </tr>
      </thead>
      <tbody>
        <?php while($a = $selecting->fetch()): ?>
            <tr>
                <td><?= $fname; ?></td>
                <td><?= $lname; ?></td>
                <td><?= $user_email; ?></td>
                <td><?= $walletbalance; ?></td>
                <td><?= $pname; ?></td>
                <td><?= $package; ?></td>
            </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    </div>
   </div>
    </div>
    </div>
   </div>

   </div>
  </div>
  </section>
</div>


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

