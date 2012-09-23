<div class="scriptingPanel">
	
	<?php if (isset($formid)):?>
	<a data-formid="#<?php echo $formid?>" class="btn"><i class="icon-play"></i> Test</a>
	<?php elseif(isset($scriptid)):?>
	<a data-scriptid="<?php echo $scriptid?>" class="btn"><i class="icon-play"></i> <?php echo $button_name?></a>
	<?php elseif(isset($macroid)):?>
	<a data-macroid="<?php echo $macroid?>" class="btn"><i class="icon-play"></i> <?php echo $button_name?></a>
	<?php endif?>
	<div class="status"></div>
	<div class="loader"></div>
</div>