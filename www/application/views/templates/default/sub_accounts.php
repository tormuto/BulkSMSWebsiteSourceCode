	<h3>
		<a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' style='color:#333;text-decoration:none;' >
			<i class='fa fa-list-ul'></i>  Sub Accounts
		</a>
	</h3>
	<hr/>
	<?php if(!empty($Error)){ ?>
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
	<?php
		$default_params=$this->session->userdata('login_data');
	?>
		<div id='actions_div'>
			<a href='javascript:;' onclick="javascript:$('#sub_account_form_div').slideToggle()" class='btn btn-lg btn-default' title='add new sub_account'><span class='glyphicon glyphicon-plus'></span> Add New</a>
		</div>
		<hr/>
	<div class='row' >
	<div class='col-md-6 col-sm-8 sub_account_form_div' id='sub_account_form_div'  style='display:none;'>
		<form role='form ' method='post' >
			<div class='form-group'>
				<label for='sub_account'>Sub Account</label>
				<div class='input-group'>
					<span class='input-group-addon'><?php echo $this->general_model->format_user_id($default_params['user_id']); ?>_</span>
					<input type='text' name='sub_account' required  pattern='^[a-zA-Z0-9]+$' maxlength='25' title='alphanumeric' value="<?php  echo set_value('sub_account'); ?>" class='form-control input-sm' >
					<span class='input-group-addon'>
						<input type='checkbox' name='enabled'  <?php  echo set_checkbox('enabled',1,true); ?> value='1' />
					</span>
				</div>
			</div>
			<div class='form-group'>
				<label for='password'>Password</label>
				<input type='text' name='sub_account_password' class='form-control input-sm' value="<?php  echo set_value('sub_account_password'); ?>" required >
			</div>
			<div class='form-group' title='Optional' >
				<label for='notification_email'>Notification Email</label>
				<input type='email' name='notification_email' class='form-control input-sm' value="<?php  echo set_value('notification_email',$default_params['email']); ?>" required>
			</div>
			<div  class='row'>
				<div class='form-group col-xs-12 col-md-4 col-sm-4'>
					<label>Default Prefix</label>
					<input placeholder="+234" name="default_dial_code"  type='text' maxlength='5' pattern='^\+?[0-9]{1,5}$' title="Country/Network's default dial code, e.g 234" value='<?php  echo set_value('default_dial_code',$default_params['default_dial_code']); ?>'  class='form-control input-sm default_dial_code' required >
				</div>
				<div class='form-group col-xs-12 col-md-4 col-sm-4'>
					<label>Default Timezone</label>
					<div class='input-group'>
						<span class='input-group-addon'>GMT</span>
						<input placeholder="+01:00" name="timezone_offset" type='text' maxlength='6'  pattern='^[-+]?[0-9]{1,2}(:[0-9]{1,2})?$' title='e.g +1' value="<?php echo set_value('timezone_offset',$default_params['timezone_offset']);?>" class='form-control input-sm' required >
					</div>
				</div>
				<div class='form-group col-xs-12 col-md-4 col-sm-4'>
					<label>Default Sender</label>
					<input placeholder="Sender Id" name='default_sender_id'  minlength='3' maxlength='11' type="text" value="<?php echo set_value('default_sender_id',$default_params['default_sender_id']);?>" class='form-control input-sm' required >
				</div>
			</div>
			<button class='btn btn-sm btn-success pull-right' value='add_account' name='add_account'><i class='fa fa-plus'></i> ADD SUB ACCOUNT</button>
		</form>
	</div>
	<?php if(!empty($edit_sub_account_data)){ ?>
	<div class='col-md-6 col-sm-8 sub_account_form_div'>
		<form role='form ' method='post' >
			<input type='hidden' name='sub_account_id' value='<?php echo $edit_sub_account_data['sub_account_id'];?>' />
			<div class='form-group'>
				<label for='sub_account'>Sub Account</label>
				<div class='input-group'>
					<span class='input-group-addon'><?php echo $this->general_model->format_user_id($default_params['user_id']); ?>_</span>
					<input type='text' name='sub_account' required  pattern='^[a-zA-Z0-9]+$' maxlength='25' title='alphanumeric' value="<?php  echo set_value('sub_account',$edit_sub_account_data['sub_account']); ?>" class='form-control input-sm' >
					<span class='input-group-addon'>
						<input type='checkbox' name='enabled'  <?php  echo set_checkbox('enabled',1,$edit_sub_account_data['enabled']==1); ?> value='1' />
					</span>
				</div>
			</div>
			<div class='form-group'>
				<label for='password'>Password</label>
				<input type='text' name='sub_account_password' class='form-control input-sm' value="<?php  echo set_value('sub_account_password',$edit_sub_account_data['sub_account_password']); ?>" required >
			</div>
			<div class='form-group' title='Optional' >
				<label for='notification_email'>Notification Email</label>
				<input type='email' name='notification_email' class='form-control input-sm' value="<?php  echo set_value('notification_email',$edit_sub_account_data['notification_email']); ?>" required>
			</div>
			<div  class='row'>
				<div class='form-group col-xs-12 col-md-4 col-sm-4'>
					<label>Default Prefix</label>
					<input placeholder="+234" name="default_dial_code"  type='text' maxlength='5' pattern='^\+?[0-9]{1,5}$' title="Country/Network's default dial code, e.g 234" value='<?php  echo set_value('default_dial_code',$edit_sub_account_data['default_dial_code']); ?>'  class='form-control input-sm default_dial_code' required >
				</div>
				<div class='form-group col-xs-12 col-md-4 col-sm-4'>
					<label>Default Timezone</label>
					<div class='input-group'>
						<span class='input-group-addon'>GMT</span>
						<input  placeholder="+01:00" name="timezone_offset" type='text' maxlength='6'  pattern='^[-+]?[0-9]{1,2}(:[0-9]{1,2})?$' title='e.g +1' value="<?php echo set_value('timezone_offset',$edit_sub_account_data['timezone_offset']);?>" class='form-control input-sm' required >
					</div>
				</div>
				<div class='form-group col-xs-12 col-md-4 col-sm-4'>
					<label>Default Sender</label>
					<input placeholder="Sender Id" name='default_sender_id'  minlength='3' maxlength='11' type="text" value="<?php echo set_value('default_sender_id',$edit_sub_account_data['default_sender_id']);?>" class='form-control input-sm' required >
				</div>
			</div>
			<button class='btn btn-sm btn-default pull-right' value='update_account' name='update_account'><i class='fa fa-floppy-o'></i> UPDATE SUB ACCOUNT</button>		
		</form>
	</div>
	<?php } ?>
	
	</div>
	<div class='clearfix'></div>
	<br/>
	<?php if(!empty($sub_accounts)){ ?>
	<div class='row'>
		<div class='list-group'>
		<?php foreach($sub_accounts as $sub_account_data){ ?>
		<div class='list-group-item'>
			<div class='list-group-item-heading'>
				<a href='<?php echo "?delete_sub_account={$sub_account_data['sub_account_id']}"; ?>' class='close text-danger' onclick="return confirm('Do you really want to delete this sub_account?');" ><i class='fa fa-trash'></i></a>
				<strong ><?php echo $this->general_model->format_sub_account($sub_account_data['sub_account'],$default_params['user_id']); ?></strong>
			</div>
			<div class='list-group-item-text'>
				<div style='margin:8px 0px;' >
					<?php if(!empty($sub_account_data['notification_email'])){ ?>
					<span title='Notification Email' class='label btn-default' style='color:#333;' ><i class='fa fa-email'></i> <?php echo $sub_account_data['notification_email']; ?></span>
					<?php } ?>
				</div>
				<span class='btn btn-default btn-xs' title='credit balance' ><i class='fa fa-database'></i> <?php echo $sub_account_data['balance']; ?> credits</span>
				<span class='btn btn-default btn-xs' title='default sender id' ><i class='fa fa-user'></i> <?php echo $sub_account_data['default_sender_id']; ?></span>
				<span class='btn btn-default btn-xs' title='default dial code' ><i class='fa fa-phone'></i> +<?php echo $sub_account_data['default_dial_code']; ?></span>
				<span class='btn btn-default btn-xs' title='default dial code' ><i class='fa fa-clock-o'></i>
					 GMT
					<?php 
						if($sub_account_data['timezone_offset']>0)echo '+';
						if($sub_account_data['timezone_offset']!=0)echo $sub_account_data['timezone_offset'];
					?>
				</span>				
				<?php echo empty($sub_account_data['enabled'])?"<span class='label label-warning'>In-Active</span>":"<span class='label label-success'>Active</span> "; ?>
				<a href='<?php echo "?edit_sub_account={$sub_account_data['sub_account_id']}"; ?>'  class='btn btn-xs' >
					<i class='fa fa-edit'></i> edit
				</a>
				<a href='<?php echo "?edit_sub_account={$sub_account_data['sub_account_id']}"; ?>'  class='btn btn-xs' >
					<i class='fa fa-key'></i> password
				</a>

				<form class='form-inline' style='margin-top:10px;' method='post' role='form' >
					<input type='hidden' name='sub_account_id' value='<?php echo $sub_account_data['sub_account_id']; ?>' />
					<div class='form-group'>
						<div class='input-group'>
							<input type='number' name='amount' class='form-control input-sm' required min='1' />
							<span class='input-group-addon'>units</span>
						</div>
					</div>
					<button class='btn btn-sm btn-success' name='add_credits' value='add credit' title='Add credits here from main account'  >Add Credits</button>
					<button class='btn btn-sm btn-warning' name='remove_credits' value='remove credit' title='Return credits back to main account' >Remove Credits</button>
				</form>
			</div>
		</div>
		<?php } ?>
		</div>
		<div class='text-right' >
			<?php echo $this->general_model->get_pagination($p,$totalpages); ?>
		</div>
	</div>
	<?php } ?>
	
	<br/>