<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		if(empty($og_description))$og_description=$configs['site_meta_description'];
	?>
    <meta name="description" content="<?php echo $og_description; ?>">
    <meta name="author" content="Tormuto.com">
	<meta name='keywords' content="<?php echo $configs['site_meta_keywords']; ?>">
	<meta name='copyright' content="<?php echo $configs['site_meta_copyright']; ?>">

    <title><?php
		if(empty($page_title))$page_title=$site_name;		
		echo ($page_title==$site_name)?$site_name:"$page_title - $site_name";
	?></title>
	
	<link rel="shortcut icon" href="<?php echo $this->general_model->get_url('favicon.ico'); ?>" type="image/x-icon" />
	<script src="<?php echo $this->general_model->get_url('assets/js/modernizr-inputtypes.js');?>"></script>
	<script src="<?php echo $this->general_model->get_url('assets/js/jquery.min.js');?>"></script>
	
	<link href="<?php echo $this->general_model->get_url('bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
	<link href="<?php echo $this->general_model->get_url('assets/css/custom_bootstrap_header.css');?>" rel="stylesheet">
	
	<?php
		if(!empty($configs['facebook_app_id']))
		{
			if(empty($og_image))$og_image=$this->general_model->get_url('cheap_global_sms_image1.jpg');
	?>
		<meta property='og:title' content="<?php echo $page_title;?>" />
		<meta property='og:type' content="business" />
		<meta property='og:url' content="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" />
		<meta property='og:site_name' content="<?php echo $configs['site_name']; ?>" />
		<meta property='og:image' content='<?php echo $og_image;?>' />
		<meta property='fb:app_id' content='<?php echo $configs['facebook_app_id'];?>' />
		<?php
		if(!empty($og_description)){
		?>
		<meta property='og:description' content="<?php echo str_replace('"',' ',$og_description);?>" />
		<?php }?>
	<?php
		}
	?>
	
</head>
<body style='background-color:#fff;' >
    <div id="wrapper">
        <nav class="navbar navbar-inverse navbar-fixed-top-x" role="navigation" id='top_navbar'>
        <div class="container container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                </button>
				<a href='<?php echo $this->general_model->get_url();?>' class='navbar-brand <?php if($page_name=='dashboard')echo 'active'; ?>"'  >
					<img src='<?php echo $this->general_model->get_url('assets/images/logo.png'); ?>' alt='<?php echo $site_name; ?>' style='max-height:50px;'/>
                    <?php echo $site_name; ?>
				</a>
            </div>
            <div class="collapse navbar-collapse" id='myNavbar'>
                <ul class="nav navbar-nav">
                    <li class="<?php if($page_name=='send_sms')echo 'active'; ?>">
                        <a href="<?php echo $this->general_model->get_url('send_sms');?>"><i class="fa fa-fw fa-send"></i> Send SMS</a>
                    </li>
                    <li class="<?php if($page_name=='pricing')echo 'active'; ?>">
                        <a href="<?php echo $this->general_model->get_url('pricing');?>"><i class="fa fa-fw fa-table"></i> Pricing</a>
                    </li>
                    <li  class="<?php if($page_name=='coverage_list')echo 'active'; ?>">
                        <a href="<?php echo $this->general_model->get_url('coverage_list');?>"><i class="fa fa-fw fa-globe"></i> Coverage</a>
                    </li>
                    <li  class="dropdown <?php if(!empty($current_tab)&&$current_tab=='sms_log')echo 'active'; ?>">
                        <a href="#" class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false' >
							<i class="fa fa-fw fa-list"></i> Reports<i class="fa fa-fw fa-caret-down pull-right"></i>
						</a>
                        <ul class="dropdown-menu">                           
							<li>
								<a href="<?php echo $this->general_model->get_url('sms_log');?>">
									<i class="fa fa-fw fa-list"></i> All Delivery Reports
								</a>
							</li>
							<li>
								<a href="<?php echo $this->general_model->get_url('sms_log?stage=pending');?>">
									<i class="fa fa-fw fa-calendar"></i> Scheduled Messages
								</a>
							</li>
							<li>
								<a href="<?php echo $this->general_model->get_url('sms_log?stage=sent');?>">
									<i class="fa fa-fw fa-envelope-o"></i> Sent Messages
								</a>
							</li>
							<li>
								<a href="<?php echo $this->general_model->get_url('sms_log?stage=failed');?>">
									<i class="fa fa-fw fa-warning"></i> Undelivered Messages
								</a>
							</li>
                        </ul>
                    </li>   
                    <li  class='dropdown'>
                        <a href="#" class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
							<i class="fa fa-fw fa-wrench"></i> Tools<i class="fa fa-fw fa-caret-down pull-right"></i>
						</a>
                        <ul class="dropdown-menu">  
                            <li  class="<?php if($page_name=='my_contacts')echo 'active'; ?>">
                                <a href="<?php echo $this->general_model->get_url('my_contacts');?>">
                                    <i class="fa fa-fw fa-mobile"></i> Contacts Manager
                                </a>
                            </li>
							<li>
                                <a href="<?php echo $this->general_model->get_url('gateway_api');?>"><i class="fa fa-fw fa-wrench"></i> Gateway API</a>
							</li>
							<li>
								<a href="<?php echo $this->general_model->get_url('reseller');?>" style='font-weight:800;' ><i class="fa fa-fw fa-recycle"></i> Bulk SMS Reseller</a>
							</li>
                        </ul>
                    </li>
					<li>                        
                        <div style='display:inline-block;float:right;margin:10px;margin-top:15px;'>
                            <span class='dropdown' >
                                <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false' title='Change Langugage'>
                                    <i class='fa fa-language' style='color:#ffffff;'></i>
                                    <img src='//:0' alt='' class='flag flag_GBR' id='google_translate_flags_main_img' />
                                </a>
                                <ul class='dropdown-menu' style='max-height:400px;overflow:auto;' id='google_translate_flags_container'></ul>
                            </span>
                          </div>
					</li>
				</ul>
            
                <ul class="nav navbar-nav navbar-right">
                    <?php if($this->general_model->logged_in() && !empty($my_profile)){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i> <?php echo $my_profile['default_sender_id']; ?> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo $this->general_model->get_url('pricing');?>"><i class="fa fa-fw fa-database"></i><?php echo $my_profile['balance']; ?> Units</a>
                            </li>
                            <?php if(!empty($my_profile['reseller_account'])){ ?>
                            <li>
                                <a href='<?php echo $this->general_model->get_url('?refill_surety=1');?>' >
                                <?php echo empty($my_profile['owing_surety'])?"<i class='fa fa-check text-success'></i>":"<i class='fa fa-warning-sign text-danger'></i>"; ?> Reseller Surety
                               </a>
                            </li>
                            <?php } ?>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('sub_accounts');?>"><i class="fa fa-fw fa-cubes"></i> Sub Accounts</a>
                            </li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('transaction');?>" style='white-space:nowrap;'><i class="fa fa-fw fa-list"></i> My Transactions</a>
                            </li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('profile'); ?>"><i class="fa fa-fw fa-user"></i> Profile</a>
                            </li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('reset_password'); ?>"  style='white-space:nowrap;' ><i class="fa fa-fw fa-key"></i> Reset Password</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('?logout=1'); ?>" style='color:#A94442;' ><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?php echo $this->general_model->get_url('pricing');?>"><i class="fa fa-fw fa-database"></i> 	<?php 
                                if($my_profile['balance']<0&&$my_profile['flag_level']>0)echo "<span class='text-danger'>-0</span>"; 
                                else echo  $my_profile['balance'];
                            ?>
                        </a>
                    </li>
                    <?php } else { ?>
                        <li>
                            <a href="javascript:void(0)" data-toggle='modal' data-target='#login'> Login</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle='modal' data-target='#signup' > Register</a>
                        </li>
                    <?php } ?>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu alert-dropdown">
                            <li>
                                <a href="<?php echo $this->general_model->get_url('faqs');?>">FAQs</a>
                            </li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('terms');?>">Terms & Condition</a>
                            </li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('privacy_policy');?>">Privacy</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo $this->general_model->get_url('contact_us');?>">Contact Us</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                
            
            </div>
        </div>
        </nav>
<?php  if(!$this->general_model->logged_in()){ ?>
<form action='<?php echo $this->general_model->get_url('login'); ?>' role='form' method='post'>
  <!-- Code for Login / Signup Popup -->
  <!-- Modal Log in -->
	<div style="display: none;" class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	  <div class="modal-dialog" style="margin-top: 150px;max-width:400px;">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	        <h4 class="modal-title" id="myModalLabel1">Login</h4>
	      </div>
	      <div class="modal-body">
	        <div class='help-block'> Already have an account? </div>
			<div class='form-group'>
				<label>Your Email</label>
				<input placeholder="example@gmail.com" name="email" type="email"  value="<?php echo set_value('email');?>" class='form-control input-sm' required />
			</div>
	        <div class='form-group'>
				<label>Password</label>
				<input placeholder="Password" name="password" type="password" class='form-control input-sm' required>
			</div>
			<div class='text-right'>
				
				<a href="<?php echo $this->general_model->get_url('reset_password'); ?>">Forgot Password?</a>
			</div>
	      </div>
	      <div class="modal-footer">
			<button  class="btn btn-primary pull-left" type='submit' > Log In </button>
			<span> Not a member yet?</span>
			<a href='javascript:void(0)' style='white-space:nowrap;' data-dismiss='modal'  data-toggle='modal' data-target='#signup' >Sign Up!</a>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
 <!--Modal Login Ends -->
 </form>
 
 
 <form action='<?php echo $this->general_model->get_url('registration'); ?>' role='form' method='post'>
 <!-- Modal Sign-up Starts -->
	<div class="modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	  <div class="modal-dialog" style="margin-top:100px;">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	        <h4 class="modal-title" id="myModalLabel2">Register</h4>
	      </div>
	      <div class="modal-body" id="signup_details">
			<div class='row'>
				<div class='form-group col-sm-6 col-md-6'>
					<label>First Name</label>
					<input placeholder="Firstname" name="firstname" type="text" mustmatch='^[a-zA-Z]+$' mustmatchmessage='Firstname can only contain alphabets. Symbols and punctuation marks are not allowed' value="<?php echo set_value('firstname');?>" class='form-control input-sm' required maxlength='35' >
				</div>
				<div class='form-group col-sm-6 col-md-6'>
					<label>Last Name</label>
					<input placeholder="Lastname" name="lastname" type="text"  mustmatch='^[a-zA-Z]+$' mustmatchmessage='Lastname can only contain alphabets. Symbols and punctuation marks are not allowed' value="<?php echo set_value('lastname');?>" class='form-control input-sm' required  maxlength='35' >
				</div>
			</div>
			
			<div class='row'>
				<div class='form-group col-sm-8 col-md-4'>
					<label>Country</label>
					<select name="country" class='form-control input-sm' required onchange="$('div.form-group .default_dial_code').val($(this).find('option:selected').attr('dial_code'))" >
						<?php
						if(empty($countries))$countries=$this->general_model->get_countries(false);
						
						foreach($countries as $country_id=>$country)
						{
					?>
						<option <?php
						echo " value='$country_id' ";
						echo " dial_code='{$country['dial_code']}' ";
						echo set_select('country',$country_id,$country_id==37);
						?> ><?php echo $country['country'];?></option>
					<?php
						}
					?>
					</select>
				</div>
				<div class='form-group col-sm-4 col-md-2'>
					<label>Prefix</label>
					<input placeholder="+234" name="default_dial_code" type='text' maxlength='5' pattern='^\+?[0-9]{1,5}$' title="Country/Network's default dial code, e.g 234" value='<?php echo set_value('default_dial_code','+234'); ?>'  class='form-control input-sm default_dial_code' required >
				</div>
				<div class='form-group col-sm-12 col-md-3'>
					<label>Timezone <a href='https://wikipedia.org/wiki/List_of_time_zones_by_country' title='Supplying the timezone offset of your country ensures that any information you recieve carries accurate timestamp' target='_blank' style='cursor:help;'>?</a></label>
					<div class='input-group'>
						<span class='input-group-addon'>GMT</span>
						<input placeholder="+01:00" name="timezone_offset" type='text' maxlength='6'  pattern='^[-+]?[0-9]{1,2}(:[0-9]{1,2})?$' title='e.g +1' value="<?php echo set_value('timezone_offset','+01:00');?>" class='form-control input-sm' required >
					</div>
				</div>
				<div class='form-group col-sm-12 col-md-3'>
					<label>Default Sender</label>
					<input placeholder="Sender Id" name='default_sender_id'  minlength='3' pattern="^.{3,11}|[0-9]{3,14}$"  title='Between 3 to 11 characters (or 3 to 14 digits if numeric)' maxlength='14' type="text" value="<?php echo set_value('default_sender_id');?>" class='form-control input-sm' required >
				</div>
			</div>
	        
			<div class='row'>
				<div class='form-group  col-sm-6 col-md-6'>
					<label>Email</label>
					<input placeholder="example@gmail.com" name='email' type='email'  value="<?php echo set_value('email');?>"  class='form-control input-sm' required >
				</div>
				<div class='form-group  col-sm-6 col-md-6'>
					<label>Phone</label>
					<input placeholder="+23480XXXXXXXX" name="phone" type="text" title='+XXXXXXXXXXXX'  pattern="^\+?[0-9]{7,}$" value='<?php echo set_value('phone','+234');?>'  class='form-control input-sm default_dial_code' required >
				</div>
			</div>
			<div class='row'>
				<div class='form-group  col-sm-6 col-md-6'>
					<label>Password</label>
					<input placeholder="Password" name="password" type="password"  class='form-control input-sm' required >
				</div>
				<div class='form-group col-sm-6 col-md-6'>
					<label>Confirm</label>
					<input placeholder="Re-type Password" name="confirm_password" type="password"  class='form-control input-sm' required >
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
			<button class="btn btn-primary pull-left" type='submit' > Sign Up </button>
	       <span>&nbsp;&nbsp;&nbsp; Already a member? </span>
		   <a href='javascript:void(0)' data-dismiss='modal' data-toggle='modal' data-target='#login' >  Login now  </a> 
		 </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
  <!-- Modal Sign up ends! -->
  <!-- End code for Login / Signup Popup -->
  </form>
 <?php } ?>
    <div class="container" style='min-height:680px;padding-bottom:50px;margin-top:10px;'>
        <?php if(!empty($this->general_model->flash_message)){ ?>
                <div class='alert alert-info fade in'>
                    <span class='close' data-dismiss='alert'>&times;</span>
                    <?php echo $this->general_model->flash_message;?>
                </div>
        <?php } ?>