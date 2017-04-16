<h3 class='breadcrumb'>
	Configuration
	
	<a href='?show_balance=1' class='pull-right' title='Show balances' ><i class='fa fa-database'></i></a>
</h3>
<?php	
	if(!empty($Error)){
?>
	<div class='alert alert-warning'>
		<button class='close' data-dismiss='alert'>&times;</button>
		<?php echo $Error;?>
	</div>
<?php } if(!empty($Success)){ ?>
	<div class='alert alert-success'>
		<button class='close' data-dismiss='alert'>&times;</button>
		<?php echo $Success;?>
	</div>
<?php } ?>
<hr/>
<form role='form' method='post' class='form-inline' >
	<div class='form-group'>
		<label for='start_date'>Start Date</label>
		<input type='date' name='start_date' value="<?php echo set_value('start_date',date('Y-m-d',strtotime('-6 days')));?>" class='form-control input-sm' placeholder='yyyy-mm-dd' required>
	</div>
	<div class='form-group'>
		<label for='end_date'>End Date</label>
		<input type='date' name='end_date' value="<?php echo set_value('end_date',date('Y-m-d'));?>" class='form-control input-sm' placeholder='yyyy-mm-dd' required>
	</div>
	<button name='show_dues' value='1' class='btn btn-sm btn-default'>Compute Dues</button>
</form>
<hr/>
<form role='form' method='post'>
	<div class='form-group col-md-3 col-sm-4'>
		<label for='site_name'>
			Website Name
		</label>
		<input type='text' name='site_name' value="<?php echo $presetData['site_name'];?>" class='form-control input-sm' placeholder='' required>
	</div>
	
	<div class='form-group col-md-2 col-sm-4'>
		<label for='cron_mails_per_minute'>
			CronMails per Minute
		</label>
		<input type='number' name='cron_mails_per_minute' min='1' max='10' placeholder='2' value="<?php echo $presetData['cron_mails_per_minute'];?>" class='form-control input-sm' required>
	</div>
	<div class='form-group col-md-3 col-sm-4'>
		<label for='minimum_units'>
			Minimum Units
		</label>
		<input type='number' name='minimum_units' value="<?php echo $presetData['minimum_units'];?>" class='form-control input-sm' placeholder='1' required>
	</div>
	<div class='form-group col-md-3 col-sm-4'>
		<label for='max_linked_sms'>Max hyperlinked bulk SMS</label>
		<input type='number' name='max_linked_sms' value="<?php echo $presetData['max_linked_sms'];?>" class='form-control input-sm' placeholder='50' required>
	</div>

	<div class='clearfix'></div>
	<h3>CheapGlobalSMS.com Account Configuration</h3>
	<hr/>
	<div class='form-group col-md-6 col-sm-6'>
		<label for='cgsms_sub_account'>CheapGlobalSMS Sub-Account</label>
		<input type='text' name='cgsms_sub_account' class='form-control' value="<?php echo $presetData['cgsms_sub_account'];?>" placeholder='001_subaccountname' required pattern='[0-9]+_[a-zA-Z0-9_]+' />
	</div>
	
	<div class='form-group col-md-6 col-sm-6'>
		<label for='cgsms_sub_account_password'>CheapGlobalSMS Sub-Account Password</label>
		<input type='text' name='cgsms_sub_account_password' class='form-control' value="<?php echo $presetData['cgsms_sub_account_password'];?>" placeholder='password' required />
	</div>
	<div class='col-md-12'>
		<a href='http://cheapglobalsms.com/sub_accounts'>Get/manage your sub-account here</a>
	</div>
	<div class='clearfix'></div>
	<hr/>
	<div class='form-group col-md-12'>
		<label for=''>Blacklisted Names</label>
		<textarea name='blacklisted_names' class='form-control'  placeholder='Blacklist malicious user names/ sender_ids; these ids will not be allowed to register' rows=2><?php echo $presetData['blacklisted_names'];?></textarea>
	</div>
	
	<div class='clearfix'></div>
	<h3> Website Notice </h3>
	<hr/>	
	<div class='form-group col-md-6 col-sm-6'>
		<label for='site_notice_logged_in'>When Logged In</label>
		<textarea name='site_notice_logged_in' class='form-control'  placeholder='Enjoy your stay' rows=2><?php echo $presetData['site_notice_logged_in'];?></textarea>
	</div>
	<div class='form-group col-md-6 col-sm-6'>
		<label for='site_notice_logged_out'>When Logged Out</label>
		<textarea name='site_notice_logged_out' class='form-control'  placeholder='Please login to enjoy' rows=2><?php echo $presetData['site_notice_logged_out'];?></textarea>
	</div>

	<div class='clearfix'></div>
	<h3>SOCIAL</h3>
	<hr/>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='facebook_url'>Facebook Url</label>
		<input type='text' name='facebook_url' class='form-control' value="<?php echo $presetData['facebook_url'];?>" placeholder='http://facebook.com/username'/>
	</div>
	
	<div class='form-group col-md-3 col-sm-3'>
		<label for='facebook_page_id'>FB Page ID</label>
		<input type='text' name='facebook_page_id' class='form-control' value="<?php echo $presetData['facebook_page_id'];?>" placeholder='311923839017947'/>
	</div>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='facebook_app_id'>FB App ID</label>
		<input type='text' name='facebook_app_id' class='form-control' value="<?php echo $presetData['facebook_app_id'];?>" placeholder='311923839017947'/>
	</div>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='twitter_url'>Twitter Url</label>
		<input type='text' name='twitter_url' class='form-control' value="<?php echo $presetData['twitter_url'];?>" placeholder='http://twitter.com/username'/>
	</div>
	
	<div class='clearfix'></div>
	<h3>
		SEO PARAMETERS
	</h3>
	<hr/>
	<div class='form-group col-md-6 col-sm-6'>
		<label for='site_meta_title'>Website Meta Title</label>
		<input type='text' name='site_meta_title' class='form-control' required value="<?php echo $presetData['site_meta_title'];?>" placeholder='Customized International SMS'/>
	</div>
	
	<div class='form-group col-md-6 col-sm-6'>
		<label for='site_meta_copyright'>Website Meta Copyright</label>
		<input type='text' name='site_meta_copyright' class='form-control' required value="<?php echo $presetData['site_meta_copyright'];?>" placeholder='&copy; <?php echo date('Y'); ?> all rights reserved'/>
	</div>
	
	<div class='form-group col-md-6 col-sm-6'>
		<label for='site_meta_keywords'>Website Meta Keywords</label>
		<textarea name='site_meta_keywords' class='form-control'  placeholder='bulk sms international,	
i want to send bulk sms,sms gateway,sms marketing,bulk sms,send bulk sms,cheap bulk sms,cheap sms,sms provider,worldwide sms,sms via internet,sms web service,send sms online,php sms,low cost sms,send sms from pc,send text message' rows=2 required><?php echo $presetData['site_meta_keywords'];?></textarea>
	</div>
	
	
	<div class='form-group col-md-6 col-sm-6'>
		<label for='site_meta_description'>Website Meta Description</label>
		<textarea name='site_meta_description' class='form-control'  placeholder='The fastest and most reliable Bulk SMS service provider to all networks worldwide. With robust SMS gateway API for developers' rows='2' required><?php echo $presetData['site_meta_description'];?></textarea>
	</div>
	
	<div class='clearfix'></div>
	<div>
		<button class='btn btn-primary pull-right' value='save' name='save_configs'><span class='glyphicon glyphicon-save'> SAVE</button>
	</div>	
</form>
<div class='clearfix'></div>
<hr/>
<div class='alert alert-info'>
	Remember to set up a <strong>Cron Job</strong> with the following command.<br/>
	<pre> */15 * * * * 	curl <?php echo $this->general_model->get_url('run_sms_cron'); ?> >/dev/null 2>&amp;1 </pre>
	<p>i.e, run this command "<i>curl <?php echo $this->general_model->get_url('run_sms_cron'); ?> >/dev/null 2>&amp;1</i>" once every 15 minutes</p>
	<hr/>
	<h4>Also, set up another cron like this</h4>
	<pre> */15 * * * * curl <?php echo $this->general_model->get_url('run_mail_queue'); ?> >/dev/null 2>&amp;1 </pre>
	<p>i.e, run this command "<i>curl <?php echo $this->general_model->get_url('run_mail_queue'); ?> >/dev/null 2>&amp;1</i>" once every 15 minutes</p>
</div>