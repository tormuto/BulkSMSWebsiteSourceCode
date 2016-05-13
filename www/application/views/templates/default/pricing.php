<h2><?php echo $section_title; ?></h2>
<hr/>
<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
<?php if(!isset($configs['tax_percent'])){ ?>
<div class='alert alert-danger'>
	Payment gateway is not yet configured.
</div>
<?php } else { ?>

<?php if(empty($transaction)){ ?>
<div class='help-block'><strong class='text-warning'><i class='fa fa-info-circle'></i> You can purchase a minimum of <?php echo $configs['minimum_units']; ?> units at a time.</strong></div>

<form method='post' role='form'>
	<?php
		$currencies=$this->general_model->get_currencies(true);
	?>
		
		<div class='form-group col-md-6 col-sm-6'>
			<label>Amount <small>(+ <?php echo $configs['tax_percent']; ?>% VAT)</small> <span id='rate_span'></span></label>
			<div class='input-group'>
				<input type='number' name='amount'  id='amount' step='0.01' required onkeyup='amountChanged();' class='form-control input-sm'>
				<span class='input-group-addon' id='curr_code_span'></span>
			</div>
		</div>
	
		<div class='form-group col-md-6 col-sm-6'>
			<label>Select Currency</label>
			<select name='payment_currency' id='payment_currency' class='form-control input-sm' onchange="currencyChanged()"  >
				<?php
					$default_pm_options="";
					$av_currencies=array();
					
					foreach($currencies as $currency_code=>$currency_data){
						if(empty($currency_data['enabled']))continue;
						$this_pms="";
						foreach($this->general_model->payment_gateway_params as $apm=>$apmd)
						{
							
							//if($apm=='jostpay'&&(@$my_profile['user_id']!=1&&@$my_profile['user_id']!=165))continue;
							
							if(empty($configs["{$apm}_enabled"]))continue;
							$expcurr=explode(',',$configs["{$apm}_currencies"]);
							if(!in_array($currency_code,$expcurr))continue;
							$is_sel=(@$payment_method==$apm)?'selected':'';
							
							$pg_label=empty($configs["{$apm}_label"])?$this->general_model->split_format($apm):$configs["{$apm}_label"];
						
							$this_pms.="<option value='$apm' $is_sel >$pg_label</option>";
						}
						
						if(empty($this_pms))continue;
						if($configs['currency_code']==$currency_code)$default_pm_options=$this_pms;
						elseif(empty($default_pm_options))$default_pm_options=$this_pms;
						
						$av_currencies[]=$currency_code;
					?>
					<option value='<?php echo $currency_code; ?>'  conv_value='<?php echo $currency_data['value']; ?>'  payment_methods="<?php echo $this_pms; ?>" ><?php echo "{$currency_data['currency_title']} ($currency_code) "; ?> </option>
				<?php } ?>						
			</select>
		</div>
		
		<div class='form-group col-md-6 col-sm-6'>
			<label>SMS Credits</label>
			<div class='input-group'>
				<span class='input-group-addon'> = </span>
				<input type='number' name='units' id='units' min='<?php echo $configs['minimum_units']; ?>' onkeyup='unitsChanged();' class='form-control input-sm' value='50000' >	
				<span class='input-group-addon'>units</span>
			</div>
		</div>
		
		<div class='form-group col-md-6 col-sm-6 col-sm-6'>
			<label>Payment Method</label>
			<select required class='form-control input-sm' name='payment_method' id='payment_method'>
				<option value=''>Select Payment Method</option>
				<?php echo $default_pm_options; ?>
			</select>
		</div>
		<div class='col-md-12 col-sm-12 text-right'>
			<button class='btn btn-default btn-sm alone'>
				Buy SMS Credits
			</button>
		</div>
</form>
<h3>SMS Price List</h3>
<div class='table-responsive'>
	<table class='table table-bordered table-striped'>
		<tr>
			<th class='col-xs-1'>S/N</th>
			<th class='col-xs-4'>Units Range</th>
			<?php
				if(!in_array('USD',$av_currencies))$av_currencies[]='USD';

			foreach($av_currencies as $av_currency){?>
			<th class='col-xs-2'>Price (<?php echo $av_currency; ?>)</th>
			<?php } ?>
		</tr>
	<?php
		$prices=$this->general_model->get_prices();
		$prev_units=0;

		$size=count($prices);
		
		for($sn=0;$sn<$size;$sn++)
		{
		?>
		<tr>
			<td><?php echo $sn+1; ?>.</td>
			<td>
				<?php 
					$temp_min=number_format($prices[$sn]['min_units'],0,'',',');
					
					if($sn+1==$size)$temp_max='above';
					else $temp_max=number_format($prices[$sn+1]['min_units']-1,0,'',',');
					
					echo "$temp_min - $temp_max"; 
				?>
			</td>
			<?php foreach($av_currencies as $av_currency){?>
			<td><?php echo $prices[$sn]['price']*$currencies[$av_currency]['value']; ?></td>
			<?php } ?>
		</tr>	
		<?php
		}
	?>
	</table>

</div>
<div >
	Two factors determines the <strong>actual</strong> cost of a bulk SMS.<br/>
	Cost = Price per units X <a href='<?php echo $this->general_model->get_url('coverage_list');?>' >Units per SMS</a>
</div>
<script type='text/javascript'>
	
	var prices=<?php echo json_encode($prices); ?>;
	var reverse_prices=<?php echo json_encode(array_reverse($prices,true)); ?>;
	var currency_value=0,currency_code='',rate_decimal=2;
	
	function parse_int(str){ return $.isNumeric(str)?parseInt(str):0; }
	function parse_float(str){ return $.isNumeric(str)?parseFloat(str):0; }
	
	function number_format(str,dec,round_to)
	{ 	
		val =parse_float(str);
		pow=Math.pow(10,dec);
		if(typeof round_to!=='undefined'&&round_to)return Math.floor(val*pow)/pow;
		else return Math.ceil(val*pow)/pow;
	}
	
	function currencyChanged()
	{
		$('#payment_method').html($('#payment_currency option:selected').attr('payment_methods'));
		$('#curr_code_span').html($('#payment_currency').val());
		currency_value=$('#payment_currency option:selected').attr('conv_value');
		currency_code=$('#payment_currency').val();
		if(currency_value!=1)rate_decimal=6;
		else rate_decimal=2;
		
		unitsChanged();
	}
	
	
	function unitsChanged()
	{
		var new_amount=0;
		var new_units=parse_int($('#units').val());
		
		if(new_units>0)
		{			
			$.each(prices,function(price_name,price_data)
			{
				temp=number_format(new_units*price_data['price']*currency_value,2,1);
				if(new_amount==0){
					new_amount=temp;
					
					var new_rate=number_format(price_data['price']*currency_value,rate_decimal,true);
					$('#rate_span').html(new_rate+' '+currency_code+' per units');
				}
				if(new_units<parse_int(price_data['min_units']))return; //break-out
				new_amount=temp;
				
				
				var new_rate=number_format(price_data['price']*currency_value,rate_decimal,true);
				$('#rate_span').html(new_rate+' '+currency_code+' per units');
			});
			
			if(new_amount==0)$('#rate_span').html('');
			else{
				var vat=new_amount*<?php echo $configs['tax_percent']; ?>*0.01;
				new_amount+=vat;
				new_amount=number_format(new_amount,2,false);
			}
		}
		
		$('#amount').val(new_amount);
	}

	
	function amountChanged()
	{
		var new_units=0;
		var new_amount=parse_float($('#amount').val());
		
		if(new_amount>0)
		{
			var tax_amount=(new_amount/105)*5;
			new_amount-=tax_amount;
			
			$.each(reverse_prices,function(price_name,price_data)
			{
				temp=Math.floor(new_amount/(price_data['price']*currency_value));
				if(new_units==0){
					new_units=temp;

					var new_rate=number_format(price_data['price']*currency_value,rate_decimal,true);
					$('#rate_span').html(new_rate+' '+currency_code+' per units');
				}
				if(temp<parse_int(price_data['min_units']))return; //break-out
				new_units=temp;
				
				
				var new_rate=number_format(price_data['price']*currency_value,rate_decimal,true);
				$('#rate_span').html(new_rate+' '+currency_code+' per units');
			});
		}
		
		if(new_units==0)$('#rate_span').html('');
		$('#units').val(new_units);
	}
	
	$(function(){ $('#units').val('50000'); currencyChanged(); });
</script>
<?php } else { ?>
	<div class='col-md-6 col-sm-8 col-sm-offset-2  col-md-offset-3' style='border:1px solid #ccc;padding:25px 5px;border-radius:5px;display:table;'>
		<div class='text-danger'>
			PLEASE DO NOT PAY BELOW THE TOTAL AMOUNT PAYABLE OF <strong><?php  echo $transaction['amount']." ".$transaction['currency_code'];?></strong>
		</div>
		<div class='col-md-12'>
			<ul class='list-group before_submit'>
				<li class='list-group-item'><strong>DETAILS:</strong> <?php echo $transaction['details']; ?></li>
				<li class='list-group-item'><strong>DATE:</strong> <?php echo date('d-m-Y g:i a',$transaction['time']); ?></li>
				<li class='list-group-item'>
					<strong>Amount:</strong> <?php  echo $json_details['original_amount']." ".$transaction['currency_code'];?>
				</li>
				<li class='list-group-item'>
					<strong>TAX/VAT:</strong> <?php  echo $json_details['tax_amount']." ".$transaction['currency_code'];?> <i>(<?php echo $json_details['tax_percent']; ?> %)</i>
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
					<strong>Total Amount Payable:</strong> <span style='font-weight:bold;color:#f00;font-style:italic;font-size:16px;' ><?php  echo $transaction['amount']." ".$transaction['currency_code'];?></span>
				</li>
				<li class='list-group-item'>
					<strong>Payment Method:</strong> <?php echo $configs[$transaction['payment_method']."_label"];?>
				</li>
				<?php if($transaction['payment_method']=='bank_deposit'&&$transaction['amount']>=1000){ ?>
				<li class='list-group-item list-group-item-warning'>
					NOTE: There is a CBN directive of #50 <strong>fixed charges</strong> (stamp duty), levied on all payment that is up to #1000, made into any corporate accounts. 
				</li>
				<?php } ?>
			</ul>
			<?php if($transaction['payment_method']!='bank_deposit'&&$transaction['payment_method']!='pay_on_delivery'&&$transaction['payment_method']!='western_union'&&$transaction['payment_method']!='ussd_code'&&$transaction['currency_code']=='NGN'){ ?>
				<div class='alert alert-warning'>
					<span class='close' data-dismiss='alert'>&times;</span>
					Please Note: If you are making any online payment via <i>interswitch</i>, you must either:<br/>
					Be resistered for for interswitch <abbr title='one time password'>OTP</abbr>
					(<a href="https://connect.interswitchng.com/documentation/safetoken-services/" target='_blank' class='alert-link'>see the simple steps</a>) <br/>
					OR be given the token, for making online transactions from your bank.
				</div>
			<?php } ?>
			<div id='info_holder' class='before_submit' ></div>
			<form method="<?php echo $form_method;?>" id='payment_form' action="<?php echo $action;?>">
				<div style='line-height:2;font-weight:bold;display:none;' class='text-warning after_submit' >
				<?php echo $input_fields; ?>
				</div>
				
				<span class='pull-right'>
					<button class='btn btn-primary btn-sm before_submit' name='continue' value='Pay' type='button' onclick='submitForm();' >
						Proceed
					</button>
					
					<button class='btn btn-primary btn-sm after_submit' type='button' onclick='window.print();' style='display:none;' ><i class='fa fa-print'></i> Print</button>
					<a class='btn btn-primary btn-sm after_submit'  style='display:none;' href='<?php echo $this->general_model->get_url('transaction');?>' >
						View Transaction Log
					</a>
				</span>
			</form>
		</div>
		<div class='clearfix'></div>
		<script type='text/javascript'>
			var payment_method='<?php echo $transaction['payment_method']; ?>';
			var redirecting=false;
			
			function submitForm()
			{
				if(redirecting)return;
				
				$('#info_holder').addClass('text-success').removeClass('text-danger').html('Please wait...');
				
				var url=base_url+'ajax_processor';
				$.post(url,{action:'commit_transaction'},function(response)
					{
						if(response=='success')
						{
							if(payment_method=='bank_deposit')
							{
								$('.after_submit').show();
								$('.before_submit').hide();
							}
							else
							{
								showInfo('Redirecting to payment gateway...');
								$('#payment_form').submit(); 
								redirecting=true;
							}
						}
						else showError(response);
					}).error(function(xhr){
						showError(xhr.statusText);
						window.console&console.log("Ajax Error:"+xhr.statusText+", "+xhr.responseText);
					});
			}
		</script>
	</div>
<?php } ?>

<?php } ?>