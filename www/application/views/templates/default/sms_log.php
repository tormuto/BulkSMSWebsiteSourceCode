<h3><?php
 if($filter['stage']==='pending')echo "<i class='fa fa-clock-o'></i> Scheduled SMS Log";
 elseif($filter['stage']==='sent')echo "<i class='fa fa-envelope'></i> Sent SMS Log";
 elseif($filter['stage']==='failed')echo "<i class='fa fa-times-circle'></i> Failed SMS Log";
 else echo "<i class='fa fa-list'></i> SMS Log";
?></h3>
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
<div class='text-right'>
	<form  method='get' class='form-inline' role='form' onsubmit='return searchFormSubmitted()' id='search_form'  >
		<div class='form-group'>
			<select name='stage' class='form-control input-sm'>
				<option value='' <?php if($filter['stage']==='')echo 'selected'; ?>>all</option>
				<option value='pending' <?php if($filter['stage']==='pending')echo 'selected'; ?>>scheduled</option>
				<option value='sent'<?php if($filter['stage']==='sent')echo 'selected'; ?> >sent</option>
				<option value='failed' <?php if($filter['stage']==='failed')echo 'selected'; ?> >failed</option>
			</select>
		</div>
		<div class='form-group'>
			<div class='input-group'>
				<span class='input-group-addon'>From:</span>
				<input type='date' pattern='<?php echo $this->general_model->date_patern; ?>'  title='e.g 2016-01-31' placeholder='yyyy-mm-dd' name='sd'  value='<?php echo $filter['start_date']; ?>' class='form-control input-sm'>
			</div>
		</div>
		<div class='form-group'>
			<div class='input-group'>
				<span class='input-group-addon'>To:</span>
				<input type='date' pattern='<?php echo $this->general_model->date_patern; ?>'  title='e.g 2016-01-31' placeholder='yyyy-mm-dd' name='ed'  value='<?php echo $filter['end_date']; ?>' class='form-control input-sm'>
			</div>
		</div>
		<div class='form-group' id='perpage_div'>
			<div class='input-group'>
				<input type='number' min='5' name='perpage' placeholder='10' value='<?php echo $filter['perpage']; ?>' class='form-control input-sm'>
				<span class='input-group-addon' id='perpage_span' > perpage</span>
			</div>
		</div>
		<div class='form-group'>
			<input type='search' name='q' placeholder='Batch Id, Recipient or message' value='<?php echo $filter['search_term']; ?>' class='form-control input-sm'>
		</div>
		<div class='form-group'>
			<div class='input-group'>
				<select class='form-control input-sm' name='result_action' id='result_action' onchange='resultActionChanged()' title='What should be done with any result(s) found' >
					<option value='' <?php if($filter['result_action']=='')echo 'selected'; ?>> display results </option>
					<option value='download_csv' <?php if($filter['result_action']=='download_csv')echo 'selected'; ?> > Download numbers (comma separated)</option>
					<option value='download_csv_space' <?php if($filter['result_action']=='download_csv_space')echo 'selected'; ?> > Download numbers  (space separated)</option>
					<option value='calculate_total_units' <?php if($filter['result_action']=='calculate_total_units')echo 'selected'; ?> >Calculate total SMS units</option>
					<option value='delete_batch'> Delete All Results</option>
				</select>
				<span class='input-group-btn'>
					<button class='btn btn-default btn-sm'><i class='fa fa-search'></i></button>	
				</span>
			</div>
		</div>
	</form>
</div>
<br/>
	
<?php if(empty($messages)){ ?>
	<div class='alert alert-warning'>
		<span class='close' data-dismiss='alert'>&times;</span>
		No messages found here.
	</div>
<?php	} else { ?>
<div class='text-success' style='font-size:16px;' >
	<?php echo $total; ?> Results found
</div>
<br/>
<form  method='post' onsubmit="return ($('#action').val()=='delete_batch')?confirm('Do you really want to delete the selected records?'):true;">
	<input type='hidden' name='perpage' value='<?php echo $filter['perpage']; ?>' />
		<div class='modal fade' id='smsModal' tabindex='-1' role='dialog' aria-labelledby='smsModalLabel'>
		  <div class='modal-dialog' role='document'>
			<div class='modal-content'>
			  <div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title' id='smsModalLabel'>SEND SMS</h4>
			  </div>
			  <div class='modal-body'>
				<div class='help-block' id='recipient_info'>
				
				</div>
				<div class='row'>
					<div class='form-group col-md-8 col-sm-7 col-xs-6'>
						<label for='sender_id'>Sender ID</label>
						<input class='form-control input-sm' type='text' maxlength='11' minlength='3' name='sender_id' id='sender_id' placeholder='<?php echo $my_profile['default_sender_id']; ?>' />
					</div>
					<div class='form-group col-md-4 col-sm-5 col-xs-6'>
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
				<div class='checkbox'>
					<label for='unicode'>
						<input type='checkbox' name='unicode' value='1' />
						Preserve Unicode <i>(e.g Chinese, Russian or other special symbols) <strong>72 chars/page</strong></i>
					</label>			
				</div>
				<div class='help-block'>Optionally schedule this message to rather be sent later.</div>
				<div class='row'>
					<div class='form-group col-md-12'>
						<label for='schedule_date_time'>Future Date-time</label>
						<input class='form-control input-sm' type='datetime-local' placeholder='YYYY-MM-DD hh:mm am'  title='e.g 2016-31-01 5:30 am' name='schedule_date_time' />
					</div>
				</div>
				
				
				<?php if($my_profile['country_id']==37){ ?>
				<div class='form-group'>
					<label style='color:#990000;'>Standard or Corporate(DND Bypass) Route</label>
					<select class='form-control input-sm' id='route' name='route' data-toggle='tooltip' title="TIPS: While using CORPORATE ROUTE helps your budget (economizing by sending through standard channel if the destination has already been found to be unrestrictive). FINANCIAL ROUTE doesn't do such fallback (typically used for OTP/Transactional messages)." >
						<option value='0' <?php if(@$_POST['route']==0)echo 'selected'; ?> >Use Standard Route</option>
						<option value='1' <?php if(@$_POST['route']=='1')echo 'selected'; ?> >Use Corporate Route for all recipients - 2 units per page</option>
						<option value='2' <?php if(@$_POST['route']=='2')echo 'selected'; ?> >Use Corporate Route if on DND - 2 units if on DND</option>
					</select>
				</div>
				<?php } ?>
			
				
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
	<div class='clearfix'></div>
	<div class='text-warning'>
		<i class='fa fa-warning'></i> Please note that; SMS to some networks/countries attracts <strong>more than 1 units</strong> per SMS page 
		(<a href='<?php echo $this->general_model->get_url('coverage_list'); ?>'>see the coverage list</a>)
	</div>
	<div class='clearfix'></div>
	<br/>
	<div class='table-responsive'>
		<table class='table table-bordered table-striped'>
			<thead>
				<th class='col-xs-1'>
					<input type='checkbox' id='control_checkbox' /> S/N
				</th>
				<th class='col-xs-2'>Recipient</th>
				<th class='col-xs-2'>Sender</th>
				<th class='col-xs-5'>Message</th>
				<th class='col-xs-2'>Info</th>
			</thead>
		<?php
			$sn=0;
			$offset=$filter['offset'];
			//todo: export link on the top.
			foreach($messages as $sms)
			{
				$sn++;
				$sms['recipient']='+'.$sms['recipient'];
			?>
			<tr>
				<td>
					<input type='checkbox' name='<?php echo "checkbox_$sn"; ?>' id='<?php echo "checkbox_$sn"; ?>' value='<?php echo $sms['sms_id'].':'.$sms['recipient']; ?>' class='single_checkbox' >
					<?php echo ++$offset; ?>.
				</td>
				<td>
					<?php					
						if(!empty($sms['firstname'])||!empty($sms['lastname']))echo "<a href='tel:{$sms['recipient']}' title='{$sms['recipient']}' class='recipient' >{$sms['firstname']}  {$sms['lastname']} </a>";
						else echo "<a href='tel:{$sms['recipient']}' class='recipient' ><i class='fa fa-phone'></i> {$sms['recipient']}</a>"; 
						
						if(!empty($sms['group_name']))echo "<div class='text-muted'><i class='fa fa-users'></i> {$sms['group_name']} </div>";
					?>
					<div>
						<a class='btn btn-default btn-xs send_single_sms' checkbox='<?php echo "#checkbox_$sn"; ?>' title="<?php echo "TO: {$sms['recipient']}"; ?>" href='javascript:;' data-route="<?php echo $sms['route']; ?>">
							<i class='fa fa-envelope'></i> Edit & Send SMS
						</a>
					</div>
				</td>
				<td class='sender_id' ><?php echo $sms['sender']; ?></td>
				<td style='white-space: normal;' >
					<div class='message'><?php echo $sms['message']; ?></div>
					<div class='text-right' >
						<span style='font-size:70%;'>Batch ID: <?php echo $sms['batch_id']; ?></span>
						<a class='btn btn-default btn-xs' href='<?php echo $this->general_model->get_url("sms_log?p=$p&q={$filter['search_term']}&stage={$filter['stage']}&action=delete&sms_id={$sms['sms_id']}"); ?>' title='Delete' >
								<i class='fa fa-trash text-danger'></i>
						</a>
					</div>
				</td>
				<td>
					<div style='font-style:italic;' >
					<?php $ts=array($sms['time_submitted'],$sms['time_scheduled'],$sms['time_sent']);
						echo date('d-m-Y g:i a',max($ts)); ?>
					</div>
					<div>
						<span class='<?php echo $this->general_model->get_sms_design($sms['status'],'text-');?>' title="<?php echo $this->general_model->sms_status[$sms['status']]['msg']; ?>" >
							<?php echo $this->general_model->sms_status[$sms['status']]['title']; ?>
						</span>
						<span class='btn btn-xs btn-default'><?php echo $sms['pages']; echo ($sms['pages']>1)?' pages':' page'; ?></span>
						<?php if(!empty($sms['units'])){ ?>
						<span class='label label-default'><?php echo $sms['units'];  echo ($sms['units']>1)?' units':' unit'; ?></span>
						<?php } ?>
                        <?php if($sms['status']!=2){ ?>
						<div style='font-size:70%;' class='text-muted' ><?php echo $sms['info']; ?></div>
						<?php } ?>
					</div>
				</td>
			</tr>	
		<?php } ?>
		</table>
		
	</div>
	<div class='row'>
		<div class='col-md-3 col-sm-3 alone'>
			<div class='input-group'>
			<select class='form-control input-sm' name='action' id='action' onchange="if(this.value=='send_sms')showSMSModal()" required >
				<option value=''> with selected </option>
				<option value='send_sms'> Send SMS </option>
				<option value='download_csv'> Download CSV (comma separated numbers) </option>
				<option value='download_csv_space'> Download CSV  (space separated numbers) </option>
				<option value='delete_batch'> Delete  Record </option>
			</select>
			<span class='input-group-btn'>
				<button class='btn btn-default btn-sm' id='go_button' >
					<i class='fa fa-play'></i> GO
				</button>
			</span>
			</div>
		</div>
		<div class='col-md-9 col-sm-9 text-right alone'>
			<?php
				if($totalpages>0)echo $this->general_model->get_pagination($p,$totalpages);	
			?>
		</div>
	</div>

</form>

<?php } ?>


<script type='text/javascript'>
 //todo: if(action=='send_sms'&&modal_state=='hidden'){showmodal()}
	
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

 
	var num=0;
	
	function showSMSModal(btn)
	{
		if(typeof btn==='undefined')
		{
			var sel_count=$('.single_checkbox:checked').length;
			
			if(sel_count==0)
			{
				ui_alert('Please select recipients.');
				return;
			}
			
			if(sel_count>1)msg="Send SMS to the "+sel_count+" selected recipients.";
			else msg="Send SMS to the selected recipient. ";
			
			$('#recipient_info').html(msg);
		}
		else
		{	//prefill message and sender-id.
			
			$('#recipient_info').html(btn.attr('title'));
			btntr=btn.closest('tr');
			$('#message').val(btntr.find('.message').text());
			$('#sender_id').val(btntr.find('.sender_id').text());
			$('#route').val(btntr.attr('data-route'));
		}
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
			showSMSModal($(this));
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