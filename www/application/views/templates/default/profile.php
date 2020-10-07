<div class='default_breadcrumb'><h3>My Profile</h3><hr/></div>
<div class='col-md-8 col-sm-8 col-md-offset-2'>
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
	<?php }	?>
	<form role="form" method='post' enctype='multipart/form-data'>		
		<div class='row'>
			<div class='form-group col-md-6 col-sm-6'>
				<label>First Name</label>
				<input placeholder="Firstname" name="firstname" type="text" value="<?php echo set_value('firstname',$my_profile['firstname']);?>" class='form-control input-sm' <?php echo empty($my_profile['flag_level'])?'required':'disabled'; ?>   maxlength='35'  mustmatchmessage='Firstname can only contain alphabets. Symbols and punctuation marks are not allowed'  >
			</div>
			<div class='form-group col-md-6 col-sm-6'>
				<label>Last Name</label>
				<input placeholder="Lastname" name="lastname" type="text" value="<?php echo set_value('lastname',$my_profile['lastname']);?>" class='form-control input-sm' <?php echo empty($my_profile['flag_level'])?'required':'disabled'; ?> maxlength='35'  mustmatchmessage='Lastname can only contain alphabets. Symbols and punctuation marks are not allowed' >
			</div>
		</div>
		<div class='row'>
			<div class='form-group col-xs-8 col-md-4 col-sm-6'>
					<label>Country</label>
					<select name="country" class='form-control input-sm' required onchange="$('div.form-group .default_dial_code').val($(this).find('option:selected').attr('dial_code'))" >
						<?php
						if(empty($countries))$countries=$this->general_model->get_countries(false);
						
						foreach($countries as $country_code=>$country){
					?>
						<option <?php
						echo " value='{$country['country_code']}' ";
						echo " dial_code='{$country['dial_code']}' ";
						echo set_select('country',$country['country_code'],$country['country_code']==$my_profile['country_code']);
						?> ><?php echo $country['country'];?></option>
					<?php
						}
					?>
					</select>
				</div>
				<div class='form-group col-xs-4 col-md-2 col-sm-6'>
					<label>Prefix</label>
					<input placeholder="+<?php echo $configs['default_dial_code']; ?>" name="default_dial_code"  type='text' maxlength='5' pattern='^\+?[0-9]{1,5}$' title="Country/Network's default dial code, e.g <?php echo $configs['default_dial_code']; ?>" value='<?php echo set_value('default_dial_code',$my_profile['default_dial_code']); ?>'  class='form-control input-sm default_dial_code' required >
				</div>
				<div class='form-group col-xs-12 col-md-3 col-sm-6'>
					<label>Timezone <a href='https://wikipedia.org/wiki/List_of_time_zones_by_country' title='Supplying the timezone offset of your country ensures that any information you recieve carries accurate timestamp' target='_blank' style='cursor:help;'>?</a></label>
					<div class='input-group'>
						<span class='input-group-addon'>GMT</span>
						<input placeholder="+01:00" name="timezone_offset" type='text' maxlength='6'  pattern='^[-+]?[0-9]{1,2}(:[0-9]{1,2})?$' title='e.g +1' value="<?php echo set_value('timezone_offset',$my_profile['timezone_offset']);?>" class='form-control input-sm' required >
					</div>
				</div>
				<div class='form-group col-xs-12 col-md-3 col-sm-6'>
					<label>Default Sender</label>
					<input placeholder="Sender Id" name='default_sender_id'  minlength='3' maxlength='11' type="text" value="<?php echo set_value('default_sender_id',$my_profile['default_sender_id']);?>" class='form-control input-sm' required >
				</div>
		</div>
		<div class='row'>
			<div class='form-group col-md-6 col-sm-6'>
				<label>Email</label>
				<input placeholder="example@gmail.com" readonly='readonly' type="email" value="<?php echo $my_profile['email'];?>"  class='form-control input-sm' required  >
				<div class='help-block'><em class='text-warning' id='email_info' style='font-size:11px;'></em></div>
			</div>
			<div class='form-group  col-md-6 col-sm-6'>
				<label>Phone</label>
				<input placeholder="+<?php echo $configs['default_dial_code']; ?>80XXXXXXXX" name="phone" type='text' pattern="^\+?[0-9]{7,}$"  value='<?php echo set_value('phone',$my_profile['phone']);?>'  class='form-control input-sm default_dial_code' <?php echo empty($my_profile['flag_level'])?'required':'disabled'; ?>  >
			</div>
		</div>
		<?php if(empty($my_profile['verification_file'])||$my_profile['flag_level']>=2){ ?>
		<div class='row'>
			<div class='form-group  col-md-12'>
				<label>Verifcation Document <i style='font-size:75%;'>(300Kb Max.)</i></label>
				<input name='verification_file' type='file' max_size='300' class='' accept='.jpg,.png' <?php if($my_profile['flag_level']>=2)echo 'required'; ?> />
				<div class='clearfix'></div>
				<div class='text-warning'>Please upload your driver's License or International Passport</div>
			</div>
		</div>
		<?php } ?>
		<div class='row'>
			<div class='form-group col-md-12'>
				<label for='credit_notification'  >
					<input type='checkbox' name='credit_notification' value='1' <?php echo set_checkbox('credit_notification',1,$my_profile['credit_notification']==1);?> />
					Notify me via SMS whenever my account get credited manually.
				</label>
			</div>
		</div>

		<div class='clearfix'></div>
		<button class='btn btn-success btn-sm pull-right' name='update_profile' value='update' >
			<i class='fa fa-floppy-o'></i> Update Profile
		</button>
	</form>
</div>