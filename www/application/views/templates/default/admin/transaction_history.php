<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error); ?>
	<div class="panel panel-default">
		<div class='panel-heading text-right'>
			<form class='form-inline autovalidate' role='form' class='pull-right col-md-8'>
				<div class='form-group'>
					<input type='text' name='email' value="<?php echo $filter['email'];?>" class='form-control input-sm' placeholder='email' />
				</div>
				<div class='form-group'>
					<input type='date' name='sd' value="<?php echo $filter['start_date'];?>" class='form-control input-sm datepicker' validateas='date' placeholder='start date' />
				</div>
				<div class='form-group'>
					<input type='date' name='ed' value="<?php echo $filter['end_date'];?>" class='form-control input-sm datepicker' validateas='date' placeholder='end date' />
				</div>
				<div class='form-group'>
					<select name='s' class='form-control input-sm'>
						<option value='' >--STATUS--</option>
						<option value='0' <?php if($filter['status']=='0')echo 'selected';?>>Pending</option>
						<option value='-1' <?php if($filter['status']=='-1')echo 'selected';?>>Failed</option>
						<option value='1' <?php if($filter['status']=='1')echo 'selected';?>>Completed</option>
					</select>
				</div>
				
				<div class='form-group'>
					<select name='pm' class='form-control input-sm'>
						<option value='' >--Payment Method--</option>
						<?php foreach($this->general_model->payment_methods as $method=>$method_name){
								$isel=($method==$filter['payment_method'])?'selected':'';
						?>
							<option value='<?php echo $method; ?>' <?php echo $isel; ?> ><?php echo $method_name; ?>
						<?php } ?>
					</select>
				</div>
				
				<button  class='btn btn-sm btn-default'>
					<i class='fa fa-search'></i>
				</button>
			</form>
		</div>
		
		<div class="panel-body">
			<?php
				if(!empty($transactions))
				{
			?>
			<div class='table-responsive'>
			<table class='table table-striped table-bordered'>
				<tr style='white-space:nowrap;'>
					<th>S/N</th>
					<th>EMAIL</th>
					<th>DETAILS</th>
					<th>TRANS. REF.</th>
					<th>DATE</th>
					<th>AMOUNT</th>
					<th>PAYMENT </th>
					<th>ACTION </th>
				</tr>
				<?php					
					$sn=$filter['offset'];				

					foreach($transactions as $row)
					{

						$json_info=$this->general_model->get_json($row['json_info']);
						$json_details=$this->general_model->get_json($row['json_details']);
						

						if($row['status']==1)
						{
							$final_url=$this->general_model->get_invoice_link($row);
							$trans_action="<a href='$final_url' class='btn btn-primary btn-xs' title='Print / Download' target='_blank'>
								<span class='glyphicon glyphicon-print' ></span>								
								</a>";
						}
						else
						{
							$trans_action="<a href='".$this->general_model->get_url("transaction?p=$p&confirm_trans={$row['transaction_reference']}")."' target='_blank' class='btn btn-sm btn-info'>REQUERY</a>";
						}
						$ddate=date('d-m-Y g:i a',$row['time']);
						$n=$total-$sn;
						$sn++;
					?>
							<tr >
									<td><?php echo $n; ?></td>
									<td><a href="<?php echo $this->general_model->get_url("admin_manage_users?q={$row['email']}"); ?>"><?php echo $row['email']; ?></a></td>
									<td><?php echo $row['details']; ?></td>
									<td><?php echo $row['transaction_reference']; ?>
									</td>
									<td style='white-space:nowrap;'>
										<?php echo $ddate; ?>
									</td>
									<td>
										<span class='btn btn-xs btn-default'><?php echo $row['amount'].' '. $row['currency_code']; ?></span>
									</td>
						<td>
							<strong>Approved Amount:</strong> <?php echo number_format(@$json_details['approved_amount'],2); ?>  <?php echo $row['currency_code']; ?>
							<br/>
							<strong>VIA:</strong> <?php echo $this->general_model->split_format($row['payment_method']); ?>

							<div onclick="$(this).closest('td').find('.payment_more_details').slideToggle('fast');" class='btn-link text-center' style='cursor:pointer;' >
								<?php if($row['status']==0&&$row['payment_method']=='bank_deposit')
												echo 'Teller/Bank Transfer Detail';
										else 	echo 'Show More Details';
								?>
								<i class='fa fa-chevron-down'></i> 
							</div>
							<div class='payment_more_details'>
							
							<?php 
									if($row['status']==0&&($row['payment_method']=='bank_deposit'||$row['payment_method']=='pay_on_delivery')){ ?>
									<form method='post'>
										<input type='hidden' name='confirm_trans' value='<?php echo $row['transaction_reference']; ?>' />
										<div class='form-group'>
											<label>Bank Name</label>
											<input name='bank_name' type='text' class='form-control input-sm' required placeholder='Access Bank' value="<?php echo @$json_info['bank_name'];?>" />
										</div>
										<div class='form-group'>
											<label>Depositor's Name</label>
											<input name='depositor_name' type='text' class='form-control input-sm' required  value="<?php echo @$json_info['depositor_name'];?>" />
										</div>
										<div class='form-group'>
											<label>Teller/Reference Number</label>
											<input name='teller_number' type='text' class='form-control input-sm' required value="<?php echo @$json_info['teller_number'];?>" />
										</div>
										<div class='form-group'>
											<label>Amount Transferred</label>
											<input name='amount_transfered' type='number' step='any' placeholder='<?php echo $row['amount']; ?>' class='form-control input-sm' required value="<?php echo @$json_info['amount_transfered'];?>" />
										</div>
										<div class='form-group'>
											<label>Payment Date</label>
											<input name='payment_date' type='date' class='form-control input-sm' required value="<?php echo @$json_info['payment_date'];?>" placeholder='yyyy-mm-dd' title='e.g 28-10-2015' pattern="<?php echo $this->general_model->date_patern; ?>" />
										</div>
										
										<button class='btn btn-success btn-xs pull-right' name='complete' value='complete' onclick="return confirm('This action is irreversible\r\nDo you really want to complete this transaction?');" >Complete</button>
									</form>
									<?php
									}
									elseif($row['status']==0&&($row['payment_method']=='western_union')){ ?>
									<form method='post'>
										
										<input type='hidden' name='confirm_trans' value='<?php echo $row['transaction_reference']; ?>' />
										<div class='form-group'>
											<label>Sender's Country</label>
											<input name='senders_country' type='text' class='form-control input-sm' required placeholder='Nigeria' value="<?php echo @$json_info['senders_country'];?>" />
										</div>
										<div class='form-group'>
											<label>Sender's Firstname</label>
											<input name='senders_firstname' type='text' class='form-control input-sm' required  value="<?php echo @$json_info['senders_firstname'];?>" />
										</div>
										<div class='form-group'>
											<label>Sender's Lastname</label>
											<input name='senders_lastname' type='text' class='form-control input-sm' required value="<?php echo @$json_info['senders_lastname'];?>" />
										</div>
										<div class='form-group'>
											<label>MTCN (10 digits)</label>
											<input name='mtcn' type='text' maxlength='10' minlength='10' pattern='^[0-9]{10}$' class='form-control input-sm' required value="<?php echo @$json_info['mtcn'];?>" />
										</div>
										<div class='form-group'>
											<label>Amount Transferred</label>
											<input name='amount_transfered' type='number' step='any' class='form-control input-sm' required value="<?php echo @$json_info['amount_transfered'];?>" />
										</div>
										<div class='form-group'>
											<label>Security Answer</label>
											<input name='security_answer' type='text' class='form-control input-sm' required value="<?php echo @$json_info['security_answer'];?>"  />
										</div>
										
										<button class='btn btn-success btn-xs pull-right' name='complete' value='western_union' onclick="return confirm('This action is irreversible\r\nDo you really want to complete this transaction?');" >Complete</button>
									</form>
									<?php
									}		
									elseif($row['status']==0&&($row['payment_method']=='ussd_code')){ ?>
									<form method='post'>
										<input type='hidden' name='confirm_trans' value='<?php echo $row['transaction_reference']; ?>' />
										<div class='form-group'>
											<label>Phone Number</label>
											<input name='bank_phone_number' type='tel' class='form-control input-sm' required value="<?php echo @$json_info['bank_phone_number'];?>" />
										</div>
										<div class='form-group'>
											<label>Amount Transferred</label>
											<input name='amount_transfered' type='number' step='any' class='form-control input-sm' required value="<?php echo @$json_info['amount_transfered'];?>" />
										</div>
										
										<button class='btn btn-success btn-xs pull-right' name='complete' value='ussd_code' onclick="return confirm('This action is irreversible\r\nDo you really want to complete this transaction?');" >Complete</button>
									</form>
									<?php
									}
									elseif($row['status']==0&&in_array($row['payment_method'],$this->general_model->payment_methods_no_requery)){
									?>
									<form method='post'>
										<div class='form-group'>
											<label>Payment Note</label>
											<textarea name='teller_info' class='form-control input-sm' required ><?php echo @$json_info['info'];?></textarea>
											<input type='hidden' name='confirm_trans' value='<?php echo $row['transaction_reference']; ?>' />
										</div>
										<button class='btn btn-success btn-xs pull-right' name='complete' value='complete' onclick="return confirm('This action is irreversible\r\nDo you really want to complete this transaction?');">
											<i class='fa fa-check'></i> Complete</button>
									</form>
									<?php
									}
									else
									{
									
										if(!empty($json_info['mtcn'])){ ?>
										<strong>Western Union Information</strong><br/>
										<?php 
											foreach($this->general_model->payment_western_union_params as $pbp)
												echo $this->general_model->split_format($pbp).': '.$json_info[$pbp].'<br/>';
										?>
										<br/>
										<?php 
										}
										
										if(!empty($json_info['teller_number'])){ ?>
										<strong>Teller Information</strong><br/>
										<?php 
											foreach($this->general_model->payment_bank_params as $pbp)
												echo $this->general_model->split_format($pbp).': '.$json_info[$pbp].'<br/>';
										?>
										<br/>
										<?php 
										}
										
										if(!empty($json_info['bank_phone_number'])){ ?>
										<strong>USSD Information</strong><br/>
										<?php 
											foreach($this->general_model->payment_ussd_code_params as $pbp)
												echo $this->general_model->split_format($pbp).': '.$json_info[$pbp].'<br/>';
										?>
										<br/>
										<?php 
										}
										
										if(!empty($json_info['response_description'])){
									?>
										<strong>Response Description</strong><br/>
										<?php echo $json_info['response_description']; ?>
										<br/>
									<?php } 
															//&&$row['status']!=1
										if(!empty($json_info['info'])){ ?>
										<strong>Response Information</strong><br/>
										<?php echo nl2br($json_info['info']); ?><br/>
										<?php }?>
										
										<strong>Response Code: </strong>
										<?php echo empty($json_info['response_code'])?'':$json_info['response_code']; ?>
									<?php
									}
									?>
								
							</div>
						</td>
						
							<td >
								<?php if(!in_array($row['payment_method'],$this->general_model->payment_methods_no_requery))echo $trans_action; ?>
							<?php
								if($row['status']==-1)echo "<div><span class='btn btn-xs text-danger'><i class='fa fa-times'></i> Failed</span></div>";
								elseif($row['status']==1)echo "<div><span class='btn btn-xs text-success'><i class='fa fa-check'></i> Completed</span></div>";
								else echo "<div><span class='btn btn-xs text-warning'><i class='fa fa-ban'></i> Pending</span></div>";
							?>
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
		<div class="panel-footer text-right"><?php echo $pagination;?></div>
	</div>