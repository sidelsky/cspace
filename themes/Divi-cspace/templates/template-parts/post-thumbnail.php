<?php

	if(has_post_thumbnail()){ 
		echo get_the_post_thumbnail(); 
	}else{
		echo 'MISSING FEATURED IMAGE';
	}

?>
