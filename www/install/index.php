<?php
ini_set('error_reporting',E_WARNING|E_PARSE|E_ERROR);

if(file_exists("../config.php")&&!isset($_POST['install']))
{
	include('../config.php');
	$_POST['currency_code']=_CURRENCY_CODE_;
	$_POST['db_host']=_DB_HOST_;
	$_POST['db_name']=_DB_NAME_;
	$_POST['db_prefix']=_DB_PREFIX_;
	$_POST['db_user']=_DB_USERNAME_;
	$_POST['db_pass']=_DB_PASS_;
	$_POST['admin_email']=_ADMIN_EMAIL_;
	$_POST['admin_pass']=_ADMIN_PASS_;
}

if(empty($_POST['db_host']))$_POST['db_host']='localhost';
if(empty($_POST['template']))$_POST['template']='default';
if(empty($_POST['currency_code']))$_POST['currency_code']='NGN';

if(isset($_POST['install']))
{
	$cid=@mysqli_connect('localhost', $_POST['db_user'], $_POST['db_pass'],$_POST['db_name']);	
	
	if(!$cid)$Error=mysqli_connect_error()." Please make sure you have already created the database and assigned a user to it.";	
	elseif(!file_exists('install.sql'))$Error="Could not load sql file:  install.sql";
	elseif(($file=@fopen("../config.php", "w"))===false)$Error='Update configuration parameters failed. Check write permissions for file "config.htm".';
	else
	{
		$str=
		"<?php\n".
		"define('_DB_HOST_','{$_POST['db_host']}');\n".
		"define('_CURRENCY_CODE_','{$_POST['currency_code']}');\n".
		"define('_DB_NAME_','{$_POST['db_name']}');\n".
		"define('_DB_PREFIX_','{$_POST['db_prefix']}');\n".
		"define('_DB_USERNAME_','{$_POST['db_user']}');\n".
		"define('_DB_PASS_',\"{$_POST['db_pass']}\");\n".
		"define('_ADMIN_EMAIL_','{$_POST['admin_email']}');\n".
		"define('_ADMIN_PASS_',\"{$_POST['admin_pass']}\");\n".
		"?>";
		
		fwrite($file, $str);
		fclose($file);
		
		$lines = file('install.sql');
		if ($lines) 
		{
			$sql = '';
			foreach($lines as $line) 
			{
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) 
				{
					$sql .= $line;
					if (preg_match('/;\s*$/', $line)) 
					{
						$sql = str_replace("DROP TABLE IF EXISTS `gm_", "DROP TABLE IF EXISTS `" .$_POST['db_prefix'], $sql);
						$sql = str_replace("CREATE TABLE IF NOT EXISTS `gm_", "CREATE TABLE IF NOT EXISTS `" .$_POST['db_prefix'], $sql);
						$sql = str_replace("INSERT INTO `gm_", "INSERT INTO `" .$_POST['db_prefix'], $sql);
						$sql = str_replace("ALTER TABLE `gm_", "ALTER TABLE `" .$_POST['db_prefix'], $sql);
						
						$sql=trim($sql);
						
						if(substr($sql,0,5)=='ALTER')@mysqli_query($cid,$sql);
						else mysqli_query($cid,$sql) or die(mysqli_error($cid). "<br/>FROM: $sql ");
						$sql = '';
					}
				}
			}
		}
		
		//@unlink('../install/install.sql');
		//@unlink('../install/index.php');
		//@rmdir('../install');
		header("Location:../panel");
	}
}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Website Configuration</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class='container'>
		<div class='col-xs-6 col-xs-offset-3'>
			<h3 class='breadcrumb'>
				<span class='glyphicon glyphicon-wrench'></span>
				Configuration Settings
			</h3>
			<form role='form' method='post'>
				<?php
					if(!empty($Error))
					{
				?>
					<div class='alert alert-warning'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<?php echo $Error;?>
					</div>
				<?php
					}
				?>
				
				<div class='row'>
				<div class='form-group col-md-3'>
					<label for='currency_code'>Currency</label>
					<select name='currency_code' class='form-control' required>
						<?php
						$currency_codes=array('NGN','USD');
						foreach($currency_codes as $currency_code)
						{
						?>
							<option <?php echo "value='$currency_code'"; if($_POST['currency_code']==$currency_code)echo 'selected';?> >
								<?php echo $currency_code; ?>
							</option>
						<?php
						}
						?>
					</select>
				</div>
				<div class='form-group col-md-5'>
					<label for='db_host'>
						Database Host
					</label>
					<input type='text' name='db_host' value="<?php echo $_POST['db_host'];?>" class='form-control' placeholder='localhost' required>
				</div>
				<div class='form-group col-md-4'>
					<label for='db_name'>
						Database Name*
					</label>
					<input type='text' name='db_name' value="<?php echo $_POST['db_name'];?>" class='form-control' placeholder='cgsms' required>
				</div>
				</div>
				<div class='row'>
				<div class='form-group col-md-3'>
					<label for='db_prefix'>
						Db Prefix
					</label>
					<input type='text' name='db_prefix' value="<?php echo $_POST['db_prefix'];?>" class='form-control' placeholder='gm_'>
				</div>
				<div class='form-group col-md-5'>
					<label for='db_user'>
						Database User*
					</label>
					<input type='text' name='db_user' value="<?php echo $_POST['db_user'];?>" class='form-control' required>
				</div>
				<div class='form-group col-md-4'>
					<label for='db_pass'>
						Database Password
					</label>
					<input type='text' name='db_pass' value="<?php echo $_POST['db_pass'];?>" class='form-control'>
				</div>
				</div>
				<div class='form-group' title='Where all the administrative reports will be sent.'>
					<label for='admin_email'>
						Admin Email
					</label>
					<input type='email' name='admin_email' value="<?php echo $_POST['admin_email'];?>" class='form-control' required/>
				</div>
				<div class='form-group'>
					<label for='admin_pass'>
						Admin Password
					</label>
					<input type='text' name='admin_pass' id='admin_pass' value="<?php echo $_POST['admin_pass'];?>" class='form-control' required>
					<div class='text-danger'>Please note the admin email and password details. You will need it to access the admin panel.</div>
				</div>		
				<div class='clearfix'></div>
				<div>
					<button class='btn btn-primary pull-right' value='install' name='install'><span class='glyphicon glyphicon-save'> INSTALL</button>
				</div>			
			</form>
		</div>
	</div>
  </body>
</html>