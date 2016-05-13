<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title;?></title>
    <link href="<?php echo $home_url;?>bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script src="<?php echo $home_url;?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $home_url;?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo $home_url;?>assets/js/jquery.autovalidate.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style type='text/css' rel='stylesheet'>
		body,html{min-height:100%; height:100%;}
		
		.top_banner
		{
			min-height:100px;
			background-color:#ffffff;
			padding-top:10px;
		}
		
		.navbar
		{
			border-top:1px solid #ccc;
			border-bottom:1px solid #ccc;
		}
		
		
		.form-group label
		{
			font-size:120%;
		}
		
		#page_body
		{
			min-height:34em;
		}
	</style>
  </head>
  <body>
	<div class='container' >
		<div class='top_banner '>
			<h1>
				<?php echo $site_name;?>
			</h1>
		</div>
		<div id='page_body'>
			<form role='form' method='post'>
				<h3 class='breadcrumb' style='border-bottom:1px solid #ccc;'><span class='glyphicon glyphicon-log-in'></span> Admin Login</h3>
				<?php if(!empty($Error)){ ?>
					<div class='alert alert-warning fade in'>
						<span class='close' data-dismiss='alert'>&times;</span>
						<?php echo $Error;?>
					</div>
				<?php }  if(!empty($Success)){ ?>
				<div class='alert alert-success'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<?php echo $Success;?>
				</div>
			<?php }  ?>
				<div class='col-md-3 col-md-offset-4  col-sm-4 col-sm-offset-3' style='border:1px solid #ccc;padding:25px 5px;border-radius:5px;'>					
					<label>Email</label>
					<div class='input-group'>
						<span class='input-group-addon'>@</span>
						<input type='email' name='email' placeholder='email' class='form-control pull-right' required />		
					</div>
					<br/>
					<div class='clearfix'></div>
						<label>Password</label>
					<div class='input-group'>
						<span class='input-group-addon'>
							<span class='glyphicon glyphicon-lock'></span>
						</span>
						<input type='password' name='password' placeholder='password' class='form-control pull-right' required />
					</div>
					<br/>
					<button class='btn btn-primary pull-right' style='margin-right:10px;'> Login</button>
					<div class='clearfix'></div>
					<hr style='margin:10px 0px;'/>
					<a href='#' style='float:right;margin-right:10px;'>I forgot my password</a>
				</div>		
			</form>
		</div>
		<hr/>
		<center class='footer'>
			All rights reserved &copy 2014
		</center>
	</div>
  </body>
</html>