<h2>Boxee devices</h2>
<hr />
<div class="row-fluid">
	<?php foreach($boxees as $k => $boxee):?>
		<?php echo $k % 3 == 0 ? '</div><div class="row-fluid">' : '';?>
		<div class="span4">
			<div data-boxee_id="<?php echo $boxee['boxee_id']?>">
				<h4 class="boxee"><?php echo $boxee['boxee_name']?> </h4>
				
				<hr />
				<a class="btn btn-mini edit_boxee"><i class="icon-book"></i> Edit</a>
				<hr />
				<h6>Pair</h6>
				<div class="pair1">
					<a class="btn btn-primary btn-large"><i class="icon-random"></i> Pair</a>
				</div>
				<div class="pair2" style="display: none">
					<form method="post" class="form-horizontal">
						<input type="text" class="input-small" class="code" name="code" placeholder="Code" />
						<button type="submit" class="btn btn-success btn-small"><i class="icon-random"></i> Confirm code</button>
						
					</form>
				</div>
				<div class="pair3" style="display: none">
					<div class="alert alert-success">Device paired.</div>
				</div>
			</div>
		</div>
	<?php endforeach?>
</div>


<div class="managebuttons">
	<a class="btn" id="new_boxee" data-toggle="modal" href="#boxeeCP" ><i class="icon-plus-sign"></i> New Boxee</a>
</div>


<script type="text/javascript">
$(function() {

	function sendbox_db(id, m, arv, handler, errorhander){
		
		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('BoxeeBox.command')?>',
			  data: {boxee_id: id, method: m, args: arv},
			  success: handler,
			  error: function(data){
					globalmessage('error',data.responseText);
			  },
			  dataType: "json"
		});
	}
	
	$('.pair1 a').click(function(){
		var par = $(this).parent().parent();
		var th = $(this).parent();
		sendbox_db(par.data('boxee_id'), 'pair', false, function(data){
			th.hide();
			par.find('.pair2').show();
		});
	});
	$('.pair2 form').submit(function(){
		var par = $(this).parent().parent();
		var th = $(this).parent();
		sendbox_db(par.data('boxee_id'), 'confirmPair', $(this).find('[name=code]').val(), function(data){
			th.hide();
			par.find('.pair3').fadeIn();
		});
		return false;
	});
	

	

	
	$('#deletePanel a').live('click', function(){
		$.post('<?php echo $url->reverse('BoxeeBox.ajaxBoxee')?>', {boxee_id: $('#boxee_id').val(), remove: true}, function(data){
			location.reload();
		});
	});

	
	$('.edit_boxee').click(function(){
		var id = $(this).parent().data('boxee_id');
		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('BoxeeBox.ajaxBoxee')?>',
			  data: {boxee_id: id, get: true},
			  success: function(data){
				  setdata(data);
				  $('#deletePanel').show();
				  $('#boxeeCP').modal('show');
			  },
			  error: function(data){
				  alert(data.responseText);
			  },
			  dataType: "json"
		});
		
		
		return false;
	});
	
	$('#new_boxee').click(function(){
		setdata({boxee_id: '', boxee_name: '', host: ''});
		$('#deletePanel').hide();
		$('#boxeeCP').modal('show');
		return false;
	});


	function setdata(data){
		$('#boxee_id').val(data.boxee_id);
		$('#boxee_name').val(data.boxee_name);
		$('#host').val(data.host);
	}
	
	$('#boxee_form').submit(function(){
		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('BoxeeBox.ajaxBoxee')?>',
			  data: $(this).serialize(),
			  success: function(data){
				  location.reload();
			  },
			  error: function(data){
				  alert(data.responseText);
			  },
			  dataType: "json"
		});

		return false;
	});

});
</script>
<div class="modal hide" id="boxeeCP">
	<form method="post" class="form-horizontal" id="boxee_form">
		<input type="hidden" name="boxee_id" id="boxee_id" value="" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Boxee Configuration</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="room_name">Boxee name</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="boxee_name" name="boxee_name" />
					<p class="help-block">A short name for this boxee.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="host">Host</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="host" name="host" />
					<p class="help-block">A short name for this boxee.</p>
				</div>
			</div>
			<div class="control-group" id="deletePanel">
				<label class="control-label">Delete</label>
				<div class="controls">
					<a href="#" class="btn btn-danger">Delete boxee</a>
					<p class="help-block">NOTE! This can not be undone.</p>
				</div>		
			</div>
		</div>
		<div class="modal-footer">
			
			
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			
			<button type="submit" class="btn btn-primary">Save changes</button>
		</div>
		
	</form>
</div>
