<?php $view->render('header');?>

<div id="floorpanel">
<?php foreach($floors as $floor):?>
	<div id="floorItem_<?php echo $floor['floor_id']?>">
		
		
		<section class="span10">
		<a class="floor_name" href="<?php echo $url->reverse('DeviceControl.floor', array('id' => $floor['floor_id']))?>"><?php echo $floor['floor_name']?></a>
		</section>
		<section class="span2">
			<a class="openConf btn" data-floorid="<?php echo $floor['floor_id']?>" href="#floorCP" ><i class="icon-pencil"></i> Edit</a>
		</section>
	</div>
<?php endforeach?>
</div>
<div class="managebuttons">
	<a class="btn" id="new_floor" data-toggle="modal" href="#floorCP" ><i class="icon-plus-sign"></i> New Floor</a>
</div>

<div class="modal hide" id="floorCP">
	<form method="post" class="form-horizontal" id="floor_form">
		<input type="hidden" name="floor_id" id="floor_id" value="" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Floor configuration</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="floor_name">Floor name</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="floor_name" name="floor_name" />
					<p class="help-block">A describing name for your floor.</p>
				</div>
				
			</div>
			<div class="control-group">
				<label class="control-label">Delete</label>
				<div class="controls">
					<a href="#" id="deleteFloor" class="btn btn-danger">Delete</a>
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
<script type="text/javascript">
$(document).ready(function(){
	$('.openConf').live('click', function(){
		$('#floorCP').modal('show');
		var id = $(this).data('floorid');
		$.post('<?php echo $url->reverse('DeviceControl.getFloor')?>', {floorid: id}, function(data){
			$('#floor_id').val(data.floor_id);
			$('#floor_name').val(data.floor_name);
			$('#deleteFloor').parent().parent().show();
		}, "json");		
	});

	$('#new_floor').click(function(){
		$('#floor_id').val('');
		$('#floor_name').val('');
		$('#deleteFloor').parent().parent().hide();
		
	});

	$('#deleteFloor').live('click', function(){
		$.post('<?php echo $url->reverse('DeviceControl.ajaxFloor')?>', {floor_id: $('#floor_id').val(), remove: true}, function(data){
			$('#floorItem_'+$('#floor_id').val()).fadeOut();
			$('#floorCP').modal('hide');
			globalmessage('success', data);
		}); 		
	});

	$('#floor_form').submit(function(){

		$.post('<?php echo $url->reverse('DeviceControl.ajaxFloor')?>', $(this).serialize(), function(data){

			var item = $('#floorItem_'+data.data.floor_id+' .floor_name'); 

			if (item.length)item.text(data.data.floor_name);
			else{
				location.reload();
			}
		},"json"); 

		return false;
	});
	
	$('#floorpanel').sortable({
		update: function(event, ui){
			var order = $('#floorpanel').sortable('serialize');
			$.post('<?php echo $url->reverse('DeviceControl.ajaxFloorsSort')?>', order, function(data){
				globalmessage('success', 'Reordered floors.');
			}); 
		}
	});
});
</script>
<?php $view->render('footer');?>