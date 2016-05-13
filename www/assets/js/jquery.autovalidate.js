jQuery(function($){
	var theFormCriteria    = 'form'; //'form.autovalidate'
	var watermarkCriteria  = '.watermark[title]';
	var watermarkClass     = 'watermarked';
	
	var theFieldCriteria   = [];
	var theFieldTypes      = 'input textarea select'.split(' ');
	var theFieldClasses    = ['required'];
	var theFieldAttributes = 'mustmatchfield mustmatch pattern type mustnotmatch minvalue maxvalue minlength minchosen maxchosen validateas'.split(' ');

	$.each( theFieldTypes, function(_,inFieldType){
		$.each( theFieldClasses, function(_,inFieldClass){
			theFieldCriteria.push( inFieldType+"."+inFieldClass );
		});
		$.each( theFieldAttributes, function(_,inFieldAttribute){
			theFieldCriteria.push( inFieldType+"["+inFieldAttribute+"]" );
		});
	});
	theFieldCriteria = theFieldCriteria.join(',');
	
	var theFailedValidationClass = 'has-error';
	var theFieldClassSetter = function( inJField, inClassName, inKeepFlag,errorMsg )
	{
		var holderGroup=inJField.parent('.form-group,.checkbox-inline,.checkbox,.radio,.radio-inline,.ui-field-contain');
		if(holderGroup.length)
		{
			inKeepFlag?holderGroup.addClass(inClassName ):holderGroup.removeClass( inClassName );
			

			if (typeof $.fn.popover == 'function') //or typeof($.fn.tooltip) != 'undefined'
			{
				inJField.attr('data-toggle','tooltip').attr('data-placement','bottom').tooltip({});
				if(inKeepFlag){
					inJField.tooltip('hide').attr('data-original-title', errorMsg).tooltip('fixTitle').tooltip('show');
				} else {	
					inJField.tooltip('hide').attr('data-original-title', '').tooltip('fixTitle');
				}
			}
			else
			{
				error_holder=holderGroup.find('.error-holder');
				
				if(!error_holder.length)
				{
					str="<div class='error-holder text-danger' style='display:block;'></div>";
					holderGroup.append(str);
					error_holder=holderGroup.find('.error-holder');
				}
				inKeepFlag?error_holder.html(errorMsg).show():error_holder.html('').hide();	
			}
		}
	}

	var theFieldValidator = function(inEventOrField)
	{
		var theField  = inEventOrField.target || inEventOrField;
		var theJField = $(theField);
		var theJForm  = $(theField.form);
		if ( theJField.data('blessedBox') ){
			return theWatcher( theJField.data('blessedBox') );
		}

		var required     = theJField.hasClass('required')||theJField.is('[required]');
		var mustMatchField = theJField.attr("mustmatchfield");
		var mustMatch    = theJField.attr("mustmatch");
		var mustNotMatch = theJField.attr("mustnotmatch");
		var minVal       = theJField.attr("minvalue")?theJField.attr("minvalue"):theJField.attr("min");
		var maxVal       = theJField.attr("maxvalue")?theJField.attr("maxvalue"):theJField.attr("max");
		var minLen       = theJField.attr("minlength");
		var maxLen       = theJField.attr("maxlength") && theJField.attr("maxlength") >= 0 && theJField.attr("maxlength");
		var minChosen    = theJField.attr("minchosen");
		var maxChosen    = theJField.attr("maxchosen");
		var valAs	     = theJField.attr("validateas");
		var valMsg       = theJField.attr("mustmatchmessage");
		
		if(theJField.attr('pattern')&&!mustMatch)mustMatch=theJField.attr('pattern');
		if(!valAs)valAs=theJField.attr('type');

		// Clear validation errors on myself (and maybe related checkboxes)
		theFieldClassSetter( theJField, theFailedValidationClass, false );
		
		if ( theJField.data('siblingBoxes') )
		{
			theJField.data('siblingBoxes').each(function(_,inSiblingBox){
				theFieldClassSetter( $(inSiblingBox), theFailedValidationClass, false );
			});
		}		
		var theValidationErrorsByName = theJForm.data( 'validationErrorsByName' );
		delete theValidationErrorsByName[ theField.name ]; // Later checkboxes or radio buttons blow away earlier; validation options on LAST item

		var theValue = (theJField.is(watermarkCriteria) && theJField.hasClass(watermarkClass)) ? '' : theField.value;
		if (theField.type=='radio'){
			theValue = theJForm.find("input[name="+theField.name+"]:checked").val() || "";
		}
		
		var niceName = theJField.attr("nicename") || theField.name.replace(/_/g,' ');

		if (valAs){
			switch( valAs.toLowerCase() ){
				case 'email':
					mustMatch='^[^@ ]+@[^@. ]+\\.[^@ ]+$';
					if (!valMsg) valMsg = niceName+" doesn't look like a valid email address. It must be of the format 'john@host.com'";
				break;
				case 'phone':
					mustMatch='^\\D*\\d*\\D*(\\d{3})?\\D*\\d{3}\\D*\\d{4}\\D*$';
					if (!valMsg) valMsg = niceName+" doesn't look like a valid phone number.";
				break;
				case 'zipcode':
					mustMatch='^\\d{5}(?:-\\d{4})?$';
					if (!valMsg) valMsg = niceName+" doesn't look like a valid zip code. It should be 5 digits, optionally followed by a dash and four more, e.g. 19009 or 19009-2314";
				break;
				case 'integer':
					mustMatch='^-?\\d+$';
					if (!valMsg) valMsg = niceName+" must be an integer.";
				break;
				case 'float':
					mustMatch='^-?(?:\\d+|\\d*\.\\d+)$';
					if (!valMsg) valMsg = niceName+" must be a number, such as 1024 or 3.1415 (no commas are allowed).";
				break;	
				case 'url':
					mustMatch='(ftp|http|https):\\/\\/(\w+:{0,1}\\w*@)?(\\S+)(:[0-9]+)?(\\/|\\/([\\w#!:.?+=&%@!\\-\\/]))?';
					if (!valMsg) valMsg = niceName+" doesn't look like a valid URL. It must be of the format 'http://www.tormuto.com'.";
				break;				
			}
		}
		
		var errors = [];

		// TODO: support requiring radio buttons
		if (required && !theValue)
		{
			errors.push(
				theJField.attr('requiredmessage') ||
				(theJForm.attr('requiredmessage') && theJForm.attr('requiredmessage').replace(/%nicename%/gi,niceName)) ||
				(niceName+' is a required field.')
			);
		}

		if (mustMatchField && theValue)
		{
			mmfield=$('[name='+mustMatchField+']');
			var otherNiceName = mmfield.attr("nicename") || mustMatchField.replace(/_/g,' ');
			
			if(mmfield.length)
			{
				if (mmfield.val()!= theValue) errors.push( valMsg || (niceName+' must have the same value as '+otherNiceName) );
			}
		}

		if (mustMatch && theValue)
		{
			mustMatch = new RegExp(mustMatch,(theJField.attr('mustmatchcasesensitive')=='true'?'':'i'));
			if (!mustMatch.test(theValue)) errors.push( valMsg || (niceName+' is not in a valid format.') );
		}

		if (mustNotMatch && theValue)
		{
			mustNotMatch=new RegExp(mustNotMatch,(theJField.attr('mustmatchcasesensitive')=='true'?'':'i'));
			if (mustNotMatch.test(theValue)) errors.push( valMsg || (niceName+' is not in a valid format.') );
		}

		if (minVal && theValue && (theValue*1 < minVal*1)) errors.push( niceName+' may not be less than '+minVal+'.' );
		if (maxVal && theValue && (theValue*1 > maxVal*1)) errors.push( niceName+' may not be greater than '+maxVal+'.' );
		if (minLen && (theValue.length < minLen*1 ) && (required || theValue)) errors.push( niceName+' must have at least '+minLen+' characters.' );
		if (maxLen && (theValue.length > maxLen*1))errors.push( niceName+' may not be more than '+maxLen+' characters (it is currently '+theValue.length+' characters).' );
		

		if (valAs=='date' && theValue)
		{
			var curVal = new Date(theValue);
			if (isNaN(curVal)) errors.push( niceName+' must be a valid date (e.g. 12/31/2001)' );
			//TODO: format the dates nicely, e.g. #M#/#D#/#YYYY#
			if (minVal && ((new Date(minVal)) > curVal)) errors.push( niceName+' must not be earlier than '+(new Date(minVal)).toLocaleString()+'.' );
			if (maxVal && ((new Date(maxVal)) < curVal)) errors.push(niceName+' must be earlier than '+(new Date(maxVal)).toLocaleString()+'.' );
		}

		if (minChosen || maxChosen)
		{
			var theNumChosen;
			if ( theField.type=='checkbox' ){
				theNumChosen = theJForm.find( "input[name='"+theField.name+"']:checked" ).length;
			} else if ( theField.options ){
				theNumChosen = theJField.find( 'option:selected' ).length;
			}
			if (theNumChosen<minChosen) errors.push( 'Please choose at least '+minChosen+' '+niceName );
			if (theNumChosen>maxChosen) errors.push( 'Please choose no more than '+maxChosen+' '+niceName );
		}
		
		if (errors.length)
		{
			theValidationErrorsByName[theField.name] = {el:theField, message:errors.join("\n") };
			theFieldClassSetter( theJField, theFailedValidationClass, true ,errors[0]);
			if ( theJField.data('siblingBoxes') )
			{
				theJField.data('siblingBoxes').each(function(_,inSiblingBox){
					theFieldClassSetter( $(inSiblingBox), theFailedValidationClass, true,error[0] );
				});
			}
		}
	}
	
	var theInitializer = function(_,inForm)
	{
		inForm = $(inForm);
		inForm.data( "validationErrorsByName", {} );

		// Walk by index instead of using .find() for speed and to track ordering
		for (var i=0,len=inForm[0].elements.length;i<len;i++)
		{
			var theField = $(inForm[0].elements[i]);
			if (theField.is(watermarkCriteria)) (function(f)
			{
				var mark = f.attr('title');
				f.focus(function(){
					if (f.val()==mark) f.removeClass(watermarkClass).val('');
				}).blur(function() {
					if (!f.val()) f.addClass(watermarkClass).val(mark);
				}).blur();
			})(theField);
			
			if (theField.is(theFieldCriteria) || ( theField.attr('maxlength') && theField.attr('maxlength') >= 0 ) )
			{
				theField.data('nativeIndex',i);
				if (theField[0].type=='checkbox' || theField[0].type=='radio')
				{
					var theSiblings = $(theField.form).find("input[name='"+theField.name+"']:"+theField[0].type).not(theField);
					theSiblings.data('blessedBox',theField[0]);
					theSiblings.add(theField).click(theFieldValidator);
					theField.data('siblingBoxes',theSiblings);
				}
				else 
				{
					theField.blur(theFieldValidator);
					theField.change(theFieldValidator);
				}
			}
		}
		
		//call this before processing any inline onsubmit
		var oldSubmit = inForm[0].onsubmit;
		inForm[0].onsubmit = null;
		
		inForm.submit(function(inEvent)
		{
			for (var i=0,len=inForm[0].elements.length;i<len;i++)
			{
				theFieldValidator(inForm[0].elements[i] );
			}
	
			var theValidationErrors = [];
			$.each(inForm.data('validationErrorsByName'),function(_,inFieldErrors)
			{
				theValidationErrors.push(inFieldErrors);
			});
	
			if (theValidationErrors.length)
			{
				theValidationErrors.sort(function(e1,e2){
					e1 = e1.el.tabIndex*1000 + $(e1.el).data('nativeIndex');
					e2 = e2.el.tabIndex*1000 + $(e2.el).data('nativeIndex');
					return e1<e2?-1:e1>e2?1:0;
				});
				var theErrorList = $.map(theValidationErrors,function(inError){
					return inError.message;
				}).join("\n");
				
				if(inForm.attr('alertError'))alert(theErrorList);
				var theFirstField = theValidationErrors[0].el;
				
				try
				{
					parentTab=$(theFirstField).closest('.form_tab');
					if(parentTab.length)
					{
						parentTabId=parentTab.attr('id');
						$("a[href='#"+parentTabId+"']").click();
					}
				}
				catch(e){}
				
				try{ theFirstField.focus();	 } catch(e){};
				try{ theFirstField.select(); } catch(e){};
				
				inEvent.cancelFurtherSubmits=true;
				inEvent.preventDefault();
				inEvent.stopPropagation();
				return false;
			}
			
			if(oldSubmit){
				var oldRet = oldSubmit.call(this);
				if(typeof oldRet ==='boolean')return oldRet;
			}

			return true;
		});
	};

	$(theFormCriteria).each( theInitializer);
	$(document).bind('DOMNodeInserted',function(inEvent){
		$(inEvent.target).find(theFormCriteria).andSelf().filter(theFormCriteria).each(theInitializer);
	});
	
	$('input[type=file][max_size]').change(function (event) 
	{
		var append='KB';
		var size=this.files[0].size,kb=1024,mb=1024*kb;
		
		max_size=$(this).attr('max_size');
		if(max_size!=''&&size/kb>max_size)
		{
			alert('The image files size must not be greater than '+max_size+'KB'); 	
			$(this).val('');
			event.preventDefault();
			return;
		}
		
		if(size>mb){
			append='MB';
			size=size/mb;
		}	
		else size=size/kb;
		
		size=Math.round(size*100)/100;	
		
		$(this).attr('title',size+append);
	});
	
	
	
	$('.required').each(function(){
		thisname=$(this).attr('name');
		label=$('label[for='+thisname+']');
		
		if(label.length)
		{
			prev_cont=label.html();
			prev_cont+=" <sup class='text-danger'>*</sup>";
			label.html(prev_cont);
		}
	});
	
	$('.form-group[hint]').each(function(){
		hint=$(this).attr('hint');
		hint="<div class='text-muted'>"+hint+"</div>";
		$(this).find('.form-control').after(hint);
	});
	
	
	$('[default]').each(function(){
		var default_val=$(this).attr('default');
		if(typeof default_val === 'undefined')default_val='';
		
		if($(this).is('[type]')&&
			($(this).attr('type')=='checkbox'||$(this).attr('type')=='radio')
			)
		{
			if(default_val==$(this).val())$(this).prop('checked',true);
		}
		else $(this).val(default_val);
	});	
	
	var txts = document.getElementsByTagName('TEXTAREA') 

	for(var i = 0, l = txts.length; i < l; i++)
	{
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
	
	
});
