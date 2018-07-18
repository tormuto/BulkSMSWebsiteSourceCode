            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
	<footer class='text-center container' style='border-top:1px solid #ddd;padding-top:8px;' >
		<script type='text/javascript'>
			$(function(){
				if (!Modernizr.inputtypes.date){
				
					//$('input[type=date],.datepicker').datepicker({format:'yyyy-mm-dd'}); //ordinary datepicker plugin
					//$('input[type=date],.datepicker').datepicker({dateFormat:'yy-mm-dd'});//jqueryui plugin
					$('input[type=date],.datepicker').datetimepicker({format:'YYYY-MM-DD'});
					$('input[type=datetime]').datetimepicker( {format:'YYYY-MM-DD h:mm a'});
					$('input[type=time]').datetimepicker( {format:'h:mm a'});
				}
			});
		</script>
		<img src='<?php echo $this->general_model->get_url('assets/images/interswitch_complete.jpg'); ?>' style='max-height:26px;' />
		A product of <a href='http://tormuto.com/' style='font-weight:bold;' target='_blank' >Tormuto Info. Tech.</a>
	</footer>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->	
	
	
	<link href="<?php echo $this->general_model->get_url('assets/css/jquery-ui.css');?>" rel="stylesheet">
	<link href='<?php echo $this->general_model->get_url('bootstrap/css/bootstrap-theme.min.css');?>' rel='stylesheet'>
	<link href='<?php echo $this->general_model->get_url('assets/css/font-awesome.min.css');?>' rel='stylesheet' type='text/css'>	
	
	<link href='<?php echo $this->general_model->get_url('assets/css/sb-admin.css');?>' rel='stylesheet'>
	<link href='<?php echo $this->general_model->get_url('assets/css/cgsms_style.css');?>' rel='stylesheet'>
	<link rel='stylesheet' type='text/css' href='<?php echo $this->general_model->get_url('assets/css/bootstrap-datetimepicker.min.css');?>' />
	<!--
	<script src="<?php echo $this->general_model->get_url('assets/js/jquery-ui.js');?>"></script>
	-->
	<script src="<?php echo $this->general_model->get_url('assets/js/moment.min.js');?>"></script>
	<script src="<?php echo $this->general_model->get_url('assets/js/moment_locales_en_gb.js');?>"></script>
	<script src="<?php echo $this->general_model->get_url('assets/js/bootstrap-datetimepicker.min.js');?>"></script>
	<script src="<?php echo $this->general_model->get_url('bootstrap/js/bootstrap.min.js');?>"></script>
			
	<script type='text/javascript'> var base_url='<?php echo base_url(); ?>'; </script>
    <script src="<?php echo $this->general_model->get_url('assets/js/script.js');?>"></script>
	<script src='<?php echo $this->general_model->get_url('assets/js/jquery.autovalidate.js');?>'></script>
	
<?php if(!$this->general_model->on_localhost()){ ?>
	<div class='text-center'></div>
	<script type='text/javascript'>
	function googleTranslateElementInit() {
	  new google.translate.TranslateElement({pageLanguage: 'en', layout:google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
	}
	</script>

	<script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>
<?php } ?>
</body>
</html>