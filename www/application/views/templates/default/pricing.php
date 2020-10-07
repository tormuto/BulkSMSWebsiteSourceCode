<div class='default_breadcrumb'><h2><?php echo $section_title; ?></h2><hr/></div>
<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
<?php if(!isset($configs['tax_percent'])){ ?>
<div class='alert alert-danger'>
	Payment gateway is not yet configured.
</div>
<?php } else {  ?>
<div class='help-block'><strong class='text-warning'><i class='fa fa-info-circle'></i> You can purchase a minimum of <?php echo $configs['minimum_units']; ?> units at a time.</strong></div>

<form method='post' role='form'>
		<?php $currencies=$this->general_model->get_currencies(true); ?>
		<div class='form-group col-sm-4'>
			<label>Amount <small>(+ <?php echo $configs['tax_percent']; ?>% VAT)</small> <span id='rate_span'></span></label>
			<div class='input-group'>
				<input type='number' name='amount'  id='amount' step='0.01' required onkeyup='amountChanged();' class='form-control input-sm'>
				<span class='input-group-addon' id='curr_code_span'></span>
			</div>
		</div>
	
		<div class='form-group col-sm-4'>
			<label>Select Currency</label>
			<select name='payment_currency' id='payment_currency' class='form-control input-sm' onchange="currencyChanged()"  >
				<?php
					$default_pm_options="";
					$av_currencies=array();
					
					foreach($currencies as $currency_code=>$currency_data){
						if(empty($currency_data['enabled']))continue;
						$this_pms="";
						$av_currencies[]=$currency_code;
					?>
					<option value='<?php echo $currency_code; ?>'  conv_value='<?php echo $currency_data['value']; ?>'  ><?php echo "{$currency_data['currency_title']} ($currency_code) "; ?> </option>
				<?php } ?>						
			</select>
		</div>
		
		<div class='form-group col-sm-4'>
			<label>SMS Credits</label>
			<div class='input-group'>
				<span class='input-group-addon'> = </span>
				<input type='number' name='units' id='units' min='<?php echo $configs['minimum_units']; ?>' onkeyup='unitsChanged();' class='form-control input-sm' value='50000' >	
				<span class='input-group-addon'>units</span>
			</div>
		</div>
		
		<input type='hidden' name='payment_method' value='unifiedpurse' />
		
		<div class='clearfix'></div>
		<div style='font-weight:bold;text-align:center;'>
			<?php echo (strtolower($configs["unifiedpurse_label"])=='unifiedpurse')?'':$configs["unifiedpurse_label"]; ?>
		</div>
		<div class='clearfix'></div>
		<div class='text-center'>
			<?php if(empty($my_profile)){ ?>
			<a class='btn btn-primary btn-lg alone' href='<?php echo $this->general_model->get_url('login?dest=pricing'); ?>'>
				Login & Pay Via UnifiedPurse
			</a>
			<?php } else { ?>
			<button class='btn btn-primary btn-lg alone before_submit' name='continue' value='Pay' id='normal_final_checkout_btn' type='button' onclick='submitForm();'>
				Pay Via UnifiedPurse
			</button>
			<?php } ?>
		</div>
</form>
<hr/>
<h3 style='color:#777;font-weight:bold;'>
    <i class='fa fa-calculator'></i> SMS Credits Calculator
</h3>
<form action='<?php echo $this->general_model->get_url('coverage_list'); ?>' method='get' class='form-inline'>
	<strong style='color:#777;font-size:20px;'>
		<i class='fa fa-question-circle'></i>  I want to send
	</strong>
	<div class='form-group'>
        <div class='input-group'>
            <input type='number' min='1' class='form-control input-lg' required placeholder='Number of messages' name='traffic_volume' value='50000' />
            <span class='input-group-addon'>SMS</span>
        </div>
    </div>
    <div class='form-group form-group-lg'>
        <div class='input-group'>
            <span class='input-group-addon'>TO</span>
            <select class='form-control input-lg basic_select2' required  name='country' >
                <option value=''>Country</option>
                <?php 
                $countries=$this->general_model->get_coverage_countries();
                foreach($countries as $country_code=>$country){ 
				?><option value='<?php echo $country_code;?>' ><?php echo $country;?></option><?php } ?>
            </select>
        </div>
    </div>
    <button class='btn btn-info btn-lg'><i class='fa fa-calculator'></i> How Much Will It Cost?</button>
</form>
<div class='clearfix'></div><hr/>

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
		
		for($sn=0;$sn<$size;$sn++){
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
	<?php } ?>
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
	
	
	function unitsChanged(){
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

	
	function amountChanged(){
		var new_units=0;
		var new_amount=parse_float($('#amount').val());
		
		if(new_amount>0)
		{
			var tax_amount=(new_amount/105)*5;
			new_amount-=tax_amount;
			
			$.each(reverse_prices,function(price_name,price_data){
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
	
	$(function(){
		var temp=window.location.hash;
        if(temp.substring(0,11)=='#pre_units='){
            temp=temp.substring(11);
        } else temp=50000;
		
		$('#units').val(temp).trigger('focus');
		currencyChanged();
	});
	
	
	var commiting_transaction=false;
	function submitForm(){
		if(commiting_transaction)return;
		commiting_transaction=true;
		$('#normal_final_checkout_btn').prop('disabled',true);
		
		$('#info_holder').addClass('text-success').removeClass('text-danger').html("<strong style='font-size:16px;'>Initiating... Please wait.</strong>");
		var url=base_url+'ajax_processor';
		var sms_units=$('#units').val();
		var amount=$('#amount').val();
		var payment_currency=$('#payment_currency').val();
		
		$.post(url,{action:'commit_transaction','units':sms_units,'payment_currency':payment_currency},function(response){
			if(response.indexOf('success:')!=-1){
				var temp=response.split(':');
				var transaction_reference=temp[1];
				var temp_amount=temp[2];
				var temp_currency=temp[3];
				var payment_memo=sms_units+" SMS Credits";
				payWithUnifiedpurse(temp_currency,temp_amount,transaction_reference,payment_memo);
				showInfo('Redirecting to payment gateway...');
			}
			else showError(response);
		}).error(function(xhr){
			commiting_transaction=false;
			$('#normal_final_checkout_btn').prop('disabled',false);
			showError(xhr.statusText);
			window.console&console.log("Ajax Error:"+xhr.statusText+", "+xhr.responseText);
		});
	}
	
</script>
<?php
	if(!empty($my_profile)){
?>
	<script src='//unifiedpurse.com/assets/js/payment.js' async defer></script>
	<script>
		function payWithUnifiedpurse(currency,amount,transaction_reference,payment_memo){
			var notify_url=base_url+'transaction?confirm_trans='+transaction_reference;
			
			var paymentSettings={
				receiver: '<?php echo $configs["unifiedpurse_username"]; ?>',
				currency: currency,
				amount: amount,
				ref:transaction_reference,
				email:'<?php echo $my_profile['email']; ?>',
				memo:payment_memo,
				notification_url:notify_url,
				onDone: function(response){
					var str="<h4>Thank you very much</h4><p>Please while we confirm your payment...</p>";
					showInfo(str);
					window.location.href=base_url+'transaction?confirm_trans='+transaction_reference; //response.ref
				},
				onClose: function(msg){
					window.console&&console.log('Payment widget closed. ',msg);
					window.location.href=base_url+'pricing';
				}
			}

			var unifiedpurseHandler = Unifiedpurse.pay(paymentSettings);
		}
	</script>
<?php }
	} ?>