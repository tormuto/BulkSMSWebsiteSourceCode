<div class='default_breadcrumb'><h2>Coverage List</h2></div>
<div class='help-block'><strong class='text-warning'><?php echo $configs['site_name']; ?> allows you to Send Bulk SMS messages to over 1,400 networks across the globe!</strong></div>
<hr/>
<div >
	<form  method='get' class='form-inline' role='form' onsubmit='return searchFormSubmitted()' id='search_form' >
		<strong style='font-size:18px;'>I want to send</strong>
		<div class='form-group' data-toggle='tooltip' title="Take advantage of our volume-discount by buying high volume credits at a time. The higher the quantity, the lower the rates." >
			<div class='input-group' >
				<input type='number' pattern='[0-9]*'  name='traffic_volume' placeholder='Traffic Purchase Volume' value='<?php echo empty($filter['traffic_volume'])?50000:$filter['traffic_volume']; ?>' class='form-control input-sm' required />
				<span class='input-group-addon' >sms</span>
			</div>
		</div>
		<div class='form-group'>
			<div class='input-group'>
				<span class='input-group-addon'>To</span>
				<div class='form-group'>
					<select name='country' class='form-control input-sm'>
						<option value=''>All Countries</option>
						<?php 
							$countries=$this->general_model->get_coverage_countries();
							foreach($countries as $country_code=>$country){ ?>
							<option value='<?php echo $country_code;?>'  <?php if($country_code==$filter['country_code'])echo 'selected'; ?>>
								<?php echo $country;?>
							</option>
							<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class='form-group more_filters_div' style='display:none;'>
			<select name='continent' class='form-control input-sm'>
				<option value=''>All Continents</option>
				<?php 
					$continents=array('AF'=>'Africa','AS'=>'Asia','AU'=>'Australia','CA'=>'Central America','E'=>'Europe','NA'=>'North America','SA'=>'South America');
					foreach($continents as $continent=>$continent_label){ ?>
					<option value='<?php echo $continent;?>'  <?php if($continent==$filter['continent'])echo 'selected'; ?>>
						<?php echo $continent_label;?>
					</option>
					<?php } ?>
			</select>
		</div>
		
		<div class='form-group more_filters_div' style='display:none;'>
			<div class='input-group'>
				<span class='input-group-addon'>Prefix: +</span>
				<input type='number' name='prefix' placeholder='e.g <?php echo $configs['default_dial_code']; ?>' value="<?php echo $filter['prefix']; ?>" class='form-control input-sm'>
			</div>
		</div>
		<div class='form-group' >
			<div class='input-group more_filters_div' style='display:none;' >
				<input type='number'  name='units' placeholder='all units' value='<?php echo $filter['units']; ?>' class='form-control input-sm' />
				<span class='input-group-addon' >units</span>
			</div>
		</div>
		
		<button class='btn btn-primary btn-sm' name='action' value='List' >
			<i class='fa fa-search'></i> Show Pricing & Coverage
		</button>
		<button class='btn btn-primary btn-sm ' name='action' value='export' >
			<i class='fa fa-list'></i> Export EXCEL
		</button>
		<span onclick="$('.more_filters_div').slideToggle()" class='btn-link' style='cursor:pointer;' ><i class='fa fa-filter'></i> More Filters</span>
	</form>

</div>
<br/>

<?php if(empty($coverage_list)){ ?>
<div class='help-block'><i class='fa fa-info'></i> Please use the filter form to fetch the coverage list.</div>
<?php } else { ?>
<div class='help-block'><i class='fa fa-info'></i> The units here depicts the number of your sms credit that will be charged per page (160 characters) of SMS sent to a recipient on the corresponding network.</div>
<div class='table-responsive'>
<table class='table table-bordered table-striped responsive'>
	<tr>
		<th class='col-xs-1'>ID</th>
		<th class='col-xs-2'>Country</th>
		<th class='col-xs-3'>Network</th>
		<th class='col-xs-1'>Prefix</th>
		<th class='col-xs-1' title='units per sms page'>Credits per SMS</th>
		<?php if(!empty($filter['traffic_volume'])){ ?>		
		<th class='col-xs-2'>Explanation</th>	
		<?php } ?>
		<th class='col-xs-1'>Continent</th>	
		<th class='col-xs-1'>Country Code</th>	
	</tr>
<?php
	$sn=0;
	$pricing_url=$this->general_model->get_url('pricing');
	$cur_code=_CURRENCY_CODE_;
	$tvol=floatval($filter['traffic_volume']);
	$tvolf=number_format($tvol);
	
	foreach($coverage_list as $route)
	{
	?>
	<tr>
		<td><?php echo ++$sn; ?>.</td>
		<td><?php echo $route['country']; ?></td>
		<td><?php echo $route['network']; ?></td>
		<td>+<?php echo $route['dial_code']; ?></td>
		<td><?php echo $route['units']; ?></td>
		<?php if(!empty($filter['traffic_volume'])){ ?>
		<td><?php			
			if(!empty($tvol)){
				$tunits=$tvol*$route['units'];
				$tunitsf=number_format($tunits);
				
				$ppv=$this->general_model->sms_units_to_price($tunits);
				$ppm=$ppv/$tvol;
				$ppm=ceil($ppm*100000)/100000;
				$ppv=ceil($ppv*100000)/100000;
				$ppv=number_format($ppv);
				
				echo "<small class='text-warning'>
						$tvolf SMS needs $tunitsf credits = $ppv $cur_code (Meaning: $ppm $cur_code per SMS).
						<a href='$pricing_url#pre_units=$tunits' class='btn btn-xs btn-primary btn-alone'>Buy Credits</a>
					</small>";
			}
		?></td>
		<?php } ?>
		<td><?php echo $route['continent']; ?></td>
		<td><?php echo $route['country_code']; ?></td>
	</tr>	
	<?php
	}
?>
</table>
</div>
<?php } ?>