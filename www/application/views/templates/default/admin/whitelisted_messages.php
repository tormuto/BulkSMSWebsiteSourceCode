<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
	<div class='row text-right'>
		<div class='col-md-12'>
			<form class='form-inline' role='form'>							
				<div class='form-group'>
					<input type='email' name='email' class='form-control input-sm' placeholder='email' value="<?php echo $filter['email'];?>" >
				</div>
				<div class='form-group'>
					<div class='input-group'>
						<input type='search' name='q' value="<?php echo $filter['search_term'];?>" class='form-control input-sm' placeholder='search term..'>
						<span class='input-group-btn'>
							<button  class='btn btn-sm btn-default' title='Search'>
								<i class='fa fa-search'></i>
							</button>
						</span>
					</div>
				</div>
			</form>
		</div>
	</div>
	<hr/>
<div class="panel panel-success">
	<div class='panel-heading'>
		Whitelisted Messages		
	</div>
	<div class="panel-body">
		<?php if(empty($whitelisted_messages)){ ?>
			<div class='alert alert-warning'>
				<button class='close' data-dismiss='alert'>&times;</button>
				No record found!
			</div>
		<?php } else { ?>
		<div class='table-responsive'>
		<table style='font-size:10px;' class='table table-striped table-bordered'>
			<tr >
				<th> S/N</th>
				<th> SENDER</th>
				<th> MESSAGE</th>
				<th>RELATED USER</th>
				<th>DATE-TIME</th>
			</tr>
			<?php
				$sn=$filter['offset'];	
				foreach($whitelisted_messages as $row)
				{
					$n=$total-$sn;
					$sn++;
				?>
					<tr >
						<td><?php echo $n; ?></td>
						<td><?php echo $row['sender_id']; ?></td>
						<td style='word-break: break-all;' ><?php echo $row['message']; ?></td>
						<td >
							<a href='<?php echo $this->general_model->get_url("admin_manage_members/{$row['user_id']}");?>'>
								<?php echo $row['firstname']." ".$row['lastname'];?>
							</a>
						</td>
						<td>
							<?php echo date('D jS M. Y <br/>g:i a',strtotime($row['date_time'])); ?>
							<a href="<?php echo $this->general_model->get_url("admin_whitelisted_messages?delete_whitelist={$row['whitelisted_sms_id']}&email={$filter['email']}&q={$filter['search_term']}"); ?>" title='Remove this whitelist' onclick="return confirm('Do you really want to remove this whitelist?');" class='pull-right' ><i class='fa fa-trash text-danger'></i></a>
						</td>			
					 </tr>
			<?php } ?>
		</table>					
		</div>
		<?php				
			$pagination=$this->general_model->get_pagination($p,$totalpages);
		}
		if(empty($pagination))$pagination=" ";
		?>
	</div>
	<div class="panel-footer" style='text-align:right;'><?php echo $pagination;?></div>
</div>