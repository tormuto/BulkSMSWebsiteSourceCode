<form role='form' method='post' class='autovalidate' id='password_form' >
	<h3><i class='fa fa-unlock'></i> Reset Password</h3>
	<hr/>
	<?php
		if(empty($Error)&&validation_errors()!='')$Error=validation_errors();
		if(!empty($Error))
		{
	?>
		<div class='alert alert-warning'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Error;?>
		</div>
	<?php
		}
		if(!empty($Success))
		{
	?>
		<div class='alert alert-success'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Success;?>
		</div>
	<?php
		}
		
		elseif($this->general_model->logged_in())
		{
	?>
	<div class='col-md-6 col-sm-8 col-sm-offset-2  col-md-offset-3' style='border:1px solid #ccc;padding:25px 5px;border-radius:5px'>
		<div class='form-group'>
			<label>Current Password</label>
			<div class='input-group'>
				<span class='input-group-addon'>
					<i class='fa fa-key'></i>
				</span>
				<input type='password' name='current_password' placeholder='current password' class='form-control pull-right' required />
			</div>
		</div>
		<div class='form-group'>
			<label>New Password</label>
			<div class='input-group'>
				<span class='input-group-addon'>
					<i class='fa fa-lock'></i>
				</span>
				<input type='password' name='password' placeholder='password' class='form-control pull-right' maxlength='25' required />
			</div>
		</div>
		<div class='form-group'>
			<label>Confirm New Password</label>
			<div class='input-group'>
				<span class='input-group-addon'>
					<i class='fa fa-lock'></i>
				</span>
				<input type='password' name='password' placeholder='password' class='form-control pull-right' maxlength='25' required />
			</div>
		</div>
		<div class='form-group'>
			<button class='btn btn-primary pull-right' style='margin-right:10px;' > Save</button>
		</div>
		<div class='clearfix'></div>
	</div>
	<?php
		}
		else
		{
	?>			
	<div class='col-md-8 col-sm-8 col-sm-offset-2 col-md-offset-2' style='padding:25px 5px;'>
			<label for='email' class='sr-only'>Your Email</label>
			<div class='input-group'>
				<span class='input-group-addon'>@</span>
				<input type='email' name='email' placeholder='your email' class='form-control pull-right' required />		
				<span class='input-group-addon' onclick="$('#password_form').submit();" style='cursor:pointer;' > Reset Password</span>
			</div>
			<div class='clearfix'></div><hr/>
			<a href='<?php echo $this->general_model->get_url('login');?>' style='float:right;margin-right:10px;'>I Remember Now!</a>
	</div>		
	<?php
		}
	?>			
</form>