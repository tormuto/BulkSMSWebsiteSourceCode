<?php echo $this->general_model->display_bootstrap_alert(@$Success,@$Error,@$Warning,@$Info); ?>
	<div class='row text-right'>
		<div class='col-md-12'>
			<form class='form-inline' role='form'>							
				<div class='form-group'>
					<select name='type' class='form-control input-sm'>
						<option value='' <?php if(empty($filter['type']))echo 'selected'; ?> >All</option>
						<option value='general' <?php if($filter['type']=='general')echo 'selected'; ?> >General</option>
						<option value='suspended_sms' <?php if($filter['type']=='suspended_sms')echo 'selected'; ?> >Suspended SMS</option>
						<option value='payment' <?php if($filter['type']=='payment')echo 'selected'; ?> >Payment Error</option>
						<option value='technical' <?php if($filter['type']=='technical')echo 'selected'; ?> >Technical</option>
					</select>
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
<div class="panel panel-warning">
	<div class='panel-heading'>
		<div>
			ERROR LOG
			<a style='padding:0px;' class='pull-right btn-xs text-danger' href='<?php echo $this->general_model->get_url('admin_error_log/clear');?>' >
				<i class='fa fa-trash-o'></i> Clear Error Log
			</a>
		</div>			
	</div>
	<div class="panel-body">
		<?php if(empty($errors)){ ?>
			<div class='alert alert-warning'>
				<button class='close' data-dismiss='alert'>&times;</button>
				No record found!
			</div>
		<?php } else { ?>
		<div class='table-responsive'>
		<table style='font-size:10px;' class='table table-striped table-bordered'>
			<tr >
				<th> S/N</th>
				<th> Type</th>
				<th> TITLE</th>
				<th>DETAILS</th>
				<th>RELATED USER</th>
				<th>DATE</th>
			</tr>
			<?php
				$sn=$filter['offset'];	
				foreach($errors as $row)
				{
					$n=$total-$sn;
					$sn++;
				?>
					<tr >
						<td><?php echo $n; ?></td>
						<td><?php echo $row['type']; ?></td>
						<td><?php echo $row['topic']; ?></td>
						<td style='word-break: break-all;' >
							<?php echo $row['details']; ?>
						</td>
						<td >
							<a href='<?php echo $this->general_model->get_url("admin_manage_users?f_user_id={$row['user_id']}");?>'>
								<?php echo $row['firstname']." ".$row['lastname'];?>
							</a>
						</td>
						<td><?php echo date('D jS M. Y <br/>g:i a',$row['time']); ?></td>			
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