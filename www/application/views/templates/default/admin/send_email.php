<h3 class='breadcrumb'>
	<i class='fa fa-envelope'></i> Send Emails / Newsletters
</h3>
<?php echo $this->general_model-> display_bootstrap_alert(@$Success,@$Error); ?>
<form role='form' method='post' enctype='multipart/form-data' >
<?php if(empty($Success)){  $domain=(substr($_SERVER['HTTP_HOST'],0,4)=='www.')?substr($_SERVER['HTTP_HOST'],4):$_SERVER['HTTP_HOST']; ?>
	<br/>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='from' >From</label>
		<input type='email' name='from' value="<?php echo set_value('from',"support@$domain");?>" class='form-control input-sm' placeholder='<?php echo "no-reply@$domain"; ?>'>
		<div class='help-block'><span class='text-warning'></span></div>
	</div>
	
	<div class='form-group col-md-3 col-sm-3'>
		<label for='from_name' >Name</label>
		<input type='text' name='from_name' value="<?php echo set_value('from_name',$configs['site_name']);?>" class='form-control input-sm' placeholder='<?php echo $configs['site_name']; ?>'>
		<div class='help-block'><span class='text-warning'></span></div>
	</div>
	
	<div class='form-group col-md-3 col-sm-3'>
		<label for='recipient_type' >To	</label>
		<select name='recipient_type' id='recipient_type' onchange='recipientTypeChanged();' class='form-control input-sm' placeholder='default'>
			<option value='specified' <?php echo set_select('recipient_type','specified');?> >Specified Emails</option>
			<option value='all' <?php echo set_select('recipient_type','all');?>>All Members (Using the filters)</option>
		</select>
		<div class='help-block'><span class='text-warning'></span></div>
	</div>

	<div class='form-group col-md-3 col-sm-3'>
		<label>Countries</label>
		<select name='country_code' class='form-control input-sm' multiple >
			<option value=''>Select countries</option>
			<?php foreach($countries as $country_code=>$country){ ?>
				<option value='<?php echo $country_code;?>'  >
					<?php echo $country;?> 
				</option>
			<?php } ?>
		</select>
	</div>
	<div class='clearfix'></div>
	
	<div id='specified_recipients_div'>
		<div class='form-group col-md-12 col-sm-12'>
			<label for='recipient_emails'>Email(s)</label>
			<input type='text' name='recipient_emails' id='recipient_emails' value="<?php echo set_value('recipient_emails',$pref_recipients);?>" class='form-control input-sm' placeholder='a@b.com,c@d.net'>
			<div class='help-block'><span class='text-warning'></span></div>
		</div>
		
		<div class='form-group col-md-12 col-sm-12'>
			<label for='recipient_file'>JSON or CSV Recipient FILE</label>
			<input type='file' accept='.txt,.json,.csv,.text' name='recipient_file' id='recipient_file' placeholder >
			<div class='help-block'><span class='text-warning'></span></div>
		</div>
		<div class='clearfix'></div>
	</div>
	<div class='col-md-12'><span class='text-warning'>
		If any placeholder is found in the message body, the actual value from the json (or user's details as the case may be) of each recipient will be substituted. 
		E.G "hello [firstname]" will become "hello ibukun" if used.
	</span></div>
	
	<div class='form-group col-md-12 col-sm-12'>
		<label for='subject'>Subject</label>
		<input type='text' name='subject' required value="<?php echo set_value('subject');?>" class='form-control input-sm'>
		<div class='help-block'><span class='text-warning'></span></div>
	</div>
	
	<div class='clearfix'></div>
	<div class='form-group col-md-12 col-sm-12'>
		<textarea name='message' class='form-control textarea required'  rows='7'><?php echo set_value('message');?></textarea>
		<div class='help-block'><span class='text-warning'></span></div>
		
		<div class='help-block'>NOTE: Mails sent to more than 10 recipients will <strong>automatically be scheduled</strong> to be delivered <strong>as soon as possible</strong>, if a future scheduled date/time is not defined. </div>
	</div>
	<div class='clearfix'></div>
	<div class='form-group col-md-4 col-sm-4'>
		<label for='date'>Schedule Date</label>
		<input type='text' name='date' placeholder='dd-mm-YYYY' pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" value="<?php echo set_value('date');?>" class='form-control input-sm'>
		<div class='help-block'><span class='text-warning'></span></div>
	</div>
	<div class='form-group col-md-4 col-sm-4'>
		<label for='time'>Schedule Time</label>
		<input type='time' name='time' placeholder='HH:mm' value="<?php echo set_value('time');?>"  pattern="[0-9]{2}:[0-9]{2}" class='form-control input-sm'>
		<div class='help-block'><span class='text-warning'></span></div>
	</div>
	<div  class='col-md-4 col-sm-4 text-right'>
		<label style='display:block;'  >&nbsp;</label>	
		<button class='btn btn-primary btn-sm' value='send_email' name='send_email'><span class='glyphicon glyphicon-send'></span> SEND</button>
	</div>
</form>
<script type='text/javascript'>
	function recipientTypeChanged()	{
		if($('#recipient_type').val()=='specified'){
			$('#specified_recipients_div').show();
			$('#filters_div').hide();
			$('#recipient_emails').prop('required',true);
		}
		else {
			$('#specified_recipients_div').hide();
			$('#filters_div').show();
			$('#recipient_emails').prop('required',false);
		}
	}

	$(function(){recipientTypeChanged();});
</script>
<?php
}
?>