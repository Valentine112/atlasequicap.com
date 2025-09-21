<?php
session_start();

include "../../config/db.php";
include "header.php";
$msg = "";
use PHPMailer\PHPMailer\PHPMailer;
if(isset($_GET['email'])){
	$email = $_GET['email'];
}else{
	$email = '';
}


if(isset($_SESSION['uid'])){
	



}
else{


	header("location:../c2wadmin/signin.php");
}
 


  $sql= "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($link,$sql);
  if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
  }
        


    if(isset($_POST['edit'])){
      $fullname =$link->real_escape_string( $_POST['fullname']);
      $email =$link->real_escape_string( $_POST['email']);
      $status =$link->real_escape_string( $_POST['status']);
      $certificate = $link->real_escape_string( $_POST['certificate']);
      $wallet = $link->real_escape_string( $_POST['wallet']);
      $stock = $link->real_escape_string( $_POST['stock']);
      $crypto = $link->real_escape_string( $_POST['crypto']);
      $eth = $link->real_escape_string( $_POST['eth']);
      $btc =$link->real_escape_string( $_POST['btc']);
      $tether =$link->real_escape_string( $_POST['tether']);
          
      $updating = $link->prepare("UPDATE users SET fullname = ?, email = ?, status = ?, certificate = ?, wallet = ?, stock = ?, crypto = ?, eth = ?, btc = ?, usdt = ? WHERE email = ?");
      $updating->bind_param('ssiisssssss', $fullname, $email, $status, $certificate, $wallet, $stock, $crypto, $eth, $btc, $tether, $email);
      if($updating->execute()):
            $msg = "Account Details Edited Successfully!";
      else:
          $msg = "Cannot Edit Account! ";
      endif;
    }

  
  if(isset($row['active'])  && $row['active']==1){
    $acst = 'Activated &nbsp;&nbsp;<i style="color:green;font-size:20px;" class="fa  fa-check" ></i>';
    
  }else{
    $acst = 'Deactivated &nbsp;&nbsp;<i style="color:red;font-size:20px;" class="fa  fa-times" ></i>';
  }

  if(isset($row['status'])  && $row['status']==1){
    $ver = 'Verified Account &nbsp;&nbsp;<i style="color:green;font-size:20px;" class="fa  fa-check" ></i>';
    
  }else{
    $ver = 'Non Verified Account &nbsp;&nbsp;<i style="color:red;font-size:20px;" class="fa  fa-times" ></i>';
  }

?>



  <div class="content-wrapper">
  


  <!-- Main content -->
  <section class="content">


<div style="width:100%">
          <div class="box box-default">
            <div class="box-header with-border">

	<div class="row">


		 <h2 class="text-center">INVESTORS MANAGEMENT</h2>
		  </br>

</br>
          
          </div>

          <div class="section-body">
              
                  <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
                  <div class="col-lg-12">
                    
       </br>

          </br>
              
            <div class="invoice">
              <div class="invoice-print">
                <div class="row">
                   
                    <form action="user-edit.php?email=<?php echo $email ;?>" method="POST">

                <div class="">
                  <div class="col-md-12">
                   
                    <div class="table-responsive">
                      
                    <table class="table table-striped table-hover table-md">

                    <tr>
                      <th>Fullname</th>
                      <th>Email</th>
                      <th class="text-center">Status</th>
                      <th class="text-right">Trade Id</th>
                    </tr>

                    <tr>
                       <div class="form-group row mb-4">
                          <td> 
                            <input type="text" name="fullname" class="form-control" value="<?php echo  $row['fullname'] ;?>"> 
                          </td>
                        </div>
                      <div class="form-group row mb-4">
                        <td> 
                          <input type="text" name="email" class="form-control" value="<?php echo  $row['email'] ;?>"> </td>
</div>
                       <div class="form-group row mb-4">
                          <td> <input type="text" name="status" class="form-control" value="<?php echo  $row['status'] ;?>"> </td>
</div>

 <div class="form-group row mb-4">
                          <td> <input type="text" name="tradeid" class="form-control" value="<?= $row['tradeId'] ;?>" disabled> </td>
</div>
                    

                        </tr>

                        <tr>
                          <th>Certificate</th>
                          <th>Wallet</th>
                          <th>Stock</th>
                          <th>Crypto</th>
                        </tr>

                        <tr>
                          <div class="form-group row mb-4">
                            <td> 
                              <input type="text" name="certificate" class="form-control" value="<?= $row['certificate'] ;?>"> 
                            </td>
                          </div>
                          <div class="form-group row mb-4">
                            <td> 
                              <input type="text" name="wallet" class="form-control" value="<?= $row['wallet'] ;?>"> 
                            </td>
                          </div>
                          <div class="form-group row mb-4">
                            <td> 
                              <input type="text" name="stock" class="form-control" value="<?= $row['stock'] ;?>"> 
                            </td>
                          </div>
                          <div class="form-group row mb-4">
                            <td> 
                              <input type="text" name="crypto" class="form-control" value="<?= $row['crypto'] ;?>"> 
                            </td>
                          </div>
                        </tr>

                        <tr>
                          <th >ETH</th>
                         <th >BTC</th></th>
                          <th class="text-right">TETHER</th>
                        </tr>
                        <tr>
                        
                     
                    <div class="form-group row mb-4">
                          <td> <input type="text" name="eth" class="form-control" value="<?php echo $row['eth'] ;?>"></td>
                          </div>


                          </div><div class="form-group row mb-4">
                          <td> <input type="text" name="btc" class="form-control" value="<?php echo $row['btc'] ;?>"></td>

</div>
                    
<div class="form-group row mb-4">
                          <td> <input type="text" name="tether" class="form-control" value="<?php echo $row['usdt'] ;?>"></td>
                          </div>

                          </div>
                         
                        </tr>

                        
                        
                        
                        </br></br>






                       
                        </br></br>
                       
                      

                        <tr>
                          <td>
                        <button  type="submit" name="edit" class="btn btn-success btn-icon icon-left"><i class="fa fa-check"></i> Edit User Details</button></td>
                        </tr>

                </table>

                      </form>
                    </div>
                   
                        <hr>
             
              </div>
            </div>
 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
          </div>
        </section>
      </div>
      
    </div>
  </div>
