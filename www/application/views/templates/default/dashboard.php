	<div class="jumbotron">
		<h1><?php echo $configs['site_name']; ?></h1>
		<p>This is a showcase of the FREE, ready-made bulk sms reseller website. Become a CheapGlobalSMS.com reseller and <a href='https://cheapglobalsms.com/reseller' >get it here</a></p>

		<?php if($this->general_model->logged_in()){ ?>
		<p>
			<a class='btn btn-success btn-lg' href='<?php echo $this->general_model->get_url('pricing'); ?>' role="button" style='margin-top:8px;'>
				Get SMS Credits
			</a> 
			<a class='btn btn-primary btn-lg' href='<?php echo $this->general_model->get_url('send_sms'); ?>' role="button" style='margin-top:8px;'>
				Send SMS
			</a>
		</p>
		<?php } else { ?>
		<p><a class="btn btn-primary btn-lg" href='javascript:void(0)' data-toggle='modal' data-target='#signup' role="button">Register</a></p>
		<?php } ?>
	</div>
	<?php if($this->general_model->logged_in()&&!$this->general_model->has_sub_account($my_profile['user_id'])){ ?>
		<div class='alert alert-info fade in'>
			<span class='close' data-dismiss='alert'>&times;</span>
			It appears you don't have any <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' class='alert-link'>sub-account</a>. You will need one.
		</div>
	<?php } ?>
	<h2>Highlights</h2>
	<ul class='list-group' style='font-size:18px;'>
		<li class='list-group-item'>			
			<h3 class='list-group-item-heading'>
				1. Instant SMS delivery at the lowest rates <strong>worldwide</strong>
			</h3>
			<p class='list-group-item-text'>
				Two factors determines the <strong>actual</strong> cost of a bulk SMS.<br/>
				Cost = <a href='<?php echo $this->general_model->get_url('pricing');?>' >Price per units</a> <strong>X</strong> <a href='<?php echo $this->general_model->get_url('coverage_list');?>' >Units per SMS</a>
			</p>			
		</li>
		<li class='list-group-item'>
			<h3 class='list-group-item-heading'>
				2. Well Structured <a href='<?php echo $this->general_model->get_url('my_contacts');?>'>Contacts Manager</a>
			</h3>
			<p class='list-group-item-text'>
				Import and backup your phone contact; or<br/>
				upload & download multiple contacts files for bulk messaging.
			</p>
		</li>
		<li class='list-group-item'>
			<h3 class='list-group-item-heading'>
				3. SMS Log & Scheduler
			</h3>
			<p class='list-group-item-text'>
				Access & manage your SMS <a href='<?php echo $this->general_model->get_url('sms_log');?>'>delivery reports</a> in real-time
			</p>
		</li>
		<li class='list-group-item'>
			<h3 class='list-group-item-heading'>
				4. Fully featured developers API
			</h3>
			<p class='list-group-item-text'>
				You can do everything programatically.<br/> From SMS sending/scheduling, to contacts manager. <a href='<?php echo $this->general_model->get_url('gateway_api'); ?>'>have a look</a>
			</p>
		</li>
	</ul>