

function showError(msg){
	if(infoHolder)infoHolder.addClass('alert-danger').removeClass('alert-success').html(msg).show();
	else ui_alert(msg); 
}

function showInfo(msg){
	if(infoHolder)infoHolder.addClass('alert-success').removeClass('alert-danger').html(msg).show();
	else ui_alert(msg); 
}	
	
	/*
function showError(msg){
	if(!('#error_alert').length)ui_alert(msg); 
	else {
		$('#error_alert_msg').html(msg);
		$('#error_alert').fadeIn('slow');
	}
}
*/
	
function runAjax(params,callbackFunction)
{
	var url=base_url+'ajax_processor';
	
	
	$.post(url,params,function(response)
	{
		var response_json={};
		
		if(!response||response=='')response_json.response_error='Empty response from server';
		else {
				
			var json;
			try
			{
				json = jQuery.parseJSON(response);
				response_json=json;
			} 
			catch (e){
				response_json.response_error="Error:"+e+" parsing:"+response;
			}
		}
		callbackFunction(response_json);
	}).error(function(xhr){
		var response_json={};
		response_json.response_error='Connection Error: '+xhr.statusText;
		callbackFunction(response_json);
	});
}

	function ui_alert(msg,title)
	{
		if(typeof title === 'undefined')title='CGSMS Alert';
		
		$("<div title='"+title+"'>"+msg+"</div>").dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			}
		  }
		});
	}

	function ui_confirm(msg,callback_function,action,title,callback_function_cancel)
	{
		if(typeof title === 'undefined')title='CGSMS Confirm';
		if(typeof btn_text === 'undefined')btn_text='OK';
		
		$("<div title='"+title+"'>"+msg+"</div>").dialog({
		  //resizable: false,
		 // height:140,
		  modal: true,
		  buttons: {
			OK: function() {
			  $( this ).dialog( "close" );
			  callback_function();
			},
			Cancel: function() {
			   if(typeof callback_function_cancel == 'function')callback_function_cancel();
			  $( this ).dialog( "close" );
			}
		  }
		});
	}


	$(function()
	{
		window.infoHolder=$('#info_holder');
		
		$('body').on('focus','.autocomplete_contact',
			function()
			{
				new_source=base_url+'ajax_processor/?action=suggest_contact&val='+$(this).val();
				$(this).autocomplete({source: new_source});
			}
		);

		$('body').on('keyup','.autocomplete_contact',
			function()
			{
				new_source=base_url+'ajax_processor/?action=suggest_contact&val='+$(this).val();
				$(this).autocomplete( "option", "source",new_source);
			}
		);
		
			
		$('body').on('click','.single_checkbox',function(){
			if($(this).is(':checked'))
			{
				if(!$('.single_checkbox').not(':checked').length)$('#control_checkbox').prop('checked',true);
			}
			else $('#control_checkbox').prop('checked',false);
		});
		
		
		$('body').on('click','#control_checkbox',function(){
			if($(this).is(':checked'))$('.single_checkbox').prop('checked',true);
			else $('.single_checkbox').prop('checked',false);
		});
		
		$('#control_checkbox,.single_checkbox').prop('checked',false);
			
		if($('#period_settings').length){
			$('#period_settings select').on('change',recalculate_settings);
			recalculate_settings();
		}
			
		$('textarea#message').on('keyup change',monitor_sms_characters);
		monitor_sms_characters();
		
		impose_textarea_maxlength();
		$('[data-toggle="tooltip"]').tooltip();
		
	});

	function impose_textarea_maxlength()
	{
	  var txts = document.getElementsByTagName('TEXTAREA') 

	  for(var i = 0, l = txts.length; i < l; i++) {
		if(/^[0-9]+$/.test(txts[i].getAttribute("maxlength")))
		{ 
		  var func = function()
		  { 
			var len = parseInt(this.getAttribute("maxlength"), 10);
			if(this.value.length > len)
			{ 
			  alert('Maximum length exceeded: ' + len); 
			  this.value = this.value.substr(0, len); 
			  return false; 
			} 
		  }

		  txts[i].onkeyup = func;
		  txts[i].onblur = func;
		} 
	  } 
	}

	function monitor_sms_characters()
	{
		if(!$('textarea#message').length)return;
		var len =$('textarea#message').val().length;
		if(len==0)$('#message_count_info,#message_count_info2').hide();
		else
		{			
			if(len==0)pages= 0;
			else if(len<=160)pages=1;
			else pages= 1+Math.ceil((len-160)/145);
				
			if(pages>1)
			{
				$('#message_count_info').html(len+' Characters; <strong>'+pages+' Pages</strong>').show();	
				$('#message_count_info2').html('NOTE: you will now be charged for <strong>'+pages+' Pages</strong> to each receipient').show();
			}
			else
			{
				$('#message_count_info').html(len+' Characters; <strong>'+pages+' Page</strong>').show();	
				$('#message_count_info2').hide();
			}
		}
	}



	function recalculate_settings()
	{
		var str='Once every ';
		var tempval=$('select[name=r_hour]').val();
		
		if($('select[name=r_hour_fixed]').val()=='1')temp_str=$('select[name=r_hour] option:selected').attr('str_value');
		else if(tempval==1)temp_str='hour';
		else temp_str=tempval+' hours';
		
		str+=temp_str;
		
		
		if($('select[name=r_weekday]').val()=='1'&&$('select[name=r_weekday_fixed]').val()=='0'){}
		else {
			if(str!='')
			{	
				if($('select[name=r_weekday_fixed]').val()=='1')str+=' of ';
				else str+='; ';
			}

			var tempval=$('select[name=r_weekday]').val();
			
			if($('select[name=r_weekday_fixed]').val()=='1')temp_str=$('select[name=r_weekday] option:selected').attr('str_value');
			else if(tempval==1)temp_str='every day in the week';
			else temp_str='every '+tempval+' days in the week';
			
			str+=temp_str;
		}
		
		
		if($('select[name=r_monthday]').val()=='1'&&$('select[name=r_monthday_fixed]').val()=='0'){ }
		else {
			if(str!='')str+=', of ';
			var tempval=$('select[name=r_monthday]').val();
			
			if($('select[name=r_monthday_fixed]').val()=='1')temp_str=$('select[name=r_monthday] option:selected').attr('str_value')+' day of the month';
			else if(tempval==1)temp_str='every day in the month';
			else temp_str='every '+tempval+' days in the month';
			
			str+=temp_str;
		}
		
		if($('select[name=r_monthweek]').val()=='1'&&$('select[name=r_monthweek_fixed]').val()=='0'){}
		else {
			if(str!='')str+=' in ';
			var tempval=$('select[name=r_monthweek]').val();
			
			if($('select[name=r_monthweek_fixed]').val()=='1')temp_str='the '+$('select[name=r_monthweek] option:selected').attr('str_value')+' week of the month';
			else if(tempval==1)temp_str='every week in the month';
			else temp_str='every '+tempval+' weeks in the month';
			
			str+=temp_str;
		}
		
		
		if($('select[name=r_month]').val()=='1'&&$('select[name=r_month_fixed]').val()=='0'){}
		else {
			if(str!='')
			{	
				if($('select[name=r_month_fixed]').val()=='1')str+=' of ';
				else str+='; ';
			}

			var tempval=$('select[name=r_month]').val();
			
			if($('select[name=r_month_fixed]').val()=='1')temp_str=$('select[name=r_month] option:selected').attr('str_value');
			else if(tempval==1)temp_str='every month';
			else temp_str='every '+tempval+' months';
			
			str+=temp_str;
		}
		
		
		$('#settings_meaning').html(str+'.');
		if($('#period_settings_equiv').length){
			var ps_str=$('select[name=r_hour_fixed]').val()+','+$('select[name=r_hour]').val()+','+$('select[name=r_monthday_fixed]').val()+','+$('select[name=r_monthday]').val()+','+$('select[name=r_month_fixed]').val()+','+$('select[name=r_month]').val()+','+$('select[name=r_weekday_fixed]').val()+','+$('select[name=r_weekday]').val()+','+$('select[name=r_monthweek_fixed]').val()+','+$('select[name=r_monthweek]').val();
			$('#period_settings_equiv').val(ps_str);
		}
	}
	

	function validateCPS(){
		if($('#period_settings select[name=r_month_fixed]').val()==1){
			var d=$('#period_settings select[name=r_monthday]').val()*1;
			var m=$('#period_settings select[name=r_month]').val()*1;
			
			if((m==4||m==6||m==9||m==11)&&d>30)msg="The specified month does not have more than 30 days";
			else if(m==2&&d>29)msg="The specified day can never occur in the month of feburary";
			else msg='';

			if(msg!=''){
				ui_alert(msg);
				return false;
			}
		}
			
		if($('#period_settings select[name=r_monthday_fixed]').val()==1&&($('#period_settings select[name=r_monthweek_fixed]').val()==1||$('#period_settings select[name=r_monthweek]').val()*1>1)){
			ui_alert("To avoid abuse, usage of 'Day of the month' together with 'Week of the month' has been disabled. If you really understand the setings, you should almost never have a need to use the two together. Please contact us for more clarifications.");
				return false;
		}
		
		return true;
	}
