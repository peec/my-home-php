<?php $view->render('header');?>

<?php if ($view->config['modules']['x10']):?>
<div class="span4">
	<h2>Info</h2>
	<div class="well">
	<p><strong><?php echo count($devices)?></strong> devices are managed.</p>
	</div>

	<h2>Quick Control</h2>
	<div class="well">
	<ul class="nav nav-list">
		
		<?php foreach($devices as $dev):?>
		<li><a href="<?php echo $url->reverse('DeviceControl.floor', array('id' => $dev['floor_id']))?>"><?php echo $dev['room_name']?> - <?php echo $dev['device_name']?></a></li>
		<?php endforeach?>
	</ul>
	</div>
	
	<h2>Used codes</h2>
	<?php foreach($devices as $dev):?>
	<a href="#" class="btn"><?php echo $dev['device_code']?></a>
	
	<?php endforeach?>
	
	
	
	
</div>

<div class="span5">
	<?php if (count($images) > 0):?>
	<div id="houseImageSlide" class="carousel slide">
		<!-- Carousel items -->
		<div class="carousel-inner">
			<?php foreach($images as $k => $image):?>
				<div class="item <?php echo $k == 0 ? 'active' : ''?>"><img src="<?php echo $url->asset('img/house/'.$image)?>" alt="<?php echo $image?>"></div>
			<?php endforeach?>
		</div>
		<!-- Carousel nav -->
		<a class="carousel-control left" href="#houseImageSlide" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#houseImageSlide" data-slide="next">&rsaquo;</a>
	</div>
	<?php else:?>
	<div class="alert alert-info">You have no images in the slider, add images of your house to personalize the homepage of your automation site.</div>
    <?php endif?>
	<?php echo $form = $view->form->create('imageForm')->attr('class', 'well form-inline')->attr('enctype', 'multipart/form-data')?>
	<input type="file" name="image" class="input-xlarge" />
	<button type="submit" class="btn btn-primary">Save image</button>
	<?php echo $form->close()?>
</div>

<?php endif?>

<?php $view->render('footer');?>