<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../../logo/logo.png">
  <title>QFSLedgerConnect Administration</title>
  <!-- Tell the browser to be responsive to screen width -->

      <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <meta content="width=initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
 
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">



  <link rel="stylesheet" href="css/page.css">
  <!-- Font Awesome -->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/header.css">


</head>
<body class="cover-body">
  <div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a  class="logo">
     
     
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ADMIN PANEL</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a  class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
      
            <li>
                
                
            </li>   
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
  <style>
      a{
          color:#fff;
      }
  </style>     
        
      </div>
  
     
      <ul class="sidebar-menu" data-widget="tree" >
        <li class="header" style="color: #fff;">WELCOME <?php  echo $_SESSION['email'];?></li>
       
      
       
        <li><a href="index.php?"><i class="fa fa-home"></i> Home </a>

                  </li>
                  
                  
                  
                  
                  
                   <li class="treeview">
          <a href="#">
            <i class="fa fa-lock"></i>
            <span> Security</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="password.php"><i class="fa fa-refresh"></i>Change Password</a></li>
            
            <!--<li><a href="deactivate.php"><i class="fa fa-times"></i> Deactive Authenticator</a></li>-->
            
          </ul>
        </li>
                  
                  <li><a href="log-outa.php">
				  <i class="fa  fa-sign-out"></i> Log-out</a>
          </li>
              <!--<li><a href="settings.php">
				  <i class="fa  fa-cog"></i> Settings</a>
          </li>-->
          
          <li><a href="addwallet.php">
				  <i class="fa fa-plus"></i> Admin Wallet</a>
          </li>
            <li><a href="addtrader.php">
				  <i class="fa fa-plus"></i> Add Copytraders</a>
          </li>

          <li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span> Investors Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <!--<li><a href="users.php"><i class="fa fa-users"></i> View Investors</a></li>-->
            
            <!--<li><a href="../../logs/signup.php"><i class="fa fa-users"></i> Add Investors</a></li>-->
            
            <!--<li><a href="verify.php"><i class="fa fa-check"></i> Verify Investors</a></li>-->
            <li><a href="edit.php"><i class="fa fa-users"></i> Manage Users</a></li>
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i>
            <span> Requested Transactions</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="withdraw.php"><i class="fa fa-refresh"></i> Withdrawal Request</a></li>
            <li><a href="deposit.php"><i class="fa fa-send"></i> Deposited Orders</a></li>
            
            <!--<li><a href="deposit.php"><i class="fa fa-refresh"></i> Deposits</a></li>-->
          </ul>
        </li>
		
      <li><a href="linked.php">
			  <i class="fa  fa-plus"></i> View Linked wallets</a>
      </li>

        <li><a href="sendmail.php">
          <i class="fa fa-comment"></i> Send Mail</a>
        </li>
       
       
       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  
   



<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!--jQuery UI 1.11.4-->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
