<h3 class='breadcrumb'>Manage Site Pages</h3>
<form role='form' method='post'>
<?php
	if(empty($Error))$Error=validation_errors();
	
	if(!empty($Error))
	{
?>
	<div class='alert alert-warning'>
		<button class='close' data-dismiss='alert'>&times;</button>
		<?php echo $Error;?>
	</div>
<?php
	}

	if(!empty($Success))
	{
?>
	<div class='alert alert-success'>
		<button class='close' data-dismiss='alert'>&times;</button>
		<?php echo $Success;?>
	</div>
<?php
	}
?>
	<div class='clearfix'></div>
	<h3>PAYMENT CONFIGURATION</h3>
	<hr/>
	
	
	<div class='clearfix'></div>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='free_sms'>Free SMS</label>
		<input type='number' name='free_sms' class='form-control' value="<?php echo $presetData['free_sms'];?>" reqired placeholder='6' />
	</div>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='free_sms'>Tax</label>
		<div class='input-group'>
			<input type='number' name='tax_percent' step='any' class='form-control' value="<?php echo $presetData['tax_percent'];?>" reqired placeholder='5' />
			<span class='input-group-addon'>%</span>
		</div>
	</div>
	
	<div class='clearfix'></div>
	<h3>GATEWAY SETTINGS</h3>
	<hr/>
	
	<div class='help-block'>
		All the gateways' callback urls should be pointed to <code><?php echo $this->general_model->get_url('transaction');?></code>
	</div>
	
	<?php
		foreach($this->general_model->payment_gateway_params as $pgpname => $pgp)
		{
			$pgmid=$pgp['merchant_id'];
			$pgmide=$this->general_model->split_format($pgmid);
			
			$pgmide2=$this->general_model->split_format($pgpname);
			
			$pgk=$pgp['key'];
			$pgke=$this->general_model->split_format($pgk);
			//$pgke=str_ireplace("$pgpname ",'',$pgke);
			
			$pgu=@$pgp['uid'];
			$pgue=$this->general_model->split_format($pgu);
			
			$pgcurr="{$pgpname}_currencies";
			$pgen="{$pgpname}_enabled";
			$pgdemo="{$pgpname}_demo";
			$pgclass="p_{$pgpname}";
			$pglabel="{$pgpname}_label";
			$pgcharge="{$pgpname}_charge";
			$pgcharge_fixed="{$pgpname}_charge_fixed";
			$pgcharge_cap="{$pgpname}_charge_cap";
			$mid_size=4;
	?>	
	<div class='clearfix'></div>
	<div class='well well-sm'>
		<?php if(empty($pgp['url'])){ ?>
			<h4>Accept Payments With <?php echo $pgmide2; ?></h4>
		<?php } else { ?>
		<a href='<?php echo $pgp['url']; ?>' target='_blank'>
			<h4>Accept Payments With <?php echo $pgmide2; ?></h4>
		</a>
		<?php } ?>
		<p><?php echo $pgp['descr']; ?></p>
	</div>
	<div class='clearfix'></div>
	
	<div class='form-group col-md-6 col-sm-6 col-xs-12'>
		<label for='<?php echo $pglabel; ?>'><?php echo $this->general_model->split_format($pgpname); ?> Label</label>
		<input type='text' name='<?php echo $pglabel; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pglabel];?>" />
	</div>
	
	<div class='form-group col-md-2 col-sm-2 col-xs-6'>
		<label for='<?php echo $pgcharge_fixed; ?>'><?php echo $this->general_model->split_format($pgpname); ?> Fixed Charge </label>
		<div class='input-group'>
			<input type='number' step='any' name='<?php echo $pgcharge_fixed; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pgcharge_fixed];?>" />
			<span class='input-group-addon'><?php echo $configs['currency_code']; ?></span>
		</div>
	</div>
	
	<div class='form-group col-md-2 col-sm-2 col-xs-6'>
		<label for='<?php echo $pgcharge; ?>'><?php echo $this->general_model->split_format($pgpname); ?> Charge </label>
		<div class='input-group'>
			<input type='number' step='any' name='<?php echo $pgcharge; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pgcharge];?>" />
			<span class='input-group-addon'>%</span>
		</div>
	</div>
	
	<div class='form-group col-md-2 col-sm-2 col-xs-6'>
		<label for='<?php echo $pgcharge_cap; ?>'><?php echo $this->general_model->split_format($pgpname); ?> Max. Charge </label>
		<div class='input-group'>
			<input type='number' step='any' placeholder='300'		name='<?php echo $pgcharge_cap; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pgcharge_cap];?>" />
			<span class='input-group-addon'><?php echo $configs['currency_code']; ?></span>
		</div>	
	</div>
	
	<div class='clearfix'></div>
	
	<div class='form-group col-md-3 col-sm-3 col-sm-3'>
		<label for='<?php echo $pgmid; ?>'><?php echo $pgmide; ?></label>
		<?php  if(empty($pgp['textarea'])){ ?>
		<input type='text' name='<?php echo $pgmid; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pgmid];?>" />
		<?php } else { ?>
		<textarea name='<?php echo $pgmid; ?>' class='form-control <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> 
		placeholder='<?php if(strstr($pgpname,'bank')!==false)echo 'Account Name: oneaccountname  Account Number:939393933939 Account Name: oneotheraccount';?>'
		><?php echo $presetData[$pgmid];?></textarea>
		<?php } ?>
	</div>
	
	<?php if($pgpname=='gtpay'){ $mid_size=2; ?>
	<div class='form-group col-md-2 col-sm-2 col-sm-2'>
		<label for='gtpay_direct_webpay'>Gateway</label>
		<select name='gtpay_direct_webpay' class='form-control input-sm'>
			<option value='1' <?php if($presetData['gtpay_direct_webpay']==1)echo 'selected';?>>
				Direct (webpay)
			</option>
			<option value='0' <?php if(empty($presetData['gtpay_direct_webpay']))echo 'selected';?>>
				User Select
			</option>
		</select>
	</div>
	<?php } ?>
	
	<?php if(!empty($pgue)){ $mid_size=2; ?>
	<div class='form-group col-md-3 col-sm-3 col-sm-3'>
		<label for='<?php echo $pgu; ?>'><?php echo $pgue; ?></label>
		<input type='text' name='<?php echo $pgu; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pgu];?>" />
	</div>
	<?php } ?>
	<?php if(!empty($pgk)){ ?>
	<div class='form-group <?php echo "col-md-$mid_size col-sm-$mid_size"; ?>'>
		<label for='<?php echo $pgk; ?>'><?php echo $pgke; ?></label>
		<input type='text' name='<?php echo $pgk; ?>' class='form-control input-sm <?php echo $pgclass;?>' <?php if(!empty($presetData[$pgen]))echo 'required'; ?> value="<?php echo $presetData[$pgk];?>" />
	</div>
	<?php } ?>
	<div class='form-group col-md-2 col-sm-2 col-sm-2'>
		<label for='<?php echo $pgdemo; ?>'>Mode</label>
		<select name='<?php echo $pgdemo; ?>' class='form-control input-sm'>
			<option value='1' <?php if($presetData[$pgdemo]==1)echo 'selected';?>>
				Demo
			</option>
			<option value='0' <?php if(empty($presetData[$pgdemo]))echo 'selected';?>>
				Live
			</option>
		</select>
	</div>
	<div class='form-group col-md-2 col-sm-2 col-sm-2'>
		<label for='<?php echo $pgen; ?>'>Status</label>
		<select name='<?php echo $pgen; ?>' class='form-control input-sm' onchange="$('.<?php echo $pgclass; ?>').prop('required',($(this).val()==1)?true:false);">
			<option value='1' <?php if($presetData[$pgen]==1)echo 'selected';?>>
				Enabled
			</option>
			<option value='0' <?php if(empty($presetData[$pgen]))echo 'selected';?>>
				Disabled
			</option>
		</select>
	</div>
	<div class='form-group col-md-12 col-sm-12 col-xs-12'>
		<label><?php echo $this->general_model->split_format($pgpname); ;?> Currencies</label>
		<div>
			<?php foreach($currencies as $currency_code=>$currency_data){ ?>
				<label class='checkbox-inline' >
					<input type='checkbox' value='<?php echo $currency_code; ?>' name='<?php echo $pgcurr; ?>[]' 
						<?php 
							$expcurr=explode(',',$presetData[$pgcurr]);
							if(in_array($currency_code,$expcurr))echo 'checked';
						?> 
						>
					<?php echo $currency_code; ?>
				</label>
			<?php } ?>		
		</div>
	</div>
	<div class='clearfix'></div>
	<hr/>
	<?php } ?>
	<div class='clearfix'></div>
	<div class='text-right'>
		<button class='btn btn-primary' value='save' name='save_configs'><i class='fa fa-floppy-o'></i> SAVE</button>
	</div>
</form>