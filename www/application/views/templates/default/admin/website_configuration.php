<h3 class='breadcrumb'>
	Configuration	
	<a href='?show_balance=1' class='pull-right' title='Show balances' ><i class='fa fa-database'></i></a>
</h3>
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
<?php } ?>
<hr/>
<form role='form' method='post' class='form-inline' >
	<div class='form-group'>
		<label for='start_date'>Start Date</label>
		<input type='date' name='start_date' value="<?php echo set_value('start_date',date('Y-m-d',strtotime('-6 days')));?>" class='form-control input-sm' placeholder='yyyy-mm-dd' required>
	</div>
	<div class='form-group'>
		<label for='end_date'>End Date</label>
		<input type='date' name='end_date' value="<?php echo set_value('end_date',date('Y-m-d'));?>" class='form-control input-sm' placeholder='yyyy-mm-dd' required>
	</div>
	<button name='show_dues' value='1' class='btn btn-sm btn-default'>Compute Dues</button>
</form>
<hr/>
<form role='form' method='post'>
	<div class='form-group col-md-3 col-sm-4'>
		<label for='site_name'>Website Name</label>
		<input type='text' name='site_name' value="<?php echo $presetData['site_name'];?>" class='form-control input-sm' placeholder='' required>
	</div>	
	<div class='form-group col-md-2 col-sm-4'>
		<label for='cron_mails_per_minute'>CronMails per Minute</label>
		<input type='number' name='cron_mails_per_minute' min='1' max='10' placeholder='2' value="<?php echo $presetData['cron_mails_per_minute'];?>" class='form-control input-sm' required>
	</div>
	<div class='form-group col-md-2 col-sm-4'>
		<label for='minimum_units'>Minimum Units</label>
		<input type='number' name='minimum_units' value="<?php echo $presetData['minimum_units'];?>" class='form-control input-sm' placeholder='1' required>
	</div>
	<div class='form-group col-sm-2'>
		<label for='max_linked_sms' class='small'>Max hyperlinked bulk SMS</label>
		<input type='number' name='max_linked_sms' value="<?php echo $presetData['max_linked_sms'];?>" class='form-control input-sm' placeholder='50' required>
	</div>
	<div class='form-group col-sm-3'>
		<label for='force_default_lang'>Force Default Lang</label>
		<select name='force_default_lang' class='form-control input-sm'>
            <option value=''>English (Default)</option>
            <?php foreach($this->general_model->languages as $lang =>$lang_name){
                    if($lang=='en')continue;
                    $is_sel=($presetData['force_default_lang']==$lang)?'selected':'';
                    echo "<option value='$lang' $is_sel>$lang_name</option>";
                } ?>
        </select>
	</div>
	
	
	<div class='form-group col-sm-2'>
		<label for='default_dial_code' class='small'>Default Dial Code</label>
		<input type='number' min='1' max='9999' name='default_dial_code' value="<?php echo $presetData['default_dial_code'];?>" class='form-control input-sm' placeholder='234' required>
	</div>
	<?php $countries=$this->general_model->get_countries(); ?>
	<div class='form-group col-md-3 col-sm-3'>
		<label>Default Country</label>
		<select name='default_country_code' class='form-control input-sm' required >
			<option value=''>Select countries</option>
			<?php foreach($countries as $country_code=>$country){ ?>
				<option value='<?php echo $country_code;?>'  <?php if($country_code!=''&&$country_code==$presetData['default_country_code'])echo 'selected'; ?> >
					<?php echo $country;?> 
				</option>
			<?php } ?>
		</select>
	</div>
	
	<div class='clearfix'></div>
	<h3>CheapGlobalSMS.com Account Configuration</h3>
	<hr/>
	<div class='form-group col-sm-6'>
		<label for='cgsms_sub_account'>CheapGlobalSMS Sub-Account</label>
		<input type='text' name='cgsms_sub_account' class='form-control' value="<?php echo $presetData['cgsms_sub_account'];?>" placeholder='001_subaccountname' required pattern='[0-9]+_[a-zA-Z0-9_]+' />
	</div>
	
	<div class='form-group col-sm-6'>
		<label for='cgsms_sub_account_password'>CheapGlobalSMS Sub-Account Password</label>
		<input type='text' name='cgsms_sub_account_password' class='form-control' value="<?php echo $presetData['cgsms_sub_account_password'];?>" placeholder='password' required />
	</div>
	<div class='col-md-12'>
		<a href='http://cheapglobalsms.com/sub_accounts'>Get/manage your sub-account here</a>
	</div>
	<div class='clearfix'></div>
	<hr/>
    
    <div class='form-group col-md-12' data-toggle='tooltip' title='E.g @gmail.com,@yahoo.com, ... If supplied, only those email containing these will be allowed'>
        <label for=''>Allowed Signup Email Domains</label>
        <?php 
            $temp_default="@yahoo.,@gmail.,@googlemail.,@aol.,@yandex.,@live.,@outlook.,@hotmail.,@ymail.,@mail.,@gmx.,@rocketmail.,@protonmail.,@hushmail.,@foxmail.,@vfemail.net,@pophorn.,@puxmail.,@qq.,@msn.";
            if(!isset($presetData['allowed_signup_email_domains']))$presetData['allowed_signup_email_domains']=$temp_default; ?>
        <textarea name='allowed_signup_email_domains' class='form-control'  placeholder='<?php echo $temp_default; ?>' rows=2><?php echo $presetData['allowed_signup_email_domains'];?></textarea>
    </div>
	<div class='form-group col-md-12'>
		<label for=''>Blacklisted Names</label>
		<textarea name='blacklisted_names' class='form-control'  placeholder='Blacklist malicious user names/ sender_ids; these ids will not be allowed to register' rows=2><?php echo $presetData['blacklisted_names'];?></textarea>
	</div>
	
	<div class='clearfix'></div>
	<h3> Website Notice </h3>
	<hr/>	
	<div class='form-group col-sm-6'>
		<label for='site_notice_logged_in'>When Logged In</label>
		<textarea name='site_notice_logged_in' class='form-control'  placeholder='Enjoy your stay' rows=2><?php echo $presetData['site_notice_logged_in'];?></textarea>
	</div>
	<div class='form-group col-sm-6'>
		<label for='site_notice_logged_out'>When Logged Out</label>
		<textarea name='site_notice_logged_out' class='form-control'  placeholder='Please login to enjoy' rows=2><?php echo $presetData['site_notice_logged_out'];?></textarea>
	</div>

	<div class='clearfix'></div>
	<h3>SOCIAL <small>(optional)</small></h3>
	<hr/>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='facebook_url'>Facebook Url</label>
		<input type='text' name='facebook_url' class='form-control' value="<?php echo $presetData['facebook_url'];?>" placeholder='http://facebook.com/username'/>
	</div>
	
	<div class='form-group col-md-3 col-sm-3'>
		<label for='facebook_app_id'>FB App ID</label>
		<input type='text' name='facebook_app_id' class='form-control' value="<?php echo $presetData['facebook_app_id'];?>" placeholder='311923839017947'/>
	</div>
	<div class='form-group col-md-3 col-sm-3'>
		<label for='twitter_url'>Twitter Url</label>
		<input type='text' name='twitter_url' class='form-control' value="<?php echo $presetData['twitter_url'];?>" placeholder='http://twitter.com/username'/>
	</div>
	
	<div class='clearfix'></div>
	<h3>
		SEO PARAMETERS
	</h3>
	<hr/>
	<div class='form-group col-sm-6'>
		<label for='site_meta_title'>Website Meta Title</label>
		<input type='text' name='site_meta_title' class='form-control' required value="<?php echo $presetData['site_meta_title'];?>" placeholder='Customized International SMS'/>
	</div>
	
	<div class='form-group col-sm-6'>
		<label for='site_meta_copyright'>Website Meta Copyright</label>
		<input type='text' name='site_meta_copyright' class='form-control' required value="<?php echo $presetData['site_meta_copyright'];?>" placeholder='&copy; <?php echo date('Y'); ?> all rights reserved'/>
	</div>
	
	<div class='form-group col-sm-6'>
		<label for='site_meta_keywords'>Website Meta Keywords</label>
		<textarea name='site_meta_keywords' class='form-control'  placeholder='bulk sms international,	
i want to send bulk sms,sms gateway,sms marketing,bulk sms,send bulk sms,cheap bulk sms,cheap sms,sms provider,worldwide sms,sms via internet,sms web service,send sms online,php sms,low cost sms,send sms from pc,send text message' rows=2 required><?php echo $presetData['site_meta_keywords'];?></textarea>
	</div>
	
	
	<div class='form-group col-sm-6'>
		<label for='site_meta_description'>Website Meta Description</label>
		<textarea name='site_meta_description' class='form-control'  placeholder='The fastest and most reliable Bulk SMS service provider to all networks worldwide. With robust SMS gateway API for developers' rows='2' required><?php echo $presetData['site_meta_description'];?></textarea>
	</div>
    
    
	<div class='clearfix'></div>
	<h3> Snippets </h3>
	<hr/>	
	<div class='form-group col-sm-6'>
		<label for='snippets_in_header'>To Be Inserted Before &lt;/HEAD&gt;</label>
		<textarea name='snippets_in_header' class='form-control'  placeholder='Optional extra JS/CSS' rows='3' ><?php echo $presetData['snippets_in_header'];?></textarea>
	</div>
	<div class='form-group col-sm-6'>
		<label for='snippets_in_footer'>To Be Inserted Before &lt;/BODY&gt;</label>
		<textarea name='snippets_in_footer' class='form-control'  placeholder='Optional extra JS/html' rows='3'><?php echo $presetData['snippets_in_footer'];?></textarea>
        <span class='btn-link' onclick="$('#sample_snippet_div').toggle();" >E.g, a Tawk Chat Snippet like this</span>
        <div id='sample_snippet_div'style='display:none;' >
        <pre>
        &lt;script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        if(typeof my_email!=='undefined'&&my_email){
            Tawk_API.visitor = {email:my_email,name:my_name};
        }
        
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/YOUR-TAWK-CODE/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        &lt;/script>
        </pre>
        </div>
	</div>

    
    <div class='clearfix'></div>
<?php 
{
    $base_url=$this->general_model->get_url();
    $descr=nl2br($configs['site_meta_description']);

$home_default="
	<div class='jumbotron'>
		<h1>{$configs['site_name']}</h1>
		<p>
            $descr
        </p>

		<p>
			<a class='btn btn-success btn-lg' href='{$base_url}pricing' role='button' style='margin-top:8px;'>
				Get SMS Credits
			</a> 
			<a class='btn btn-primary btn-lg' href='{$base_url}send_sms' role='button' style='margin-top:8px;'>
				Send SMS
			</a>
		</p>
	</div>
	<h2>Highlights</h2>
	<ul class='list-group' style='font-size:18px;'>
		<li class='list-group-item'>			
			<h3 class='list-group-item-heading'>
				1. Instant SMS delivery at the lowest rates <strong>worldwide</strong>
			</h3>
			<p class='list-group-item-text'>
				Two factors determines the <strong>actual</strong> cost of a bulk SMS.<br/>
				Cost = <a href='{$base_url}pricing' >Price per units</a> <strong>X</strong> <a href='{$base_url}coverage_list' >Units per SMS</a>
			</p>			
		</li>
		<li class='list-group-item'>
			<h3 class='list-group-item-heading'>
				2. Well Structured <a href='{$base_url}my_contacts'>Contacts Manager</a>
			</h3>
			<p class='list-group-item-text'>
				Import and backup your phone contact; or<br/>
				upload & download multiple contacts files for bulk messaging.
			</p>
		</li>
		<li class='list-group-item'>
			<h3 class='list-group-item-heading'>
				3. SMS Log & Scheduler
			</h3>
			<p class='list-group-item-text'>
				Access & manage your SMS <a href='{$base_url}sms_log'>delivery reports</a> in real-time
			</p>
		</li>
		<li class='list-group-item'>
			<h3 class='list-group-item-heading'>
				4. Fully featured developers API
			</h3>
			<p class='list-group-item-text'>
				You can do everything programatically.<br/> From SMS sending/scheduling, to contacts manager. <a href='{$base_url}gateway_api'>have a look</a>
			</p>
		</li>
	</ul>";
    
    if(!isset($presetData['home']))$presetData['home']=$home_default;
    $presetData['home']='';
}
?>
    <div class='form-group'>
        <label>HomePage/Dashboard Content  <span class='btn btn-xs btn-default' onclick='resetDefaultHompage()' >reset default</span></label>
        <div>
            For this to reflect on frontend; the the file at:
            <code>/application/views/template/{current-template-folder}/dashboard.php</code>
            contains this code:<code>&lt;?php echo $configs['home']; ?></code>
        </div>
        <textarea class='form-control input-sm textarea' name='home' id='home_text_field'><?php echo $presetData['home'];?></textarea>
    </div>
    
    
	<div class='clearfix'></div>
	<div class='text-center'>
		<button class='btn btn-lg btn-primary' value='save' name='save_configs'><span class='fa fa-save'> SAVE</button>
	</div>	
</form>
<div class='clearfix'></div>
<hr/>
<div class='alert alert-info'>
	Remember to set up a <strong>Cron Job</strong> with the following command.<br/>
	<pre> */15 * * * * 	curl <?php echo $this->general_model->get_url('run_sms_cron'); ?> >/dev/null 2>&amp;1 </pre>
	<p>i.e, run this command "<i>curl <?php echo $this->general_model->get_url('run_sms_cron'); ?> >/dev/null 2>&amp;1</i>" once every 15 minutes</p>
	<hr/>
	<h4>Also, set up another cron like this</h4>
	<pre> */15 * * * * curl <?php echo $this->general_model->get_url('run_mail_queue'); ?> >/dev/null 2>&amp;1 </pre>
	<p>i.e, run this command "<i>curl <?php echo $this->general_model->get_url('run_mail_queue'); ?> >/dev/null 2>&amp;1</i>" once every 15 minutes</p>
</div>

<div style='display:none;' id='home_default_content'><?php echo $home_default; ?></div>

<script type='text/javascript'>
    function resetDefaultHompage(){
        var temp_html=$('#home_default_content').html();
        $('#home_text_field').closest('.form-group').find('.note-codable,.note-editable').html(temp_html);
        $('#home_text_field').trigger('change');
    }
</script>