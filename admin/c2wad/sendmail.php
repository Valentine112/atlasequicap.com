<?php

include "../../config/config1.php";
require '../../vendor/autoload.php';
include "../../config/db.php";

use Service\{
  Mailing,
  EmailBody,
  Func
};

include "header.php";
$msg = "";

if(!isset($_SESSION['uid'])){
	header("location:../c2wadmin/signin.php");
}

$selecting1 = $link->prepare("SELECT fullname, email FROM users ORDER BY id ASC");
$selecting1->execute();
$selecting1->bind_result($fullname, $index_email);
$selecting1->store_result();

if(isset($_POST['submit'])){

$recipient = $link->real_escape_string($_POST['recipients']);
 
$subject = $link->real_escape_string($_POST['subject']);

$message = $_POST['message'];

$emails = $link->real_escape_string($_POST['emails']);

$message = nl2br($message);
$message = str_replace("/\r|\n|\r\n/", "", $message);

$data = [
  "head" => $subject,
  "elements" => $message
];


$error = false;

if($recipient == "everyone"){
    while ($b = $selecting1->fetch()) {

      $mailing = new Mailing($index_email, $fullname, null, Func::email_config());
      $mailing->set_params((new EmailBody($data))->main(), "GlobalQFSNetwork");

      $error = $mailing->send_mail() ? false : true;
      // $mailing = new Mailing($index_email, $name);
      // $mailing->config();
      // $mailing->set_params($body, $subject);
      // $mailing->send();
      
      //   if($mailing->send()):
      //       $error = false;
      //   endif;
    }
}else{
    if(!empty($emails)){
        $selecting2 = $link->prepare("SELECT fullname FROM users WHERE email = ? ORDER BY id ASC LIMIT 1");
        $selecting2->bind_param('s', $emails);
        $selecting2->execute();
        $selecting2->bind_result($fullname);
        $selecting2->store_result();
        $selecting2->fetch();

        $mailing = new Mailing($emails, $fullname, null, Func::email_config());
        $mailing->set_params((new EmailBody($data))->main(), "GlobalQFSNetwork");
        if($mailing->send_mail()):
          $error = false;
        endif;

        $error = $mailing->send_mail() ? false : true;
    }
}



  if(!$error) {
    
      $msg =  "Mail has been sent successfully!";
  }
               
        else{
            $msg = "Something went wrong. Please try again later!";
        }
    
}

    $selecting = $link->prepare("SELECT fullname, email, wallet FROM users ORDER BY id ASC");
    $selecting->execute();
    $selecting->bind_result($fullname, $user_email, $wallet);
    $selecting->store_result();

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

<script src="js/sendmail.js"></script>

<style>
  .select-who span{
    padding: 1%;
    padding-left: 2%;
    padding-right: 2%; 
    border-radius: 15px;
    cursor:pointer;
  }
  input[type="text"] {
      color: #000!important;
      font-weight: 600;
  }
  textarea{
      font-weight: 600;
  }
</style>

  <div class="content-wrapper">
  


  <!-- Main content -->
  <section class="content">


<div style="width:100%">
          <div class="box box-default">
            <div class="box-header with-border">

	<div class="row">


		 <h2 class="text-center">MAIL MANAGEMENT</h2>
		  </br>

     </br>

 
          <hr></hr>
          
            <div class="box-header with-border">
            
            <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
          </br>

     <form class="form" action="sendmail.php" method="POST" >
            <div style="width: 100%; text-align: center;" class="select-who">
              <span style="background-color: #5e5e5e; color: #fff;" data-elem="private" onclick="who(this)">Private</span> &emsp; <span data-elem="everyone" onclick="who(this)">Everyone</span>
            </div>
           <h3>Send Mail</h3>
		   
              <div class="form-group">

                <div class="form-group">
                    <div class="form-group">
                    <input type="text" name="emails" id="email" placeholder="Recipient Email" class="form-control">
                    </div>
                
                  <input type="text" name="recipients" value="private" id="who" hidden readonly>
                <input type="text" name="subject" placeholder="Email Subject"  class="form-control">
                </div>
                
                <div class="form-group">
                <textarea  name="message" placeholder="Write your mail here" class="form-control"></textarea>
              </div>
	  <button style="" type="submit" class="btn btn-primary" name="submit" > <i class="fa fa-send"></i>&nbsp; Send Mail </button>

    </form>
    <br><br>

    <div style="width:100%">
  <div class="box box-default">
    <div class="box-header with-border">

    <table class="display" style="width: 100%;" id="example">
      <thead>
        <tr>
            <th>Fullname</th>
            <th>Email</th>
            <th>Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php while($a = $selecting->fetch()): ?>
            <tr>
                <td><?= $fullname; ?></td>
                <td><?= $user_email; ?></td>
                <td><?= $wallet; ?></td>
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

