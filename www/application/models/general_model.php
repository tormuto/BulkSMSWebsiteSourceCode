<?php
	class General_model extends CI_Model
	{
		public function __construct(){
			$this->load_db();
			$this->flash_message='';
			
			$this->transaction_types=array(1=>'ACCOUNT FUNDING',2=>'SMS',3=>'EARNING');
			$this->account_statuses=array('0'=>'INACTIVE','1'=>'ACTIVE','2'=>'SUSPENDED');
			
			$this->sms_status=array(
				'-4'=>array('title'=>'undeliverable','css'=>'danger','msg'=>'The message is undeliverable','cgsms'=>'UNDELIVERABLE'),
				'-3'=>array('title'=>'expired','css'=>'danger','msg'=>'Message validity period has expired. (e.g phone off or not in coverage area)','cgsms'=>'EXPIRED'),
				'-2'=>array('title'=>'rejected','css'=>'warning','msg'=>'The message was rejected. The message is undeliverable.','cgsms'=>'REJECTED'),
				'-1'=>array('title'=>'failed','css'=>'default','msg'=>'An issue occured while attempting to send the SMS.'),
				'0'=>array('title'=>'pending','css'=>'info','msg'=>'This message is on queue, to be sent.','cgsms'=>'PENDING'), 
				'1'=>array('title'=>'sent','css'=>'primary','msg'=>'The message was accepted by the Network server. Awaiting delivery status.','cgsms'=>'ACCEPTED'),
				'2'=>array('title'=>'delivered','css'=>'success','msg'=>'Message is delivered to destination.','cgsms'=>'DELIVERED')
				);
			
			$this->payment_ussd_code_params=array('bank_phone_number','amount_transfered');
			$this->payment_bank_params=array('bank_name','depositor_name','teller_number','amount_transfered','payment_date');
			$this->payment_western_union_params=array('senders_firstname','senders_lastname','senders_country','mtcn','security_answer','amount_transfered');
				
			$this->payment_methods=array(
				//'free'=>'FREE','cumulated'=>'CUMULATED',
				'ucollect'=>'UCollect','gtpay'=>'GTPay',
				'interswitch'=>'Interswitch','bitcoin'=>'Bitcoin','perfectmoney'=>'PerfectMoney',
				'2checkout'=>'2CheckOut','zenith_globalpay'=>'Zenith Globalpay',
				'bank_deposit'=>'Bank Deposit','ussd_code'=>'USSD Code','pay_on_delivery'=>'Pay On Delivery/Venue','western_union'=>'Western Union',
				'stanbic'=>'StanbicIBTC','paypal'=>'Paypay','firstpay'=>'Firstpay','skye'=>'Skye',
				'simplepay'=>'Simplepay','voguepay'=>'VoguePay','jostpay'=>'JostPay',
				'free_checkout'=>'FREE'
				);
			
			$this->payment_methods_no_requery=array('perfectmoney','bitcoin','bank_deposit','pay_on_delivery','ussd_code','western_union','2checkout','paypal','free_checkout','simplepay','voguepay');
			
			
			$this->payment_gateway_params=array
				(
					'ucollect'=>array('merchant_id'=>'ucollect_merchant_id','key'=>'ucollect_service_key'),
					'gtpay'=>array('merchant_id'=>'gtpay_merchant_id','key'=>'gtpay_hash_key'),
					'interswitch'=>array('merchant_id'=>'interswitch_product_id','key'=>'interswitch_mac_key'),
					'perfectmoney'=>array('merchant_id'=>'perfectmoney_account','key'=>'perfectmoney_paraphrase'),
					'bitcoin'=>array('merchant_id'=>'bitcoin_address','key'=>''),
					'2checkout'=>array('merchant_id'=>'2checkout_seller_id','key'=>'2checkout_secret'),
					'zenith_globalpay'=>array('merchant_id'=>'globalpay_merchant_id','uid'=>'globalpay_user_id','key'=>'globalpay_password'),
					'bank_deposit'=>array('merchant_id'=>'bank_account_details','key'=>'','textarea'=>'1'),
					'jostpay'=>array('merchant_id'=>'jostpay_merchant','key'=>''),
					'ussd_code'=>array('merchant_id'=>'ussd_code_details','key'=>'','textarea'=>'1'),
					'pay_on_delivery'=>array('merchant_id'=>'pay_on_delivery_note','key'=>'','textarea'=>'1'),
					'western_union'=>array('merchant_id'=>'western_union_note','key'=>'','textarea'=>'1'),
					'stanbic'=>array('merchant_id'=>'stanbic_merchant_ngn','uid'=>'stanbic_merchant_usd','key'=>''),
					'paypal'=>array('merchant_id'=>'paypal_email','key'=>''),
					'firstpay'=>array('merchant_id'=>'firstpay_merchant_id','key'=>''),
					'skye'=>array('merchant_id'=>'skye_merchant_id','key'=>''),
					'simplepay'=>array('merchant_id'=>'simplepay_username','key'=>''),
					
					'voguepay'=>array('merchant_id'=>'voguepay_merchant_id','key'=>''),
				);
			
			$this->debugging=($this->input->get('debug')!='');			
			//"2008/6/30";; "12/22/78", "1/17/2006", "1/17/6";; "2008-6-30", "78-12-22", "8-6-21" ;;"30-6-2008",
			//ISO:  "2008/06/30", "1978/12/22" ;;  "08-06-30", "78-12-22"
			
			$this->date_patern="^\s*([0-9]{2,4}[\-/][0-9]{1,2}[\-/][0-9]{1,2})|([0-9]{1,2}[\-/][0-9]{1,2}[\-/][0-9]{2,4})\s*$";
			$this->time_pattern="^\s*[0-9]{1,2}(:[0-9]{1,2})?(\s*[aApP][mM])?\s*$";
			$this->date_time_patern="^\s*(([0-9]{2,4}[\-/][0-9]{1,2}[\-/][0-9]{1,2})|([0-9]{1,2}[\-/][0-9]{1,2}[\-/][0-9]{2,4}))?\s*([0-9]{1,2}(:[0-9]{1,2})?(\s*[aApP][mM])?)?\s*$";
			
			$this->date_time_patern_php='~'.$this->date_time_patern.'~';
			$this->db->query("SET sql_mode=''");
		}
		
		
		function count_message_pages($text,$unicode=false){
			$len=mb_strlen($text,'UTF-8');
			if($len==0)return 0;
			if($unicode)return ceil($len/72);
			if($len<=160)return 1;
			return 1+ceil(($len-160)/145);
		}
		/*
Multi-Links
07027,0709

Starcomms
07028,07029,0819

Visafone
07025,07026,0704

0707 	ZoomMobile (formerly Reltel)

0804 	MTEL

Airtel Nigeria
0701x,0708,0802,0808,0812,0902 

Globacom
0705,0805,0807,0811,0815,0905

Etisalat Nigeria
0909,0908,0817,0818,0809


MTN Nigeria
0706,0703,0803 ,0806 ,0810 ,0816 ,0813 ,0814 ,0903 
*/
		
		function get_cgsms_coverage_cost($phone){
			if(strlen($phone)==13){ //handle nigeria here
				$temp_pref=substr($phone,0,6);
				if(in_array($temp_pref,array('234702','234704','234819','234709')))return 3;
				if(in_array($temp_pref,array('234706','234703,0803','234806','234810','234816','234813','234814','234903')))return 1.5;
				if(substr($phone,0,3)=='234')return 1;
			}

			if(empty($this->unique_coverage_list)){
				$result=$this->db->select_max('units')->select('dial_code')->group_by('dial_code')->get('coverage_list')->result();
				$records=array();
				foreach($result as $row)$records[$row->dial_code]=$row->units;
				$this->unique_coverage_list=$records;
			}

			for($i=4;$i>0;$i--){
				$substr=substr($phone,0,$i);
				if(isset($this->unique_coverage_list[$substr]))return $this->unique_coverage_list[$substr];
			}
			
			return 6; //arbitarily high value until determined
		}
		
		
		function get_api_errors(){
			$errors=array(
				
				1=>'sub_account not supplied',
				2=>'sub_account_pass not supplied',
				3=>'action not supplied',
				4=>'Incorrect sub_account name or password',
				5=>'The sub-account is currently disabled',
				6=>'Invalid action',
				7=>'missing request parameter',
				8=>'incorrect request parameter',
				9=>'confusing request parameters',
				10=>'no record found',
				11=>'operation failed'
				);
				
			return $errors;
		}
		
		
		function get_sms_design($status,$append='btn btn-'){
			$des=$this->sms_status[$status]['css'];
			return $append.$des;
		}
		
		function find_cgsms_status($s){
			$status=null;
			
			foreach($this->sms_status as $si=> $sms_status){
				if(!empty($sms_status['cgsms'])&&$sms_status['cgsms']==$s)
				{
					$status=$si;
					break;
				}
			}
			if($status===0)$status=1;
			return $status;
		}

		
		function get_prices($reseller_highest_price=0){
			$query=$this->db->order_by('price','desc')->get('prices');
			$result=$query->result_array();
			
			if(!empty($reseller_highest_price)){
				$new_result=array();
				$orig_min_units=-1;
				
				foreach($result as $row){
					if($orig_min_units==-1)$orig_min_units=$row['min_units'];
					if($row['price']>$reseller_highest_price)continue;
					if(empty($new_result)){
						$this->reseller_min_sales=$row['min_units'];
						$row['min_units']=$orig_min_units;
					}
					$new_result[]=$row;
				}
				$result=$new_result;
			}
			
			return $result;
		}
		function get_reseller_highest_price(){
			if(!isset($this->reseller_highest_price)){
				$this->reseller_highest_price=$this->get_config('reseller_highest_price');
			}
			return $this->reseller_highest_price;
		}
		
		function get_reseller_min_sales(){
			if(empty($this->reseller_min_sales))$this->get_prices(true);
			return $this->reseller_min_sales;
		}
		function get_reseller_surety_fee(){
			if(!isset($this->reseller_surety_fee)){
				$reseller_min_sales=$this->get_reseller_min_sales();
				$this->reseller_surety_fee=$reseller_min_sales/40; //half of sopposed profit
			}
			return $this->reseller_surety_fee;
		}
		
		

		function update_prices($new_prices){
			$this->db->where('price !=','')->delete('prices');
			$this->db->insert_batch('prices',$new_prices);
		}
		
		function sms_units_to_price($new_units,$currency_value=1,$is_reseller=false){	
			$new_amount=0;	
			
			$prices=$this->get_prices($is_reseller);	
			if(!is_numeric($new_units))$new_units=0;
			
			if($new_units>0){			
				foreach($prices as $price_name=>$price_data)
				{
					$temp=$new_units*$price_data['price']*$currency_value;
					if($new_amount==0)$new_amount=$temp;				
					if($new_units<$price_data['min_units'])break;
					$new_amount=$temp;
				}
			}

			return $new_amount;
		}
		
		function sms_price_to_units($new_amount,$currency_value=1,$prices=''){
			if($prices=='')$prices=$this->get_prices();
			if(!is_numeric($new_amount))$new_amount=0;
			$new_units=0;
		
			if($new_amount>0){			
				foreach($prices as $price_name=>$price_data)
				{
					
					$temp=floor($new_amount/($price_data['price']*$currency_value));
					if($new_units==0)$new_units=$temp;
					if($temp<$price_data['min_units'])break;
					$new_units=$temp;
				}
			}
			
			return $new_units;
		}
		

		function get_available_payment_methods($configs){
			$methods=array();

			foreach($this->payment_methods as $pm_key=>$pm_val){
				if(!empty($configs['currency_code']))
				{
					$currency_code=$configs['currency_code'];
					if($currency_code=='USD'&&($pm_key=='gtpay'||$pm_key=='interswitch'))continue;
					elseif($currency_code!='USD'&&$pm_key=='perfectmoney')continue;
					//elseif($currency_code!='BTC'&&$pm_key=='bitcoin')continue;
				}

				if(!empty($configs[$pm_key."_enabled"]))
				{
					$methods[$pm_key]=$pm_val;
				}
			}
			return $methods;
		}

		#################### LOGIN RELATED FUNCTIONS #############################
	
		function _is_allowed_crawler(){
			$pattern = '/(FacebookExternalHit|GoogleBot)/i';			
			return preg_match($pattern,$_SERVER['HTTP_USER_AGENT']);
		}
	
		function logged_in(){
			$login_data=$this->session->userdata('login_data');
			return empty($login_data['user_id'])?false:$login_data['user_id'];
		}

		public function admin_logged_in(){
			return ($this->session->userdata('admin_logged_in')==1);
		}

		public function log_admin_in(){			$this->session->set_userdata('admin_logged_in',1);
		}
		public function log_admin_out(){			$this->session->unset_userdata('admin_logged_in');
		}
		
		
		function set_login_cookie($login_id='',$login_key=''){
			//$accessRange="";
			$accessRange=".{$_SERVER['HTTP_HOST']}";
			setcookie("login_key",$login_key,time()+604800,"/",$accessRange);
			setcookie("login_id",$login_id,time()+604800,"/",$accessRange);
		}
		
		function unset_login_cookie(){ $this->set_login_cookie(); }
		
		function log_user_in($email,$password='',$raw_password=false){
			if($raw_password)$encpass=$password;
			else $encpass=md5($password);
			
			$email=strtolower($email);
			
			if(is_numeric($email)&&empty($password))$query=$this->db->where('user_id',$email)->limit(1)->get('users');
			else $query=$this->db->query('SELECT * FROM '._DB_PREFIX_.'users WHERE email=? AND (password=? OR temp_password=?) LIMIT 1',array($email,$encpass,$encpass));

			if($row=$query->row()){
				$user_id=$row->user_id;

				$login_data=array(
					'user_id'=>$user_id,
					'default_sender_id'=>$row->default_sender_id,
					'email'=>$row->email,'time'=>time(),
					'timezone_offset'=>$row->timezone_offset,
					'default_dial_code'=>$row->default_dial_code,
					'default_sender_id'=>$row->default_sender_id,
				);

				if($row->temp_password!='')
				{
					if($row->temp_password==$encpass)$login_data['must_reset_password']=true;
					else $this->db->where('user_id',$row->user_id)->update('users',array('temp_password'=>''));
				}
				
				if(date('Y-m-d')!=date('Y-m-d',$row->last_seen))
				{
					//$this->db->where('user_id',$row->user_id)->update('users',array('free_sms_sent'=>0));
				}
				
				if($row->email==$this->session->userdata('pending_login_email'))
				{
					$pending_facebook_user_id=$this->session->userdata('pending_facebook_user_id');
					if(!empty($pending_facebook_user_id))$this->db->where('user_id',$row->user_id)->update('users',array('facebook_user_id'=>$pending_facebook_user_id));
				}
				
				$this->db->where('user_id',$row->user_id)->update('users',array('last_seen'=>time()));
				$this->set_login_cookie($row->user_id,$row->password);
				$this->session->set_userdata('login_data',$login_data);
				return true;
			}
			return false;
		}
		
		
		function update_login_data($key,$value=''){
			$login_data=$this->session->userdata('login_data');
			if(empty($login_data))return '';
			if(is_array($key))$login_data=array_merge($login_data,$key);
			else $login_data[$key]=$value;
			$this->session->set_userdata('login_data',$login_data);
		}
		
		
		function get_login_data($key,$subkey=''){
			$login_data=$this->session->userdata('login_data');
			if(empty($login_data))return '';
			if(empty($login_data[$key]))return '';

			$dkey=$login_data[$key];
			if($subkey=='')return $dkey;

			if(empty($dkey[$subkey]))return '';
			return $dkey[$subkey];
		}

		function get_user_id(){return $this->get_login_data('user_id');}
		######################### END LOGIN RELATED FUNCTIONS ##################
	 
	   function format_message_input($message,$user='')
	   {
			$message=trim($message);
			if(!empty($user))return str_replace(array('[firstname]','[lastname]','[email]','[phone]'),array($user['firstname'],$user['lastname'],$user['email'],$user['phone']),$message);
			return $message;
	   }
	   
	   function add_plus($phone){ return '+'.$phone; }
	   function format_user_id($user_id){ return str_pad($user_id,3,'0',STR_PAD_LEFT); }
	   function format_sub_account($sub_account,$user_id){ return str_pad($user_id,3,'0',STR_PAD_LEFT)."_$sub_account"; }

	   
		####################### Bitcoin Payment Helper #######
		
	   function secure_btc_amount($btc){
		   $btc=$btc*1.055; //add 5.5% for correction
		   $temp=ceil($btc*10000)/10000;
		   $temp2=rand(100,999)/100000000;
		   return $temp+$temp2;
	   }
		
		
		function formatBTC($value,$add_code=false) {
			$value = sprintf('%.8f', $value);
			$value = rtrim($value, '0');
			if($add_code)return "$value BTC";
			return $value;
		}
		
		
		function shatoshi_to_btc($shatoshi){ return $shatoshi/100000000; }
		
		function btc_to_shatoshi($btc){ return $btc*100000000; }
		
		function is_duplicate_hash($hash){
			return $this->db->where('checksum',$hash)->limit(1)->from('transactions')->count_all_results();
		}
		
	   
	
		   
	   function get_blockchain_confirmations($block_height){
		   
		   if(is_array($block_height)){
			   if(!empty($block_height['lock_time']))$block_height=$block_height['lock_time'];
			   else $block_height=empty($block_height['block_height'])?0:$block_height['block_height'];
		   }
		   
		   if(empty($block_height))return 0;
			if(!isset($this->current_blockchain_height)){
				$blockheight_checked_time=$this->get_config('blockheight_checked_time');
				
				$cbh=$this->get_config('current_blockchain_height');
				$time=time();
				$offset=$time-($blockheight_checked_time*1);
				if($offset>600||!empty($cbh))
				{
					$temp=$this->_curl_json('https://blockchain.info/q/getblockcount');
										
					if(empty($temp['error']))
					{
						$val=$temp['response']*1;
						
						if(!empty($val)){
							$time=time();
							$this->update_config('current_blockchain_height',$val);
							$this->update_config('blockheight_checked_time',$time);
							if(!empty($this->configs)){
								$this->configs['current_blockchain_height']=$val;
								$this->configs['blockheight_checked_time']=$time;
							}
							$this->current_blockchain_height=$val;
							return $val;
						}
						
					}
				}
				$cbh=$this->get_config('current_blockchain_height');
				if(!empty($cbh))$this->current_blockchain_height=$cbh;
			}
			
			if(isset($this->current_blockchain_height)){
				return $this->current_blockchain_height - $block_height + 1;
			}
			
			return 0;
	   }
	
	###################################  CURRENCIES #################
		
		
		
		
		function replace_currencies($currencies){
			$this->db->query('TRUNCATE '._DB_PREFIX_.'currencies');
			$this->db->insert_batch('currencies',$currencies);		
		}
		
		function get_currencies($nocache=false){
			if(!$nocache&&!empty($this->currencies))return $this->currencies;
			$query=$this->db->get('currencies');
			$records=array();
			foreach($query->result_array() as $row)$records[$row['currency']]=$row;
			$this->currencies=$records;
			return $records;
		}
		

		function admin_filter_recipients($filters,$field_key='',$index_field='user_id'){
			if(!empty($filters['country_id']))$this->db->where('country_id',$filters['country_id']);
			
			if($field_key!='')$this->db->select($field_key.',user_id');

			$results=$this->db->get('users')->result_array();
			$records=array();

			foreach($results as $row){
				$user_id=$row['user_id'];
				$val=($field_key=='')?$row:$row[$field_key];
				
				if($index_field=='')$records[]=$val;
				else $records[$user_id]=$val;
			}

			return $records;
		}
		
		
		############################ CONFIGS FUNCTIONS ################

	
		function get_config($key){
			if(empty($this->configs))$this->get_configs();
			if(isset($this->configs[$key]))return $this->configs[$key];
			return "";
		}

		function get_configs($keys=''){
			if(!empty($keys)){
				$keys=explode(',',$keys);
				$this->db->where_in('config_name',$keys);
			}
			$query=$this->db->get('website_configuration');
			$results=array();
			foreach($query->result() as $row)$results[$row->config_name]=$row->config_value;
			if(isset($results['reseller_highest_price']))$this->reseller_highest_price=$results['reseller_highest_price'];
			if(!defined('_CURRENCY_CODE_'))$currency_code='NGN'; else $currency_code=_CURRENCY_CODE_;
			
			$results['currency_code']=$currency_code;
			if(empty($results['site_name']))$results['site_name']=$_SERVER['HTTP_HOST'];
			$this->configs=$results;
			return $results;
		}
		
		
		function update_config($key,$value){
			$num=$this->db->where('config_name',$key)->from('website_configuration')->count_all_results();
			if($num==0)$this->db->insert('website_configuration',array('config_name'=>$key,'config_value'=>$value));
			else $this->db->where('config_name',$key)->update('website_configuration',array('config_value'=>$value));
		}


		function update_configs($settings){
			foreach($settings as $key=>$value){
				$batch[]=array('config_name'=>$key,'config_value'=>$value);
			}
			$this->safe_update_batch('website_configuration',$batch,'config_name');
		}
		
		function get_users($only_count=false,$filter=''){
			if(!empty($filter['user_id']))$this->db->where('user_id',$filter['user_id']);
			if(!empty($filter['country']))$this->db->where('country_id',$filter['country']);
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'firstname,lastname,email,default_sender_id',false,true);
				if(!empty($like_query))
				{
					$like_query="($like_query)";
					$this->db->where($like_query,null,true);
				}
			}
			
			$this->db->from('users');			
			if($only_count)return $this->db->count_all_results();
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			
			if(empty($filter['order_by']))$filter['order_by']='last_seen';
			if(in_array($filter['order_by'],array('balance','last_seen','email','user_id')))$this->db->order_by($filter['order_by'],'desc');

			return $this->db->get()->result_array();
		}
		
		function get_assoc($array,$index='',$key=''){
			$records=array();
			$this_index='';
			
			foreach($array as $row){
				if($index!='')$this_index=@$row[$index];
				if($key!='')$row=@$row[$key];
					
				if($index!='')$records[$this_index]=$row;
				else $records[]=$row;	
			}
			return $records;
		}
		
		function get_contact($contact_id,$user_id,$sub_account_id=0){
			$query=$this->db->where('contact_id',$contact_id)->where('user_id',$user_id)->where('sub_account_id',$sub_account_id)->limit(1)->get('contacts');
			return $query->row_array();
		}
		
		function get_group_contacts($groups,$user_id,$sub_account_id=0,$indexed=true){
			if(!is_array($groups)){
				$groups=trim($groups,' ,');
				$groups=explode(',',$groups);
			}
			$result=$this->db->where_in('group_name',$groups)->where('user_id',$user_id)->where('sub_account_id',$sub_account_id)->get('contacts')->result_array();
			
			if(!$indexed)return $result;
			$records=array();
			
			foreach($result as $row)$records[$row['phone']]=$row;
			return $records;
		}
		
		function delete_contact($contact_ids,$user_id,$sub_account_id=0){
			$query=$this->db->where('contact_id',$contact_ids)->limit(1)->get('contacts');
			$contact= $query->row_array();
			if(empty($contact))return "Contact record doesn't exist.";
			if($contact['user_id']!=$user_id||$contact['sub_account_id']!=$sub_account_id)return "You do not have the permission to delete this contact.";
			$this->db->where('contact_id',$contact_ids)->limit(1)->delete('contacts');
			return true;			
		}
		
		function get_contacts_groups($user_id,$sub_account_id=0){
			$query=$this->db->query("SELECT group_name,COUNT(phone) total_numbers FROM "._DB_PREFIX_."contacts WHERE user_id=? AND sub_account_id=? GROUP BY group_name ",array($user_id,$sub_account_id));
			$records=$query->result_array();
			return $this->get_assoc($records,'group_name','total_numbers');
		}
		
		function delete_contacts($filter){
			if(empty($filter['user_id'])&&empty($filter['sub_account_id']))return 'No contact owner specified.';
			
			$where=array();
			$where['sub_account_id']=isset($filter['sub_account_id'])?$filter['sub_account_id']:0;			
			if(!empty($filter['user_id']))$where['user_id']=$filter['user_id'];
			if(!empty($filter['group_name']))$where['group_name']=$filter['group_name'];
			
			$this->db->where($where);
			
			if(!empty($filter['contact_ids'])){
				if(!is_array($filter['contact_ids']))$filter['contact_ids']=explode(',',$filter['contact_ids']);
				$this->db->where_in('contact_id',$filter['contact_ids']);
			}
			
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'phone,firstname,lastname',false,true);
				if(!empty($like_query))$like_query="($like_query)";
				$this->db->where($like_query,null,true);
			}
			
			$num=$this->db->from('contacts')->count_all_results();
			
			if($num>0){
				$this->db->where($where);
				if(!empty($filter['contact_ids']))$this->db->where_in('contact_id',$filter['contact_ids']);
				if(!empty($like_query))$this->db->where($like_query,null,true);
				$this->db->delete('contacts');
			}
			
			return $num;
		}
		
		function update_coverage_list($coverage){
			if(empty($coverage))return;
			$this->db->query('TRUNCATE TABLE '._DB_PREFIX_.'coverage_list');
			$this->db->insert_batch('coverage_list',$coverage);
		}
		
		function get_coverage_list($filter=''){
			if(!empty($filter)&&is_array($filter)){
				if(!empty($filter['country_code']))$this->db->where('country_code',$filter['country_code']);
				if(!empty($filter['prefix']))$this->db->where('dial_code',$filter['prefix']);
				if(!empty($filter['units']))$this->db->where('units >',$filter['units'])->where('units <=',$filter['units']);
				if(!empty($filter['continent']))$this->db->where('continent',$filter['continent']);
			}
			
			return $this->db->order_by('country')->order_by('units')->get('coverage_list')->result_array();
		}
		
		function get_coverage_countries(){
			$results=$this->db->group_by('country_code')->select('country,country_code')->order_by(
			'country')->get('coverage_list')->result();
			$records=array();
			foreach($results as $row)$records[$row->country_code]=$row->country;
			return $records;
		}
		
		function get_contacts($only_count=false,$filter=''){
			$this->db->from('contacts');
			if(!isset($filter['sub_account_id']))$filter['sub_account_id']=0;
			
			if(!empty($filter['user_id']))$this->db->where('user_id',$filter['user_id']);
			$this->db->where('sub_account_id',$filter['sub_account_id']);
			if(!empty($filter['group_name']))$this->db->where('group_name',$filter['group_name']);			
			if(!empty($filter['contact_ids']))			{				if(is_array($filter['contact_ids']))$filter['contact_ids']=implode(',',$filter['contact_ids']);				$this->db->where('contact_id',$filter['contact_ids']);			}
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query(trim($filter['search_term'],' +0'),'phone,firstname,lastname',false,true);
				if(!empty($like_query))$like_query="($like_query)";
				$this->db->where($like_query,null,true);
			}
			
			if($only_count)return $this->db->count_all_results();
			$this->db->order_by('firstname')->order_by('phone');
			if(empty($filter['offset']))$filter['offset']=0;
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			return $this->db->get()->result_array();
		}
		
		function save_contacts($contacts){
			$this->db->insert_batch('contacts',$contacts);
			$this->db_delete_duplicates('contacts','contact_id','user_id,phone,group_name,sub_account_id');			
		}
		
		function db_delete_duplicates($table,$id_field,$key_fields,$delete_latest=true){
			$sign=$delete_latest?'<':'>';
			
			$sql="DELETE
				FROM "._DB_PREFIX_."$table USING "._DB_PREFIX_."$table,
					"._DB_PREFIX_."$table t1
				where "._DB_PREFIX_."$table.$id_field $sign t1.$id_field ";
				
			if(is_string($key_fields))$key_fields=explode(',',$key_fields);
			foreach($key_fields as $kf)$sql.=" AND "._DB_PREFIX_."$table.$kf = t1.$kf ";

			return $this->db->query($sql);
		}
		
		function get_sms_log($only_count=false,$filter=''){
			$this->db->from('sms_log');
			if(!isset($filter['deleted']))$this->db->where('deleted',0);
			elseif(isset($filter['deleted'])&&$filter['deleted']!=2)$this->db->where('deleted',$filter['deleted']);
						
			$where=array();
			if(isset($filter['sub_account_id'])&&is_numeric($filter['sub_account_id']))$where['sms_log.sub_account_id']=$filter['sub_account_id'];
			if(!isset($where['sms_log.sub_account_id'])&&isset($filter['user_id'])&&is_numeric($filter['user_id']))$where['sms_log.user_id']=$filter['user_id'];
			if(!empty($where))$this->db->where($where);
			
			if(isset($filter['s'])&&is_numeric($filter['s']))$this->db->where('sms_log.status',$filter['s']);
			elseif(!empty($filter['stage'])){
				if($filter['stage']=='pending')$this->db->where('status',0);
				elseif($filter['stage']=='sent')$this->db->where('status >',0);
				elseif($filter['stage']=='failed')$this->db->where('status <',0); //-1 occured on cgsms itself
			}
			
			if(!empty($filter['recipient']))$this->db->where('recipient',$filter['recipient']);
			if(!empty($filter['sender_id']))$this->db->where('sender',$filter['sender_id']);

			if(!empty($filter['type']))$this->db->where('type',$filter['type']);
			
			if(!empty($filter['search_term'])&&$this->valid_sms_batch_id(trim($filter['search_term']))){
				$filter['batch_id']=trim($filter['search_term']);
				unset($filter['search_term']);				
			}
			
			if(!empty($filter['batch_id']))$this->db->where('batch_id',$filter['batch_id']);
			if(!empty($filter['sms_id']))$this->db->where('sms_id',$filter['sms_id']);
			if(!empty($filter['sms_ids'])){
				if(!is_array($filter['sms_ids']))$filter['sms_ids']=explode(',',$filter['sms_ids']);
				$this->db->where_in('sms_id',$filter['sms_ids']);
			}
			
			if(!empty($filter['start_date']))$this->db->where(_DB_PREFIX_.'sms_log.time_submitted>=',strtotime($filter['start_date']),false);
			if(!empty($filter['end_date']))$this->db->where(_DB_PREFIX_.'sms_log.time_submitted<=',strtotime($filter['end_date'])+86399,false);
				
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'message,recipient,sender',false,true);
				if(!empty($like_query))$like_query="($like_query)";
				$this->db->where($like_query,null,true);
			}
			
			if($only_count)return $this->db->count_all_results();
			
			$this->db->select('sms_log.*,contacts.firstname,contacts.lastname,contacts.group_name');
			
			//BUG: condeigniter doesn't add prefix after join's AND.
			$this->db->join('contacts','contacts.phone=sms_log.recipient AND '._DB_PREFIX_.'contacts.sub_account_id='._DB_PREFIX_.'sms_log.sub_account_id AND '._DB_PREFIX_.'contacts.user_id='._DB_PREFIX_.'sms_log.user_id AND ( '._DB_PREFIX_.'contacts.firstname!="" OR '._DB_PREFIX_.'contacts.firstname!="") ','left')->group_by('sms_log.sms_id');
			
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);			
			return $this->db->order_by('sms_id','DESC')->get()->result_array();
		}
		
		function stop_sms_log($filter,$action='stopped'){ return $this->run_batch_action($filter,$action); }
		
		function run_batch_action($filter,$action='stopped'){
			$where=array('deleted'=>0);			
			if(isset($filter['sub_account_id'])&&is_numeric($filter['sub_account_id']))$where['sub_account_id']=$filter['sub_account_id'];
			if(!isset($where['sub_account_id'])&&isset($filter['user_id'])&&is_numeric($filter['user_id']))$where['user_id']=$filter['user_id'];
			
			
			if(isset($filter['s'])&&is_numeric($filter['s']))$where['status']=$filter['s'];
			elseif(!empty($filter['stage'])){
				if($filter['stage']=='pending')$where['status']=0;
				elseif($filter['stage']=='sent')$where['status >']=0;
				elseif($filter['stage']=='failed')$where['status <']=0;
			}
			
			if(!empty($filter['sms_id']))$where['sms_id']=$filter['sms_id'];
			if(!empty($filter['recipient']))$where['recipient']=$filter['recipient'];
			if(!empty($filter['sender_id']))$where['sender']=$filter['sender_id'];
			if(!empty($filter['type']))$where['type']=$filter['type'];
			
			if(!empty($filter['search_term'])&&$this->valid_sms_batch_id(trim($filter['search_term']))){
				$filter['batch_id']=trim($filter['search_term']);
				unset($filter['search_term']);				
			}
			
			if(!empty($filter['batch_id']))$where['batch_id']=$filter['batch_id'];
			
			
			if($action!='get_total_units')$where['locked']=0;
			if($action=='stopped')$where['status']=0;
			
			$this->db->from('sms_log')->where($where);
			
			if(!empty($filter['sms_ids'])){
				if(!is_array($filter['sms_ids']))$filter['sms_ids']=explode(',',$filter['sms_ids']);
				$this->db->where_in('sms_id',$filter['sms_ids']);
			}
			if(!empty($filter['start_date']))$this->db->where('time_submitted>=',strtotime($filter['start_date']),false);
			if(!empty($filter['end_date']))$this->db->where('time_submitted<=',strtotime($filter['end_date'])+86399,false);
				
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'message,recipient,sender',false,true);
				if(!empty($like_query))
				{
					$like_query="($like_query)";
					$this->db->where($like_query,null,true);
				}
			}
			
			if($action=='get_total_units'){
				$this->db->select("SUM(units) as total_units,COUNT(sms_id) AS total_sms",false); 
				$row=$this->db->get()->row();
				$resp=array('total_units'=>(int)$row->total_units,'total'=>(int)$row->total_sms);
				return $resp;
			}
			
			$total=$this->db->count_all_results();
			if(empty($total))return array('total'=>0);
			
			$this->db->where($where);
			if(!empty($filter['sms_ids']))$this->db->where_in('sms_id',$filter['sms_ids']);
			if(!empty($filter['start_date']))$this->db->where('time_submitted>=',strtotime($filter['start_date']),false);
			if(!empty($filter['end_date']))$this->db->where('time_submitted<=',strtotime($filter['end_date'])+86399,false);
			if(!empty($like_query))$this->db->where($like_query,null,true);

			if($action=='deleted')$this->db->update('sms_log',array('deleted'=>1));
			else $this->db->update('sms_log',array('status'=>-1,'info'=>'Message stopped'));
			
			return array('total'=>$total);
		}
		
		function get_total_user_balance(){
			$row=$this->db->select_sum('balance','total_balance')->get('users')->row();
			$row2=$this->db->select_sum('balance','total_balance')->get('sub_accounts')->row();
			$row3=$this->db->where('status',0)->where('deleted','0')->select_sum('units','total_balance')->get('sms_log')->row();
			return $row->total_balance+$row2->total_balance+$row3->total_balance;
		}
		
		
		################ Logical CPS Groups Functions #############
			
		function get_logical_cps_groups($only_count=false,$filter=''){
			$logical_cps_groups=array();
			$this->db->from('logical_cps_groups');				
			
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'logical_cps_group,question_who,question_what',false,true);
				if(!empty($like_query))$this->db->where("($like_query)",null,true);
			}
			if($only_count)return $this->db->count_all_results();
			$this->db->order_by('logical_cps_group','desc')->order_by('logical_cps_group_id');
			
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			$query=$this->db->get();
			foreach($query->result() as $row)$logical_cps_groups[$row->logical_cps_group_id]=(array)$row;
			return $logical_cps_groups;		
			
		}
		
		function get_logical_cps_group($logical_cps_group_id,$field='logical_cps_group_id'){
			return $this->db->where($field,$logical_cps_group_id)->limit(1)->get('logical_cps_groups')->row_array();
		}
		
		function delete_logical_cps_group($logical_cps_group_id){ $this->db->where('logical_cps_group_id',$logical_cps_group_id)->limit(1)->delete('logical_cps_groups'); }		
		
		function add_logical_cps_group($logical_cps_group_data){ $this->db->insert('logical_cps_groups',$logical_cps_group_data); }
		function update_logical_cps_group($logical_cps_group_data,$logical_cps_group_id){ $this->db->where('logical_cps_group_id',$logical_cps_group_id)->update('logical_cps_groups',$logical_cps_group_data); }	
	
		
		################ Logical CPS Functions #############
		
		function get_logical_cpss($only_count=false,$filter=''){
			$logical_cpss=array();
			$this->db->from('logical_cpss');
			
			if(!empty($filter['lcps_ids'])){
				if(!is_array($filter['lcps_ids']))$filter['lcps_ids']=explode(',',$filter['lcps_ids']);
				$this->db->where_in('logical_cps_id',$filter['lcps_ids']);
			}
			
			
			if($only_count)return $this->db->count_all_results();
			
			if(!empty($filter['join_categories']))$this->db->join('sms_categories','sms_categories.sms_category_id=logical_cpss.sms_category_id')->select('logical_cpss.*,sms_categories.sms_category');
			$this->db->order_by('logical_cps','desc')->order_by('logical_cps_id');
			
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			$query=$this->db->get();
			foreach($query->result() as $row)$logical_cpss[$row->logical_cps_id]=(array)$row;
			return $logical_cpss;		
			
		}
		
		function get_logical_cps($logical_cps_id,$field='logical_cps_id'){
			return $this->db->where($field,$logical_cps_id)->limit(1)->get('logical_cpss')->row_array();
		}
		
		function delete_logical_cps($logical_cps_id){ $this->db->where('logical_cps_id',$logical_cps_id)->limit(1)->delete('logical_cpss'); }		
		
		function add_logical_cps($logical_cps_data){ $this->db->insert('logical_cpss',$logical_cps_data); }
		function update_logical_cps($logical_cps_data,$logical_cps_id){ $this->db->where('logical_cps_id',$logical_cps_id)->update('logical_cpss',$logical_cps_data); }	
		


		################ SMS Categories Functions #############
		
		function sms_category_has_templates($sms_category_id){ return $this->db->from('sms_templates')->where('sms_category_id',$sms_category_id)->limit(1)->count_all_results(); }
		
		function get_sms_categories($only_count=false,$filter=''){
			$sms_categories=array();
			$this->db->from('sms_categories');				
			
			$where2=array();
			if(isset($filter['sub_account_id'])&&is_numeric($filter['sub_account_id']))$where2['sub_account_id']=$filter['sub_account_id'];
			if(!isset($where2['sub_account_id'])&&isset($filter['user_id'])&&is_numeric($filter['user_id']))$where2['user_id']=$filter['user_id'];
			
			if(!empty($filter['local'])&&!empty($where2))$this->db->where($where2);
			elseif(empty($where2))$this->db->where(array('user_id'=>0,'sub_account_id'=>0));
			else
			{
				if(isset($where2['user_id']))$where_str[]="user_id='".addslashes($where2['user_id'])."'";
				if(isset($where2['sub_account_id']))$where_str[]="sub_account_id='".addslashes($where2['sub_account_id'])."'";
				$where_str=implode(' AND ',$where_str);
				
				$this->db->where("((user_id=0 AND sub_account_id=0) OR ($where_str) )",null,false);
			}
			
			if($only_count)return $this->db->count_all_results();
			
			$this->db->order_by('user_id','desc')->order_by('sms_category');
			
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			$query=$this->db->get();
			foreach($query->result() as $row)$sms_categories[$row->sms_category_id]=(array)$row;
			return $sms_categories;
		}
		
		function get_sms_category($sms_category_id,$user_id=false,$sub_account_id=false,$field='sms_category_id'){
			if($sub_account_id!==false)$this->db->where('sub_account_id',$sub_account_id);
			elseif($user_id!==false)$this->db->where('user_id',$user_id);
			return $this->db->where($field,$sms_category_id)->limit(1)->get('sms_categories')->row_array();
		}
		
		function delete_sms_category($sms_category_id){ $this->db->where('sms_category_id',$sms_category_id)->limit(1)->delete('sms_categories'); }		
		
		function add_sms_category($sms_category_data){ $this->db->insert('sms_categories',$sms_category_data); }
		function update_sms_category($sms_category_data,$sms_category_id){ $this->db->where('sms_category_id',$sms_category_id)->update('sms_categories',$sms_category_data); }	
		

		
		################ SMS Templates Functions #############
		
		function sms_template_has_schedules($sms_template_id){ return $this->db->from('cpss')->where('sms_template_id',$sms_template_id)->limit(1)->count_all_results(); }
		
		function get_sms_templates($only_count=false,$filter=''){
			$sms_templates=array();
			$this->db->from('sms_templates');
			
			if(!empty($filter['sms_template_id'])&&is_numeric($filter['sms_template_id']))$this->db->where('sms_template_id',$filter['sms_template_id']);
			else
			{
				$where2=array();
				if(isset($filter['sub_account_id'])&&is_numeric($filter['sub_account_id']))$where2['sub_account_id']=$filter['sub_account_id'];
				if(empty($where2['sub_account_id'])&&isset($filter['user_id'])&&is_numeric($filter['user_id']))$where2['user_id']=$filter['user_id'];
				
				if(!empty($filter['local'])&&!empty($where2))$this->db->where($where2);
				elseif(empty($where2))$this->db->where(array('user_id'=>0,'sub_account_id'=>0));
				else
				{
					if(isset($where2['user_id']))$where_str[]="user_id='".addslashes($where2['user_id'])."'";
					if(isset($where2['sub_account_id']))$where_str[]="sub_account_id='".addslashes($where2['sub_account_id'])."'";
					$where_str=implode(' AND ',$where_str);
					
					$this->db->where("((user_id=0 AND sub_account_id=0) OR ($where_str) )",null,false);
				}
				
				if(isset($filter['sms_category_id'])&&is_numeric($filter['sms_category_id']))$this->db->where('sms_category_id',$filter['sms_category_id']);
				
				if(!empty($filter['search_term'])){
					$like_query=$this->generate_like_query($filter['search_term'],'sms_template',false,true);
					if(!empty($like_query))
					{
						$like_query="($like_query)";
						$this->db->where($like_query,null,true);
					}
				}
			}
			
			if($only_count)return $this->db->count_all_results();
			$this->db->order_by('user_id','desc')->order_by('sms_template');
			
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			$query=$this->db->get();
			
			
			foreach($query->result() as $row)$sms_templates[$row->sms_template_id]=(array)$row;
			return $sms_templates;		
		}
		
		function get_sms_template($sms_template_id,$user_id=false,$sub_account_id=false,$field='sms_template_id'){
			if($sub_account_id!==false)$this->db->where('sub_account_id',$sub_account_id);
			elseif($user_id!==false)$this->db->where('user_id',$user_id);
			return $this->db->where($field,$sms_template_id)->limit(1)->get('sms_templates')->row_array();
		}
		
		function get_sms_template_message($sms_template_id,$user_id=false,$sub_account_id=false){
			if($user_id!==false)$this->db->where('user_id',$user_id);
			if($sub_account_id!==false)$this->db->where('sub_account_id',$sub_account_id);
			return $this->db->where('sms_template_id',$sms_template_id)->limit(1)->select('sms_template')->get('sms_templates')->row('sms_template');
		}
		
		function delete_sms_template($sms_template_id){ $this->db->where('sms_template_id',$sms_template_id)->limit(1)->delete('sms_templates'); }		
		
		function delete_sms_templates($filters){
			$where=array();
			if(isset($filters['user_id']))$where['user_id']=$filters['user_id'];
			if(isset($filters['sub_account_id']))$where['sub_account_id']=$filters['sub_account_id'];
			if(empty($where))return 0;
			
			if(!empty($filters['sms_template_ids'])){
				if(is_string($filters['sms_template_ids']))$filters['sms_template_ids']=explode(',',$filters['sms_template_ids']);
				$this->db->where_in('sms_template_id',$filters['sms_template_ids']);
			}
			
			$num=$this->db->from('sms_templates')->where($where)->count_all_results();

			if($num){
				if(!empty($filters['sms_template_ids']))$this->db->where_in('sms_template_id',$filters['sms_template_ids']);
				$this->db->where($where)->delete('sms_templates'); 
			}
			return $num;
		}
		
		function delete_sms_categories($filters){
			$where=array();
			if(isset($filters['user_id']))$where['user_id']=$filters['user_id'];
			if(isset($filters['sub_account_id']))$where['sub_account_id']=$filters['sub_account_id'];
			if(empty($where))return 0;
			
			if(!empty($filters['sms_category_ids'])){
				if(is_string($filters['sms_category_ids']))$filters['sms_category_ids']=explode(',',$filters['sms_category_ids']);
				$this->db->where_in('sms_category_id',$filters['sms_category_ids']);
			}
			
			$num=$this->db->from('sms_categories')->where($where)->count_all_results();
			
			if($num){
				if(!empty($filters['sms_category_ids']))$this->db->where_in('sms_category_id',$filters['sms_category_ids']);
				$this->db->where($where)->delete('sms_categories'); 
			}
			return $num;
		}		
		
		function submit_sms_template_batch($sms_template_batch){ $this->db->insert_batch('sms_templates',$sms_template_batch); }
		function add_sms_template($sms_template_data){ return $this->db->insert('sms_templates',$sms_template_data); }
		function update_sms_template($sms_template_data,$sms_template_id){ $this->db->where('sms_template_id',$sms_template_id)->update('sms_templates',$sms_template_data); }	
		

		
		
		################ Periodic SMS Functions #############
		
		function submit_cps_batch($cps_batch){return $this->db->insert_batch('cpss',$cps_batch); }
				
		function get_cpss($only_count=false,$filter=''){
			$cpss=array();
		
			if(!empty($filter['user_id']))$this->db->where('cpss.user_id',$filter['user_id']);
			if(isset($filter['sub_account_id']))$this->db->where('cpss.sub_account_id',$filter['sub_account_id']);
			if(!empty($filter['batch_id']))$this->db->where('cpss.cps_batch_id',trim($filter['batch_id']));
			
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],_DB_PREFIX_.'cpss.firstname,'._DB_PREFIX_.'cpss.lastname,'._DB_PREFIX_.'cpss.phone,'._DB_PREFIX_.'cpss.group_name',true,true);
				if(!empty($like_query))
				{
					$like_query="($like_query)";
					$this->db->where($like_query,null,true);
				}
			}
			
			if(isset($filter['cps_status'])&&is_numeric($filter['cps_status']))$this->db->where('cpss.cps_status',$filter['cps_status']);
			
			if(isset($filter['sms_category_id'])&&is_numeric($filter['sms_category_id']))$this->db->where('cpss.sms_category_id',$filter['sms_category_id']);
			
			if(!empty($filter['cps_ids'])){
				if(!is_array($filter['cps_ids']))$filter['cps_ids']=explode(',',$filter['cps_ids']);
				$this->db->where_in('cps_id',$filter['cps_ids']);
			}
			
			if(!empty($filter['result_action'])&&in_array($filter['result_action'],array('pause','stop','activate','delete'))){
				if($filter['result_action']=='delete')$this->db->delete('cpss');
				else {
					if($filter['result_action']=='pause')$new_status=0;
					elseif($filter['result_action']=='activate')$new_status=1;
					else $new_status=-1;
					
					$this->db->update('cpss',array('cps_status'=>$new_status));
				}

				return true;
			}
			
			$this->db->from('cpss');
			if($only_count)return $this->db->count_all_results();
			
			if(!empty($filter['join_template']))$this->db->select('cpss.*,sms_templates.sms_template')->join('sms_templates','cpss.sms_template_id=sms_templates.sms_template_id','left');
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			return $this->db->order_by('cps_id','desc')->get()->result_array();
		}
		
		function get_cps($cps_id,$user_id=false,$sub_account_id=false,$field='cps_id'){
			if($sub_account_id!==false)$this->db->where('sub_account_id',$sub_account_id);
			elseif($user_id!==false)$this->db->where('user_id',$user_id);
			$row= $this->db->where($field,$cps_id)->limit(1)->get('cpss')->row_array();
			return $row;
		}
		
		function delete_cps($cps_id){ $this->db->where('cps_id',$cps_id)->limit(1)->delete('cpss'); }		
		
		function add_cps($cps_data){ $this->db->insert('cpss',$cps_data); }
		

		
		
		################ Sub Account Functions #############
		
		function has_sub_account($user_id){ return $this->db->from('sub_accounts')->where('user_id',$user_id)->limit(1)->count_all_results(); }
		
		function get_sub_accounts($only_count=false,$filter=''){
			$sub_accounts=array();
			$this->db->from('sub_accounts');	
			if(!empty($filter['user_id']))$this->db->where('user_id',$filter['user_id']);
			if($only_count)return $this->db->count_all_results();
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			$query=$this->db->get();
			foreach($query->result() as $row)$sub_accounts[$row->sub_account_id]=(array)$row;
			return $sub_accounts;		
			
		}
		
		function get_sub_account($sub_account_id,$user_id=false,$field='sub_account_id'){
			if($user_id!==false)$this->db->where('user_id',$user_id);
			return $this->db->where($field,$sub_account_id)->limit(1)->get('sub_accounts')->row_array();
		}
		
		function delete_sub_account($sub_account_id){ $this->db->where('sub_account_id',$sub_account_id)->limit(1)->delete('sub_accounts'); }		
		function add_sub_account($sub_account_data){ $this->db->insert('sub_accounts',$sub_account_data); }
		function update_sub_account($sub_account_data,$sub_account_id){ $this->db->where('sub_account_id',$sub_account_id)->update('sub_accounts',$sub_account_data); }		
		
		function api_get_sub_account($sub,$pass){
			$subs=explode('_',$sub);
			if(!is_numeric($subs[0])||count($subs)!=2)return false;
			
			$subs[0]=ltrim($subs[0],'0');
			
			return $this->db->limit(1)->where(array('user_id'=>$subs[0],'sub_account'=>$subs[1],'sub_account_password'=>$pass))->get('sub_accounts')->row_array();
		}
		
		
		################ Countries & States #############

		function get_countries($simple_name_value=true){
			$query=$this->db->order_by('country','asc')->get('countries');
			$records=array();
			if($simple_name_value)foreach($query->result() as $row)$records[$row->country_id]=$row->country;
			else foreach($query->result() as $row)$records[$row->country_id]=(array)$row;
			return $records;
		}


		function get_states($country_id,$only_names=false){
			$this->db->select('state_id, state');
			if($country_id!=-1)$this->db->where("country_id",$country_id);

			$query=$this->db->order_by("state", "asc")->get("states");
			$states=array();
			foreach($query->result() as $row){
				if($only_names)$states[$row->state_id]=$row->state;
				else $states[]=$row;
			}
			return $states;
		}


		######################### UTILITIES #####################################
		
		function get_ordinal($i,$return_string=true){			
			if($i==1||($i>20&&$i%10==1))$ord='st';
			elseif($i==2||($i>20&&$i%10==2))$ord='nd';
			elseif($i==3||($i>20&&$i%10==3))$ord='rd';
			else $ord='th';
			return $return_string?$i.$ord:$ord;
		}
		
		
		function get_month_str($m,$type='l'){
			if($m<1||$m>12)return '';
			$months=array(
				1=>array('s'=>'jan','l'=>'january'),
				2=>array('s'=>'feb','l'=>'feburary'),
				3=>array('s'=>'mar','l'=>'march'),
				4=>array('s'=>'apr','l'=>'april'),
				5=>array('s'=>'may','l'=>'may'),
				6=>array('s'=>'jun','l'=>'june'),
				7=>array('s'=>'jul','l'=>'july'),
				8=>array('s'=>'aug','l'=>'august'),
				9=>array('s'=>'sep','l'=>'september'),
				10=>array('s'=>'oct','l'=>'october'),
				11=>array('s'=>'nov','l'=>'november'),
				12=>array('s'=>'dec','l'=>'december'),
			);
			
			return $months[$m][$type];
		}
		
		function get_weekday_str($d,$type='l'){
			if($d<1||$d>7)return '';
			$days=array(
				1=>array('s'=>'sun','l'=>'sunday'),
				2=>array('s'=>'mon','l'=>'monday'),
				3=>array('s'=>'tue','l'=>'tuesday'),
				4=>array('s'=>'wed','l'=>'wednesday'),
				5=>array('s'=>'thu','l'=>'thursday'),
				6=>array('s'=>'fri','l'=>'friday'),
				7=>array('s'=>'sat','l'=>'saturday'),
			);
			
			return $days[$d][$type];
		}
		
		
		//if not exists, insert; else update
		function safe_update_batch($table_name,$records,$filter_field){
			$filters=array();
			
			foreach($records as $record){
				if(!empty($record[$filter_field]))$filters[]=$record[$filter_field];				
			}
			
			if(empty($filters))$found_fields=array();
			else{
				$this->db->query("SET SESSION group_concat_max_len=10000000");
				$query=$this->db->select("GROUP_CONCAT($filter_field) AS existing_keys",FALSE)->where_in($filter_field, $filters)->get($table_name);
				$row=$query->row(); //echo "<div style='word-break:break-all;'>found: ".$row->existing_keys."</div>";
				$found_fields=explode(',',$row->existing_keys);
			}
			
			$insert_batch=array();
			$update_batch=array();
			
			foreach($records as $record){
				if(!empty($record[$filter_field])&&in_array($record[$filter_field],$found_fields))$update_batch[]=$record;
				else $insert_batch[]=$record;
			}
			
			if(!empty($insert_batch))$this->db->insert_batch($table_name,$insert_batch);
			if(!empty($update_batch))$this->db->update_batch($table_name,$update_batch,$filter_field);
		}
		
		function signup($record){
			$this->db->where('email',$record['email'])->delete('pending_email_data');
			$this->db->insert('users',$record);
			return $this->db->insert_id();
		}
	
		function get_user($user,$filter_field=''){
			if($filter_field=='')$filter_field=is_numeric($user)?'user_id':'email';
			
			$query=$this->db->where($filter_field,$user)->limit(1)->get('users');
			if($row=$query->row())return (array)$row;
			return false;
		}

		function get_profile($user,$filter='user_id'){
			$query=$this->db->select('users.*')->from('users')->where("users.$filter",$user)->limit(1)->get();
			if($row=$query->row_array())return $row;
		}

		function get_user_profile($user){
			$filter=($this->valid_email($user))?'email':'user_id';
			return $this->get_profile($user,$filter);
		}
		
		function update_user_data($user_id,$update,$new_value=''){
			$update_data=is_array($update)?$update:array($update=>$new_value);
			$this->db->where('user_id',$user_id)->limit(1)->update('users',$update_data);
		}
		
		function update_sub_account_data($sub_account_id,$update,$new_value=''){
			$update_data=is_array($update)?$update:array($update=>$new_value);
			$this->db->where('sub_account_id',$sub_account_id)->limit(1)->update('sub_accounts',$update_data);
		}

		function insert_pending_email_data($record){
			$time=time();
			$past_time=time()-3600;

			$this->db->where('time <',$past_time)->delete('pending_email_data');
			$record['time']=$time;
			$this->db->insert('pending_email_data',$record);
			return $this->db->insert_id();
		}

		function get_pending_email_data($cid,$code){
			$query=$this->db->where('id',$cid)->where('code',$code)->order_by('id','desc')->limit(1)->get('pending_email_data');
			if($row=$query->row())return (array)$row;
			return false;
		}

		################## TRANSACTION SEGEMENT ###################

		function insert_transaction($transaction){
			$this->db->insert('transactions',$transaction);
			return $this->db->insert_id();
		}

		function get_transaction($trans_id,$include_userdata=false){
			if($include_userdata){
				$this->db->join('users','users.user_id = transactions.user_id');
				$this->db->select('transactions.*,users.email,users.firstname,users.lastname,users.phone');
			}
			$query=$this->db->where('transaction_reference',$trans_id)->limit(1)->get('transactions');
			if($row=$query->row())return (array)$row;
			return false;
		}

		function update_transaction($transaction_id,$update_data,$use_id=false){
			if($use_id)$this->db->where('id',$transaction_id)->update('transactions',$update_data);
			else $this->db->where('transaction_reference',$transaction_id)->update('transactions',$update_data);
		}

		function get_transactions($only_count=false,$filter='',$include_userdata=true){
			$this->db->from('transactions');

			if(is_array($filter)){
				if(!empty($filter['user_id']))$this->db->where('transactions.user_id',$filter['user_id']);
				if(!empty($filter['start_date']))$this->db->where(_DB_PREFIX_.'transactions.time>=',strtotime($filter['start_date']),false);
				if(!empty($filter['end_date']))$this->db->where(_DB_PREFIX_.'transactions.time<=',strtotime($filter['end_date'])+86399,false);
				if(!empty($filter['type']))$this->db->where('transactions.type',$filter['type']);
				if(!empty($filter['section']))
				{
					if($filter['section']!='withdrawal')
					{
						$this->db->where("(transactions.type='exchange' OR  (transactions.type='fund_account' AND status=1) )");
					}
					else $this->db->where('transactions.type','withdraw');
				}
				if(isset($filter['status'])&&$filter['status']!='')$this->db->where('transactions.status',$filter['status']);
				if(!empty($filter['payment_method']))$this->db->where('transactions.payment_method',$filter['payment_method']);
				if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
				if(!empty($filter['email']))
				{
					$this->db->like('users.email',$filter['email']);
					$include_userdata=true;
				}
			}

			if($include_userdata){
				$this->db->join('users','users.user_id = transactions.user_id');
				$this->db->select('transactions.*,users.email');
			}

			if($only_count)return $this->db->count_all_results();


			$query=$this->db->order_by('id','desc')->get();
			$records=array();
			foreach($query->result() as $row)$records[$row->id]=(array)$row;
			return $records;
		}

		
		function change_transaction_status($transaction_reference,$new_status,$json_info,$batch_used=''){
			$transaction=$this->get_transaction($transaction_reference,true);
			if(empty($transaction))return "Transaction not found.";
			if($transaction['status']==1)return "Transaction already completed.";
			
			
			if($new_status==1){
				$user_data=$this->get_user($transaction['user_id'],'user_id');
				$json_details=$this->get_json($transaction['json_details']);
				
				$sms_units=$json_details['sms_units'];
				$user_data['balance']+$json_details['sms_units'];
				
				$new_bal=$user_data['balance']+$sms_units; 					
				$update_data=array();
				$extra_notes="";
				
				if(!empty($user_data['reseller_account'])&&$new_bal>0)
				{
					$data['configs']=$this->general_model->get_configs();
					$reseller_surety_fee=$this->general_model->get_reseller_surety_fee(); 
					
					if(!empty($user_data['reseller_account'])&&$user_data['surety_units']<$reseller_surety_fee){
						$balance=$new_bal;
						
						$surety_owed=$reseller_surety_fee-$user_data['surety_units'];
						if($balance>=$surety_owed){
							$surety_topup=$surety_owed;
						} else $surety_topup=$balance;
												
						$new_bal=$balance-$surety_topup;
						$update_data['surety_units']=$user_data['surety_units']+$surety_topup;
						$extra_notes.="<br/>Please Note that $surety_topup SMS units has been charged form your SMS balance, to cover for the deficiency in your Reseller Surety Units<br/>";
					}
				}
				
				$update_data['balance']=$new_bal;
				$this->update_user_data($user_data['user_id'],$update_data);
				
				$message="Transaction Details<br/>";
				foreach($transaction as $trk=>$trv)$message.=strtoupper($trk)." = $trv<br/>";						
				$this->send_email(_ADMIN_EMAIL_,'Successful Transaction',$message);
				
				$user_data['balance']=$new_bal;
				$sms_msg="Your account at ".$this->get_site_name()." has just been topped up with $sms_units SMS credits. Your new balance is $new_bal Credits";
				
				$message="Dear {$user_data['lastname']},<br/><br/>Your account has successfully been topped up with $sms_units SMS credit units .<br/>Your new balance is $new_bal Credits.$extra_notes <br/><br/>Regards.";
				$this->send_email($transaction['email'],'Transaction Successful',$message);				
			}
			elseif($new_status==-1){
				$user_data=$this->get_user($transaction['user_id'],'user_id');
				
				$topic="";
				if(!empty($json_data['info']))$topic.=$json_data['info'];
				if(!empty($json_data['response_description']))$topic=$json_data['response_description'];
				if(empty($topic))$topic='Failed Transaction Confirmation';
				
				$msg="Payment Confirmation Response Data<br/>";
				foreach($_REQUEST as $srk=>$srv)$msg.="$srk = $srv<br/>";
			
				$this->log_error($topic,$msg,$transaction['user_id'],'payment');
				
				if($new_status!=0)
				{
					$message="Dear {$user_data['lastname']},<br/><br/>The transaction $transaction_reference was not successful.<br/>View the details at ".$this->get_url('transaction?receipt='.$transaction['transaction_reference']);						
					$this->send_email($transaction['email'],'Transaction Failed',$message);
				}
			}
			
			$update_data=array('status'=>$new_status,'json_info'=>json_encode($json_info));
			if(!empty($batch_used))$update_data['checksum']=$batch_used;
			$this->db->where('transaction_reference',$transaction_reference)->update('transactions',$update_data);
			
			
			if($new_status==1)return array('sms_msg'=>$sms_msg,'user'=>$user_data);
		}
		
		
		function get_adsense_horizontal($ad_client,$ad_slot){
			if($this->mobile_detected||$this->is_mobile)
			$str="<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
					<ins class='adsbygoogle' style='display:inline-block;width:320px;height:50px' data-ad-client='$ad_client' data-ad-slot='$ad_slot'></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>";
			else
			$str="<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
					<ins class='adsbygoogle' style='display:inline-block;width:728px;height:90px' data-ad-client='$ad_client' data-ad-slot='$ad_slot'></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>";
			
			return $str;
		}
		#--------------------------------------------#


		function get_errors($only_count=false,$filter=''){
			$this->db->from('errors');
		
			if(!empty($filter['type']))$this->db->where('type',$filter['type']);
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'topic,details',false,true);
				if(!empty($like_query))
				{
					$like_query="($like_query)";
					$this->db->where($like_query,null,true);
				}
			}
						
			if($only_count)return $this->db->count_all_results();
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			
			$this->db->join('users','users.user_id=errors.user_id','left')->select('errors.*,users.firstname,users.lastname,users.email');
			return $this->db->order_by('error_id','desc')->get()->result_array();
		}

		function get_whitelisted_messages($only_count=false,$filter=''){
			$this->db->from('whitelisted_sms')->join('users','users.user_id=whitelisted_sms.user_id');
		
			if(!empty($filter['email']))$this->db->where('users.email',$filter['email']);
		
			if(!empty($filter['sub_account_id']))$this->db->where('whitelisted_sms.sub_account_id',$filter['sub_account_id']);
			elseif(!empty($filter['user_id']))$this->db->where('whitelisted_sms.user_id',$filter['user_id']);
			
			if(!empty($filter['search_term'])){
				$like_query=$this->generate_like_query($filter['search_term'],'sender_id,message',false,true);
				if(!empty($like_query))
				{
					$like_query="($like_query)";
					$this->db->where($like_query,null,true);
				}
			}
						
			if($only_count)return $this->db->count_all_results();
			if(!empty($filter['perpage']))$this->db->limit($filter['perpage'],$filter['offset']);
			
			return $this->db->select('whitelisted_sms.*,users.firstname,users.lastname,users.email')->order_by('whitelisted_sms_id','desc')->get()->result_array();
		}

		
		function delete_whitelisted_sms($whitelisted_sms_id){
			$this->db->where('whitelisted_sms_id',$whitelisted_sms_id)->limit(1)->delete('whitelisted_sms');
			return true;			
		}

		function clear_error_log(){$this->db->where('type !=','suspended_sms')->delete('errors');}
		
		function remove_error($error_id){$this->db->where('error_id',$error_id)->limit(1)->delete('errors');}
		
		function dispatch_suspended_batch($batch_id){
			$log=$this->db->where('related_id',$batch_id)->where('type','suspended_sms')->limit(1)->get('errors')->row_array();
			if(empty($log))return "Record not found, or already dispatched";
			
			$log_json=$this->get_json($log['json_details']);
			if(!$this->is_sms_whitelisted($log_json)){
				$log_json['date_time']=date('Y-m-d H:i');
				$this->db->insert('whitelisted_sms',$log_json);
			}

			$this->db->where('batch_id',$batch_id)->where('locked',1)->where('status',0)->update('sms_log',array('locked'=>0));
			$this->db->where('related_id',$batch_id)->where('type','suspended_sms')->limit(1)->delete('errors');
			return true;
		}
		
		function cancel_suspended_batch($batch_id){
			$log=$this->db->where('related_id',$batch_id)->where('type','suspended_sms')->limit(1)->get('errors')->row_array();
			if(empty($log))return "Record not found, or already dispatched";
			$res=$this->db->where('batch_id',$batch_id)->where('locked',1)->where('status',0)->select_sum('units','units_sum')->select('user_id,sub_account_id')->get('sms_log');
			$row=$res->row();
			$this->charge_balance($row->units_sum*-1,$row->user_id,$row->sub_account_id);
			
			$this->db->where('batch_id',$batch_id)->where('locked',1)->where('status',0)->update('sms_log',array('locked'=>0,'status'=>'-2','units'=>0,'units_confirmed'=>1));
			$this->db->where('related_id',$batch_id)->where('type','suspended_sms')->limit(1)->delete('errors');
			
			return true;
		}
		
		function penalize_suspended_batch($batch_id){
			$log=$this->db->where('related_id',$batch_id)->where('type','suspended_sms')->limit(1)->get('errors')->row_array();
			if(empty($log))return "Record not found, or already dispatched";
			$this->db->where('batch_id',$batch_id)->where('locked',1)->where('status',0)->update('sms_log',array('locked'=>0,'status'=>'-4','units_confirmed'=>1));
			$this->db->where('related_id',$batch_id)->where('type','suspended_sms')->limit(1)->delete('errors');
			
			return true;
		}
		
		function is_sms_whitelisted($message,$sender_id='',$user_id='',$sub_account_id=0){
			if(is_array($message)){
				$sender_id=$message['sender_id'];
				$user_id=$message['user_id'];
				$sub_account_id=$message['sub_account_id'];
				$message=$message['message'];
			}
		
			$message=trim($message,' ,.;?');
		
			if(!empty($sub_account_id))$this->db->where('sub_account_id',$sub_account_id);
			elseif(!empty($user_id))$this->db->where('user_id',$user_id);
			
			return $this->db->from('whitelisted_sms')->where('sender_id',$sender_id)->where('message',$message)->limit(1)->count_all_results();
		}
		
		function log_error($topic,$details,$user_id=0,$type='general',$related_id='',$json_details=''){		
			if(!is_string($json_details))$json_details=json_encode($json_details);
			
			$error_data=array(
							'user_id'=>$user_id,
							'time'=>time(),
							'topic'=>$topic,
							'details'=>$details,
							'type'=>$type,
							'related_id'=>$related_id,
							'json_details'=>$json_details,
							);
							
			$this->db->insert('errors',$error_data);
		}
	
		function _log_error($error,$e_title=3){
			if(is_numeric($e_title))$e_title="Error Level $e_title";
			elseif(!empty($e_title)){
				$temp=$e_title;
				$e_title=$error;
				$error=$temp;
			}
			$this->log_error($e_title,$error,0,'technical');
			//error_log($error."\r\n\r\n",$level, FCPATH."/cgsms_errors.log");
		}
		
		function _batch_completed_mail($batch_id,$batch_total,$sent_count,$user_id,$sub_account_id=0){
			if($batch_total<2)return;
			if($sub_account_id==0)$account=$this->db->where('user_id',$user_id)->limit(1)->get('users')->row_array();
			else $account=$this->db->where('sub_account_id',$sub_account_id)->limit(1)->get('sub_accounts')->row_array();
			if(empty($account['email']))return;
			
			if(empty($account['sub_account']))$formatted_sub='';
			else $formatted_sub=$this->format_sub_account($account['user_id'],$account['sub_account']);
			
			
			$tz=$this->tz_offset_to_name($account['timezone_offset']);
			if(empty($tz)){
				$tz='Africa/Lagos';
				$account['timezone_offset']=1;
			}
			date_default_timezone_set($tz);
			$date_time=date('Y-m-d H:i');
			
			
			if($batch_total>1){						
				$mail_message=
				"SMS Batch $batch_id Completed<br/>
				Total SMS: $batch_total<br/>
				Total Sent: $sent_count<br/>
				Balance: {$account['balance']}<br/>
				Time: $date_time<br/>
				Timezone: {$account['timezone_offset']} GMT<br/>";
				
				if(!empty($formatted_sub))
					$mail_message.="Sub-account: $formatted_sub<br/>";
			}
			
			$this->send_email($account['email'],"Batch SMS Completed",$mail_message);
			
			date_default_timezone_set('Africa/Lagos');
		}
		
		
		function send_sms_batch($sms_batch,$configs){
			if(empty($sms_batch))return false;
			ignore_user_abort(true);
			ini_set('memory_limit', '-1'); // ini_set('memory_limit', '128M');
			
			$batch_id=$sms_batch[0]['batch_id'];
			$user_id=$sms_batch[0]['user_id'];
			$sub_account_id=$sms_batch[0]['sub_account_id'];
			
			$total_sms_count=count($sms_batch);
			$sent_count=false;
		
			$sent_count=$this->_cgsms_send_instant_sms($sms_batch,$configs);
			if(is_numeric($sent_count)){
				$this->_batch_completed_mail($batch_id,$total_sms_count,$sent_count,$user_id);
				return $sent_count;
			}

			//message couldn't be sent this moment, unlock them; so they'll be sent by cron.
			if($sent_count===false)$this->db->where('batch_id',$batch_id)->update('sms_log',array('locked'=>0));
			return false;
		}
		
		function _curl_json($url,$post_data='',$is_post=false)
		{
			$ch = curl_init();
			$query=empty($post_data)?'':http_build_query($post_data);
			
			if(!$is_post&&$query!=''){
				if(strpos($url,'?')===false)$url.="?$query";
				else $url.="&$query";
			}
			
			curl_setopt($ch, CURLOPT_URL, $url);
			if($is_post){
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			}
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$response = curl_exec($ch);
			$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($response_code != 200)$response=curl_error($ch);
			curl_close($ch);
			
			if($response_code != 200)return array('error'=>"HTTP ERROR $response_code: $response");
			else
			{
				$json=@json_decode($response,true);
				if($json===null)return array('error'=>"Invalid Remote Server Response",'response_text'=>$response);
				if(is_numeric($json))return array('response_text'=>$response,'response'=>$response);
				else return $json;
			}
			
		}
		
		function _cheapglobalsms_get_balance($configs,$raw=false){	
			$params=array(
				'sub_account'=>$configs['cgsms_sub_account'],
				'sub_account_pass'=>$configs['cgsms_sub_account_password'],
				'action'=>'account_info'
				);
			
			$resp=$this->_curl_json('http://cheapglobalsms.com/api_v1',$params,true);
			if(isset($resp['response_code'])&&$resp['response_code']!=200)return $resp['response_info'];
			if(!empty($resp['error']))return $resp['error'];
			return $resp;
		}
		
		//SEND a single batch of sms; 
		function _cgsms_send_instant_sms($sms_batch,$configs){
			$messages=array();
			$callback_url=$this->get_url('callback_processor/cgsms');		
			$callback_data=$sms_batch[0]['user_id'].':'.$sms_batch[0]['sub_account_id'];
			$batch_id=$sms_batch[0]['batch_id'];
			$default_message=$sms_batch[0]['message'];
			
			foreach($sms_batch as $sms)
			{
				if(!empty($sms['status'])||!empty($sms['units_confirmed']))continue;
				$callback_data1=$callback_data.':'.$sms['sms_id'].':'.$sms['pages'].':'.$sms['units'];				
				$temp_array=array('phone'=>$sms['recipient'],'extra_data'=>$callback_data1);
				if($sms['message']!=$default_message)$temp_array['override_message']=$sms['message'];
				$messages[]=$temp_array;
			}
			
			
			$params=array(
			'sub_account'=>$configs['cgsms_sub_account'],
			'sub_account_pass'=>$configs['cgsms_sub_account_password'],
			'action'=>'send_sms',
			'callback_url'=>$callback_url,
			'type'=>$sms_batch[0]['type'],
			'unicode'=>$sms_batch[0]['unicode'],
			'sender_id'=>$sms_batch[0]['sender'],
			'contacts'=>json_encode($messages),
			'message'=>$default_message
			);
			
			$response_json=$this->_curl_json('http://cheapglobalsms.com/api_v1',$params,true);
		
			$this->_log_error('CheapGlobalSMS Sending params',json_encode($params));
			
			if(empty($response_json['error'])){
				//response_json->total
				$update_data=array(
					'time_sent'=>time(),
					'status'=>1,
					'info'=>'Message Submitted'
					);
				$this->db->where('batch_id',$batch_id)->update('sms_log',$update_data);
				
				return $response_json['total'];
			}
			
			$this->_log_error('CheapGlobalSMS bug while sending sms',json_encode($params)."<br/><br/><br/>===><br/>  ".$response_json['error']);
			return false;
		}
		
		function _cheapglobalsms_process_delivery_reports($results){
			if(empty($results))return "empty record submitted";
			$processed_records=array();
			
			foreach($results as $result)
			{
				$cd=explode(':',$result->extra_data);
				$user_id=$cd[0];
				$sub_account_id=$cd[1];
				$sms_id=$cd[2];
				$new_sms_status=$result->status; 

				$pages=$result->pages;
				$initial_pages=$cd[3];
				$initial_units=$cd[4];
				
				$units=$result->units;
				$new_units=$units*$result->pages;
				$old_units=$initial_units*$initial_pages;
				
				$processed_records[]=$sms_id;
				
				if($new_units!=$old_units)
				{					
					$sms=$this->db->where('sms_id',$sms_id)->limit(1)->select('units_confirmed,units')->get('sms_log')->row_array();
					if(empty($sms['units_confirmed']))
					{
						$units_changes=($new_units-$old_units);
						$this->charge_balance($units_changes,$user_id,$sub_account_id);
					}
					else $units_changes=0;
					/*
					if($units_changes<0)
					{
						$msg="Refunding $units_changes=($units-$old_units) to $user_id,$sub_account_id, sms_id=$sms_id<br/><br/>".json_encode($result);
						$this->log_error('DEBUG:: RETURNING CREDIT',$msg,0,'technical');
					}
					*/
				}
				
				$update_data=array('info'=>$result->info,'status'=>$new_sms_status,'units'=>$units,'locked'=>0,'units_confirmed'=>1,'pages'=>$pages);
				$this->db->where('sms_id',$sms_id)->update('sms_log',$update_data);
			}
			
			return "Processed records: ".json_encode($processed_records);
		}
		
		############### cron functions ########################
		function parse_period_settings($str){
			$str=trim($str,', ');
			$arr=explode(',',$str); 
			if(count($arr)!=10)return array();

			return array(
				'r_hour_fixed'=>$arr[0],
				'r_hour'=>$arr[1],
				'r_monthday_fixed'=>$arr[2],
				'r_monthday'=>$arr[3],
				'r_month_fixed'=>$arr[4],
				'r_month'=>$arr[5],
				'r_weekday_fixed'=>$arr[6],
				'r_weekday'=>$arr[7],
				'r_monthweek_fixed'=>$arr[8],
				'r_monthweek'=>$arr[9],
			);
		}
		
		function periods_to_str($arr){
			if(!is_array($arr)||count($arr)!=10)return '';
			return $arr['r_hour_fixed'].','.$arr['r_hour'].','.$arr['r_monthday_fixed'].','.$arr['r_monthday'].','.$arr['r_month_fixed'].','.$arr['r_month'].','.$arr['r_weekday_fixed'].','.$arr['r_weekday'].','.$arr['r_monthweek_fixed'].','.$arr['r_monthweek'];
		}
		
		function get_period_description($settings)
		{			
			if(is_string($settings))$settings=$this->parse_period_settings($settings);
			if(empty($settings))return '';
			$str='Once every ';
			$tempval=$settings['r_hour'];
			
			if($settings['r_hour_fixed']=='1')$temp_str=$this->get_am_pm($settings['r_hour']);
			elseif($tempval==1)$temp_str='hour';
			else $temp_str=$tempval.' hours';
			
			$str.=$temp_str;
			
			
			if($settings['r_weekday']=='1'&&$settings['r_weekday_fixed']=='0'){}
			else {
				if($str!='')
				{	
					if($settings['r_weekday_fixed']=='1')$str.=' of ';
					else $str.='; ';
				}

				$tempval=$settings['r_weekday'];
				
				if($settings['r_weekday_fixed']=='1')$temp_str=$this->get_weekday_str($settings['r_weekday'],'l');
				elseif($tempval==1)$temp_str='every day in the week';
				else $temp_str='every '.$tempval.' days in the week';
				
				$str.=$temp_str;
			}
			
			
			if($settings['r_monthday']=='1'&&$settings['r_monthday_fixed']=='0'){ }
			else {
				if($str!='')$str.=', of ';
				$tempval=$settings['r_monthday'];
				
				if($settings['r_monthday_fixed']=='1')$temp_str=$this->get_ordinal($settings['r_monthday']).' day of the month';
				elseif($tempval==1)$temp_str='every day in the month';
				else $temp_str='every '.$tempval.' days in the month';
				
				$str.=$temp_str;
			}
			
			if($settings['r_monthweek']=='1'&&$settings['r_monthweek_fixed']=='0'){}
			else {
				if($str!='')$str.=' in ';
				$tempval=$settings['r_monthweek'];
				
				if($settings['r_monthweek_fixed']=='1')$temp_str='the '.$this->get_ordinal($settings['r_monthweek']).' week of the month';
				elseif($tempval==1)$temp_str='every week in the month';
				else $temp_str='every '.$tempval.' weeks in the month';
				
				$str.=$temp_str;
			}
			
			
			if($settings['r_month']=='1'&&$settings['r_month_fixed']=='0'){}
			else {
				if($str!='')
				{	
					if($settings['r_month_fixed']=='1')$str.=' of ';
					else $str.='; ';
				}

				$tempval=$settings['r_month'];
				
				if($settings['r_month_fixed']=='1')$temp_str=$this->get_month_str($settings['r_month'],'l');
				elseif($tempval==1)$temp_str='every month';
				else $temp_str='every '.$tempval.' months';
				
				$str.=$temp_str;
			}
			
			return $str;
		}
			
		
		function date_to_cps_periods($date_time){
			if(!is_numeric($date_time))$date_time=strtotime($date_time);
			
			if($this->tz_offset_to_seconds($this->current_tzo)!==$this->tz_offset_to_seconds($this->default_tzo)){
				date_default_timezone_set($this->default_tz);
				$diff_tzo=true;
			} 
			else $diff_tzo=false;
			
			$r_hour=date('G',$date_time);
			if(empty($r_hour))$r_hour=1;
			
			$return= array(
				'r_hour'=>$r_hour,
				'r_hour_fixed'=>'1',
				'r_monthday'=>date('j',$date_time),
				'r_monthday_fixed'=>'1',
				'r_month'=>date('n',$date_time),
				'r_month_fixed'=>'1',
				'r_weekday'=>'1',
				'r_weekday_fixed'=>'0',
				'r_monthweek'=>'1',
				'r_monthweek_fixed'=>'0',
			);		
			
			if($diff_tzo)date_default_timezone_set($this->current_tz);
			
			return $return;
		}
		
		function get_due_cps($this_dispatch_time,$limit=1000)
		{
			/*
			$minute=date('i'); //minute with leading zero
			//constraint to 30 minutes interval.
			$minute=($minute>30)?30:0;
			*/
			$hour=date('G'); //H with leading zero
			$monthday=date('j'); //d with leading zero
			$month=date('n'); //m with leading zero
			$weekday=date('w')+1; //day of week, 0 = sunday:: ok
			
			$temp=date('Y-m-1');
			$monthweek=intval(date('W'))-intval(date('W',strtotime($temp)));

			//any condition will always be picked if e.g r_hour=1 and r_hour_fixed=0; then $hour%r_hour  === always 0
		   $sql="SELECT *,COUNT(cps_id) total_cps
		   FROM "._DB_PREFIX_."cpss WHERE cps_status=1 AND last_date_time!='$this_dispatch_time'
			AND ((r_hour_fixed=1 AND r_hour=$hour) OR (r_hour_fixed=0 AND $hour%r_hour=0))
			AND ((r_monthday_fixed=1 AND r_monthday=$monthday) OR (r_monthday_fixed=0 AND $monthday%r_monthday=0))
			AND ((r_month_fixed=1 AND r_month=$month) OR (r_month_fixed=0 AND $month%r_month=0))
			AND ((r_weekday_fixed=1 AND r_weekday=$weekday) OR (r_weekday_fixed=0 AND $weekday%r_weekday=0))
			AND ((r_monthweek_fixed=1 AND r_monthweek=$monthweek) OR (r_monthweek_fixed=0 AND $monthweek%r_monthweek=0))
			GROUP BY cps_batch_id LIMIT $limit
		    ";
		  $query=$this->db->query($sql);
		  return $query->result_array();
		}
		
		function update_cps($cps_id,$update_data){
			$this->db->where('cps_id',$cps_id)->limit(1)->update('cpss',$update_data);
		}
		
		function cache_template_categories($user_id,$sub_account_id){
			$this->db->query("SET SESSION group_concat_max_len=10000000");
			if(!empty($sub_account_id))$this->db->where('sub_account_id',$sub_account_id);
			else $this->db->where('user_id',$user_id);
			$result=$this->db->select("sms_category_id,GROUP_CONCAT(sms_template_id) AS template_ids",FALSE)->group_by('sms_category_id')->get('sms_templates')->result();
			
			$caches=array();
			foreach($result as $row){
				$caches[$row->sms_category_id]=empty($row->template_ids)?array():explode(',',$row->template_ids);
				shuffle($caches[$row->sms_category_id]);
			}

			return $caches;
		}
		
		
		function select_cps_template($cps)
		{
			$cps=(object)$cps;
			$template_id=$cps->sms_template_id;
			$sent_temp_ids=explode(',',$cps->sent_sms_template_ids);
			
			if(empty($template_id))
			{
				if(!isset($this->default_template_cache[$cps->sms_category_id]))
				{
					$template_cache_user=$cps->user_id.':'.$cps->sub_account_id;
					if($this->current_template_cache_user!==$template_cache_user){
						$this->temp_template_cache=$this->cache_template_categories($cps->user_id,$cps->sub_account_id);
						$this->current_template_cache_user=$template_cache_user;
					}
					
					if(isset($this->temp_template_cache[$cps->sms_category_id])){
						$temp=$this->temp_template_cache[$cps->sms_category_id];
					}
				} 
				else $temp=$this->default_template_cache[$cps->sms_category_id];
				
				if(!empty($temp)){
					foreach($temp as $tempi){
						if(!in_array($tempi,$sent_temp_ids)){
							$template_id=$tempi;
							break;
						}
					}
					
					if(empty($template_id))$template_id=$temp[0];
				}
			}
			
			if(!empty($template_id))return $this->db->select('sms_template,sms_template_id')->where('sms_template_id',$template_id)->limit(1)->get('sms_templates')->row_array();
			
			return array();
		}
		
		function get_due_sms_batches(){
			$time=time();
			$sql="SELECT COUNT(sms_id) total,batch_id,sub_account_id,user_id
						FROM "._DB_PREFIX_."sms_log
						WHERE deleted=0 AND status=0 AND locked=0 AND time_scheduled<=$time 
						GROUP BY batch_id";
						
			return $this->db->query($sql)->result_array();				
		}
		
		function cron_get_cps_recipients($batch_id){
			return $this->db->where('cps_batch_id',$batch_id)->where('cps_status',1)->select('phone,firstname,lastname,group_name,cps_id')->get('cpss')->result_array();
		}
		
		function cron_get_sms_batch($batch_id){
			$where=array('batch_id'=>$batch_id,'status'=>0,'locked'=>0,'deleted'=>0);
			$sms_batch=$this->db->where($where)->get('sms_log')->result_array();
			if(!empty($sms_batch))$this->db->where($where)->update('sms_log',array('locked'=>1));
			return $sms_batch;
		}
		
		function update_cps_batch($batch_id,$update_data){
			$this->db->where('cps_batch_id',$batch_id)->update('cpss',$update_data);
		}
		
		function get_sms_batch($batch_id){ return $this->db->where('batch_id',$batch_id)->get('sms_log')->result_array(); }
		
		######################### - API FUNCTIONS - ##################
		
		function schedule_sms($sms_batch,$is_batch=true){
			if($is_batch)$this->db->insert_batch('sms_log',$sms_batch); 
			else $this->db->insert('sms_log',$sms_batch); 
		}
		
		######################## UTILITIES #####################################
		
		
		function get_balance($user_id){
			$query=$this->db->where('user_id',$user_id)->limit(1)->select('balance')->get('users');
			if($row=$query->row())return $row->balance;
			return '';
		}
		
		function charge_balance($amount,$user_id,$sub_account_id=0){
			$set_query=($amount>0)?"balance=balance-$amount":"balance=balance+".abs($amount);
			
			if($sub_account_id>0)$this->db->query("UPDATE "._DB_PREFIX_."sub_accounts SET $set_query WHERE sub_account_id=$sub_account_id LIMIT 1");
			else $this->db->query("UPDATE "._DB_PREFIX_."users SET $set_query WHERE user_id=$user_id LIMIT 1");
		}
		
				
		function deactivate_reseller_account($user_id){
			$user=$this->get_user($user_id);
			if(empty($user))return 'User record not found';
			if(empty($user['reseller_account']))return 'This is not a reseller account';
			if(!empty($user['owing_surety']))return 'A reseller account without surety can-not be downgraded. Please refill your surety first.';
			
			$this->db->where('user_id',$user_id)->limit(1)->update('users',array('reseller_account'=>0));			
			return true;						
		}
		
		function refill_surety_units($user,$activate_reseller=false){
			if(!is_array($user))$user=$this->get_user($user);
			if(empty($user))return 'User record not found';
			if(empty($user['reseller_account'])&&!$activate_reseller)return 'This is not a reseller account';
			elseif($activate_reseller&&!empty($user['reseller_account']))return 'This reseller account is already activated';
			
			$reseller_surety_fee=$this->get_reseller_surety_fee();
			if(empty($user['owing_surety'])&&!empty($user['reseller_account']))return 'This reseller account is already insured';
			if($user['balance']<$reseller_surety_fee)return "Insufficient balance, there is need to have at least $reseller_surety_fee SMS units.";
			$new_bal=$user['balance']-$reseller_surety_fee;
			$date_time=date('Y-m-d H:i');

			$this->db->query("UPDATE "._DB_PREFIX_."users SET reseller_account=1,last_surety_updated='$date_time',balance=balance-$reseller_surety_fee,owing_surety=0 WHERE user_id='{$user['user_id']}' LIMIT 1");
			$this->db->query("UPDATE "._DB_PREFIX_."sub_accounts SET owing_surety=0 WHERE user_id='{$user['user_id']}' ");
		
			$message="Dear {$user['firstname']},<br/><br/>Please Note that $reseller_surety_fee SMS units has been charged from your SMS balance, to cover for the deficiency in your Reseller Surety Units. Leaving your new SMS balance at $new_bal Units. <br/><br/>Regards.";
			$this->send_email($user['email'],'Surety Units Refilled',$message);
			
			$details="-$reseller_surety_fee SMS units for reseller surety top-up. New SMS balance: $new_bal Units";
			$time=time();
			$amount=$this->sms_units_to_price($reseller_surety_fee);
						
			$transaction=
				array(
						'user_id'=>$user['user_id'],
						'time'=>$time,
						'transaction_reference'=>$time,
						'amount'=>$amount,
						'type'=>2,
						'currency_code'=>'NGN',
						'details'=>$details,									
						'payment_method'=>'free_checkout',
						'sms_units'=>$reseller_surety_fee,
						'net_amount_ngn'=>$amount,
						'status'=>1
					);
							
			$this->db->insert('transactions',$transaction);
			
			return true;
		}
		
		
		function valid_date_time($date_time){
			$date_time=trim($date_time);
			
			if(!empty($date_time)&&preg_match($this->date_time_patern_php,$date_time)){
				$time=strtotime($date_time);
				return date('Y-m-d H:i',$time);
			}
			return '';
		}
		
		function get_www_url(){
			$url=base_url();
			if(substr($url,0,7)=='http://')$url=substr($url,7);
			elseif(substr($url,0,8)=='https://')$url=substr($url,8);
			
			$parts=explode('.',$url);
			if(count($parts)<3)$url="www.$url";
			return trim($url,'/');
		}

		function get_user_data($user_id,$key){
			$query=$this->db->where('user_id',$user_id)->limit(1)->select($key)->get('users');
			if($row=$query->row_array()){
				$key_arr=explode(',',$key);
				if(count($key_arr)==1)return $row[$key];
				else return $row;
			}
			return '';
		}
		
		
		function unset_cache($key){
			$user_id=$this->get_login_data('user_id');
			if(empty($user_id))return false;
			$cache=$this->get_json($this->get_user_data($user_id,'cache'));
			if(isset($cache[$key])){
				unset($cache[$key]);
				$this->update_user_data($user_id,'cache',json_encode($cache));
			}
			return true;
		}
		
		function set_cache($key,$val){
			$user_id=$this->get_login_data('user_id');
			if(empty($user_id))return false;
			$cache=$this->get_json($this->get_user_data($user_id,'cache'));
			$cache[$key]=$val;
			$this->update_user_data($user_id,'cache',json_encode($cache));
			return true;
		}
		
		function get_cache($key){
			$user_id=$this->get_login_data('user_id');
			if(empty($user_id))return false;
			$cache=$this->get_json($this->get_user_data($user_id,'cache'));
			return isset($cache[$key])?$cache[$key]:'';		
		}

		function get_email_suggestions($val){
			$val=addslashes($val);
			$query=$this->db->query("SELECT email as value, CONCAT(firstname,' ',lastname) as label FROM "._DB_PREFIX_."users WHERE email LIKE '$val%' LIMIT 10");
			return $query->result_array();
		}

		function get_contact_suggestions($val,$user_id,$sub_account_id=0){
			$val=addslashes($val);
			$query=$this->db->query("SELECT phone as value, CONCAT(phone,' ',firstname,' ',lastname,'(',group_name,')') as label FROM "._DB_PREFIX_."contacts WHERE user_id='$user_id' AND sub_account_id='$sub_account_id' AND (phone LIKE '$val%' OR firstname LIKE '$val%' OR lastname LIKE '$val%') LIMIT 10");
			return $query->result_array();
		}

		function get_sms_template_suggestions($val,$sms_category_id){
			$val=addslashes($val);
			$query=$this->db->query("SELECT sms_template_id as value,sms_template as label FROM "._DB_PREFIX_."sms_templates WHERE sms_category_id='$sms_category_id' AND sms_template LIKE '%$val%' LIMIT 10");
			return $query->result_array();
		}
		

		function generate_like_query($term,$fields,$wildcard_only_back=false,$remove_stopwords=false){
			$term=str_replace('?',' ',$term);
			$term=str_replace('?',' ',$term);
			$fields=explode(',',$fields);
			$raw_terms=explode(' ',$term);
			$terms=array();
			
			$stopwords=array("a's","able","about","above","according","accordingly","across","actually","after","afterwards","again","against","ain't","all","allow","allows","almost","alone","along","already","also","although","always","am","among","amongst","an","and","another","any","anybody","anyhow","anyone","anything","anyway","anyways","anywhere","apart","appear","appreciate","appropriate","are","aren't","around","as","aside","ask","asking","associated","at","available","away","awfully","be","became","because","become","becomes","becoming","been","before","beforehand","behind","being","believe","below","beside","besides","best","better","between","beyond","both","brief","but","by","c'mon","c's","came","can","can't","cannot","cant","cause","causes","certain","certainly","changes","clearly","co","com","come","comes","concerning","consequently","consider","considering","contain","containing","contains","corresponding","could","couldn't","course","currently","definitely","described","despite","did","didn't","different","do","does","doesn't","doing","don't","done","down","downwards","during","each","edu","eg","eight","either","else","elsewhere","enough","entirely","especially","et","etc","even","ever","every","everybody","everyone","everything","everywhere","ex","exactly","example","except","far","few","fifth","first","five","followed","following","follows","for","former","formerly","forth","four","from","further","furthermore","get","gets","getting","given","gives","go","goes","going","gone","got","gotten","greetings","had","hadn't","happens","hardly","has","hasn't","have","haven't","having","he","he's","hello","help","hence","her","here","here's","hereafter","hereby","herein","hereupon","hers","herself","hi","him","himself","his","hither","hopefully","how","howbeit","however","i'd","i'll","i'm","i've","ie","if","ignored","immediate","in","inasmuch","inc","indeed","indicate","indicated","indicates","inner","insofar","instead","into","inward","is","isn't","it","it'd","it'll","it's","its","itself","just","keep","keeps","kept","know","known","knows","last","lately","later","latter","latterly","least","less","lest","let","let's","like","liked","likely","little","look","looking","looks","ltd","mainly","many","may","maybe","me","mean","meanwhile","merely","might","more","moreover","most","mostly","much","must","my","myself","name","namely","nd","near","nearly","necessary","need","needs","neither","never","nevertheless","new","next","nine","no","nobody","non","none","noone","nor","normally","not","nothing","novel","now","nowhere","obviously","of","off","often","oh","ok","okay","old","on","once","one","ones","only","onto","or","other","others","otherwise","ought","our","ours","ourselves","out","outside","over","overall","own","particular","particularly","per","perhaps","placed","please","plus","possible","presumably","probably","provides","que","quite","qv","rather","rd","re","really","reasonably","regarding","regardless","regards","relatively","respectively","right","said","same","saw","say","saying","says","second","secondly","see","seeing","seem","seemed","seeming","seems","seen","self","selves","sensible","sent","serious","seriously","seven","several","shall","she","should","shouldn't","since","six","so","some","somebody","somehow","someone","something","sometime","sometimes","somewhat","somewhere","soon","sorry","specified","specify","specifying","still","sub","such","sup","sure","t's","take","taken","tell","tends","th","than","thank","thanks","thanx","that","that's","thats","the","their","theirs","them","themselves","then","thence","there","there's","thereafter","thereby","therefore","therein","theres","thereupon","these","they","they'd","they'll","they're","they've","think","third","this","thorough","thoroughly","those","though","three","through","throughout","thru","thus","to","together","too","took","toward","towards","tried","tries","truly","try","trying","twice","two","un","under","unfortunately","unless","unlikely","until","unto","up","upon","us","use","used","useful","uses","using","usually","value","various","very","via","viz","vs","want","wants","was","wasn't","way","we","we'd","we'll","we're","we've","welcome","well","went","were","weren't","what","what's","whatever","when","whence","whenever","where","where's","whereafter","whereas","whereby","wherein","whereupon","wherever","whether","which","while","whither","who","who's","whoever","whole","whom","whose","why","will","willing","wish","with","within","without","won't","wonder","would","wouldn't","yes","yet","you","you'd","you'll","you're","you've","your","yours","yourself","yourselves","zero");
			
			foreach($raw_terms as $term){
				if($remove_stopwords&&in_array($term,$stopwords))continue;
				
				$term=trim(addslashes($term));
				if(empty($term))continue;
				$terms[]=$term;
			}
			
			if(empty($terms)){
				foreach($raw_terms as $term)
				{
					$term=trim(addslashes($term));
					if(empty($term))continue;
					$terms[]=$term;
				}
			}

			$t_arr=array();
			foreach($terms as $term){
				$f_arr=array();				
				foreach($fields as $field)
				{
					$f_arr[]=$wildcard_only_back?"$field LIKE '$term%'":"$field LIKE '%$term%'";
				}
				$t_arr[]=implode(' OR ',$f_arr);
			}
			
			$sql=implode(') AND (',$t_arr);
			if(count($t_arr)>1)$sql="($sql)";
			return $sql;
		}
		
		function get_id_from_profile($profile){
			if(!empty($profile['user_id']))return $profile['user_id'];
			return '';
		}

		function get_url_from_profile($profile,$prepend='',$append=''){
			$profile_id='';
			
			$prepend=trim($prepend,'/');
			if(!empty($prepend))$prepend.="/";
			
			$append=trim($append,'/');			
			if(!empty($append))$append="/$append";
			
			if(!empty($profile['user_id']))$profile_id=$profile['user_id'];
			return base_url().$prepend.$profile_id.$append;
		}
		
		
		
		function get_invoice_link($transaction){
			$tok_salt="{$transaction['transaction_reference']}-{$transaction['user_id']}";
			$tok=md5($tok_salt);
			$params="receipt={$transaction['transaction_reference']}&inv_tok=$tok";
			return $this->get_url("transaction?$params");
		}
		
		function valid_invoice_token($transaction,$inv_tok){ return md5("{$transaction['transaction_reference']}-{$transaction['user_id']}")==$inv_tok;  }

		function get_name_from_user_id($user_id,$only_firstname=false){
			$query=$this->db->where('user_id',$user_id)->select('firstname,lastname')->limit(1)->get('users');
			if($row=$query->row()){
				if($only_firstname)return $row->firstname;
				return $row->firstname." ".$row->lastname;
			}
			return false;
		}
		
		function computed_online($last_seen){
			return ($last_seen+600>time());
		}

		function get_relative_time($seconds){
			$diff=time()-$seconds;
			if($diff<3)return "just now";

			$valdmy=explode('/',date('d/w/m/Y',$seconds));
			$curdmy=explode('/',date('d/w/m/Y'));

			if($valdmy[3]==$curdmy[3]&&$valdmy[2]==$curdmy[2]&&$valdmy[1]==$curdmy[1]&&$valdmy[0]==$curdmy[0]){//same day
				$sec=1; $min=60; $hour=3600; $day=24*$hour;
				$rseconds2=$diff%$day;
				$hours=floor($rseconds2/$hour); if($hours<1)$dhours=", "; elseif($hours==1)$dhours="1 h, "; else $dhours="$hours hrs, ";
				$rseconds1=$rseconds2%$hour;
				$mins=floor($rseconds1/$min); if($mins<1)$dmins=""; elseif($mins==1)$dmins="a min, ";else $dmins="$mins mins, ";
				if($hours>0){
				$whole="$dhours$dmins";
				$toret=substr($whole,0,strlen($whole)-2);
				return "$toret ago";}

				$rseconds0=$rseconds1%$min;
				$secs=$rseconds0;
				 if($secs<1)$dsecs=", "; else $dsecs="$secs secs, ";

				$whole="$dmins$dsecs";
				$toret=substr($whole,0,strlen($whole)-2);
				return "$toret ago";
			}


			//if a day differece, return yesterday, time (yesterday 10:20 pm)
			if($valdmy[3]==$curdmy[3]&&date('z')-date('z',$seconds)==1)return "Yesterday at ".date("g:i a",$seconds);//a day difference
			if($valdmy[3]==$curdmy[3]&&date('z',$seconds)-date('z')==1)return "Tommorow by ".date("g:i a",$seconds);//a day difference
			if($valdmy[3]==$curdmy[3]&&abs(date('z')-date('z',$seconds))<7)return date("D g:i a",$seconds);//the weekday has not repeated itself in same year
			if($valdmy[3]==$curdmy[3]&&$valdmy[2]==$curdmy[2])return date("jS M. g:ia",$seconds);//same month of the same year
			if($valdmy[3]==$curdmy[3])return date("jS M. g:ia",$seconds); //same year
			//otherwise d/m/Y (2/7/2012 4:20pm)
			return date("D. jS M. Y g:ia",$seconds);
		}


		function load_db(){
			$config['hostname'] = _DB_HOST_;
			$config['username'] = _DB_USERNAME_;
			$config['password'] = _DB_PASS_;
			$config['database'] = _DB_NAME_;
			$config['dbdriver'] = "mysqli";
			$config['dbprefix'] = _DB_PREFIX_;
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			$config['cache_on'] = FALSE;
			$config['cachedir'] = "";
			$config['char_set'] = "utf8";
			$config['dbcollat'] = "utf8_general_ci";
			$this->load->database($config);
		}

		function get_url($uri=''){return (base_url().$uri);}
		
		function get_url_no_protocol($uri=''){
			$base_url=base_url();
			$bexp=explode(':',$base_url,2);
			return $bexp[1].$uri;
		}
		
		function get_site_name(){
			if(empty($this->site_name))$this->site_name=$this->get_config('site_name');
			return $this->site_name;
		}
		
		function get_main_domain() {
			$host=$_SERVER['HTTP_HOST'];
			$domain_parts = explode('.',$host);
			$count=count($domain_parts);
			if($count<=2)return $host;
			
			$permit=0;
			for($i=$count-1;$i>=0;$i--){
				$permit++;
				if(strlen($domain_parts[$i])>3)break;
			}
			
			while(count($domain_parts) >$permit)array_shift($domain_parts);
			return join('.', $domain_parts);
		}
		
		
		function send_email($to,$subject,$message,$extras="",$signature=null){			
			$from_name=$this->get_site_name();
			$domain=$this->get_main_domain();
			$from="no-reply@$domain";
			
			if(!empty($extras['from'])){
				$from=$extras['from'];
				$from_name='';
			}
			
			if(!empty($extras['from_name']))$from_name=$extras['from_name'];
			
			
			$reformat=false;
			if(stristr($message,'<br')===FALSE&&stristr($message,'<div')===FALSE&&stristr($message,'<a')===FALSE&&stristr($message,'<table')===FALSE)$reformat=true;
			
			if($reformat){
				$message=str_replace(array("\r\n", "\n\r", "\r", "\n",'\r\n', '\n\r', '\r', '\n',),"<br/>",$message);
				
				$message = preg_replace('$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" target="_blank">$2</a> ', $message." ");
				$message = preg_replace('$(\s|^)(www\.[a-z0-9_./?=&-]+)(?![^<>]*>)$i', '<a target="_blank" href="http://$2"  target="_blank">$2</a> ', $message." ");
			}
			
			if($signature===null)$message.="<br/>".$this->get_url();
			
			$this->load->library('email');
			$config=array('mailtype'=>'html','priority'=>1);
			$this->email->initialize($config);
			$this->email->from($from, $from_name)->to($to)->subject($subject)->message($message);
			//echo "\$this->email->from($from, $from_name)->to($to)->subject($subject)->message($message);";
			$resp=@$this->email->send();
			if($resp)return true;
			
			if(!empty($extras['debug'])){
				/*
					ob_start();
					$this->email->print_debugger();
					$error=ob_end_clean();
				*/

				$error='MailError:'.$this->email->print_debugger();
				$this->_log_error($error);
				return $error;
			}
			return false;
		}

		function on_localhost(){
			return (strtolower($_SERVER['HTTP_HOST'])=='localhost');
		}
		
		function display_bootstrap_alert($Success,$Error,$Warning='',$Info=''){
			$str="";
			if(!empty($Error))$str.="<div class='alert alert-danger fade in'>
						<span class='close' data-dismiss='alert'>&times;</span>$Error
					</div>";
			if(!empty($Success))$str.="<div class='alert alert-success fade in'>
						<span class='close' data-dismiss='alert'>&times;</span>$Success
					</div>";
			if(!empty($Warning))$str.="<div class='alert alert-warning fade in'>
						<span class='close' data-dismiss='alert'>&times;</span>$Warning
					</div>";
			if(!empty($Info))$str.="<div class='alert alert-info fade in'>
						<span class='close' data-dismiss='alert'>&times;</span>$Info
					</div>";
			return $str;
		}
		
		function format_category($cat,$uppercase=false){
			$cat=str_replace('_and_','_&_',$cat);
			if($uppercase)return strtoupper(str_replace('_',' ',$cat));
			return ucwords(str_replace('_',' ',$cat));
		}
		
		
		function split_format($text){
			$text=str_replace('_',' ',$text);
			return ucwords($text);
		}

		function format_text_input($text){
			$find=array('"',"'",'<');
			$replace=array('&quot;','&#39','&lt;');
			$text=str_replace($find,$replace,$text);
			return trim($text);
		}
		
		function cron_obtain_scheduled_mails(){
			$this->db->where('status',1)->where('time_sent',0)->update('scheduled_mails',array('status'=>0));
			
			$time=time();
			$limit=1000;
			$results=$this->db->query("SELECT * FROM "._DB_PREFIX_."scheduled_mails WHERE time<=$time AND status=0 LIMIT $limit")->result();
			if(!empty($results))$this->db->query("UPDATE "._DB_PREFIX_."scheduled_mails SET status=1 WHERE time<=$time AND status=0 LIMIT $limit ");
			
			return $results;
		}
		
		function cron_conclude_scheduled_mails($updates){
			$this->db->update_batch('scheduled_mails',$updates,'scheduled_mail_id');
		}
		
		function send_scheduled_emails($mails){ $this->db->insert_batch('scheduled_mails',$mails); }
		
		function limited_text($text,$max=150,$allow_newline=false){
			$text=trim(strip_tags($text));
			if(strlen($text)>$max)$text=substr($text,0,$max)."...";
			if($allow_newline){
				$text=preg_replace('/(\r\n){2,}/', "\r\n", $text);
				$text=nl2br($text);
			}
			
			return $text;		
		}
		
		function pluralize($num,$singular,$plural=''){
			if($num<=0)return $singular;
			return ($plural=='')?$singular.'s':$plural;			
		}
		
		function get_json($json_string){
			if(empty($json_string))return array();
			$json=@json_decode($json_string,true);
			if(empty($json)){
				$json_string=stripslashes($json_string);
				if(!empty($json_string))$json=@json_decode($json_string,true);
			}
			
			if(empty($json))return array();
			return $json;
		}
		
		function format_text_output($text,$user=''){
			$findArr=array('[br]','[b]','[/b]','[i]','[/i]','[u]','[/u]');
			$repArr=array('<br/>','<b>','</b>','<i>','</i>','<u>','</u>');
			$text=str_replace($findArr,$repArr,$text);
			$text=preg_replace('|(?<!\[.rl=)http[s]?://[^<>[:space:]]+[[:alnum:]/]|',"<a href='\\0' target=_blank>\\0</a>",$text);
			$text=preg_replace('|\[vrl=([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])\]([^\]]+)\[/vrl\]|',"<br/><iframe width='420' height='315' src='\\1'  title='\\2'></iframe><br/>",$text);
			$text=preg_replace('|\[erl=([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])\]([^\]]+)\[/erl\]|',"<br/><embed width='420' height='315' src='\\1' title='\\2'><br/>",$text);
			$text=preg_replace('|\[irl=([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])\]([^\]]+)\[/irl\]|',"<br/><img style='max-width:500px;' src='\\1' title='\\2'><br/>",$text);
			$text=preg_replace('|\[url=([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])\]([^\]]+)\[/url\]|',"<a href='\\1' target=_blank>\\2</a>",$text);
			//replace all three \r\n with two
			
			if(!empty($user))$text=str_replace(array('[[firstname]]','[[lastname]]','[[email]]','[[phone]]'),array($user['firstname'],$user['lastname'],$user['email'],$user['phone']),$text);
			$text=preg_replace('/(\r\n){3,}/', "\r\n\r\n", $text);
			return nl2br(trim($text));
		}

		function valid_email($email){
			return (preg_match("~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,3})$~i",$email));
		}


		function value_exists($table_name,$field,$value){
			$query=$this->db->where($field,$value)->limit(1)->get($table_name);
			return ($query->num_rows()>0);
		}

		function get_pagination_url($p){
			$append="";
			$path=uri_string();
			$query_string=explode('&',$_SERVER['QUERY_STRING']);

			foreach($query_string as $qsv){
				$exp=explode('=',$qsv);
				if($exp[0]=='p')continue;
				elseif(count($exp)==1)$append.="{$exp[0]}&";
				else $append.="{$exp[0]}={$exp[1]}&";
			}
			return $this->get_url("$path?$append"."p=$p");
		}


		function get_pagination($p,$totalpages,$url_override='',$minimal_display=false){
			$append="";
			$path=uri_string();
			$query_string=explode('&',$_SERVER['QUERY_STRING']);

			foreach($query_string as $qsv){
				$exp=explode('=',$qsv);
				if($exp[0]=='p')continue;
				elseif(count($exp)==1)$append.="{$exp[0]}&";
				else $append.="{$exp[0]}={$exp[1]}&";
			}

			$url=$this->get_url("$path?$append");
			$pagination="";

			$prev=$p-1;
			$next=$p+1;

			$append_q=empty($append)?"":"?$append";
			
			$neighbors=3;
			
			
			if($totalpages>2&&$p-$neighbors>1){
				if(empty($url_override))$pagination.="<li><a href='{$url}p=1'>1</a></li>";
				else $pagination.="<li><a href='$url_override/1/$append_q'>1</a></li>";
			}
			
			if($p-$neighbors-1>1)$pagination.="<li ><a>...</a></li>";
			
			for($tp=$neighbors;$tp>=1;$tp--){
				if($p-$tp<1)continue;
				$ttp=$p-$tp;
				if(empty($url_override))$pagination.="<li><a href='{$url}p=$ttp' >$ttp</a></li> ";
				else $pagination.="<li><a href='$url_override/$ttp/$append_q' >$ttp</a></li> ";
			}
			
			$pagination.="<li class='active' ><a>$p</a></li> ";

			for($tp=1;$tp<=$neighbors;$tp++){
				if($p+$tp>$totalpages)break;
				$ttp=$p+$tp;
				if(empty($url_override))$pagination.="<li><a href='{$url}p=$ttp' >$ttp</a></li> ";
				else $pagination.="<li><a href='$url_override/$ttp/$append_q' >$ttp</a></li> ";
			}
			
			if($p+$neighbors+1<$totalpages)$pagination.="<li ><a>...</a></li>";
			
			if($totalpages>2&&$p+$neighbors<$totalpages){
				if(empty($url_override))$pagination.="<li><a href='{$url}p=$totalpages'>$totalpages</a></li>";
				else $pagination.="<li><a href='$url_override/$totalpages/$append_q'>$totalpages</a></li>";
			}
			
			if($minimal_display&&$totalpages<=1)return "";
			$pagination="<ul class='pagination pagination-sm' style='margin:0px;'>$pagination</ul>";
			return $pagination;
		}
		
		
		/* Takes a GMT offset (in hours:minutes) and returns a timezone name */
		
		function get_am_pm($h,$return_string=true){
			$h*=1;

			if($h>12){
				$m='pm';
				$h-=12;
			}
			elseif($h==12)$m='noon';
			else $m='am';
				
			return $return_string?$h.$m:$m;
		}
		
		
		function hour_format($int,$alone=true){
			
			if($int>12)$am='pm';
			elseif($int==12)$am='noon';
			else $am='am';
			
			
			$hours=floor($int);
			
			$mins=$int-$hours;
			if($mins>0){
				$mins=floor(60*$mins);
			}
			
			$str= $hours.':'.str_pad($mins,2,'0',STR_PAD_LEFT);		

			return $alone?$str:"$str $am";
		}
		
		function tz_offset_to_seconds($tzo){
			$tzo=explode(':',$tzo,2);
			$hours=intval($tzo[0]);
			$offset=$hours*3600;
			
			if(count($tzo)==2){
				$minutes=intval($tzo[1]);
				if($hours<0)$minutes*=-1;
				$offset+=($minutes*60);
			}
			
			return $offset;
		}
		
		function tz_offset_to_name($tzo){
			$tzo=explode(':',$tzo,2);
			$hours=intval($tzo[0]);
			$offset=$hours*3600;
			
			if(count($tzo)==2){
				$minutes=intval($tzo[1]);
				if($hours<0)$minutes*=-1;
				$offset+=($minutes*60);
			}
			$tz=timezone_name_from_abbr ('',$offset,0);
			if($tz!==false)return $tz;

			$abbrarray = timezone_abbreviations_list();
			foreach ($abbrarray as $abbr){
				foreach ($abbr as $city)
				{
					if ($city['offset'] == $offset)return $city['timezone_id'];
				}
			}
			return FALSE;
		}
	
	}