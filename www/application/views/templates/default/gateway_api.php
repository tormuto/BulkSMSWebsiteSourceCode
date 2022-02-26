<?php 
	function get_api_url($uri=''){ 
		$base_url=base_url();
		$bexp=explode(':',$base_url,2);
		return 'http:'.$bexp[1].'api_v1'.$uri;
	}
	
	$D_DIAL_CODE=$configs['default_dial_code'];
?>

<div class="panel panel-default">
	<div class="panel-heading default_breadcrumb">
		<h3 style='margin:0px;'>SMS Gateway Developers' API</h3>
	</div>
	<div class="panel-body">
		<h4>Table of Contents</h4>
		<ul>
			<li style='border-bottom:1px dotted #ddd;'>
				<a href='#section_10'>1.0 Introduction</a>
				<ul>
					<li><a href='#section_11'>1.1  Automation Details</a></li>
				</ul>
			</li>
			<li style='border-bottom:1px dotted #ddd;'>
				<a href='#section_20'>2.0 SMS Automation</a>
				<ul>
					<li><a href='#section_21'>2.1 Sending SMS</a></li>
					<li><a href='#section_22'>2.2 Fetch SMS information</a></li>
					<li><a href='#section_23'>2.3 Get Account balance & Information</a></li>
					<li><a href='#section_24'>2.4 Stop SMS, Delete SMS or Get Total Units</a></li>
				</ul>
			</li>
			<li style='border-bottom:1px dotted #ddd;'>
				<a href='#section_30'>3.0 Contacts Manager/Automation</a>
				<ul>
					<li><a href='#section_31'>3.1 Save Contacts/Phone Numbers</a></li>
					<li><a href='#section_32'>3.2 Fetch Contacts</a></li>
					<li><a href='#section_33'>3.3 Get Contact Groups</a></li>
					<li><a href='#section_34'>3.4 Delete Contacts</a></li>
				</ul>
			</li>
			<li><a href='#section_40'>4.0 Disclaimer</a></li>
		</ul>
	
		<h3 id='section_10'  onclick="$('#section_1_container').slideToggle('fast');" class='text-info' style='cursor:pointer;' ><i class='fa fa-chevron-down'></i> 1.0 Introduction</h3> <hr/>
<div id='section_1_container'>
		This document describes how to programmatically interact with <?php echo $configs['site_name']; ?>
		<i>(You must have 
			<?php if(!$this->general_model->logged_in()){ ?>
			<a href='javascript:;' data-toggle='modal' data-target='#signup' >registered</a>, and
			<?php } ?>
			<a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' >created a sub-account</a> that you'll use for accessing this API )</i>.
		<br/><br/>
		Interaction of this sort might be useful to those desiring to automate SMS sending, fetch the information about sent messages, or pull SMS log into a third party program.<br/>
		This document is intended to be utilized by technical personnel with programming knowledge; specifically with a working knowledge of HTML forms and JSON.
		<p style='color:#ff0000;'>
			NOTE: Examine your automation program closely for problems. You are just as responsible for <?php echo $configs['site_name']; ?> commands initiated by an automation program as if you had performed the action manually.
		</p>
		<hr/>
		<h4 id='section_11' >1.1 Automation Details</h4>
		In the following sections, you will find the parameters for using the API and some sample codes.<br/>
		All API request must be sent to <code><?php echo get_api_url(); ?></code> via HTTP POST or GET method and the response will be returned in a JSON format.<br/><br/>
		
		<strong>NOTE:</strong> If for any request, the <code>error</code> key is present in the JSON response, then the process was NOT successful, and the value of <code>error</code> key is the description of what went wrong.<br/>
		<hr  />
</div>
		<h3 id='section_20'  onclick="$('#section_2_container').slideToggle('fast');"  class='text-info' style='cursor:pointer;' ><i class='fa fa-chevron-down'></i> 2.0 SMS Automation</h3><hr/>
<div id='section_2_container' >
			This section describes how you can automatically <a href='#section_21'>send SMS</a>, <a href='#section_22'>fetch SMS information</a>, 
			<a href='#section_24'>stop / delete SMS / get total units spent</a>
			or <a href='#section_23'>get the balance and other information</a> of the sub-account.		
		<hr/>
		<h4 id='section_21' >2.1 Sending SMS</h4>

		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>send_sms</code></td>
				<td>send_sms</td>
			</tr>
			<tr>
				<td>message</td>
				<td>The message to be sent.</td>
				<td>Hello, there will be a meeting today by 12 noon.</td>
			</tr>
			<tr>
				<td>recipients</td>
				<td>
					The recipient's phone numbers. Multiple numbers can be separated by comma (,).
					<br/>
					Any mobile numbers starting with zero will have the zero stripped and replaced with the sub-account's default dial code.<br/>
					If the mobile number does not start with a zero, the default dial code will <strong>not</strong> be applied.<br/><br/>
					E.G if the sub-account's default dial code is '+<?php echo $D_DIAL_CODE; ?>', <br/>
					<i>08086689567,+<?php echo $D_DIAL_CODE; ?>8094309000,4478128372838</i> will be converted to,
					<i><?php echo $D_DIAL_CODE; ?>8086689567,<?php echo $D_DIAL_CODE; ?>8094309000,4478128372838</i>
				</td>
				<td>+<?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td style='font-weight:bold;' colspan='3'>Optional Fields</td>
			</tr>
			<tr>
				<td>type</td>
				<td><b>0</b> means normal sms, while <b>1</b> means <a href='<?php echo $this->general_model->get_url('faqs'); ?>' target='_blank' style='cursor:help;' title='the message that appears immediately on the phone screen, rather than inbox'>flash-message</a>.<br/>
					<i>If this was not supplied, the message will be assumed to be normal SMS.</i>
				</td>
				<td>0</td>
			</tr>
			<tr>
				<td>unicode</td>
				<td><b>0</b> means non-unicode sms, while <b>1</b> means that the message contains some special characters (e.g chinese, korean,...) that must be preserved (hence the character encoding of the HTTP request is also <strong>UTF-8)</strong><br/>
					<i>Please note that when a message is in unicode format, the character limits of the messages reduces to <strong>72 characters per page.</strong></i>
				</td>
				<td>0</td>
			</tr>
			<tr>
				<td>route</td>
				<td>
					<b>0</b> means <i>Optimal Standard Route</i>, <b>1</b> means <i>Priority Delivery Route</i>, <b>2</b> means <i>Best Pricing Route</i>
				</td>
				<td>0</td>
			</tr>
			<tr>
				<td>sender_id</td>
				<td>What will appear to the recipient as the sender of the SMS. 3 to 11 characters (or 3 to 14 digits if numeric).<br/>
					<i>If this was not supplied, the sub-account's default sender_id will be used.</i>
				</td>
				<td>President</td>
			</tr>
			<tr>
				<td>default_dial_code</td>
				<td>The number that will replace the leading-zero on the recipient's phone number.<br/>
					<i>If this was not supplied, the sub-account's default_dial_code will be used.</i>
				</td>
				<td><?php echo $D_DIAL_CODE; ?></td>
			</tr>
			<tr>
				<td>send_at</td>
				<td>The message will be on queue, to be sent on the specified date.<br/>
					<i>If this was not supplied, or if the value is a past date, the message will be sent immediately.</i>
				</td>
				<td style='text-align:center;'>
					<span style='white-space:nowrap;'>2016-04-22 13:45</span>
					<div style='text-align:center;font-style:italic;'>or</div>
					2016-04-22
					<br/> or <br/>
					13:45
				</td>
			</tr>
			<tr>
				<td>timezone_offset</td>
				<td>The timezone to be used for send_at.<br/>
					<i>If this was not supplied, the sub-account's timezone_offset will be used.</i>
				</td>
				<td>+1</td>
			</tr>
			<tr>
				<td>contact_groups</td>
				<td>Includes all numbers from the supplied <i>contact groups</i> of the sub-account with the recipients</td>
				<td>default,My Customers</td>
			</tr>
			<tr>
				<td>ignore_groups</td>
				<td>Excludes all numbers that appears in supplied <i>contact groups</i> of the sub-account from the recipients</td>
				<td>My Customers,My Customers2</td>
			</tr>
			<tr>
				<td>contacts</td>
				<td>
					A  JSON Array of contacts, E.G:<br/>
					<?php
						$demo_contacts=array(
							0=>array('phone'=>"+{$D_DIAL_CODE}8093216754",'firstname'=>'Pearl'),
							1=>array('phone'=>'08086689567','firstname'=>'John','lastname'=>'Doe','group_name'=>'Client','extra_data'=>'USER001'),
							2=>array('phone'=>"+{$D_DIAL_CODE}8094309926",'override_message'=>'Hello, bro. Please be around before 11:00am','override_date_time'=>'2016-04-22 09:30'),
						);
						echo json_encode($demo_contacts);
					?>
					<div class='help-block'>** 'phone' is the only compulsory field in a contact, when sending contacts in JSON format. </div>
					<div class='help-block'>** If you include 'override_message' in a contact when sending message, it is the <i>override_message</i> that will be sent to that contact instead of the original message. </div>
					<div class='help-block'>** If you include 'override_date_time' in a contact, message will rather be sent to the contact at the supplied <i>override_date_time</i>.</div>
					<div class='help-block'>** If you include 'extra_data' in a contact when sending message, the value of the extra_data you supplied will be availabe in the record of the message when you later <a href='#section_22'>fetch_sms</a>. </div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>save_as</td>
				<td>If this is supplied, the recipient phone number(s), will be saved under the group-name; specified as the value of this field, otherwise the numbers will not be saved to contacts</td>
				<td>default</td>
			</tr>
			<tr>
				<td colspan='3' ><i>
					NOTE: All duplicate numbers will be automatically removed for you and SMS will be sent to unique recipients only.
				</i></td>
			</tr>
		</table>
		</div>
		
		<h5 style='font-weight:bold;' >
			<i class='fa fa-file-code-o'></i> Sample codes for sending SMS
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation' class='active'><a href='#plain_http_sample_21' aria-controls='plain_http_sample_21' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_21' aria-controls='php_sample_21' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'><div role='tabpanel' class='tab-pane active' id='plain_http_sample_21'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'send_sms',
						'sender_id'=>'President',
						'message'=>'Hello, there will be a meeting today by 12 noon.'
					);
					
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string&recipients=08086689567,{$D_DIAL_CODE}8094309926");
				?>
				<code><?php echo $url; ?></code>
			</div>
		  
			<div role='tabpanel' class='tab-pane' id='php_sample_21'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0Rd',
		'action'=>'send_sms',
		'sender_id'=>'President',
		'recipients'=>'08086689567,<?php echo $D_DIAL_CODE; ?>8094309926',
		'message'=>"Hello, there will be a meeting today by 12 noon."
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			$msg="SMS sent to ".$json['total']." recipient(s).";
			$sms_batch_id=$json['batch_id'];
		}
	}
	
	echo $msg;
?>
</pre>
		
		</div>
		  </div>
		</div>
		
		The response to a successful <code>send_sms</code> request will be a JSON Object, containing the <i>batch_id</i>, and the <i>total</i> number of recipient numbers that are successfully parsed.
		<pre>{"batch_id":"1_1_1437039374","total":"2"}</pre>
		Below is a sample JSON response to a failed process:
		<pre>{"error":"insufficient credit","error_code":"1"}</pre>
		<hr/>
		
		
		
		<h4 id='section_22'>2.2 Fetch SMS information</h4>

		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>fetch_sms</code></td>
				<td>fetch_sms</td>
			</tr>
			<tr><td colspan='3'>
				<div style='font-weight:bold;'>Filters (Optional Fields)</div>
				<i>Only those SMS that matches the specified criteria will be returned.</i>
			</td></tr>

			<tr>
				<td>perpage</td>
				<td>
				To fetch <abbr title='e.g thousands of SMS'>large records</abbr>, it's essential to <abbr title='fetch fewer records per-time'>paginate</abbr>.<br/>
				If supplied, maximum is 300 while minimum is 1.</td>
				<td>100</td>
			</tr>
			<tr>
				<td>p</td>
				<td>This is only useful when paginating. It indicates the current page (e.g 1st page) of the record to be fetched.</td>
				<td>1</td>
			</tr>
			<tr>
				<td>batch_id</td>
				<td>The batch_id returned from a <code>send_sms</code>  action.</td>
				<td>1_1_1437039374</td>
			</tr>
			<tr>
				<td>sms_ids</td>
				<td>If the sms_id of the message(s) were already known in advanced, they can be used for filtering.</td>
				<td>572,228</td>
			</tr>
			<tr>
				<td>stage</td>
				<td>This can be <b>pending</b>, <b>sent</b> or <b>failed</b>.</td>
				<td>sent</td>
			</tr>
			<tr>
				<td>type</td>
				<td><b>0</b> means normal sms, while <b>1</b> means <a href='<?php echo $this->general_model->get_url('faqs'); ?>' target='_blank' style='cursor:help;' title='the message that appears immediately on the phone screen, rather than inbox'>flash-message</a>.
				</td>
				<td>0</td>
			</tr>
			<tr>
				<td>recipient</td>
				<td>The recipient number, to which the SMS had been sent, e.g 08094309926 or <?php echo $D_DIAL_CODE; ?>8094309926 or +<?php echo $D_DIAL_CODE; ?>8094309926</td>
				<td><?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td>sender_id</td>
				<td>3 to 11 characters (or 3 to 14 digits if numeric)</td>
				<td>President</td>
			</tr>
			<tr>
				<td>search_term</td>
				<td>If supplied, the records that contains either the recipient, sender_id or message body that 'roughly' matches the supplied value will be returned</td>
				<td>President <?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td>start_date</td>
				<td>The minimum: time sent, or scheduled time, of the messages to be returned.</td>
				<td style='text-align:center;'>
					<span style='white-space:nowrap;'>2016-04-22 13:45</span>
					<div style='text-align:center;font-style:italic;'>or</div>
					2016-04-22
				</td>
			</tr>
			<tr>
				<td>end_date</td>
				<td>The maximum: time sent, or scheduled time, of the messages to be returned.</td>
				<td style='text-align:center;'>
					<span style='white-space:nowrap;'>28-04-2016 13:45</span>
					<div style='text-align:center;font-style:italic;'>or</div>
					28-04-2016
				</td>
			</tr>
		</table>
		</div>
		<h5 style='font-weight:bold;'>
			<i class='fa fa-file-code-o'></i> Sample Codes for fetching SMS records
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation' class='active'><a href='#plain_http_sample_22' aria-controls='plain_http_sample_22' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_22' aria-controls='php_sample_22' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'>
			<div role='tabpanel' class='tab-pane active' id='plain_http_sample_22'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'fetch_sms',
						'batch_id'=>'1_1_1437039374',
						//'stage'=>'sent'
					);
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string");
				?>
				<code><?php echo $url; ?></code>
			</div>
			<div role='tabpanel' class='tab-pane' id='php_sample_22'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0Rd',
		'action'=>'fetch_sms',
		'batch_id'=>'1_1_1437039374',
		//'stage'=>'sent'
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			//you can now do whatever with the records 
			//(e.g check the recipient, fetch the next page or display a message)
			
			$msg=$json['total']." SMS records, page ".$json['p']." of ".$json['totalpages'];
			
			$msg.= "&lt;br/> &lt;ol>";
			foreach($json['records'] as $sms)
			{
				$msg.= "&lt;li>To: ".$sms['recipient']."&lt;br/> ".$sms['message']."&lt;/li>";
			}
			$msg.= "&lt;/ol>";
		}
	}
	
	echo $msg;
?>
</pre>
	
			</div>
		  </div>
		</div>
		
		The response to a <b>successful</b> 'fetch_sms' request will be a JSON Object, containing the paging information, and an Array SMS objects, <br/>
		Below is a sample successful process's response.

<pre>
{
	"total":19,"totalpages":1,"p":1,"perpage":100,"timezone_offset":"1",
	"records":[
		{"sms_id":"68",
		"sender":"ibukun",
		"recipient":"2022451175",
		"message":"checking wrong number",
		"sub_account_id":"1",
		"user_id":"1",
		"time_scheduled":"1448224505",
		"batch_id":"1_1_1448224505",
		"status":"-2",
		"type":"0",
		"units":"1",
		"reference":"",
		"time_submitted":"1448224505",
		"time_sent":"1448224505",
		"units_confirmed":"0",
		"locked":"1",
		"info":"Network is forbidden",
		"firstname":"Gotv",
		"lastname":"NUMBER",
		"group_name":"default",
		"status_msg":"REJECTED",
		"submitted_at":"2015-11-22 20:35",
		"scheduled_to":"2015-11-22 20:35",
		"sent_at":"2015-11-22 20:35",
		"extra_data":""
		},
		{"sms_id":"67","sender":"ibukun","recipient":"<?php echo $D_DIAL_CODE; ?>8094309926","message":"hello; how're you doing","sub_account_id":"1","user_id":"1","time_scheduled":"1448223878","batch_id":"1_1_1448223878","status":"2","type":"0","units":"2","reference":"","time_submitted":"1448223878","time_sent":"1448223878","units_confirmed":"1","locked":"0","info":"Message delivered to handset","firstname":null,"lastname":null,"group_name":null,"status_msg":"DELIVERED","submitted_at":"2015-11-22 20:24","scheduled_to":"2015-11-22 20:24","sent_at":"2015-11-22 20:24","extra_data":""},
		...
		,"filter":{"sub_account_id":"1","batch_id":"","sms_id":"","sms_ids":"","type":"","recipient":"","search_term":"","sender_id":"","stage":"","start_date":"","end_date":"","p":"","perpage":100,"offset":0}
}
</pre>

	<h5 style='font-weight:bold;font-style:italic;' >For a successfully submitted SMS, the 'status' (in the JSON Object) has following meanings</h5>
	<div class='table-responsive'>
	<table class='table table-condensed table-bordered table-striped'>
		<tr>
			<th class='col-xs-1'>Code</th>
			<th>Status</th>
			<th>Details</th>
		</tr>
		<?php
			$statuses=$this->general_model->sms_status;
			foreach($statuses as $status_id=>$status_data)
			{
		?>
		<tr>
			<td><?php echo $status_id; ?></td>
			<td><?php echo strtoupper($status_data['title']); ?></td>		
			<td><?php echo $status_data['msg']; ?></td>		
		</tr>
		<?php
			}
		?>
	</table>
	</div>
	<hr/>
 
		
		<h4 id='section_23'>2.3 Get SMS balance and sub-account information</h4>

		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>account_info</code></td>
				<td>account_info</td>
			</tr>
		</table>
		</div>
		<h5 style='font-weight:bold;'>
			<i class='fa fa-file-code-o'></i> Sample Codes for getting SMS balance
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation' class='active'><a href='#plain_http_sample_23' aria-controls='plain_http_sample_23' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_23' aria-controls='php_sample_23' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'>
			<div role='tabpanel' class='tab-pane active' id='plain_http_sample_23'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'account_info',
					);
			
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string");
				?>
				<code><?php echo $url; ?></code>
			</div>
			<div role='tabpanel' class='tab-pane' id='php_sample_23'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0R',
		'action'=>'account_info'
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			$msg="Balance: ".$json['balance'];			
		}
	}
	
	echo $msg;
?>
</pre>
			</div>
		  </div>
		</div>

		The response to a <b>successful</b> 'account_info' request will be a JSON Object, containing the sub-account's full information, <br/>
		Below is a sample successful process's response.

<pre>{"sub_account_id":"1","sub_account":"mysub1","user_id":"1","balance":"1209","notification_email":"example@gmail.com","default_dial_code":"<?php echo $D_DIAL_CODE; ?>","timezone_offset":"1","default_sender_id":"tormuto"}</pre>
		Below is a sample response to a failed process, a JSON <strong>Object</strong>
<pre>{"error":"Incorrect sub_account name or password","error_code":"4"}</pre>
		<hr/> 
		
 
		<h4 id='section_24'>2.4 Stop SMS, Delete SMS or Get Total SMS Units Spent</h4> 
		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/>
		<strong>NOTE:</strong> Only the SMS that are still on schedule can be <i>stopped</i>.
		<br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key can be either <code>stop_sms</code> , <code>delete_sms</code> or <code>get_total_units</code></td>
				<td>stop_sms</td>
			</tr>
			<tr><td colspan='3'>
				<div style='font-weight:bold;'>Optional Filters<br/>
					<i style='color:#f00;font-size:11px;' >IMPORTANT: When deleting or stopping SMS, you must specify some kind of filter(s), otherwise your entire sms records will be premanently deleted/stopped</i>
				</div>
			</td></tr>
			<tr>
				<td>batch_id</td>
				<td>
					Typically a batch_id returned from a <code>send_sms</code>  action.<br/>
					<i>An attempt will be made to stop all SMS in this batch.</i>
				</td>
				<td>1_1_1437039374</td>
			</tr>
			<tr>
				<td>sms_ids</td>
				<td>Typically one or more sms_id from the SMS objects array returned from a <code>fetch_sms</code>  action.</td>
				<td>572,228</td>
			</tr>
			
			<tr>
				<td>stage</td>
				<td>This can be <b>pending</b>, <b>sent</b> or <b>failed</b>.</td>
				<td>sent</td>
			</tr>
			<tr>
				<td>type</td>
				<td><b>0</b> means normal sms, while <b>1</b> means <a href='<?php echo $this->general_model->get_url('faqs'); ?>' target='_blank' style='cursor:help;' title='the message that appears immediately on the phone screen, rather than inbox'>flash-message</a>.
				</td>
				<td>0</td>
			</tr>
			<tr>
				<td>recipient</td>
				<td>The recipient number, to which the SMS had been sent, e.g 08094309926 or <?php echo $D_DIAL_CODE; ?>8094309926 or +<?php echo $D_DIAL_CODE; ?>8094309926</td>
				<td><?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td>sender_id</td>
				<td>3 to 11 characters (or 3 to 14 digits if numeric)</td>
				<td>President</td>
			</tr>
			<tr>
				<td>start_date</td>
				<td>The minimum: time sent, or scheduled time, of the messages to be returned.</td>
				<td style='text-align:center;'>
					<span style='white-space:nowrap;'>2016-04-22 13:45</span>
					<div style='text-align:center;font-style:italic;'>or</div>
					2016-04-22
				</td>
			</tr>
			<tr>
				<td>end_date</td>
				<td>The maximum: time sent, or scheduled time, of the messages to be returned.</td>
				<td style='text-align:center;'>
					<span style='white-space:nowrap;'>28-04-2016 13:45</span>
					<div style='text-align:center;font-style:italic;'>or</div>
					28-04-2016
				</td>
			</tr>
		</table>
		</div>
		<h5 style='font-weight:bold;'>
			<i class='fa fa-file-code-o'></i> Sample Codes for stopping SMS from sending.
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation'  class='active'><a href='#plain_http_sample_24' aria-controls='plain_http_sample_24' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_24' aria-controls='php_sample_24' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'>
			<div role='tabpanel' class='tab-pane active' id='plain_http_sample_24'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'stop_sms',
						'batch_id'=>'1_1_1437039374'
					);
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string");
				?>
				<code><?php echo $url; ?></code>
			</div>
			<div role='tabpane' class='tab-pane' id='php_sample_24'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0R',
		'action'=>'stop_sms',
		'batch_id'=>'1_1_1437039374'
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			$msg=$json['total']." messages stopped";			
		}
	}
	
	echo $msg;
?>
</pre>
	
			</div>
		  </div>
		</div>
		The response to a <b>successful</b> 'stop_sms' request will be a JSON Object, containing the total number of messages successfully stopped or deleted (as the case may be).<br/>
		Below is a sample successful process's response.

<pre>{"total":"10"}</pre>
		Below is a sample response to a failed process, a JSON <strong>Object</strong>
<pre>{"error":"no record found.","error_code":"10"}</pre>
		<hr/>

</div>
 
		
		<h3 id='section_30' onclick="$('#section_3_container').slideToggle('fast');" class='text-info' style='cursor:pointer;' ><i class='fa fa-chevron-down'></i> 3.0 Contacts Manager/Automation</h3><hr/>
<div id='section_3_container' >
			This section describes how you can automatically use CGSMS's contacts management system to <a href='#section_31'>store or backup your contacts</a>, <a href='#section_32'>fetch/retrieve your contacts</a>,  or even <a href='#section_34'>delete contacts</a>
		<hr/>
		<h4 id='section_31' >3.1 Save Contacts/Phone Numbers</h4>

		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>save_contacts</code></td>
				<td>save_contacts</td>
			</tr>
			<tr>
				<td style='font-weight:bold;' colspan='3'>
					Optional/Elective Fields
					<div class='help-block'>At least one of '<i>phone_numbers</i>' or '<i>contacts</i>' must be supplied.</div>
				</td>
			</tr>
			<tr>
				<td>phone_numbers</td>
				<td>
					A string of phone numbers (multiple numbers can be separated by comma)
					<br/>
					E.G: <i>08086689567,+<?php echo $D_DIAL_CODE; ?>8094309926,4478128372838</i> 
					<div class='help-block'>Any mobile numbers starting with zero will have the zero stripped and replaced with the sub-account's default dial code. </div>
				</td>
				<td>+<?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td>contacts</td>
				<td>
					A  JSON Array of contacts, E.G:<br/>
					<?php 
						$demo_contacts=array(
							0=>array('phone'=>'08086689567','firstname'=>'John','lastname'=>'Doe','group_name'=>'Client'),
							1=>array('phone'=>"+{$D_DIAL_CODE}8094309926",'firstname'=>'Pearl'),
						);
						echo json_encode($demo_contacts);
					?>
					<div class='help-block'>'phone' is the only compulsory field, when sending contacts in JSON format. </div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>group_name</td>
				<td>All the supplied numbers/contacts will be saved under this group_name, except those contacts in JSON Array, that also has a group_name defined.<br/><br/>
					<i>If group_name was not supplied via any means, the contacts will automatically be added under 'default' group_name.</i>
				</td>
				<td>My Customers</td>
			</tr>
		</table>
		</div>
		
		<h5 style='font-weight:bold;' >
			<i class='fa fa-file-code-o'></i> Sample codes for saving contacts
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation' class='active'><a href='#plain_http_sample_31' aria-controls='plain_http_sample_31' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_31' aria-controls='php_sample_31' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'><div role='tabpanel' class='tab-pane active' id='plain_http_sample_31'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'save_contacts',
						'group_name'=>'My Customers',
					);
					
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string&contacts=08086689567,{$D_DIAL_CODE}8094309926");
				?>
				<code><?php echo $url; ?></code>
			</div>
		  
			<div role='tabpanel' class='tab-pane' id='php_sample_31'>
<pre>
&lt;?php
	$contacts=array(
		0=>array('phone'=>'08086689567','firstname'=>'Ibukun','lastname'=>'Oladipo','group_name'=>'Client'),
		1=>array('phone'=>'+<?php echo $D_DIAL_CODE; ?>8094309926','firstname'=>'Pearl'),
	);

	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0Rd',
		'action'=>'save_contacts',
		'contacts'=>json_encode($contacts),
		'group_name'=>'My Customers',
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			$msg=$json['total']." contact(s) has been saved.";
		}
	}
	
	echo $msg;
?>
</pre>
		
		</div>
		  </div>
		</div>
		
		The response to a successful <code>save_contacts</code> request will be a JSON Object, containing the <i>batch_id</i>, and the <i>total</i> number of recipient numbers that are successfully parsed.
		<pre>{"total":"2"}</pre>
		Below is a sample JSON response to a failed process:
		<pre>{"error_code":4,"error":"Incorrect sub_account name or password."}</pre>
		<hr/>
		
		
		
		<h4 id='section_32'>3.2 Fetch Contacts</h4>

		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>fetch_contacts</code></td>
				<td>fetch_contacts</td>
			</tr>
			<tr><td colspan='3'>
				<div style='font-weight:bold;'>Filters (Optional Fields)</div>
				<i>Only those contacts that matches the specified criteria will be returned.</i>
			</td></tr>

			<tr>
				<td>group_name</td>
				<td>Return only those contacts in this group</td>
				<td>My Customers</td>
			</tr>
			<tr>
				<td>search_term</td>
				<td>This can be any of phone number, firstname, lastname  to find, e.g: <i>08094309926</i> or <i>ibukun</i> or <i>08094309926 ibukun</i></td>
				<td><?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td>contact_ids</td>
				<td>If the specific contact_id(s) to be fetch were already known, it can be used for filtering.</td>
				<td>5,7</td>
			</tr>
			<tr>
				<td>perpage</td>
				<td>
				To fetch <abbr title='e.g thousands of contacts'>large records</abbr>, it's essential to <abbr title='fetch fewer records per-time'>paginate</abbr>.<br/>
				If supplied, maximum value is 300 while minimum is 1.</td>
				<td>100</td>
			</tr>
			<tr>
				<td>p</td>
				<td>This is only useful when paginating. It indicates the current page (e.g 1st page) of the record to be fetched.</td>
				<td>1</td>
			</tr>
		</table>
		</div>
		<h5 style='font-weight:bold;'>
			<i class='fa fa-file-code-o'></i> Sample Codes for fetching contacts.
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation' class='active'><a href='#plain_http_sample_32' aria-controls='plain_http_sample_32' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_32' aria-controls='php_sample_32' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'>
			<div role='tabpanel' class='tab-pane active' id='plain_http_sample_32'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'fetch_contacts',
						'group_name'=>'My Customers',
					);
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string");
				?>
				<code><?php echo $url; ?></code>
			</div>
			<div role='tabpanel' class='tab-pane' id='php_sample_32'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0Rd',
		'action'=>'fetch_contacts',
		'group_name'=>'My Customers',
		'search_term'=>'ibukun'
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			//you can now do whatever with the contact records returned
			//(e.g check list the contacts, or even fetch the next page by running the same request with p=1)
			
			$msg=$json['total']." Contacts in total, page ".$json['p']." of ".$json['totalpages'];
			
			$msg.= "&lt;br/> &lt;ol>";
			foreach($json['contacts'] as $contact)
			{
				$msg.= "&lt;li>+".$contact['phone']."&lt;br/> ".$contact['firstname']." ".$contact['lastname']." (".$contact['group_name'].")&lt;/li>";
			}
			$msg.= "&lt;/ol>";
		}
	}
	
	echo $msg;
?>
</pre>
	
			</div>
		  </div>
		</div>
		
		The response to a <b>successful</b> 'fetch_contacts' request will be a JSON Object, containing the paging information, and an Array contact objects, <br/>
		Below is a sample successful process's response.

<pre>
{
  "filter":{"user_id":"1","sub_account_id":"1","perpage":10,"group_name":"My Customers","search_term":null,"offset":0},
  "total":2,"p":1,"totalpages":1,
  "records":[{"contact_id":"20","user_id":"1","sub_account_id":"1","phone":"<?php echo $D_DIAL_CODE; ?>8086689567","firstname":"","lastname":"","group_name":"My Customers","time":"1445721399"},
  {"contact_id":"21","user_id":"1","sub_account_id":"1","phone":"<?php echo $D_DIAL_CODE; ?>8094309926","firstname":"","lastname":"","group_name":"My Customers","time":"1445721399"}
  ]
}
</pre>

		<hr/>		
		<h4 id='section_33'>3.3 Get Contact Groups</h4>

		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/><br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>get_contact_groups</code></td>
				<td>get_contact_groups</td>
			</tr>
		</table>
		</div>
		<h5 style='font-weight:bold;'>
			<i class='fa fa-file-code-o'></i> Sample Codes for getting sub-account's contact groups
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation' class='active'><a href='#plain_http_sample_33' aria-controls='plain_http_sample_33' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_33' aria-controls='php_sample_33' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'>
			<div role='tabpanel' class='tab-pane active' id='plain_http_sample_33'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'get_contact_groups',
					);
			
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string");
				?>
				<code><?php echo $url; ?></code>
			</div>
			<div role='tabpanel' class='tab-pane' id='php_sample_33'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0R',
		'action'=>'get_contact_groups'
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			$msg="Balance: ".$json['balance'];			
		}
	}
	
	echo $msg;
?>
</pre>
			</div>
		  </div>
		</div>

		The response to a <b>successful</b> 'get_contact_groups' request will be a JSON Object, containing 'contact_groups', an object having each contact_group names and their sizes (amount of contact), <br/><br/>
		Below is a sample successful process's response.

<pre>{"contact_groups":{"My Customers":"2","default":"4"}}</pre>
		<hr/> 
 
		<h4 id='section_34'>3.4 Delete Contacts</h4> 
		The following parameters can be sent, through HTTP GET or POST request to the URL: <code><?php echo get_api_url(); ?></code><br/>
		<br/>
		<div class='table-responsive' style='margin-top:10px;margin-bottom:10px;'>
		<table class='table table-striped table-bordered table-condensed' style='width:80%;margin:auto;'>
			<tr>
				<th>Input Field Name</th>
				<th>Description</th>
				<th>Example Value</th>
			</tr>
			<tr>
				<td>sub_account</td>
				<td>A sub_account name that you <a href='<?php echo $this->general_model->get_url('sub_accounts'); ?>' target='_blank'>have created.</a> </td>
				<td>001_mysub1</td>
			</tr>
			<tr>
				<td>sub_account_pass</td>
				<td>Sub account password</td>
				<td>pa55w0Rd</td>
			</tr>
			<tr>
				<td>action</td>
				<td>The value for this key must be <code>delete_contacts</code></td>
				<td>delete_contacts</td>
			</tr>
			
			<tr><td colspan='3'>
				<div style='font-weight:bold;'>Filters (Optional Fields)</div>
				<i>ALL those contacts that matches the specified criteria will be deleted.</i>
			</td></tr>
			<tr>
				<td>group_name</td>
				<td>Return only those contacts in this group</td>
				<td>My Customers</td>
			</tr>
			<tr>
				<td>search_term</td>
				<td>This can be any of phone number, firstname, lastname  to find, e.g: <i>08094309926</i> or <i>ibukun</i> or <i>08094309926 ibukun</i></td>
				<td><?php echo $D_DIAL_CODE; ?>8094309926</td>
			</tr>
			<tr>
				<td>contact_ids</td>
				<td>Typically one or more contact_id, from the array of contact objects returned from a <code>fetch_contacts</code>  action.</td>
				<td>5,7</td>
			</tr>
		</table>
		</div>
		<h5 style='font-weight:bold;'>
			<i class='fa fa-file-code-o'></i> Sample Codes for deleting contact(s).
		</h5>
		<div style='margin-bottom:20px;' >
		  <ul class='nav nav-tabs' role='tablist'>
			<li role='presentation'  class='active'><a href='#plain_http_sample_34' aria-controls='plain_http_sample_34' role='tab' data-toggle='tab'>Plain HTTP</a></li>
			<li role='presentation'><a href='#php_sample_34' aria-controls='php_sample_34' role='tab' data-toggle='tab'>PHP</a></li>
		  </ul>

		  <div class='tab-content'>
			<div role='tabpanel' class='tab-pane active' id='plain_http_sample_34'>
				<?php
					$params=array(
						'sub_account'=>'001_mysub1',
						'sub_account_pass'=>'pa55w0Rd',
						'action'=>'delete_contacts',
						'contact_ids'=>'5,7'
					);
					$query_string=http_build_query($params);
					$url=get_api_url("?$query_string");
				?>
				<code><?php echo $url; ?></code>
			</div>
			<div role='tabpane' class='tab-pane' id='php_sample_34'>
<pre>
&lt;?php
	$post_data=array(
		'sub_account'=>'001_mysub1',
		'sub_account_pass'=>'pa55w0R',
		'action'=>'delete_contacts',
		'contact_ids'=>'5,7'
	);
	
	$api_url='<?php echo get_api_url(); ?>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($response_code != 200)$response=curl_error($ch);
	curl_close($ch);

	if($response_code != 200)$msg="HTTP ERROR $response_code: $response";
	else
	{
		$json=@json_decode($response,true);
		
		if($json===null)$msg="INVALID RESPONSE: $response"; 
		elseif(!empty($json['error']))$msg=$json['error'];
		else
		{
			$msg="Total deleted contacts: ".$json['total'];			
		}
	}
	
	echo $msg;
?>
</pre>
	
			</div>
		  </div>
		</div>
		The response to a <b>successful</b> 'delete_contacts' request will be a JSON Object, containing the total number of deleted contacts.<br/>
		Below is a sample successful process's response.

<pre>{"total":"4"}</pre>
		Below is a sample response to a failed process, a JSON <strong>Object</strong>
<pre>{"error":"No contact_ids supplied to be deleted","error_code":"7"}</pre>
		<hr/>

</div>
 
		<h3 id='section_40'  onclick="$('#section_4_container').slideToggle('fast');" style='cursor:pointer;' ><i class='fa fa-chevron-down'></i> 4.0 Disclaimer</h3>
<div id='section_4_container' >
		<div style='color:#ff3300;'>
			The Author [Tormuto Info. Tech.] accepts no responsibility for damages to persons, property or data incurred through the use or misuse of these API and scripts. To the maximum extent permitted by law, in no event shall the Author [Tormuto Info. Tech.] be liable for any damages whatsoever (including, without limitation, damages for loss of business profits, business interruption, loss of business information, or other pecuniary loss) arising out of the use or inability to use this API, even if the Author has been advised of the possibility of such damages.<br/>
		This product is supplied as-is, with no warranties express or implied.
		Use this documentation at your own risk.
		</div>
	</div>
	
	<h4>Error Codes and Descriptions</h4>
	<div class='table-responsive'>
	<table class='table table-condensed table-bordered table-striped'>
		<tr>
			<th class='col-xs-1'>Error Code</th>
			<th>Description</th>
		</tr>
		<?php
			$errors=$this->general_model->get_api_errors();
			foreach($errors as $error_code=>$error_msg)
			{
		?>
		<tr>
			<td><?php echo $error_code; ?></td>
			<td><?php echo $error_msg; ?></td>		
		</tr>
		<?php
			}
		?>
	</table>
	</div>

</div>	
	
</div>
<script type='text/javascript'>
	$(function(){
		$("a[href^='#']").on('click',function(e){
			var this_attr=$(this).attr('href');
            if(this_attr!='#'){
                $('html, body').animate({ scrollTop: $(this_attr).offset().top-125 }, 2000);
                e.preventDefault();
            }
		});
	});
</script>