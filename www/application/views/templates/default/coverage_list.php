<h2>Coverage List</h2>
<div class='help-block'><strong class='text-warning'><?php echo $configs['site_name']; ?> allows you to Send Bulk SMS messages to over 1,400 networks across the globe!</strong></div>
<hr/>
<div >
	<form  method='get' class='form-inline' role='form' onsubmit='return searchFormSubmitted()' id='search_form' >
		<div class='form-group'>
			<select name='continent' class='form-control input-sm'>
				<option value=''>All Continents</option>
				<?php 
					$continents=array('AF - Africa','AS - Asia','AU - Australia','CA - Central America','E - Europe','NA - North America','SA - South America');
					foreach($continents as $continent){ ?>
					<option value='<?php echo $continent;?>'  <?php if($continent==$filter['continent'])echo 'selected'; ?>>
						<?php echo $continent;?>
					</option>
					<?php } ?>
			</select>
		</div>
		
		<div class='form-group'>
			<div class='input-group'>
				<span class='input-group-addon'>Prefix: +</span>
				<input type='number' name='prefix' placeholder='e.g 234' value="<?php echo $filter['prefix']; ?>" class='form-control input-sm'>
			</div>
		</div>
		<div class='form-group' id='perpage_div' >
			<div class='input-group' >
				<input type='number'  name='units' placeholder='all units' value='<?php echo $filter['units']; ?>' class='form-control input-sm' />
				<span class='input-group-addon' >units</span>
			</div>
		</div>
		<div class='form-group'>
			<div class='input-group'>
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
				<span class='input-group-btn'>
					<button class='btn btn-default btn-sm' name='action' value='List' >
						<i class='fa fa-search'></i> Fetch List
					</button>
				</span>
			</div>
		</div>
		
		<button class='btn btn-default btn-sm ' name='action' value='export' >
			<i class='fa fa-list'></i> Export EXCEL
		</button>
	</form>

</div>
<br/>

<?php if(empty($coverage_list)){ ?>
<div class='help-block'><i class='fa fa-info'></i> Please use the filter form to fetch the coverage list.</div>
<?php } else { ?>
<div class='help-block'><i class='fa fa-info'></i> The units here depicts the number of your sms credit that will be charged per page (160 characters) of SMS sent to a recipient on the corresponding network.</div>
<div class='table-responsive'>
<table class='table table-bordered table-striped'>
	<tr>
		<th class='col-xs-1'>ID</th>
		<th class='col-xs-2'>Country</th>
		<th class='col-xs-3'>Network</th>
		<th class='col-xs-1'>Prefix</th>
		<th class='col-xs-1' title='units per sms page'>Unit per SMS</th>	
		<th class='col-xs-1'>Continent</th>	
		<th class='col-xs-1'>Country Code</th>	
	</tr>
<?php
	$sn=0;
	
	foreach($coverage_list as $route)
	{
	?>
	<tr>
		<td><?php echo ++$sn; ?>.</td>
		<td><?php echo $route['country']; ?></td>
		<td><?php echo $route['network']; ?></td>
		<td>+<?php echo $route['dial_code']; ?></td>
		<td><?php echo $route['units']; ?></td>
		<td><?php echo $route['continent']; ?></td>
		<td><?php echo $route['country_code']; ?></td>
	</tr>	
	<?php
	}
?>
</table>
</div>
<?php } ?>