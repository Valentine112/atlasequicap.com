<?php
session_start();

include "../../config/db.php";

$msg = "";
use PHPMailer\PHPMailer\PHPMailer;

if(!isset($_SESSION['uid'])){

	header("location:../c2wadmin/signin.php");
}


if(isset($_POST['ubank'])){
    $eth = $_POST['eth'];
    $btc = $_POST['btc'];
    $xrp = $_POST['xrp'];
    $tether = $_POST['tether'];
    
    $updating = $link->prepare("UPDATE admin SET eth = ?, btc = ?, xrp = ?, tether = ? WHERE email = ?");
    $updating->bind_param('sssss', $eth, $btc, $xrp, $tether, $_SESSION['email']);
    if($updating->execute()):
        $msg = "Successfully updated wallets";
    endif;
}

include "header.php";


    ?>

 <div class="content-wrapper">
  


  <!-- Main content -->
  <section class="content">


<div class="col-md-12 col-sm-12 col-sx-12">
          <div class="box box-default">
            <div class="box-header with-border">

          <h4 align="center"><i class="fa fa-plus"></i> WALLET MANAGEMENT</h4>
</br>

          <hr></hr>
          
        
          
            <div class="box-header with-border">
            
            <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
          </br>
          
          
            <?php   
        $sql1= "SELECT * FROM admin";
  $result1 = mysqli_query($link,$sql1);
  if(mysqli_num_rows($result1) > 0){
  $row = mysqli_fetch_assoc($result1);

    if(isset($row['eth']) && isset($row['btc'])  && isset($row['xrp'])){
  $eth = $row['eth'];
  $btc = $row['btc'];
  $xrp = $row['xrp'];
  $tether = $row['tether'];

 
}else{
  $ew="cant find wallet";
   $bw="cant find wallet";
    $an="cant find wallet";
     
}
}
          ?>

     <form class="form-horizontal" action="addwallet.php" method="POST" >

           <legend>Add Wallet Details</legend>
		   
		   
		   <input type="hidden" name="email"  value="<?php echo $_SESSION['email'];?>" class="form-control">

     <div class="form-group">
         <label>Ethereum Wallet</label>
        <input type="text" name="eth" value="<?php echo $eth ;?>" placeholder="Ethereum wallet"  class="form-control form-inp">
        </div>

       <div class="form-group">
            <label>Bitcoin Wallet</label>
        <input type="text" name="btc" value="<?php echo $btc ;?>" placeholder="Bitcoin wallet"  class="form-control form-inp">
      </div>

      <div class="form-group">
           <label>XRP Wallet</label>
        <input type="text" name="xrp" value="<?php echo $xrp ;?>" placeholder="XRP wallet"   class="form-control form-inp">
      </div>

      <div class="form-group">
           <label>Tether Address</label>
        <input type="text" name="tether" value="<?php echo $tether ;?>" placeholder="Tether Address" class="form-control form-inp">
      </div>
    <div style="margin-top: 3%; text-align: center;">
      <button style="" type="submit" class="btn btn-primary" name="ubank">Upgrade Details </button>
    </div>
	  
	  
    </form>
    </div>
   </div>

   </div>
  </div>
  </section>
</div>

