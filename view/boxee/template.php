<?php $view->render('header') ?>
<script type="text/javascript">
function sendbox(m, arv, handler){
	
	$.ajax({
		  type: 'POST',
		  url: '<?php echo $url->reverse('BoxeeBox.command')?>',
		  data: {method: m, args: arv},
		  success: handler,
		  error: function(data){
				globalmessage('error',data.responseText);
		  },
		  dataType: "json"
	});
}


function openMovieInfo(fi, title){
	$('#movieInformation .file').html(fi);
	$('#movieInformation .movie_title').html(title);
	var info = $('#movieInformation .imdbinfo');

	info.find('.loader').show();
	info.find('.attr').html('');
	info.find('.poster').attr('src', '');
  	
	$.ajax({
		  type: 'POST',
		  url: '<?php echo $url->reverse('BoxeeBox.imdbapi')?>',
		  data: {name: title, file: fi},
		  success: function(data){
			  info.find('.loader').hide();		  
			  $('#movieInformation .movie_title').html(data.name.t);
			  if (data.imdb){
				info.show();
				info.find('.title').html(data.imdb.title);
			  	info.find('.plot').html(data.imdb.plot);
			  	info.find('.rating').html(data.imdb.imdbRating);
			  	info.find('.year').html(data.imdb.year);
			  	info.find('.genre').html(data.imdb.genre);
			  	info.find('.actors').html(data.imdb.actors);
			  	info.find('.poster').attr('src', data.imdb.poster);
			  	info.find('.runtime').html(data.imdb.runtime);
			  	
			  }else{
				  info.hide();
			  }
		  },
		  error: function(data){
			  info.find('.loader').hide();
			  info.hide();
		  },
		  dataType: "json"
	});

	$('#movieInformation').modal('show');
	
}


$(function() {
	$('#movieInformation .play').live('click', function(){
		
		sendbox('XBMC.Play', {file: $('#movieInformation .file').text()}, function(data){
			globalmessage('success','Starting to play new file.');
		});
	});
	$('#set_gobal_boxee').change(function(){
		$.post('<?php echo $url->reverse('BoxeeBox.setUseBoxee')?>', {use_boxee: $(this).val()}, function(data){
			location.reload();
		});
	});
	
});
</script>

<?php $view->render('boxee/movieinfo')?>


<div class="row-fluid">
<div class="span3">
	<div class="well">
		<ul class="nav nav-list">
			<li class="nav-header">Menu</li>
			<li><a href="<?php echo $url->reverse('BoxeeBox.pair')?>"><i class="icon-pencil"></i> Configure Boxee</a></li>
			<li><a href="<?php echo $url->reverse('BoxeeBox.files')?>"><i class="icon-hdd"></i> Files</a></li>
			<li><a href="<?php echo $url->reverse('BoxeeBox.remote')?>"><i class="icon-move"></i> Remote</a></li>
			
		</ul>
	</div>
	
	<h6>What boxee to control?</h6>
	<?php if(count($boxees) > 0):?>
		<select id="set_gobal_boxee">
			<?php foreach($boxees as $boxee):?> 
				<option value="<?php echo $boxee['boxee_id']?>"><?php echo $boxee['boxee_name']?></option>
			<?php endforeach?>
		</select>
	<?php else: ?> 
		<div class="alert alert-warning">No BoxeeBox added yet, <a href="<?php echo $url->reverse('BoxeeBox.pair')?>">add one</a>.</div>
	<?php endif?>
	<img class="visible-desktop" src="<?php echo $url->asset('img/boxee-logo.png')?>" alt="" width="150" height="150" />
</div>
<div class="span9">
	<div class="row-fluid">
		<?php $view->render('boxee/'.$file, (isset($args) ? $args : array()))?>
	</div>
</div>

</div>

<?php $view->render('footer') ?>