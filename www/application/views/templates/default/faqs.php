<div class="panel panel-default">
	<div class="panel-heading"><h3 style='margin:0px;'>Frequently Asked Questions</h3></div>
		<div class="panel-body">
			
			<p>
				You may only need to send little SMS, or you want to send bulk SMS, <?php echo $configs['site_name']; ?> allows you to send SMS to any country at the cheapest rates around, directly from the website.<br/>
				Apart form offering cheapest bulk SMS credits, <?php echo $configs['site_name']; ?> is the perfect and most reliable choice either you want to send <strong>international</strong> SMS or you want to send <strong>local</strong> SMS with guaranteed and <strong>instant SMS delivery</strong> worldwide.<br/>
				All you need to do is to just <a href='<?php echo $this->general_model->get_url('pricing'); ?>'>Buy cheap SMS Credits</a>, and once your <?php echo $configs['site_name']; ?> account has been funded, then you can go ahead and <a href='<?php echo $this->general_model->get_url('send_sms'); ?>'>send SMS</a> to every countries of the world.
			</p>
		</div>
		
		<ul class='list-group'>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>What is a flash message</h4>
				<p class='list-group-item-text'>
					Flash SMS is the type of message that appears immediately on the recipient's screen regardless of what he/she is doing on the phone at the moment.<br/>
					It's the type of message that most network providers displays to you when you requested for some services e.g to check your account balance. <br/>
					<i>Unlike the normal message, it does not get automatically saved into the recipient's inbox; although <strong>some</strong> phones may provide the recipient with an option to save the message or discard it after reading.<i><br/>
					Flash message costs the same SMS units on <?php echo $configs['site_name']; ?> as normal SMS.
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>Can I send SMS in Chinese,Japanese or Korean Language</h4>
				<p class='list-group-item-text'>
					Yes all the languages are supported. However for those special languages, you will need to specify that the message encoding is <strong>unicode</strong> when sending the message (please note that unicode messages reduces the character limits to 72 characters per page)
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>How can I send SMS from my website/app?</h4>
				<p class='list-group-item-text'>
					With a basic knowledge of HTML forms and JSON, you can use our well documented <a title='application programming interface' href='<?php echo $this->general_model->get_url('gateway_api'); ?>'>API functions</a> to programmatically send sms, check balance, and get  SMS status.
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>Why do i need <?php echo $configs['site_name']; ?></h4>
				<p class='list-group-item-text'>
					<ul>
						<li>
							Student groups / schools can easily communicate important information quickly as well as reminders, using communicate.
						</li>
						<li>
							Religious bodies can use <?php echo $configs['site_name']; ?> communicate more personally with its members and share spiritual thoughts daily
						</li>
						<li>
							Even if you want to just sent a single SMS to your friend, <?php echo $configs['site_name']; ?> is still cheaper than your normal network charges.
						</li>
					</ul>
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>How reliable is <?php echo $configs['site_name']; ?> API</h4>
				<p class='list-group-item-text'>
					All the API functions of <?php echo $configs['site_name']; ?>.com are perfectly working.<br/>
					In fact, the <a href='<?php echo $this->general_model->get_url('sms_widget'); ?>'><?php echo $configs['site_name']; ?> Widget</a> is using the SMS Gateway API for operation.<br/>
					This ensures that the <?php echo $configs['site_name']; ?> API functions are 100% reliable at any time for all users.
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>I saw a negative account balance, what happened?</h4>
				<p class='list-group-item-text'>
					Because the exact cost/units of the SMS couldn't be determined before the message was sent, the <strong>lowest</strong> charge to the <abbr title='e.g 234 for nigeria (the lowest is 1unit, though there are some networks that are still up to 3units'>sms prefix</abbr> per SMS page will be assumed.<br/>
					After few seconds, the real network/units will be determined; and if more units were spent, the excess will be deducted as well<br/>
					But if your balance was already empty at that point, it will reflect a negative.
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>I have paid, but not yet credited</h4>
				<p class='list-group-item-text'>
					Normally, the account funding process has been made as automated as possible.<br/>
					E.g:If you use credit card/online payment method, your account will be credited automatically as you returns from the payment gateway's website.<br/>
					For cash/bank deposit payment method, if you use your email address as depositor's name
					and exercise a little patience, your account will be credited once your payment has been received.<br/><br/>
					In case of any unforeseen circumstance, please <a href='<?php echo $this->general_model->get_url('contact_us'); ?>'>contact us</a>.<br/>
					<strong>Please Note</strong> that: payments made for SMS credits are non-refundable.
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>How secure is my credit card information, if i use online payment method</h4>
				<p class='list-group-item-text'>
					<?php echo $configs['site_name']; ?> will neither store, nor receive your credit card information from you (the payment gateway does that themselves).<br/>
					(<strong>E.G</strong>; If you're paying with your <i>naira master/visa card</i>, you will only enter your card details when you get to <I>interswitch</I>'s page)
				</p>
			</li>
			<li class='list-group-item' >
				 <h4 class='list-group-item-heading'>Brief About <?php echo $configs['site_name']; ?></h4>
				<p class='list-group-item-text'><?php echo $configs['site_name']; ?>.com offers the most reliable BulkSMS gateway in the world. If you're looking for the cheapest cost of international sms, here is the right place to find the  international sms cost. For starters, you'll get <?php echo $configs['free_sms']; ?> free global sms units, so you can use the free bulk sms services, to send bulk sms free online and test the bulk sms software. The bulk sms gateway allows you to send bulk sms in nigeria, or cheap international sms. The worldwide SMS coverage list is on <?php echo $configs['site_name']; ?>.com, see the <a href='<?php echo $this->general_model->get_url('coverage_list'); ?>'>worldwide coverage list here</a>
				</p>
			</li>
		</ul>
</div>