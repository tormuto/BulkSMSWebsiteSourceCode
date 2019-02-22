<h3><i class='fa fa-mobile'></i> My Contacts</h3>
<hr/>
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
<button class='btn btn-default btn-sm alone' onclick="$('#upload_numbers_form').hide('fast'); $('#add_numbers_form').slideToggle('fast');" >
	<i class='fa fa-plus'></i>
	Add Numbers
</button>
<div id='add_numbers_form' style='display:none;'>
	<div id='contact_div_template' style='display:none;'>
		<div class='form-group alone col-md-3 col-sm-3 col-xs-6'>
			<label for='phone_'> <span  onclick="$(this).closest('.contact_div').remove();" class='text-danger' >&times;</span> Phone</label>
			<input type='tel' name='phone_' required  class='form-control input-sm'>
		</div>
		<div class='form-group alone  col-md-3 col-sm-3 col-xs-6'>
			<label for='group_name_'>Group Name</label>
			<input type='text' name='group_name_' placeholder='Default Group' class='form-control input-sm'>
		</div>
		<div class='form-group alone col-md-3 col-sm-3 col-xs-6'>
			<label for='firstname_'>FirstName</label>
			<input type='text' name='firstname_'  class='form-control input-sm'>
		</div>
		<div class='form-group alone col-md-3 col-sm-3 col-xs-6'>
			<label for='lastname_'>LastName</label>
			<input type='text' name='lastname_'  class='form-control input-sm'>
		</div>
		<div class='clearfix'></div>
		<hr/>
	</div>

	<div class='help-block'>
		<strong class='text-warning'> <i class='fa fa-info-circle'></i> Firstname, Lastname and Group fields are optional</strong>
	</div>
	<hr/>
	<form method='post' role='form'>
		<div id='contact_divs'></div>
		<div class='clearfix'></div>
		<div class='col-md-12 col-sm-12'>
			<input type='hidden' name='num_contacts' id='num_contacts' />
			<button type='button' class='btn btn-default btn-sm alone' onclick='javascript:addNewFields();' >
				<i class='fa fa-plus'></i> Add Another
			</button>
			<button class='btn btn-default btn-sm btn-success alone pull-right'>
				<i class='fa fa-floppy-o'></i> Save Contacts
			</button>
		</div>
		<div class='clearfix'></div>
		<hr/>
		<div class='clearfix'></div>
	</form>
</div>

<button class='btn btn-default btn-sm alone pull-right' onclick="$('#add_numbers_form').hide('fast'); $('#upload_numbers_form').slideToggle('fast');" >
	<i class='fa fa-upload'></i>
	Upload Numbers
</button>
<div id='upload_numbers_form' style='display:none;'>
	<div class='help-block'>
		<strong class='text-warning'>Upload a VCard or multiple  phone numbers (comma separated)</strong>
	</div>
	<hr/>
	<form action='?' method='post' role='form'  enctype='multipart/form-data' >
		<div class='form-group alone col-md-12 col-sm-12'>
			<label for='contacts_file' >
				Select File: (<i>.csv</i>, <i>.xls</i>, <i>.xlsx</i>, <i>.vcf</i> or <i>.txt</i>) &nbsp;&nbsp;&nbsp;
				<a href='<?php echo $this->general_model->get_url('sample_contacts.xlsx'); ?>' title='download sample excel file' class='pull-right' >sample excel <i class='fa fa-download'></i></a>
			</label>
			<input type='file' name='contacts_file' accept="text/plain,text/csv,text/x-vcard,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" >
		</div>
		<div class='form-group alone col-md-12 col-sm-12'>
			<label for='phone_numbers'>Or Paste Phone Numbers</label>
			<textarea name='phone_numbers' placeholder='+2348094309926,+2348086689567' class='form-control input-sm'></textarea>
		</div>
		<div class='form-group alone col-md-12 col-sm-12'>
			<label for='group_name'>Group Name</label>
			<input type='text' name='group_name' placeholder='Default Group' value="<?php echo set_value('group_name',date('Y-m-d')); ?>" class='form-control input-sm'>
		</div>
		<div class='form-group col-md-12 col-sm-12'>
			<label>&nbsp;</label>
			<button class='btn btn-default btn-sm btn-success alone' style='display:block;' name='upload_numbers' value='upload_numbers'>
				<i class='fa fa-floppy-o'></i> Upload Contacts
			</button>
		</div>
		<div class='clearfix'></div>
	</form>
</div>

<div class='clearfix'></div>
<h4>Browse Contacts</h4>
<hr/>
<div >
	<form  method='get' class='form-inline' role='form' onsubmit='return searchFormSubmitted()' id='search_form' >
		<div class='form-group'>
			<select name='g' class='form-control input-sm'>
				<option value=''>All Groups</option>
				<?php //$default_found=false;
					foreach($my_contacts_groups as $group_name=>$total_numbers){ ?>
					<option value='<?php echo $group_name;?>'  <?php if($group_name==$filter['group_name'])echo 'selected'; ?>>
						<?php echo "($total_numbers) $group_name";?>
					</option>
					<?php } ?>
			</select>
		</div>
		<div class='form-group'>
			<input type='search' name='q' placeholder='Name or phone number' value="<?php echo $filter['search_term']; ?>" class='form-control input-sm autocomplete_contact'>
		</div>
		<div class='form-group' id='perpage_div' >
			<div class='input-group' >
				<input type='number' min='10' name='perpage' placeholder='10' value='<?php echo $filter['perpage']; ?>' class='form-control input-sm' />
				<span class='input-group-addon' id='perpage_span' >per page</span>
			</div>
		</div>
		<div class='form-group'>
			<div class='input-group'>
			<select class='form-control input-sm' name='result_action' id='result_action' onchange='resultActionChanged()' title='What should be done with any result(s) found' >
				<option value=''  <?php if($filter['result_action']=='')echo 'selected'; ?> > display results </option>
				<option value='download_csv' <?php if($filter['result_action']=='download_csv')echo 'selected'; ?> > Download results CSV (comma separated) </option>
				<option value='download_csv_space' <?php if($filter['result_action']=='download_csv_space')echo 'selected'; ?> > Download results CSV  (space separated)</option>
				<option value='download_vcard' <?php if($filter['result_action']=='download_vcard')echo 'selected'; ?> > Download Results vCard</option>
				<option value='download_excel'  <?php if($filter['result_action']=='download_excel')echo 'selected'; ?> >Export in Excel Format</option>
				<option value='delete_batch'> Delete All Results</option>
			</select>
			<span class='input-group-btn'>
				<button class='btn btn-default btn-sm'>
					<i class='fa fa-search'></i> Search
				</button>
			</span>
			</div>
		</div>
	</form>
</div>
<br/>
<div class='alert alert-info'>
		To send bulk SMS to multiple groups <a href='<?php echo $this->general_model->get_url('send_sms'); ?>' class='alert-link' >Click Here</a>
</div>
<?php if(empty($contacts)){ ?>
	<div class='alert alert-warning'>
		<span class='close' data-dismiss='alert'>&times;</span>
		No contacts found here.
	</div>
<?php } else{ ?>
<div class='text-success' style='font-size:16px;' >
	<?php echo $total; ?> Results found
</div>
<br/>

<form  method='post' onsubmit="return ($('#action').val()=='delete_batch')?confirm('Do you really want to delete the selected contacts?'):true;">
	<input type='hidden' name='perpage' value='<?php echo $filter['perpage']; ?>' />
	
		<div class='modal fade' id='smsModal' tabindex='-1' role='dialog' aria-labelledby='smsModalLabel'>
		  <div class='modal-dialog' role='document'>
			<div class='modal-content'>
			  <div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title' id='smsModalLabel'>SEND SMS</h4>
			  </div>
			  <div class='modal-body'>
				<div class='text-success'>Sender id: (3 to 11characters) or (3 to 14 digits)</div>
				<div class='help-block' id='recipient_info'></div>
				<div class='row'>
					<div class='form-group col-md-8 col-sm-8 col-sm-7 col-xs-6'>
						<label for='sender_id'>Sender ID</label>
						<input class='form-control input-sm' type='text' pattern="^.{3,11}|[0-9]{3,14}$"  title='Between 3 to 11 characters (or 3 to 14 digits if numeric)' maxlength='14'' minlength='3' name='sender_id' placeholder='<?php echo $my_profile['default_sender_id']; ?>' />
					</div>
					<div class='form-group col-md-4 col-sm-4 col-sm-5 col-xs-6'>
						<label for='type'>Type</label>
						<select class='form-control input-sm' name='type'>
							<option value='0' title="SMS that goes into recipients' inbox." >Normal SMS</option>
							<option value='1' title="SMS that appears immediately on recipients' screen." >Flash Message</option>
						</select>
					</div>
				</div>
				<div class='form-group'>
					<label for='message'>Message</label>
					<div id='message_count_info' class='text-warning'></div>
					<textarea class='form-control input-sm' id='message' name='message' placeholder='Enter the message ... ' ></textarea>
					<div id='message_count_info2' class='text-danger'></div>
				</div>
				<div class='help-block'>Optionally schedule this message to rather be sent later.</div>
				<div class='row'>
					<div class='form-group col-md-12'>
						<label for='schedule_date_time'>Future Date-time</label>
						<input class='form-control input-sm' type='datetime-local' placeholder='YYYY-MM-DD hh:mm am'  title='e.g 2016-31-01 5:30 am' name='schedule_date_time' />
					</div>
				</div>
				<div class='text-right'>
					<button class='btn btn-success' name='send_message' value='send_message' >
						<i class='fa fa-send'></i>
						Send SMS
					</button>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		
		
	<div class='table-responsive'>
	<table class='table table-bordered table-striped'>
		<thead>
			<th class='col-xs-1'>
				<input type='checkbox' id='control_checkbox' />
				S/N
			</th>
			<th class='col-xs-4'>Phone</th>
			<th class='col-xs-7'>Info</th>
		</thead>
	<?php
		$sn=0;
		$offset=$filter['offset'];
		
		foreach($contacts as $contact)
		{ 
			$sn++;
			$contact['phone']='+'.$contact['phone'];
		?>
		<tr>
			<td>
				<input type='checkbox' name='<?php echo "checkbox_$sn"; ?>' id='<?php echo "checkbox_$sn"; ?>' value='<?php echo $contact['contact_id']; ?>' class='single_checkbox' >
				<?php echo ++$offset; ?>.
			</td>
			<td>
				<?php echo "<a href='tel:{$contact['phone']}'><i class='fa fa-phone'></i> {$contact['phone']}</a>"; ?>
				<span class='pull-right' >
				<a class='btn btn-default btn-xs send_single_sms' checkbox='<?php echo "#checkbox_$sn"; ?>' title="<?php echo "TO: {$contact['firstname']} {$contact['lastname']} ({$contact['phone']})"; ?>" href='javascript:;'>
					<i class='fa fa-envelope'></i> Send SMS
				</a>
				
				<a class='btn btn-default btn-xs'  href='<?php echo $this->general_model->get_url("my_contacts?p=$p&q={$filter['search_term']}&g={$filter['group_name']}&action=download&contact_id={$contact['contact_id']}"); ?>' title='Download Contact' >
					<i class='fa fa-download'></i>
				</a>
				
				<a class='btn btn-default btn-xs' href='<?php echo $this->general_model->get_url("my_contacts?p=$p&q={$filter['search_term']}&g={$filter['group_name']}&action=delete&contact_id={$contact['contact_id']}"); ?>' title='Delete' >
						<i class='fa fa-trash text-danger'></i>
				</a>
				</span>
			</td>
			<td>
				<?php
					if(empty($contact['firstname'])&&empty($contact['lastname']))echo "[NO_NAME]";
					else echo "{$contact['firstname']} {$contact['lastname']}"; 
				?>
				<span class='text-muted pull-right' title='Contact Group' ><i class='fa fa-users'></i> <?php echo $contact['group_name']; ?></span>
			</td>
		</tr>	
		<?php
		}
	?>
	</table>
	</div>
	
	<div class='row'>
		<div class='col-md-3 col-sm-3 alone'>
			<div class='input-group'>
			<select class='form-control input-sm' name='action' id='action' required onchange="if(this.value=='send_sms')showSMSModal()" >
				<option value=''> with selected </option>
				<option value='send_sms'> Send SMS </option>
				<option value='download_csv'> Download CSV (comma separated) </option>
				<option value='download_csv_space'> Download CSV  (space separated) </option>
				<option value='download_vcard'> Download vCard </option>
				<option value='download_excel'>Export in Excel Format</option>
				<option value='delete_batch'> Delete  Contacts </option>
			</select>
			<span class='input-group-btn'>
				<button class='btn btn-default btn-sm' id='go_button' >
					<i class='fa fa-play'></i> GO
				</button>
			</span>
			</div>
		</div>
		<div class='col-md-9 col-sm-9 text-right alone'>
			<?php if(!empty($totalpages))echo $this->general_model->get_pagination($p,$totalpages); ?>
		</div>
	</div>
</form>
<?php
	}
?>


<script type='text/javascript'> 

function resultActionChanged()
{
	var new_result_action=$('#result_action').val();
	if(new_result_action!='delete_batch')
	{
		if(new_result_action=='')$('#perpage_span').html('per page');
		else $('#perpage_span').html('results');
			
		$('#perpage_div').show();
	}
	else $('#perpage_div').hide();
}


function searchFormSubmitted()
{
	var new_result_action=$('#result_action').val();
	
	if(new_result_action=='delete_batch'&&!$('#search_form').is('[delete_confirmed]'))
	{
		ui_confirm('All the results that would be found by this search will be permanently deleted. This action is irreversible. Are you sure about this?',function(){ $('#search_form').attr('delete_confirmed','true'); $('#search_form').submit();  },'Delete All','WARNING!!');
		return false;
	}
	
	return true;
}

//todo: if(action=='send_sms'&&modal_state=='hidden'){showmodal()}
	var num=0;
	
	function addNewFields()
	{
		num++;
		$('#num_contacts').val(num);
	
		str=$('#contact_div_template').html();
		str="<div id='contact_div_"+num+"' class='contact_div'>"+str+"</div><div class='clearfix'></div>";
		$('#contact_divs').append(str);
		
		$('#contact_div_'+num+' [name]').each(function(){
				$(this).attr('name',$(this).attr('name')+num);
			});
			
		$('#contact_div_'+num+' label').each(function(){
				$(this).attr('for',$(this).attr('for')+num);
			});	
	}
	
	
	function showSMSModal(msg)
	{
		if(typeof msg==='undefined')
		{
			var sel_count=$('.single_checkbox:checked').length;
			
			if(sel_count==0)
			{
				ui_alert('Please select recipients.');
				return;
			}
			if(sel_count>1)msg="Send SMS to the "+sel_count+" selected recipients.";
			else msg="Send SMS to the selected recipient. ";
		}
		$('#recipient_info').html(msg);
		$('#message').prop('required',true);
		//$('#sender_id,#message,#schedule_date,#schedule_time').val('');
		$('#smsModal').modal('show');
	}
	
	$(function()
	{
		resultActionChanged();
		
		$('body').on('click','a.send_single_sms',function(){
			$('#control_checkbox,.single_checkbox').prop('checked',false);
			$('#action').val('send_sms');
			var temp_cb=$(this).attr('checkbox');
			$(temp_cb).prop('checked',true);	
			showSMSModal($(this).attr('title'));
		});
		
		$('#go_button').on('click',function(event){
			if($('#action').val()=='send_sms')
			{
				showSMSModal();	
				event.preventDefault();
			}
		});
		
		$('#smsModal').on('hidden.bs.modal',function(){ $('#action').val(''); $('#message').prop('required',false); });
	});	
</script>