<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
<div class="panel panel-default">
		<div class='panel-heading'>
			<form class='form-inline' role='form'>
				<div class='form-group'>
					<select name='country' class='form-control input-sm'>
						<option value='' >--Country--</option>
							<?php foreach($countries as $country_code=>$country){
									$isel=($country_code==$filter['country'])?'selected':'';
							?>
								<option value='<?php echo $country_code; ?>' <?php echo $isel; ?> ><?php echo $country; ?>
							<?php } ?>
					</select>
				</div>
							
				<div class='form-group'>
					<select name='o' class='form-control input-sm'>
						<option value='last_seen' <?php if($filter['order_by']=='last_seen')echo 'selected'; ?> >ORDER BY: Last Seen</option>
						<option value='balance' <?php if($filter['order_by']=='balance')echo 'selected'; ?> >ORDER BY: Balance</option>
						<option value='email' <?php if($filter['order_by']=='email')echo 'selected'; ?> >ORDER BY: Email</option>
					</select>
				</div>
				
				<div class='form-group'>
					<div class='input-group'>
						<input type='search' name='q' value="<?php echo $filter['search_term'];?>" class='form-control input-sm' placeholder='search term..'>
						<span class='input-group-btn'>
							<button  class='btn btn-sm btn-default' title='Search'>
								<i class='fa fa-search'></i>
							</button>
						</span>
					</div>
				</div>
			</form>
		</div>
		<div class="panel-body">
			<?php if(empty($users)){ ?>
				<div class='alert alert-warning'>
					<button class='close' data-dismiss='alert'>&times;</button>
					No record found!
				</div>
			<?php } else { ?>
			<div class='table-responsive'>
			<table style='font-size:10px;' class='table table-striped table-bordered'>
				<tr >
					<th>S/N</th>
					<th>User Id</th>
					<th>Email</th>
					<th>Balance</th>
					<th>Firstname</th>
					<th>Lastname</th>
					<th>Phone</th>
					<th>Country</th>
					<th>Last Seen</th>
					<th>Actions</th>
				</tr>
				<?php
					$sn=$filter['offset'];
					$flarr=array(-1=>'Trusted',0=>'Neutral',1=>'Single Linked SMS',2=>'No Linked SMS',3=>'Restricted');
					
					foreach($users as $row)
					{
					?>
						<tr >
							<td><?php echo ++$sn; ?></td>
							<td ><?php echo $row['user_id']; ?></td>
							<td >
								<a href='<?php echo $this->general_model->get_url("admin_send_email?recp={$row['email']}"); ?>' target='_blank'><?php echo $row['email']; ?></a>
								
								<?php if(!empty($row['verification_file'])){ ?>
									<div style='margin-top:5px;' >
										<a href='<?php echo $this->general_model->get_url($row['verification_file']); ?>' target='_blank' class='btn-xs' ><i class='fa fa-picture-o'></i> view doc</a>
										<a href='<?php echo "?delete_verifiation_file={$row['user_id']}"; ?>' class='text-danger btn-xs' onclick="return confirm('do you really want to delete this verification?');" ><i class='fa fa-trash'></i> delete doc</a>
									</div>
								<?php } ?>
							</td>
							<td ><?php echo $row['balance']; ?>
								<span onclick="$(this).closest('td').find('.topup_units_form').slideToggle();" style='cursor:pointer;' >Add Units <i class='fa fa-chevron-down'></i></span>
								<form method='post' style='margin-top:5px;display:none;' class='form-inline topup_units_form' >
									<input type='hidden' name='topup_user_id' value='<?php echo $row['user_id']; ?>' />
									<div class='form-group' >
										<div class='input-group' >
											<span class='input-group-addon' style='padding:0 4px;' title="Log Transaction">
												<input type='checkbox' name='log_transaction' value='1' >
											</span>
											<input type='number' pattern='[0-9]*' class='form-control input-xs' name='topup_units'  />
										</div>
									</div>
									<button class='btn btn-xs btn-info'>Add / Remove Units</button>
									<div class='clearfix'></div>
									<hr/>
								</form>
							</td>
							<td ><?php echo $row['firstname']; ?></td>
							<td ><?php echo $row['lastname']; ?></td>
							<td ><a href='<?php echo $this->general_model->get_url("send_sms?recp={$row['phone']}"); ?>' target='_blank'><?php echo $row['phone']; ?></a></td>
							<td ><?php echo @$countries[$row['country_code']]; ?></td>
							<td>
								<?php echo date('D jS M. Y g:i a',$row['last_seen']); ?>
								<form method='post' >
									<input type='hidden' name='flag_user_id' value='<?php echo $row['user_id']; ?>' />
									<div class='form-group' style='display:inline'>
										<div class='input-group'>
											<select  class='form-control input-xs' name='flag_level' >
												<?php for($i=-1;$i<=3;$i++){ ?>
												<option value='<?php echo $i; ?>' <?php if($row['flag_level']==$i)echo 'selected'; ?> ><?php echo $flarr[$i]; ?></option>
												<?php } ?>
											</select>
											<span class='input-group-btn'>
												<button class='btn btn-xs btn-default'>flag</button>
											</span>
										</div>
									</div>
								</form>
							</td>
							<td>
								<a href="<?php echo $this->general_model->get_url("admin_sms_log?email={$row['email']}"); ?>" title='SMS LOG' class='btn btn-xs btn-default' >
									<i class='fa fa-list'></i>
								</a>
								<a href="<?php echo $this->general_model->get_url("admin_transaction_history?email={$row['email']}"); ?>" title='Transaction History' class='btn btn-xs btn-default' >
									<i class='fa fa-list-alt'></i>
								</a>
							</td>
						 </tr>
					<?php
					}
					?>
			</table>					
			</div>
			<?php				
				$pagination=$this->general_model->get_pagination($p,$totalpages);
			}
			if(empty($pagination))$pagination=" ";
?>
		</div>
		<div class="panel-footer" style='text-align:right;'><?php echo $pagination;?></div>
</div>