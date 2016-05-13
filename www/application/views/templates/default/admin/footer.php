		
	</div>
			<div class='clearfix'></div>
		<footer class='text-center'>
			<hr/>
			All rights reserved &copy; 2014
		</footer>
		
		<script type='text/javascript'>
			$(function(){
				if (!Modernizr.inputtypes.date){
					$('input[type=date],.datepicker').datetimepicker({format:'YYYY-MM-DD'});
					$('input[type=datetime]').datetimepicker( {format:'YYYY-MM-DD h:mm a'});
					$('input[type=time]').datetimepicker( {format:'h:mm a'});
				}
			});
		</script>
  </body>
</html>