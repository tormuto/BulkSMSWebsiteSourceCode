	<h3><i class='fa fa-paper-plane'></i> Send SMS</h3><hr/>
	<?php if(!empty($Error)){ ?>
		<div class='alert alert-danger fade in'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Error;?>
		</div>
	<?php } if(!empty($Success)){ ?>
		<div class='alert alert-success fade in'>
			<span class='close' data-dismiss='alert'>&times;</span>
			<?php echo $Success;?>
		</div>
	<?php } ?>
	<div class='help-block'>
		<strong class='text-warning'>Upload a VCard or multiple  phone numbers (comma separated)</strong>
		<br><small class='text-success'>Sender id: (3 to 11characters) or (3 to 14 digits)</small>
	</div>
	<hr/>
	<form method='post' enctype='multipart/form-data' >
		<div class='col-md-6 col-sm-6'>
			<div class='row'>
				<div class='form-group col-md-8 col-sm-7 col-xs-6'>
					<label for='sender_id'>Sender ID</label>
					<input class='form-control input-sm' type='text' pattern="^.{3,11}|[0-9]{3,14}$"  title='Between 3 to 11 characters (or 3 to 14 digits if numeric)' maxlength='14' minlength='3' name='sender_id' placeholder='<?php echo $my_profile['default_sender_id']; ?>' value='<?php echo set_value('sender_id',$my_profile['default_sender_id']); ?>' />
				</div>
				<div class='form-group col-md-4 col-sm-5 col-xs-6'>
					<label for='type' style='display:block;' >Type <a href='<?php echo $this->general_model->get_url('faqs'); ?>' style='float:right;cursor:help;' target='_blank' title="Flash message are those message that will appear immediately on the recipients' screen. Irregardless of what he/she is currently doing on the phone." >?</a></label>
					<select class='form-control input-sm' name='type'>
						<option value='0' title="SMS that goes into recipients' inbox." >Normal SMS</option>
						<option value='1' title="SMS that appears immediately on recipients' screen." >Flash Message</option>
					</select>
				</div>
			</div>
			<div class='form-group'>
				<label for='message' style='display:block;' >Message <span style='float:right;cursor:help;' class='btn-link' onclick="$('#placeholder_help').slideToggle(200);">Placeholder hint</span></label>
				<div style='display:none;' id='placeholder_help' class='alert alert-warning' >
					To send a customized message to each recipient from a contact group.<br/>
					
					E.g If you <a href='<?php echo $this->general_model->get_url('my_contacts'); ?>'>saved a contact</a>, with the following information: firstname: <strong>kate</strong>, Lastname: <strong>Johnson</strong>, group_name: <strong>Customer</strong>.<br/></br>
					And you send a following message to the contact.<br/>
					<p><i>Dear [firstname] [lastname]. As a [group_name], i hope you are enjoying our relationship.</i></p>
					The message will be automatically translated to:<br/>
					<p><i>Dear kate Johnson. As a Customer, i hope you are enjoying our relationship.</i></p>
				</div>
				<div id='message_count_info' class='text-warning'></div>
				<textarea class='form-control input-sm' id='message' name='message' placeholder='Enter the message ... ' required ><?php if(!empty($prefill_message))echo $prefill_message; ?></textarea>
				<div id='message_count_info2' class='text-danger'></div>
			</div>
			<div class='checkbox'>
				<label for='unicode'>
					<input type='checkbox' name='unicode' value='1' />
					Preserve Unicode <i>(e.g Chinese, Russian or other special symbols) <strong>72 chars/page</strong></i>
				</label>			
			</div>
		</div>
		<div class='col-md-6 col-sm-6'>
			<div class='form-group'>
				<label for='phone_numbers' style='display:block;' >Paste Recipient Phone Numbers <span style='float:right;cursor:help;' class='btn-link' onclick="$('#recipients_format_help').slideToggle(200);">Help</span></label>
				<div style='display:none;' id='recipients_format_help' class='alert alert-warning' >
					Multiple recipient's phone numbers can be separated by comma (,) e.g.<br/>
					<i>+2348094309926,447828383732,+14472829929</i><br/>
						or separate by space, e.g:<br/>
						2348094309926 447828383732 14472829929<br/><br/>

					Please Note that it's <strong>NOT</strong> compulsory to start a phone number with '+'.<br/> 
					Also, any mobile numbers starting with zero will have the zero stripped and replaced with your default prefix (<?php echo $my_profile['default_dial_code']; ?>).<br/>
					E.G:<br/>
					<i>08086689567,+2348094309926,4478128372838</i> will be automatically converted to, <i><?php echo $my_profile['default_dial_code']; ?>8086689567,2348094309926,4478128372838</i><br/><br/>	
					If you have any plain text file that contiains phone numbers in the format described above, you can as well upload the file.
				</div>
				<textarea name='phone_numbers' placeholder='+2348094309926,+2348086689567' class='form-control input-sm'><?php if(!empty($prefill_recp))echo $prefill_recp; ?></textarea>
			</div>
			
			<div class='form-group'>
				<label for='contacts_file' >
				Or Select File: (<i>.csv</i>, <i>.xls</i>, <i>.xlsx</i>, <i>.vcf</i> or <i>.txt</i>)
				&nbsp;&nbsp;&nbsp;
				<a href='<?php echo $this->general_model->get_url('sample_contacts.xlsx'); ?>' title='download sample excel file' class='pull-right' >sample excel <i class='fa fa-download'></i></a>
				</label>
				<input type='file' class='alone' name='contacts_file' accept="text/plain,text/csv,text/x-vcard,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" >
			</div>
		</div>
		<div class='clearfix'></div>
		<div >
			<div class='col-md-6 col-sm-6'>
				<div class='help-block'>Optionally schedule this message to rather be sent later.</div>
				<div class='row'>
					<div class='form-group col-md-12'>
						<label for='schedule_date_time'>Future Date-time</label>
						<input class='form-control input-sm' type='datetime' placeholder='YYYY-MM-DD hh:mm am' pattern='<?php echo $this->general_model->date_time_patern; ?>'  title='e.g 2016-31-01 5:30 am' name='schedule_date_time' />
					</div>
				</div>
			</div>
			<div class='col-md-6 col-sm-6'>
				<div class='help-block'>
				<label class='checkbox-inline'>
					<input type='checkbox' name='save' value='1'  onclick="$(this).is(':checked')?$('#group_name_div').show('fast'):$('#group_name_div').hide('fast');"/> Save these numbers.
				</label>
				</div>
				<div class='form-group' id='group_name_div'  style='display:none;' >
					<label for='group_name'>Group Name</label>
					<input type='text' name='group_name' placeholder='Default Group' value="<?php echo set_value('group_name',date('Y-m-d')); ?>" class='form-control input-sm'>
				</div>
			</div>
		</div>
		<?php if(!empty($my_contacts_groups)){ ?>
		<div class='clearfix'></div>
		<div class='col-md-12 col-sm-12' >
			<div class='help-block'>
				<i class='fa fa-plus'></i> Also send to all numbers from these contact groups.
			</div>
			<?php foreach($my_contacts_groups as $group_name => $group_total){ ?>
				<label class='checkbox-inline'>
					<input type='checkbox' name='to_groups[]' value='<?php echo $group_name; ?>' />
					<?php echo "$group_name ($group_total) "; ?>
				</label>			
			<?php } ?>		
		</div>
		<div class='clearfix'></div>
		<div class='col-md-12 col-sm-12' >
			<div class='text-info' style='margin:10px 0px;cursor:pointer;' onclick="$('#ignore_groups_div').slideToggle();" >
				<i class='fa fa-chevron-down'></i> Don't message recipients whose number occurs in the following groups
			</div>
			<div id='ignore_groups_div' style='display:none;'>
			<?php foreach($my_contacts_groups as $group_name => $group_total){ ?>
				<label class='checkbox-inline'>
					<input type='checkbox' name='ignore_groups[]' value='<?php echo $group_name; ?>' />
					<?php echo "$group_name ($group_total) "; ?>
				</label>			
			<?php } ?>	
			</div>
		</div>
		<?php } ?>
		<div class='clearfix'></div>
		<div class='text-right'>
			<button class='btn btn-success' name='send_message' value='send_message' >
				<i class='fa fa-send'></i>
				Send SMS
			</button>
		</div>
	</form>