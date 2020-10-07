<?php if(!empty($configs['unifiedpurse_widget_url'])){ ?>
<div style='float:right;'><span class='btn-link' style='cursor:pointer' onclick="$('#widget_help_div').slideToggle();">help</span></div>
<?php } ?>
<div class='well' id='widget_help_div' <?php if(!empty($configs['unifiedpurse_widget_url']))echo "style='display:none;'"; ?>>
	<form method='post'>
		<div class='form-group'>
			<label class='sr-only'>Transaction Widget URL</label>
			<div class='input-group'>
				<input type='text' pattern='.*unifiedpurse\.com/.*' title='//unifiedpurse.com/transaction_widget?email=..' class='form-control input-sm' required name='unifiedpurse_widget_url' value="<?php echo @$configs['unifiedpurse_widget_url']; ?>" placeholder='Paste your UnifiedPurse Widget URL' />
				<span class='input-group-btn'>
					<button class='btn btn-sm btn-default'><i class='fa fa-save'></i></button>
				</span>
			</div>
		</div>	
	</form>
	<i>
		To easily monitor your UnifiedPurse transactions here, <a href='https://unifiedpurse.com/accept_payments#transaction_widget' target='_blank'>Generate your transaction widget URL</a> from UnifiedPurse, or <a href='https://unifiedpurse.com/accept_payments#transaction_widget' target='_blank'>see how it's generated here</a>.
	</i>
</div>
<?php if(!empty($configs['unifiedpurse_widget_url'])){ ?>
<iframe src="<?php echo $configs['unifiedpurse_widget_url']; ?>" style='height:650px;width:100%;border:1px solid #eee;'></iframe>
<?php } ?>