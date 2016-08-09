<h3><?php
 if($filter['stage']==='pending')echo "<i class='fa fa-clock-o'></i> Scheduled SMS Log";
 elseif($filter['stage']==='sent')echo "<i class='fa fa-envelope'></i> Sent SMS Log";
 elseif($filter['stage']==='failed')echo "<i class='fa fa-times-circle'></i> Failed SMS Log";
 else echo "<i class='fa fa-list'></i> SMS Log";
?></h3>
<hr/>
<div class='alert'>
	<?php echo date('D jS M',strtotime($filter['start_date'])).' to '.date('D jS M',strtotime($filter['end_date'])+86399) ; ?>
</div>
<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
<div class='text-right'>
	<form  method='get' class='form-inline' role='form' onsubmit='return searchFormSubmitted()' id='search_form'  >
		<div class='form-group'>
			<select name='deleted' class='form-control input-sm'>
				<option value='' <?php if($filter['deleted']==='')echo 'selected'; ?>>exclude deleted</option>
				<option value='2' <?php if($filter['deleted']==='2')echo 'selected'; ?>>with deleted</option>
				<option value='1' <?php if($filter['deleted']==='1')echo 'selected'; ?>>only deleted</option>
			</select>
		</div>
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
				<input type='date' pattern='<?php echo $this->general_model->date_patern; ?>'  title='e.g 2016-01-31' placeholder='yyyy-mm-dd' name='sd' value='<?php echo $filter['start_date']; ?>' class='form-control input-sm'>
			</div>
		</div>
		<div class='form-group'>
			<div class='input-group'>
				<span class='input-group-addon'>To:</span>
				<input type='date' pattern='<?php echo $this->general_model->date_patern; ?>'  title='e.g 2016-01-31' placeholder='yyyy-mm-dd' name='ed'  value='<?php echo $filter['end_date']; ?>' class='form-control input-sm'>
			</div>
		</div>
		<div class='form-group' id='perpage_div'>
			<div class='input-group' style='max-width:150px;'>
				<input type='number' min='5' name='perpage' placeholder='10' value='<?php echo $filter['perpage']; ?>' class='form-control input-sm'>
				<span class='input-group-addon' id='perpage_span' > perpage</span>
			</div>
		</div>
		<div class='form-group'>
			<input type='email' name='email' placeholder='Filter by email' value='<?php echo $filter['email']; ?>' class='form-control input-sm'>
		</div>
		<div class='form-group'>
			<input type='search' name='q' placeholder='Recipient or message' value='<?php echo $filter['search_term']; ?>' class='form-control input-sm'>
		</div>
		<div class='form-group'>
			<div class='input-group'>
				<select class='form-control input-sm' name='result_action' id='result_action' onchange='resultActionChanged()' title='What should be done with any result(s) found' >
					<option value='' <?php if($filter['result_action']=='')echo 'selected'; ?>> display results </option>
					<option value='calculate_total_units' <?php if($filter['result_action']=='calculate_total_units')echo 'selected'; ?> >Calculate total SMS units</option>
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

		foreach($messages as $sms)
		{
			$sn++;
			$sms['recipient']='+'.$sms['recipient'];
		?>
		<tr>
			<td>
				<?php echo ++$offset; ?>.
				<a href='<?php echo "?user_id={$sms['user_id']}"; ?>' title='Filter by this user' ><i class='fa fa-filter'></i></a>
			</td>
			<td>
				<?php					
					if(!empty($sms['firstname'])||!empty($sms['lastname']))echo "<a href='tel:{$sms['recipient']}' title='{$sms['firstname']}  {$sms['lastname']}' class='recipient' > {$sms['recipient']}</a>";
					else echo "<a href='tel:{$sms['recipient']}' class='recipient' ><i class='fa fa-phone'></i> {$sms['recipient']}</a>"; 
					
					if(!empty($sms['group_name']))echo "<div class='text-muted'><i class='fa fa-users'></i> {$sms['group_name']} </div>";
				?>
			</td>
			<td class='sender_id' ><?php echo $sms['sender']; ?></td>
			<td>
				<span class='message'><?php echo $sms['message']; ?></span>
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
					<?php if(!empty($sms['deleted'])){ ?>
					<span class='label label-danger'>deleted</span>
					<?php } ?>
				</div>
				<div style='font-size:70%;'>Batch ID: <?php echo $sms['batch_id']; ?></div>
			</td>
		</tr>	
	<?php } ?>
	</table>
	<div class='text-right'>
		<?php echo $this->general_model->get_pagination($p,$totalpages); ?>
	</div>
<?php } ?>