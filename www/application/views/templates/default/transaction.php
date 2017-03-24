<h3 class='text-right' >
	<span class='pull-left'>
		<i class='fa fa-list'></i> TRANSACTION
	</span>
	<a href='<?php echo $this->general_model->get_url('pricing');?>' class='btn btn-success btn-sm pull-right'>
		My Balance: <?php echo $my_balance.' units'; ?> 
	</a>
</h3>
<div class='clearfix'></div>
<hr/>
<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error); ?>
<?php
	if(!empty($transaction))
	{
	?>
		<style type='text/css'>
			.errorMessage,.successMsg
			{
				color:#ffffff;
				font-size:18px;
				font-family:helvetica;
				border-radius:9px;
				display:inline-block;
				max-width:500px;
				border-radius: 8px;
				padding: 4px;
				margin:auto;
			}
			.errorMessage a,.successMsg a
			{
				color:#fff;
				font-weight:bold;
			}
			
			.errorMessage{background-color:#ff5500;}
			.successMsg{background-color:#00aa99;}			
			body,html{min-width:100%;}
		</style>
		<?php
			$json_info=$this->general_model->get_json($transaction['json_info']);
			$json_details=$this->general_model->get_json($transaction['json_details']);
			
			$next_url=$this->general_model->get_url('transaction/');
			$full_info_url=$this->general_model->get_invoice_link($transaction,true);
			
		if(!empty($display_receipt))
		{
		?>
			<div class='clearfix'></div>
			<div class='table-responsive'>
			<ul class='list-group before_submit'>
				<li class='list-group-item'><strong>DETAILS:</strong> <?php echo $transaction['details']; ?></li>
				<li class='list-group-item'><strong>DATE:</strong> <?php echo date('d-m-Y g:i a',$transaction['time']); ?></li>
				<li class='list-group-item'>
					<strong>Amount:</strong> <?php  echo $json_details['original_amount']." ".$transaction['currency_code'];?>
				</li>
				<li class='list-group-item'>
					<strong>Payment Gateway Fee:</strong> <?php  echo $json_details['gateway_charges']." ".$transaction['currency_code'];?>
				</li>
				<?php if(!empty($json_details['payment_method_fixed_charges'])){ ?>
				<li class='list-group-item'>
					<strong>Payment Method's Fixed Charges:</strong> <?php  echo $json_details['payment_method_fixed_charges']." ".$transaction['currency_code'];?>
				</li>
				<?php } ?>
				<li class='list-group-item'>
					<strong>Total Amount Payable:</strong> <?php  echo $transaction['amount']." ".$transaction['currency_code'];?>
				</li>
				<li class='list-group-item'>
					<strong>Payment Method:</strong> <?php echo $configs[$transaction['payment_method']."_label"];?>
				</li>
				<li class='list-group-item'>
					<strong>Status:</strong> <?php 
						if(empty($transaction['status']))echo "<span class='text-warning'>PENDING</span>";
						elseif($transaction['status']==1)echo "<span class='text-success'>COMPLETED</span>";
						elseif($transaction['status']==-1)echo "<span class='text-danger'>FAILED</span>";
						echo 'UNDETERMINED';
						?>
				</li>
			</ul>
			</div>
			<div  class='text-right hidden_from_print'>
				<a href="<?php echo $full_info_url; ?>" class='btn btn-xs btn-default'><i class='fa fa-print'></i> Print</a>
			</div>
		<?php		
		}
		elseif($transaction['status']==1)
		{			
		?>
			<div class='successMsg'>				
				<?php if($transaction['payment_method']=="skye"||$transaction['payment_method']=="firstpay"||$transaction['payment_method']=="stanbicibtc"||$transaction['payment_method']=='zenith_globalpay')
						{
							echo "Transaction Ref No.: {$transaction['transaction_reference']}<br/>
								  Payment Reference.: {$json_info['Payment Reference']}<br/>
								  Order ID: {$json_info['Order ID']}<br/>
								  Transaction Date.: ".date('D, F d, Y  g:i a',$transaction['time'])."<br/>
								  Transaction Status: {$json_info['Transaction Status']}<br/>
								  Transaction Amount: {$transaction['transaction_amount']}<br/>
								  Transaction Currency: {$transaction['currency_code']}<br/>";
						} else { ?>
					<?php echo empty($json_info['response_description'])?@$json_info['info']:$json_info['response_description']; ?><br/>
					Your order has been successfully Processed <br/>
					TRANSACTION REFERENCE: <?php echo $transaction['transaction_reference'];?><br/>
					<?php } ?>
					<div>
						<a href='<?php echo $next_url;?>' style='font-size:11px;'>VIEW TRANSACTION LOG</a>
						<a href="<?php echo $full_info_url;?>"  class='pull-right'>Receipt</a>
					</div>
				
			</div>
		<?php
		}
		else
		{
		?>
			<div class='errorMessage'>
				<?php if($transaction['payment_method']=="skye"||$transaction['payment_method']=="firstpay"||$transaction['payment_method']=="stanbicibtc"||$transaction['payment_method']=='zenith_globalpay')
						{ 
							$dstatus=($transaction['status']==-1)?'Failed':'Pending';
							$dresp=empty($json_info['response_description'])?$json_info['info']:$json_info['response_description'];
							echo "Transaction Ref No.: {$transaction['transaction_reference']}<br/>
								  Transaction Date.: ".date('D, F d, Y  g:i a',$transaction['time'])."<br/>
								  Transaction Status: $dstatus<br/>
								  Transaction Amount: {$transaction['transaction_amount']}<br/>
								  Transaction Currency: {$transaction['currency_code']}<br/>
								  Response: $dresp";
						} else {
						
						if($transaction['payment_method']!='gtpay'&&$transaction['payment_method']!='interswitch'&&$transaction['status']==0)echo 'Your transaction is yet to be completed';
						else echo 'Your transaction was not successful';
						?>
					
					
					<br/>
					REASON: <?php echo empty($json_info['response_description'])?@$json_info['info']:$json_info['response_description'];  ?><br/>
					TRANSACTION REFERENCE: <?php echo $transaction['transaction_reference'];?><br/>
					<?php } ?>
					<div>
						<a href='<?php echo  $next_url; ?>' style='font-size:11px;'>VIEW TRANSACTION LOG</a>
						<a href="<?php echo $full_info_url; ?>"  class='pull-right'>Receipt</a>
					</div>
			</div>
<?php
		}
	}
	elseif(!empty($list_transactions))
	{
?>				
	<div class="panel">
		<div class='panel-heading text-right'>					
			<form class='form-inline autovalidate' role='form'>
				<div class='form-group'>
					<input type='date' name='sd' value="<?php echo $filter['start_date'];?>" class='form-control input-sm datepicker' validateas='date' placeholder='start date' />
				</div>
				<div class='form-group'>
					<input type='date' name='ed' value="<?php echo $filter['end_date'];?>" class='form-control input-sm datepicker' validateas='date' placeholder='end date'/>
				</div>
				<div class='form-group'>
					<select name='s' class='form-control input-sm'>
						<option value='' >--STATUS--</option>
						<option value='0' <?php if($filter['status']=='0')echo 'selected';?>>Pending</option>
						<option value='-1' <?php if($filter['status']=='-1')echo 'selected';?>>Failed</option>
						<option value='1' <?php if($filter['status']=='1')echo 'selected';?>>Completed</option>
					</select>
				</div>
				<button  class='btn btn-sm btn-default text-warning'>
					<i class='fa fa-search'></i>
				</button>
			</form>
		</div>
		<div class="panel-body">
			<?php if(!empty($transactions)){ ?>
			<div class='table-responsive'>
			<table style='width:100%;font-size:11px;' class='table table-striped table-condensed table-bordered'>
				<tr style='white-space:nowrap;'>
					<th>S/N</th>
					<th>Details</th>
					<th>Total Amount</th>
					<th>Date</th>
					<th>Trans.ref.</th>
					<th>Payment </th>
					<th>Action </th>
				</tr>
				<?php
					$sn=$filter['offset'];	
					
					foreach($transactions as $row)
					{
						$json_info=$this->general_model->get_json($row['json_info']);
						$json_details=$this->general_model->get_json($row['json_details']);
						
						if($row['status']==1)
						{
							$final_url=$this->general_model->get_invoice_link($row,true);
							$trans_action="<a href='$final_url' class='btn btn-primary btn-xs' title='Print / Download' target='_blank'>
								<span class='glyphicon glyphicon-print' ></span>								
								</a>";
						}
						else
						{
							$trans_action="<a href='".$this->general_model->get_url("transaction?p=$p&confirm_trans={$row['transaction_reference']}")."' class='btn btn-sm btn-info'>REQUERY</a>";
						}
						$ddate=date('m/d/Y H:i',$row['time']);
					
					?>
						<tr >
							<td><?php echo ++$sn; ?></td>
							<td>
								<?php echo $row['details']; ?>
								<?php if($row['payment_method']=='bank') { ?>
									<div class='text-info'>
										TELLER NUMBER: <strong><?php echo $row['related']; ?></strong>
									</div>
								<?php } ?>
							</td>
							<td>
								<?php echo $row['amount']; ?> <?php echo $row['currency_code']; ?>
							</td>
							<td style='white-space:nowrap;'>
								<?php echo $ddate; ?>
							</td>
							<td>
								<?php echo $row['transaction_reference']; ?>
							</td>
							<td style='word-break:break-all;'>
								<strong>Approved Amount:</strong> <?php echo number_format(@$json_details['approved_amount'],2); ?>  <?php echo $row['currency_code']; ?>
								<br/>
								<strong>VIA:</strong> <?php echo $this->general_model->split_format($row['payment_method']); ?>
								<?php $has_extra=false; ?>
								<div class='payment_more_details' >
									<?php 
										if($row['status']==0&&$row['payment_method']=='bank_deposit'||$row['payment_method']=='pay_on_delivery'){ 
										$has_extra=true; 
										?>
										<form method='post'>											
											<input type='hidden' name='confirm_trans' value='<?php echo $row['transaction_reference']; ?>' />
											<div class='form-group'>
												<label>Bank Name</label>
												<input name='bank_name' type='text' class='form-control input-sm' placeholder='GTB' required value="<?php echo @$json_info['bank_name'];?>" />
											</div>
											<div class='form-group'>
												<label>Depositor's Name</label>
												<input name='depositor_name' type='text' class='form-control input-sm' required placeholder="<?php echo $my_profile['email']; ?>" value="<?php echo @$json_info['depositor_name'];?>" />
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
												<input name='payment_date' type='date' class='form-control input-sm' required value="<?php echo @$json_info['payment_date'];?>" placeholder='dd-mm-yyyy' title='e.g 28-10-2015' pattern="<?php echo $this->general_model->date_patern; ?>" />
											</div>
											<button class='btn btn-default btn-xs pull-right'  name='submit_teller' value='submit_teller'>Submit</button>
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
												<input name='senders_firstname' type='text' class='form-control input-sm' required placeholder="<?php echo $my_profile['firstname']; ?>" value="<?php echo @$json_info['senders_firstname'];?>" />
											</div>
											<div class='form-group'>
												<label>Sender's Lastname</label>
												<input name='senders_lastname' type='text' class='form-control input-sm' required placeholder="<?php echo $my_profile['lastname']; ?>" value="<?php echo @$json_info['senders_lastname'];?>" />
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
											<button class='btn btn-default btn-xs pull-right' name='submit_western_union' value='submit_western_union' >Submit</button>
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
											<button class='btn btn-default btn-xs pull-right' name='submit_ussd_code' value='submit_ussd_code' >Submit</button>
										</form>
										<?php
										}
										else
										{
											if(!empty($json_info['response_description'])){ $has_extra=true;
										?>
											<strong>Response Description</strong><br/>
											<?php echo $json_info['response_description']; ?>
											<br/>
											<?php }  
												if(!empty($json_info['info'])&&$row['status']!=1){ $has_extra=true;
											?>
											<strong>Response Information</strong><br/>
											<?php echo nl2br($json_info['info']); ?>
											<br/>
											<?php } 
											if(!empty($json_info['response_code'])){ $has_extra=true;
											?>
											<strong>Response Code: </strong>
											<?php echo empty($json_info['response_code'])?'':$json_info['response_code']; ?>
										<?php
											}
										}
										?>
								</div>
								<?php if($has_extra){ ?>
								<div onclick="$(this).closest('td').find('.payment_more_details').slideToggle('fast');" class='btn-link text-center' style='cursor:pointer;' >
									<?php if($row['status']==0&&$row['payment_method']=='bank_deposit')
													echo 'Submit Teller/Bank Transfer Detail';
											else 	echo 'Show More Details';
									?>
									<i class='fa fa-chevron-up'></i> 
								</div>
								<?php } ?>
							</td>
							
							<td>
								<?php if($row['status']==0&&($row['payment_method']=='bitcoin')){ ?>
										<form method='post'>
											<input type='hidden' name='confirm_trans' value='<?php echo $row['transaction_reference']; ?>' />
											<div class='form-group'>
												<label>Bitcoin TX Hash</label>
												<input name='bit_hash' type='text' class='form-control input-sm'  minlength='64' maxlength='64' pattern='^[a-z0-9]{64}$' required />
											</div>
											<button class='btn btn-default btn-xs pull-right' value='requery' >Requery</button>
										</form>
										<?php
										} elseif(!in_array($row['payment_method'],$this->general_model->payment_methods_no_requery))echo $trans_action; ?>
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
<?php	
	}
?>