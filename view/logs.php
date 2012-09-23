<?php $view->render('header');?>
<h1>Device logs</h1>
<div class="well">
<p>Make sure you have control of your homes electronic devices. Check logs to see if automated procedures is done and check for unwanted actions.</p>
</div>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th rel="tooltip" title="The exact timestamp of when the action was performed.">Time</th>
			<th rel="tooltip" title="The name and code of the device.">Device name/code</th>
			<th rel="tooltip" title="The action performed.">Action</th>
			<th rel="tooltip" title="The IP address that was running this action.">IP Address</th>
			<th rel="tooltip" title="Re-run this command?">Run</th>
		</tr>
	</thead>
	<tbody id="tableLogs">
	
	</tbody>
</table>
<a href="#" class="btn btn-primary" id="moreLogs">More logs ...</a>



<script type="text/javascript">
$(function() {
	var page = 0;
	loadLogs(page);

	$('#moreLogs').click(function(){
		page++;
		
		loadLogs(page);

		return false;
	});

	

	function loadLogs(p){

		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('DeviceLogs.jsonList')?>',
			  data: {page: p},
			  success: function(data){
				  for(i = 0; i < data.length; i++){
					  $('#tableLogs').append("<tr><td>"+data[i].created_at+" ("+data[i].timeago+")</td><td>"+data[i].device_name+" ("+data[i].device_code+")</td><td>"+data[i].readable_action+"</td><td>"+data[i].ip_addr+"</td><td><a href='#' class='runlog' data-log_id='"+data[i].log_id+"'><i class='icon-play'></i></a></td></tr>");
				  }
			  },
			  error: function(data){
				  if (p > 0)p--;
				  globalmessage('info', 'There are no entries in the device log yet.');
			  },
			  dataType: "json"
		});
		
	}


	$('.runlog').live('click', function(){
		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('DeviceLogs.runEntry')?>',
			  data: {log_id: $(this).data('log_id')},
			  success: function(data){
				  globalmessage('success','Successfully ran log entry.');
			  },
			  error: function(data){
				  alert(data.toSource());
				  globalmessage('info', 'Error when running log entry: ');
			  },
			  dataType: "json"
		});
	});
	
	
});


</script>

<?php $view->render('footer');?>