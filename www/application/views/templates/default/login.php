<div class='row'>
<form role='form' method='post' class='autovalidate' >
	<h4 class='content-heading'><i class='fa fa-lock'></i> Account Login</h4>
	<?php
		if(empty($Error)&&validation_errors()!='')$Error=validation_errors();
		
		
		if(empty($Error))
		{
	?>	
		<script type='text/javascript'>
			$(function(){ $('#signup').modal('show'); });
		</script>
	<?php
		}
		
		if(empty($Error)&&!empty($_REQUEST['dest']))$Error="Please login or <a href='".$this->general_model->get_url('registration')."' class='alert-link' >register</a> first.";
		
		if(!empty($Error))
		{
	?>
		<div class='alert alert-warning'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Error;?>
		</div>
	<?php
		}
	?>
	<div class='col-md-4 col-sm-4  col-md-offset-4 col-sm-offset-4' style='border:1px solid #ccc;padding:25px 5px;border-radius:5px;'>
		<label>Username or Email</label>
		<div class='input-group'>
			<span class='input-group-addon'>
				<i class='fa fa-user'></i>
			</span>
			<input type='email' name='email' placeholder='email' class='form-control pull-right' value="<?php echo set_value('email',@$_GET['pending_email']);?>"  required />		
		</div>
		<br/>
		<div class='clearfix'></div>
			<label>Password</label>
		<div class='input-group'>
			<span class='input-group-addon'>
				<i class='fa fa-key'></i>
			</span>
			<input type='password' name='password' placeholder='password' class='form-control pull-right' required />
		</div>
		<br/>
		<?php
			if(!empty($_REQUEST['dest']))
			{
		?>
			<input type='hidden' name='dest' value="<?php echo $_REQUEST['dest'];?>"/>
		<?php
			}
		?>
		<button class='btn btn-primary pull-right' style='margin-right:10px;' > Login</button>
		<div class='clearfix'></div>
		<hr/>
		<a href='<?php echo $this->general_model->get_url('registration');?>' class='btn btn-default btn-xs' >
			REGISTER
		</a>
		<a href='<?php echo $this->general_model->get_url('reset_password');?>' style='color:#F63;' class='btn btn-link btn-xs pull-right'>I forgot my password</a>
	</div>		
</form>
</div>