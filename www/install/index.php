<?php
ini_set('error_reporting',E_WARNING|E_PARSE|E_ERROR);


$domain=$_SERVER['HTTP_HOST'];
if($domain=='[::1]')$domain='localhost';
$config_file=($domain=='localhost')?'../localhost_config.php':'../config.php';

if(file_exists($config_file)&&!isset($_POST['install'])){
	include($config_file);
	$_POST['currency_code']=_CURRENCY_CODE_;
	$_POST['db_host']=_DB_HOST_;
	$_POST['db_name']=_DB_NAME_;
	$_POST['db_prefix']=_DB_PREFIX_;
	$_POST['db_user']=_DB_USERNAME_;
	$_POST['db_pass']=_DB_PASS_;
	$_POST['admin_email']=_ADMIN_EMAIL_;
	$_POST['admin_pass']=_ADMIN_PASS_;
	
	if(defined('_BASE_URL_'))$_POST['base_url']=_BASE_URL_;
	if(defined('_DEFAULT_MAIL_SENDER_'))$_POST['default_mail_sender']=_DEFAULT_MAIL_SENDER_;
	if(defined('_SMTP_HOST_'))$_POST['smtp_host']=_SMTP_HOST_;
	if(defined('_SMTP_USER_'))$_POST['smtp_user']=_SMTP_USER_;
	if(defined('_SMTP_PASS_'))$_POST['smtp_pass']=_SMTP_PASS_;
	if(defined('_SMTP_PORT_'))$_POST['smtp_port']=_SMTP_PORT_;
}

if(empty($_POST['db_host']))$_POST['db_host']='localhost';
if(empty($_POST['template']))$_POST['template']='default';
if(empty($_POST['currency_code']))$_POST['currency_code']='NGN';

$sample_host="mail.$domain";
$sample_user="no_reply@$domain";


if(empty($_POST['base_url'])){
	$is_https=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')||$_SERVER['SERVER_PORT']==443;
	$url=$is_https? 'https://' : 'http://';
	$url.=$domain.dirname($_SERVER['PHP_SELF']);
	$_POST['base_url']=dirname($url);
}
$_POST['base_url']=rtrim($_POST['base_url'],'/').'/';


if(empty($_POST['smtp_host']))$_POST['smtp_host']=$sample_host;
if(empty($_POST['smtp_user']))$_POST['smtp_user']=$sample_user;
if(empty($_POST['smtp_port']))$_POST['smtp_port']=587;

if(isset($_POST['install']))
{
	$cid=@mysqli_connect('localhost', $_POST['db_user'], $_POST['db_pass'],$_POST['db_name']);	
	
	if(!$cid)$Error=mysqli_connect_error()." Please make sure you have already created the database and assigned a user to it.";	
	elseif(!file_exists('install.sql'))$Error="Could not load sql file:  install.sql";
	elseif(($file=@fopen($config_file, "w"))===false)$Error='Update configuration parameters failed. Check write permissions for file "config.htm".';
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
		"define('_ADMIN_PASS_',\"{$_POST['admin_pass']}\");\n\n".
		"define('_BASE_URL_','{$_POST['base_url']}');\n".
		"define('_DEFAULT_MAIL_SENDER_','{$_POST['default_mail_sender']}');\n\n".
		
		"define('_SMTP_HOST_','{$_POST['smtp_host']}');\n".
		"define('_SMTP_USER_','{$_POST['smtp_user']}');\n".
		"define('_SMTP_PASS_','{$_POST['smtp_pass']}');\n".
		"define('_SMTP_PORT_','{$_POST['smtp_port']}');\n".
		"?>";
		
		fwrite($file, $str);
		fclose($file);
		mysqli_set_charset($cid,'utf8');
		
		$lines = file('install.sql');
		if ($lines) 
		{
			mysqli_query($cid,"SET sql_mode = ''");
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
		
		$sql2="SELECT * FROM {$_POST['db_prefix']}currencies WHERE 1 LIMIT 1";
		$res2=mysqli_query($cid,$sql2);
		if(mysqli_num_rows($res2)==0){
			$sql2="INSERT INTO `{$_POST['db_prefix']}currencies`(`currency`,`iso_code`,`symbol`,`currency_title`,`value`) VALUES ('NGN','566','₦','Nigerian Naira',1),('USD','840','$','US Dollars','0.0050'),('EUR','978','€','Euro','0.0046'),('GBP','826','£','Great Britain Pounds','0.0032'),('BTC','100','Ƀ','Bitcoin','0.0000025');";
			$res2=mysqli_query($cid,$sql2);
		}
		
		if($domain!='localhost'){
			@unlink('../install/install.sql');
			@unlink('../install/index.php');
			@rmdir('../install');
		}
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
  <body style='background-color:#eeeeee;'>
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
				
					<div class='row'>
					<div class='form-group col-sm-6'>
						<label for='base_url'>Base URL</label>
						<input type='url'  name='base_url' value="<?php echo $_POST['base_url'];?>" title='Most likely the parent directory to this path' class='form-control input-sm'/>
					</div>
					<div class='form-group col-md-6 col-sm-6'>
						<label for='db_pass'>
							Default Mail Sender
						</label>
						<input type='text' name='default_mail_sender' value="<?php echo $_POST['default_mail_sender'];?>" class='form-control input-sm' placeholder='WebsiteName' >
					</div>
				</div>
				<div class='text-warning'><strong>NOTE:</strong> If any one of the SMTP parameters is missing, the site will not use SMTP</div>
				<div class='row'>					
					<div class='form-group col-sm-6'>
						<label for='smtp_host'>SMTP HOST</label>
						<input type='text' name='smtp_host' value="<?php echo $_POST['smtp_host'];?>" class='form-control input-sm' placeholder='<?php echo $sample_host; ?>' >
					</div>
					
					<div class='form-group col-sm-6'>
						<label for='smtp_user'>SMTP USER</label>
						<input type='email' name='smtp_user' value="<?php echo $_POST['smtp_user'];?>" class='form-control input-sm' placeholder='<?php echo $sample_user; ?>' >
					</div>
					
					<div class='form-group col-sm-6'>
						<label for='smtp_pass'>SMTP PASS</label>
						<input type='password' name='smtp_pass' value="<?php echo $_POST['smtp_pass'];?>" class='form-control input-sm' placeholder='SMTP Email Password' >
					</div>
					
					<div class='form-group col-sm-6'>
						<label for='smtp_port'>SMTP PORT</label>
						<input type='number' name='smtp_port' min='1' value="<?php echo $_POST['smtp_port'];?>" class='form-control input-sm' placeholder='mostly: 587, non-ssl=25, ssl=465' >
					</div>
					
				
				</div>
				
				<div class='row'>
					<div class='form-group col-sm-6' title='Where all the administrative reports will be sent.'>
						<label for='admin_email'>
							Admin Email
						</label>
						<input type='email' name='admin_email' value="<?php echo $_POST['admin_email'];?>" class='form-control input-sm' required/>
					</div>
					<div class='form-group col-sm-6'>
						<label for='admin_pass'>
							Admin Password
						</label>
						<input type='text' name='admin_pass' id='admin_pass' value="<?php echo $_POST['admin_pass'];?>" class='form-control input-sm' required>
					</div>
					<div class='clearfix'></div>
					<div class='text-danger'>Please keep in mind that you will need this admin email & password to access the panel</div>
				</div>
					
				<div class='clearfix'></div>
				<div>
					<button class='btn btn-default btn-sm pull-right' value='install' name='install'><span class='glyphicon glyphicon-save'> INSTALL</button>
				</div>			
			</form>
		</div>
	</div>
  </body>
</html>