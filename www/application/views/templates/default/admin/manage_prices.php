<h3 class='breadcrumb'>
	Manage Prices
</h3>
<hr/>
<form role='form ' method='post'>
<?php
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
	<div class='help-block'>
		<span class='glyphicon glyphicon-info-sign'></span>  To remove any price, just empty the price name and save the form.
	</div>
	<div id='price_divs'></div>
	<div id='actions_div'>
		<input type='hidden' name='num_prices' id='num_prices' />
		<a href='javascript:addNewFields()' class='btn btn-default' title='add new fields'><span class='glyphicon glyphicon-plus'></span></a>
		<button class='btn btn-primary pull-right' value='save' name='save'><span class='glyphicon glyphicon-save'></span> SAVE</button>
	</div>
</form>
<hr/>
<div class='alert alert-success'>
	<i>In case it happens</i> that the pricing/coverage list get changed at <a href='https://cheapglobalsms.com' class='alert-link'>CheapGlobalSMS.com</a>, use this button to update your coverage list<br/>
	Please NOTE: This action will first empty your entire coverage list table, then refill with the updated one.<br/>
	<br/>	
	<form method='post'><button name='update_coverage_list' value='1' class='btn btn-sm btn-default' title='Update Coverage List' ><i class='fa fa-refresh'></i> Update Coverage List</button></form>
</div>
	<div id='price_div_template' style='display:none;'>
		<div class='col-md-3 form-group'>
			<label for='price_'>Price</label>
			<div class='input-group'>
				<input type='number' step='any' name='price_' class='form-control input-sm' placeholder='1.85' >
				<span class='input-group-addon'><?php echo @$configs['currency_code']; ?></span>
			</div>
		</div>
		<div class='col-md-4 form-group'>
			<label for='min_units_'>Min. Units</label>
			<input type='number' name='min_units_' class='form-control input-sm' placeholder='1'>
		</div>
		<div class='col-md-4 form-group'>
			<label for='bonus_units_'>Bonus Units</label>
			<input type='number' name='bonus_units_' class='form-control input-sm' placeholder='0' >
		</div>
		<div class='clearfix'></div>
		<hr/>
	</div>
<script type='text/javascript'>
	var num=0;
	var current_price_id='';
			
	$(function()
		{
			var prices=<?php echo $prices_json; ?>;
			
			$.each(prices,function(price_name,price_data)
			{
				addNewFields();
				//$('[name=price_'+num+']').val(price_name);
				
				$.each(price_data,function(price_i,price_i_val){
						$('[name='+price_i+'_'+num+']').val(price_i_val);
					});
				$('#price_div_'+num+' .icon_addon i').attr('class',$('#price_div_'+num+' .icon_input').val());
			});
		}
	);
	
	function addNewFields()
	{
		num++;
		$('#num_prices').val(num);
	
		str=$('#price_div_template').html();
		str="<div id='price_div_"+num+"' class='price_div col-md-6'>"+str+"</div><div class='clearfix'></div>";
		$('#price_divs').append(str);
		
		$('#price_div_'+num+' [name]').each(function(){
				$(this).attr('name',$(this).attr('name')+num);
			});
			
		$('#price_div_'+num+' label').each(function(){
				$(this).attr('for',$(this).attr('for')+num);
			});	
	}
</script>