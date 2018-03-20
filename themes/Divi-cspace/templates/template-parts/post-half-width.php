<a href="<?php echo get_the_permalink();?>">

	<?php 
	
		if(has_post_thumbnail()){ 
			echo get_the_post_thumbnail(); 
		}else{
			echo 'MISSING FEATURED IMAGE';
		}

	?>

    <div class="overlay-content">

        <h4 class="title bottom"><?php echo get_the_title();?></h4>

    </div>

	

</a>