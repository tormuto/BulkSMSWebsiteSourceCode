<div class='default_breadcrumb'><h3>Registration</h3><hr/></div>
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
						
						foreach($countries as $country_code=>$country){
					?>
						<option <?php
						echo " value='{$country['country_code']}' ";
						echo " dial_code='{$country['dial_code']}' ";
						echo set_select('country',$country['country_code'],$country['country_code']==$configs['default_country_code']);
						?> ><?php echo $country['country'];?></option>
					<?php
						}
					?>
					</select>
				</div>
				<div class='form-group col-xs-4 col-md-2'>
					<label>Code</label>
					<input placeholder="+<?php echo $configs['default_dial_code']; ?>" name="default_dial_code"  type='text' maxlength='5' pattern='^\+?[0-9]{1,5}$' title="Country/Network's default dial code, e.g <?php echo $configs['default_dial_code']; ?>" value='<?php echo set_value('default_dial_code',"+{$configs['default_dial_code']}"); ?>'  class='form-control input-sm default_dial_code' required >
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
					<input placeholder="Sender Id" name='default_sender_id'  minlength='3' pattern="^.{3,11}|[0-9]{3,14}$"  title='Between 3 to 11 characters (or 3 to 14 digits if numeric)' maxlength='14' type="text" value="<?php echo set_value('default_sender_id');?>" class='form-control input-sm' required >
				</div>
		</div>
		<div class='row'>
			<div class='form-group  col-xs-6 col-md-6'>
				<label>Email</label>
				<input placeholder="example@gmail.com" name='email' type="email" value="<?php echo set_value('email');?>" id='email_field' class='form-control input-sm' required>
				<div class='help-block'><em class='text-warning' id='email_info' style='font-size:11px;'></em></div>
			</div>
			<div class='form-group  col-xs-6 col-md-6'>
				<label>Phone</label>
				<input placeholder="+<?php echo $configs['default_dial_code']; ?>80XXXXXXXX" name="phone" type='text' pattern="^\+?[0-9]{7,}$"  value='<?php echo set_value('phone',"+{$configs['default_dial_code']}");?>'  class='form-control input-sm default_dial_code' required >
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
		<button class='btn btn-primary btn-lg pull-right' >
			SIGN UP
		</button>
	</form>
    <script type='text/javascript'>
        var allowed_signup_email_domains=<?php 
            if(empty($allowed_signup_email_domains))$allowed_signup_email_domains=array();
            echo json_encode($allowed_signup_email_domains);
        ?>
        
        var email_regex=/(.+)@(.+){2,}\.(.+){2,}/;
        
        function disallowedEmailMsg(val){
            if(!email_regex.test(val))return '';
            val=val.toLowerCase();
            if(allowed_signup_email_domains.length){
                for(var i in allowed_signup_email_domains){
                    var temp=allowed_signup_email_domains[i];
                    if(val.indexOf(temp)!==-1)return '';
                }
                
                return "Only the popular (or pre-whitelisted) email domains are allowed. Please chat now with support to whitelist your domain, otherwise, use any email that you have from the following: <small><i>"+(allowed_signup_email_domains.join(', '))+"</i></small>";
            }
            return '';
        }
    
        $(function(){
            $('#email_field').on('keyup change',function(){
                var val=$(this).val();
                if(!email_regex.test(val)){
                    $('#email_info').html('');
                    return;
                }
                
                var resp=disallowedEmailMsg(val);
                if(resp)$('#email_info').html("<div class='text-danger bold'>"+resp+"</div>");
                else $('#email_info').html('');
            });
            
            $('#email_field').on('change',function(){
                var val=$(this).val();
                if(email_regex.test(val)&&!disallowedEmailMsg(val)){
                    //email_available(val,'email_info'); //undefined
                }
            });            
        });
    </script>
	
	
		<?php
			}
		?>
</div>