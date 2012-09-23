
<h6>Devices</h6>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>Dev. code</th>
			<th>Dev. name</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($allDevices as $dev):?>
			<tr>
				<td><?php echo $dev['device_code']?></td>
				<td><?php echo $dev['room_name']?> - <?php echo $dev['device_name']?></td>
			</tr>
		<?php endforeach?>
	</tbody>
</table>