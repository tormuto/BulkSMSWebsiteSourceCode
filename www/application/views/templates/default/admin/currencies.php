	<h3><i class='fa fa-list-ul'></i> Currencies</h3>
	<hr/>
	
	<form role='form ' method='post'>
	<?php if(!empty($Error)){ ?>
		<div class='alert alert-warning'>
			<button class='close' data-dismiss='alert'>&times;</button>
			<?php echo $Error;?>
		</div>
	<?php } if(!empty($Success)){ ?>
		<div class='alert alert-success'>
			<button class='close' data-dismiss='alert'>&times;</button>
			<?php echo $Success;?>
		</div>
	<?php
		}
	?>
		<div class='help-block'>
			<span class='glyphicon glyphicon-info-sign'></span> To delete any currency, just empty the currency name and save the form. <strong>Uncheck the checkbox to disable a currency.</strong>
		</div>
		<div id='currency_divs'></div>
		<div id='actions_div'>
			<input type='hidden' name='num_currencies' id='num_currencies' />
			<a href='javascript:addNewFields()' class='btn btn-default' title='add new fields'><span class='glyphicon glyphicon-plus'></span></a>
			<button class='btn btn-primary pull-right' value='save' name='save'><i class='fa fa-save'></i> SAVE</button>
		</div>
	</form>
		<div id='currency_div_template' style='display:none;'>
			<div class='form-group col-md-2 col-sm-2 col-sm-2'>
				<label for='currency_'>Currency</label>
				<div class='input-group'>
					<span class='input-group-addon'>
						<input type='checkbox' name='enabled_' checked value='1' />
					</span>
					<input type='text' name='currency_' placeholder='NGN' pattern='^[a-zA-Z]{3}$' class='form-control input-sm' >
				</div>
			</div>
			<div class='col-md-2 col-sm-2 form-group'>
				<label for='currency_title_'>Currency Title</label>
				<input type='text' name='currency_title_' class='form-control input-sm' placeholder='Nigerian Naira'>
			</div>
			<div class='col-md-2 col-sm-2 form-group col-xs-6'>
				<label for='iso_code_'>ISO Code</label>
				<input type='number' name='iso_code_' class='form-control input-sm' placeholder='566' min='1' max='999' >
			</div>
			<div class='col-md-2 col-sm-2 form-group col-xs-6'>
				<label for='decimal_places_'>Decimal</label>
				<input type='number' name='decimal_places_' class='form-control input-sm' placeholder='2' min='0' max='8' >
			</div>		
			<div class='col-md-2 col-sm-2 form-group col-xs-6'>
				<label for='symbol_'>Symbol</label>
				<input type='text' name='symbol_' class='form-control input-sm' placeholder='#' maxlength='8' >
			</div>		
			<div class='col-md-2 col-sm-2 col-xs-6' title="If this is the default currency, set the value as 1." >
				<label for='value_'>Value</label>
				<div class='input-group'>
					<input type='number' name='value_' class='form-control input-sm currency_price' step='any' placeholder='1' >
					<span class='input-group-addon'><?php echo $configs['currency_code'];?></span>
				</div>
			</div>
			<div class='clearfix'></div>
		</div>
	<script type='text/javascript'>
		var num=0;
		var current_currency_id='';
				
		$(function()
			{
				var currencies=<?php echo json_encode($currencies); ?>;
				
				$.each(currencies,function(currency_name,currency_data)
				{
					addNewFields();
					
					$.each(currency_data,function(currency_i,currency_i_val){
							var tempp=$('[name='+currency_i+'_'+num+']');
							if(tempp.length){
								if(tempp.is('[type=checkbox]'))tempp.prop('checked',currency_i_val==tempp.val());
								else tempp.val(currency_i_val); 
							}
						});
					$('#currency_div_'+num+' .icon_addon i').attr('class',$('#currency_div_'+num+' .icon_input').val());
				});
			}
		);
		
		function addNewFields()
		{
			num++;
			$('#num_currencies').val(num);
		
			str=$('#currency_div_template').html();
			str="<div id='currency_div_"+num+"' class='currency_div row'>"+str+"</div><div class='clearfix'></div>";
			$('#currency_divs').append(str);
			
			$('#currency_div_'+num+' [name]').each(function(){
					$(this).attr('name',$(this).attr('name')+num);
				});
				
			$('#currency_div_'+num+' label').each(function(){
					$(this).attr('for',$(this).attr('for')+num);
				});	
		}
	</script>
