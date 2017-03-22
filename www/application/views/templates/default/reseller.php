<h2>Reseller Account</h2>
<hr/>
<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
<div class='row'>
	<?php
		$prices=$this->general_model->get_prices(true);
		$reseller_min_sales=$this->general_model->get_reseller_min_sales();
		$reseller_surety_fee=$this->general_model->get_reseller_surety_fee();
		
		$first=current($prices);
		$min_reseller_price=$first['price'];
		
		$default_pm_options="";
		$currencies=$this->general_model->get_currencies(true);
		$av_currencies=array();
		if(!in_array('USD',$av_currencies))$av_currencies[]='USD';
		
		$prev_units=0;
		$size=count($prices);
	?>
	<div class='alert alert-success'>
			As a reseller on <?php echo $configs['site_name']; ?>, you can start buying SMS credits at equivalent of <?php echo $min_reseller_price*$currencies['USD']['value']; ?> USD per SMS unit from the lowest quantity.<br/>
		</div>
	<div class='col-sm-6 col-sm-offset-3'>
		<div class='table-responsive'>
			<table class='table table-bordered table-striped'>
				<tr>
					<th class='col-xs-1'>S/N</th>
					<th class='col-xs-4'>Units Range</th>
					<?php foreach($av_currencies as $av_currency){?>
						<th class='col-xs-2'>Price (<?php echo $av_currency; ?>)</th>
					<?php } ?>
				</tr>
				<?php for($sn=0;$sn<$size;$sn++){ ?>
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
		<div class='list-group'>
			<div class='list-group-item'>
				<h4 class='list-group-item-heading'>Terms & Condition</h4>
			</div>
			<div class='list-group-item'>
				A reseller account will always retain a reserve/<i><b>surety units</b></i> of <?php echo number_format($reseller_surety_fee); ?> SMS credits. This <i><b>surety units</b></i> can not be spent by the reseller.
			</div>
			<div class='list-group-item'>
				A reseller is expected to always <strong>use</strong> at least, a total of <?php echo number_format($reseller_min_sales); ?> SMS units per month (30 days).
			</div>
			<div class='list-group-item'>
				If a reseller account does not use up to <?php echo number_format($reseller_min_sales); ?> units in a month, the reseller's <i><b>surety units</b></i> will be automatically forfeited/removed (to be refilled back from the reseller's main balance).
			</div>
		</div>
	
	</div>
	<?php if(empty($Success)){ ?>
	<div class='clearfix'></div>
	<div class='col-md-12 text-center'>
		<?php if(empty($my_profile['reseller_account'])){ ?>
			<a href='<?php echo $this->general_model->get_url('reseller/?activate=1'); ?>' class='btn btn-lg btn-success'>
				<i class='fa fa-check'></i> Become A Reseller
			</a>
		<?php } else { ?>
			<a href='<?php echo $this->general_model->get_url('reseller/?activate=-1'); ?>' class='btn btn-xs btn-danger' onclick="return confirm('Do you really want to remove the reseller privilege from your account <?php if(empty($my_profile['owing_surety']))echo '(NOTE: Your surety units will be forfeited)'; ?> ?');" >
				<i class='fa fa-lock'></i> Downgrade to Normal Account
			</a>
		<?php } ?>
	</div>
	<?php } ?>
</div>