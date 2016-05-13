<h3>Registration</h3><hr/>	
<div class='col-md-8 col-md-offset-2'>
	<?php if(!empty($Error)){ ?>
		<div class='alert alert-danger'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Error;?>
		</div>
	<?php } if(!empty($Success)){ ?>
		<div class='alert alert-success'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Success;?>
		</div>
	<?php } else {	?>
	<div class='alert alert-warning fade in'>
		Please submit this form to register and login, then you will automatically be credited with <?php echo $configs['free_sms']; ?> free SMS credits to test the platform and be sure of the instant worldwide SMS delivery.
	</div>
	<form role="form" method='post' enctype='multipart/form-data'>		
		<div class='row'>
			<div class='form-group col-xs-6 col-md-6'>
				<label>First Name</label>
				<input placeholder="Firstname" name="firstname" type="text" value="<?php echo set_value('firstname');?>" class='form-control input-sm' required >
			</div>
			<div class='form-group col-xs-6 col-md-6'>
				<label>Last Name</label>
				<input placeholder="Lastname" name="lastname" type="text" value="<?php echo set_value('lastname');?>" class='form-control input-sm' required >
			</div>
		</div>
		<div class='row'>
			<div class='form-group col-xs-8 col-md-4'>
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
				<div class='form-group col-xs-4 col-md-2'>
					<label>Code</label>
					<input placeholder="+234" name="default_dial_code"  type='text' maxlength='5' pattern='^\+?[0-9]{1,5}$' title="Country/Network's default dial code, e.g 234" value='<?php echo set_value('default_dial_code','+234'); ?>'  class='form-control input-sm default_dial_code' required >
				</div>
				<div class='form-group col-xs-12 col-md-3'>
					<label>Timezone <a href='https://wikipedia.org/wiki/List_of_time_zones_by_country' title='Supplying the timezone offset of your country ensures that any information you recieve carries accurate timestamp' target='_blank' style='cursor:help;'>?</a></label>
					<div class='input-group'>
						<span class='input-group-addon'>GMT</span>
						<input placeholder="+01:00" name="timezone_offset" type='text' maxlength='6'  pattern='^[-+]?[0-9]{1,2}(:[0-9]{1,2})?$' title='e.g +1' value="<?php echo set_value('timezone_offset','+01:00');?>" class='form-control input-sm' required >
					</div>
				</div>
				<div class='form-group col-xs-12 col-md-3'>
					<label>Default Sender</label>
					<input placeholder="Sender Id" name='default_sender_id'  minlength='3' maxlength='11' type="text" value="<?php echo set_value('default_sender_id');?>" class='form-control input-sm' required >
				</div>
		</div>
		<div class='row'>
			<div class='form-group  col-xs-6 col-md-6'>
				<label>Email</label>
				<input placeholder="example@gmail.com" name='email' type="email" value="<?php echo set_value('email');?>"  class='form-control input-sm' required onchange="email_available(this.value,'email_info');" >
				<div class='help-block'><em class='text-warning' id='email_info' style='font-size:11px;'></em></div>
			</div>
			<div class='form-group  col-xs-6 col-md-6'>
				<label>Phone</label>
				<input placeholder="+23480XXXXXXXX" name="phone" type='text' pattern="^\+?[0-9]{7,}$"  value='<?php echo set_value('phone','+234');?>'  class='form-control input-sm default_dial_code' required >
			</div>
		</div>
		
		<div class='row'>
			<div class='form-group  col-xs-6 col-md-6'>
				<label>Password</label>
				<input placeholder="Password" name="password" type="password"  class='form-control input-sm' required  maxlength='25' >
			</div>
			<div class='form-group col-xs-6 col-md-6'>
				<label>Confirm Pass.</label>
				<input placeholder="Re-type Password" name="confirm_password" type="password"  class='form-control input-sm' required maxlength='25' >
			</div>
		</div>

		<div class='clearfix'></div>
		<button class='btn btn-success btn-lg pull-right' >
			SIGN UP
		</button>
	</form>
	
		<?php
			}
		?>
</div>