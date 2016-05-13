<!DOCTYPE html><html lang="en">
<head>  
<meta charset="utf-8">  
<meta http-equiv="X-UA-Compatible" content="IE=edge">  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title><?php echo $page_title;?></title>	  
<link rel="shortcut icon" href="<?php echo $this->general_model->get_url('favicon.ico'); ?>" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?php echo $home_url;?>assets/css/font-awesome.min.css" />
<link href="<?php echo $home_url;?>bootstrap/css/bootstrap.min.css" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="<?php echo $home_url;?>assets/css/jquery-ui.css" />  
<link rel='stylesheet' type='text/css' href='<?php echo $this->general_model->get_url('assets/css/bootstrap-datetimepicker.min.css');?>' />
		
<script src="<?php echo $this->general_model->get_url('assets/js/modernizr-inputtypes.js');?>"></script>
<script src="<?php echo $home_url;?>assets/js/jquery.min.js"></script>  
<script src="<?php echo $home_url;?>assets/js/jquery-ui.js"></script>  

<script src="<?php echo $this->general_model->get_url('assets/js/moment.min.js');?>"></script>
<script src="<?php echo $this->general_model->get_url('assets/js/moment_locales_en_gb.js');?>"></script>
<script src="<?php echo $this->general_model->get_url('assets/js/bootstrap-datetimepicker.min.js');?>"></script>

<script src="<?php echo $home_url;?>bootstrap/js/bootstrap.min.js"></script>  
<script type='text/javascript'>var base_url='<?php echo base_url();?>';</script>	  
<script src="<?php echo $this->general_model->get_url('assets/js/script.js');?>"></script>
<!--[if lt IE 9]>    
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>    
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>  
<![endif]-->
<style type='text/css' rel='stylesheet'>
	input::-webkit-outer-spin-button,input::-webkit-inner-spin-button
	{
		-webkit-appearance: none;
		margin: 0;
	}
	
	input[type=number] {-moz-appearance: textfield;}
	.nav-pills li a{ font-size:10px; }
	.currency_div{border-bottom: 1px dotted #ddd;}
	
	 .input-xs {
	height: 22px;
	padding: 2px 5px;
	font-size: 12px;
	line-height: 1.5; /*If Placeholder of the input is moved up, rem/modify this.*/
	border-radius: 3px;
}

.input-group-xs>.form-control,
.input-group-xs>.input-group-addon,
.input-group-xs>.input-group-btn>.btn {
    height: 22px;
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5;
}
</style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $this->general_model->get_url('panel'); ?>"><?php echo $configs['site_name']; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			<i class='fa fa-list'></i> Reports <span class="caret"></span>
		  </a>
          <ul class="dropdown-menu">
            <li class='<?php if($page_name=='admin_transaction_history')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_transaction_history');?>">
					<i class='fa fa-list-alt'></i> Transactions
				</a>
			</li>
            <li class='<?php if($page_name=='admin_manage_users')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_manage_users');?>">
					<i class='fa fa-user'></i> Users
				</a>
			</li>
            <li class='<?php if($page_name=='admin_sms_log')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_sms_log');?>">
					<i class='fa fa-table'></i> SMS Log
				</a>
			</li>
            <li class='<?php if($page_name=='admin_error_log')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_error_log');?>">
					<i class='fa fa-exclamation-triangle'></i> Error Log
				</a>
			</li>
            <li class='<?php if($page_name=='admin_whitelisted_messages')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_whitelisted_messages');?>">
					<i class='fa fa-check'></i> Whitelisted Messages
				</a>
			</li>
             <li role="separator" class="divider"></li>
            <li class='<?php if($page_name=='admin_voguepay')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_voguepay');?>">
					<i class='fa fa-vimeo-square'></i> Voguepay
				</a>
			</li>
          </ul>
		</li>
		
        <li><a href="<?php echo $this->general_model->get_url();?>"><i class='fa fa-reply'></i> Main Site</a></li>
	 </ul>
	  
	  <ul class="nav navbar-nav navbar-right">
        <li <?php if($page_name=='admin_send_email')echo "class='active'";?> ><a href="<?php echo $this->general_model->get_url('admin_send_email');?>"><i class='fa fa-send'></i>	 Send Email</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class='fa fa-cog'></i> Settings <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class='<?php if($page_name=='admin_currencies')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_currencies');?>">
					<i class='fa fa-exchange'></i> Currencies
				</a>
			</li>
            <li class='<?php if($page_name=='admin_manage_prices')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_manage_prices');?>">
					<i class='fa fa-money'></i> Price List
				</a>
			</li>
            <li class='<?php if($page_name=='admin_payment_gateways')echo 'active';?>'>
				<a href="<?php echo $this->general_model->get_url('admin_payment_gateways');?>">
					<i class='fa fa-credit-card'></i> Payment Gateways
				</a>
			</li>
            <li role="separator" class="divider"></li>
            <li><a href="<?php echo $this->general_model->get_url('panel/?logout=1');?>"><i class='fa fa-power-off'></i> logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class='container' >