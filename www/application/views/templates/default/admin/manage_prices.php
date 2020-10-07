<h3 class=''>
	<i class='fa fa-list'></i> Manage Prices
</h3>
<hr/>
<?php if(!empty($Error)){ ?>
	<div class='alert alert-warning'>
		<button class='close' data-dismiss='alert'>&times;</button>
		<?php echo $Error;?>
	</div>
<?php } ?>
<?php if(!empty($Success)){ ?>
	<div class='alert alert-success'>
		<button class='close' data-dismiss='alert'>&times;</button>
		<?php echo $Success;?>
	</div>
<?php } ?>
    <div class='row'>
        <div class='col-sm-4'>
			<div class='well well-sm'>At what price, and conversion rate are you buying credits from cheapglobalsms.com?</div>
			<form role='form' method='post'>
				<div class='form-group'>
					<label>CheapGlobalSMS Price Per SMS Credit</label>
					<div class='input-group'>
						<input step='any' min='0.1' type='number' placeholder='1.75' required class='form-control input-sm' name='cheapglobalsms_price_per_unit' id='cheapglobalsms_price_per_unit' value='<?php echo empty($configs['cheapglobalsms_price_per_unit'])?1.75:$configs['cheapglobalsms_price_per_unit']; ?>'>
						<span class='input-group-addon'>NGN/Unit</span>
					</div>
				</div>
				<div class='form-group'>
					<label>Conversion Rate At CheapGlobalSMS</label>
					<div class='input-group'>
						<span class='input-group-addon'>1 <?php echo _CURRENCY_CODE_; ?>=</span>
						<input step='any' min='0.00000001' type='number' placeholder='1' required class='form-control input-sm' name='default_currency_to_ngn' id='default_currency_to_ngn' value='<?php echo empty($configs['default_currency_to_ngn'])?1:$configs['default_currency_to_ngn']; ?>'>
						<span class='input-group-addon'>NGN</span>
					</div>
				</div>
				<div class='text-center'>
					<button class='btn btn-sm btn-default'><i class='fa fa-save'></i> SAVE</button>
				</div>
			</form>
			<hr/>
            <div class='well well-sm'>
                <i class='fa fa-lightbulb-o'></i>
                Pricing are normally set such that, the higher the volume, the lower the rates.<br/>
                You can fetch a suggested pricing settings from <a href='https://cheapglobalsms.com/pricing' target='_blank'>cheapglobalsms.com/pricing</a> by supplying your preferred profit-rate <i>(to be automatically applied to the original pricing fetched from cheapglobalsms.com)</i> in the form below.
            </div>
            <form action='#' class='form-inline'  id='pricing_suggester' name='pricing_suggester'>
                <div class='form-group'>
                    <div class='input-group'>
                        <input class='form-control input-sm' type='number' step='any' min='0' name='profit_percent' id='profit_percent' required placeholder='20' value='20'  style='min-width:70px;'>
                        <span class='input-group-addon'>% Profit</span>
                    </div>
                </div>
                <div class='form-group'>
                    <button class='btn btn-sm btn-primary'>
                        Fetch
                    </button>
                </div>
            </form>
            <div id='pricing_suggestions'></div>
        </div>
        <div class='col-sm-8'>			
            <div class='help-block'>
                <i class='fa fa-info'></i> To remove any price, just empty the price name and save the form.
            </div>            
            <form role='form' method='post'>
                <div id='price_divs'></div>
                
                <div id='actions_div' style='margin-top:20px;'>
                    <input type='hidden' name='num_prices' id='num_prices' />
                    <a href='javascript:addNewFields()' class='btn btn-default' title='add new fields'>
                        <i class='fa fa-plus'></i> Add Pricing
                    </a>
                    <button class='btn btn-primary pull-right' value='save' name='save'>
                        <i class='fa fa-save'></i> SAVE
                    </button>
                </div>
            </form>
        </div>
    </div>
<hr/>
<div class='alert alert-success'>
	<i>In case it happens</i> that the pricing/coverage list get changed at <a href='https://cheapglobalsms.com' class='alert-link'>CheapGlobalSMS.com</a>, use this button to update your coverage list<br/>
	Please NOTE: This action will first empty your entire coverage list table, then refill with the updated one.<br/>
	<br/>	
	<form method='post'><button name='update_coverage_list' value='1' class='btn btn-sm btn-default' title='Update Coverage List' ><i class='fa fa-refresh'></i> Update Coverage List</button></form>
</div>
	<div id='price_div_template' style='display:none;'>
		<div class='col-sm-3 form-group'>
			<label for='price_'>Selling Price-per-unit</label>
			<div class='input-group'>
				<input type='number' step='any' name='price_' class='form-control input-sm' placeholder='1.85' style='min-width:30px;'>
				<span class='input-group-addon'><?php echo _CURRENCY_CODE_; ?></span>
			</div>
		</div>
		<div class='col-sm-4 form-group'>
			<label for='min_units_'>Min. Credit Units</label>
			<input type='number' name='min_units_' class='form-control input-sm' placeholder='1'>
		</div>
		<div class='col-sm-4 form-group'>
			<label for='bonus_units_'>Bonus Units</label>
			<input type='number' name='bonus_units_' class='form-control input-sm' placeholder='0' >
		</div>
		<div class='clearfix'></div>
		<hr style='margin:0px;' />
	</div>
<script type='text/javascript'>
	var num=0;
	var current_price_id='';
	var DEFAULT_CURRENCY_TO_NGN=1;

	$(function(){
		$('#default_currency_to_ngn').on('change',function(){
			var val=$(this).val();
			if(val<=0)val=1;
			DEFAULT_CURRENCY_TO_NGN=val;
			$('#pricing_suggestions').html('');
		});
		
		$('#default_currency_to_ngn').trigger('change');
		
        var prices=<?php echo $prices_json; ?>;
        
        $.each(prices,function(price_name,price_data){
            addNewFields();
            //$('[name=price_'+num+']').val(price_name);
            
            $.each(price_data,function(price_i,price_i_val){
                    $('[name='+price_i+'_'+num+']').val(price_i_val);
                });
            $('#price_div_'+num+' .icon_addon i').attr('class',$('#price_div_'+num+' .icon_input').val());
        });
        
        $('#pricing_suggester').on('submit',function(evt){
            evt.preventDefault();
            var url='https://cheapglobalsms.com/ajax_processor?action=fetch_pricing';
            $('#pricing_suggestions').html("Loading, please wait..");
            
            $.get(url,function(response){
                var error='';                
                if(!response||response=='')error='Empty response from server';
                else {                    
                    var json;
                    try {
                        if(typeof response === 'string')json = jQuery.parseJSON(response);
                        else json=response;
                        
                        if(!json.pricing)error="Unexpected response format from cheapglobalsms server.";
                        else {
                            var str="";
                            var profit_percent=$('#profit_percent').val();
                            
                            for(var k in json.pricing){
                                var row=json.pricing[k];
                                var temp_price=row['price_ngn']+(profit_percent*row['price_ngn']*0.01);
                                temp_price=Math.ceil(temp_price*100)/100;
								var price_equiv=temp_price*DEFAULT_CURRENCY_TO_NGN;
								price_equiv=Math.ceil(price_equiv*100)/100;
								
                                str+="<tr><td>"+temp_price+"</td><td>"+price_equiv+"</td><td>"+row['min_units']+"</td><td>"+row['max_units']+"</td></tr>";
                            }
                            str="<h4>Suggested SMS Pricing Per Credit Range</h4>"+
								"<table class='table table-striped table-bordered table-condensed'>"+
                                "<tr><td>Price NGN</td><td>Price (<?php echo _CURRENCY_CODE_; ?>)</td><td>Min</td><td>Max</td></tr>"+str+"</table>";
                            $('#pricing_suggestions').html(str);
                        }
                    } 
                    catch (e){
                        error="Error: "+e
                        console.log(response)
                    }
                }
                
                if(error!='')$('#pricing_suggestions').html("<div class='alert alert-danger'>"+error+"</div>");
            }).error(function(xhr){
                console.log('Connection Error: '+xhr.statusText,url,xhr.responseText);
                $('#pricing_suggestions').html("Error fetching pricing suggestion: <div class='well'>"+xhr.responseText+"</div>");
            });            
        });
	});
	
	function addNewFields(){
		num++;
		$('#num_prices').val(num);
	
		str=$('#price_div_template').html();
		str="<div id='price_div_"+num+"' class='price_div'>"+str+"</div><div class='clearfix'></div>";
		$('#price_divs').append(str);
		
		$('#price_div_'+num+' [name]').each(function(){
				$(this).attr('name',$(this).attr('name')+num);
			});
			
		$('#price_div_'+num+' label').each(function(){
				$(this).attr('for',$(this).attr('for')+num);
			});	
	}
</script>