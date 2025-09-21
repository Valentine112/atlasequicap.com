<?php

include "../../config/config1.php";
require '../../vendor/autoload.php';
include "../../config/db.php";

use Service\{
  Mailing,
  EmailBody,
  Func
};
use Query\Insert;

include "header.php";
$msg = "";

if(!isset($_SESSION['uid'])){
	header("location:../c2wadmin/signin.php");
}

if(isset($_POST['submit'])):
    $username = explode(",", Func::cleanData($_POST['username'], 'string'));
    $followers = explode(",", Func::cleanData($_POST['followers'], 'string'));
    $roi = explode(",",  Func::cleanData($_POST['roi'], 'string'));
    $pnl = explode(",",  Func::cleanData($_POST['pnl'], 'string'));
    $win = explode(",",  Func::cleanData($_POST['win'], 'string'));
    $loss = explode(",",  Func::cleanData($_POST['loss'], 'string'));
    $even = explode(",",  Func::cleanData($_POST['even'], 'string'));

    $inserting = $link->prepare("INSERT into traders (name, followers, roi, pnl, win, loss, even) VALUES (?,?,?,?,?,?,?)");
    foreach($username as $ind => $val):
        // Check if username already exist
        $data = [
            "name" => $val,
            "1" => "1",
            "needle" => "id",
            "table" => "traders"
        ];
        $id = Func::searchDb($link, $data, "AND");
        if(empty($id)):
            // Proceed to insert
            $f = $followers[$ind] ?? 0;
            $r = $roi[$ind] ?? 0;
            $p = $pnl[$ind] ?? 0;
            $w = $win[$ind] ?? 0;
            $l = $loss[$ind] ?? 0;
            $e = $even[$ind] ?? 0;
            //$date = Func::dateFormat();

            $inserting->bind_param('siiiiii', $val, $f, $r, $p, $w, $l, $e);
            if($inserting->execute()):
                $msg = "Trader has been added";
            endif;
        else:
            $msg = "$val already exist";
        endif;

        // SteelRaven, CryptoNest, Mr_PorFit, Warp, Real_top_G, Okeansveta, Anjuta, Wizard
        // 220, 305, 569, 1280, 760, 480
        // 47, 30, 1175, 779, 90, 15
        // 1245, 3379, 32734, 6856, 586, 9821, 58964
        // 30, 55, 48, 90, 56, 75, 30
        // 10, 5, 7, 8, 4, 3, 3
        // 1, 0, 2, 1, 1, 3, 1
    endforeach;

endif;

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

  textarea{
      font-weight: 600;
      background-color: #222;
  }
</style>

  <div class="content-wrapper">
  


  <!-- Main content -->
  <section class="content">


    <div style="width:100%">
        <div class="box box-default">
            <div class="box-header with-border">
	            <div>
                    <h2 class="text-center">Traders Management</h2>
                        <div class="box-header with-border">
                        
                        <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg </div class='btn btn-success'></br></br>";  ?>


                        <form class="form" action="addtrader.php" method="POST" >
                            <h3>Add Traders</h3>
                            <div class="row justify-content-around">

                                <div class="form-group col-lg-5">
                                    <label for="username">Username</label>
                                    <textarea name="username" id="username" class="form-control" cols="30" rows="10" placeholder="Add username"></textarea>
                                </div>

                                <div class="form-group col-lg-5">
                                    <label for="followers">Followers</label>
                                    <textarea name="followers" id="followers" class="form-control" cols="30" rows="10" placeholder="Add followers"></textarea>
                                </div>

                                <div class="form-group col-lg-5">
                                    <label for="roi">Return on Investment</label>
                                    <textarea name="roi" id="roi" class="form-control" cols="30" rows="10" placeholder="Add roi"></textarea>
                                </div>

                                <div class="form-group col-lg-5">
                                    <label for="pnl">Profit and Loss</label>
                                    <textarea name="pnl" id="pnl" class="form-control" cols="30" rows="10" placeholder="Add pnl"></textarea>
                                </div>

                                <div class="form-group col-lg-5">
                                    <label for="win">Win</label>
                                    <textarea name="win" id="win" class="form-control" cols="30" rows="10" placeholder="Add win"></textarea>
                                </div>

                                <div class="form-group col-lg-5">
                                    <label for="loss">Loss</label>
                                    <textarea name="loss" id="loss" class="form-control" cols="30" rows="10" placeholder="Add loss"></textarea>
                                </div>

                                <div class="form-group col-lg-5">
                                    <label for="even">Even</label>
                                    <textarea name="even" id="even" class="form-control" cols="30" rows="10" placeholder="Add even"></textarea>
                                </div>

                                <div class="form-group col-lg-8">
                                    <button style="" type="submit" class="btn btn-primary" name="submit"> <i class="fa fa-plus"></i>&nbsp; Add trader</button>
                                </div>
                            </div>   
                        </form>
                    <br><br>
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

