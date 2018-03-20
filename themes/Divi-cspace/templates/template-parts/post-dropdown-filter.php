<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
	<?php

		if($custom_category_filtering){
			$terms = [];
			foreach($filter_selection as $cat){
				array_push($terms, get_term_by('id', $cat, 'category'));
			}
		}else{
			$terms = get_terms( 'category', 'orderby=name' );
		}

		if( $terms ) : // to make it simple I use default categories
			echo '<select name="categoryfilter"><option>All Categories</option>';
			foreach ( $terms as $term ) :
				echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
			endforeach;
			echo '</select>';
		endif;


		?>
		<br/><br/>
		<h5>Manual Selection in results: <?php var_dump($manual_posts_filtered); ?></h5>

		<?php  ?>
    <!--
	<input type="text" name="price_min" placeholder="Min price" />
	<input type="text" name="price_max" placeholder="Max price" />
	<label>
		<input type="radio" name="date" value="ASC" /> Date: Ascending
	</label>
	<label>
		<input type="radio" name="date" value="DESC" selected="selected" /> Date: Descending
	</label>
	<label>
		<input type="checkbox" name="featured_image" /> Only posts with featured image
	</label>
    -->
	<!--<button>Apply filter</button>-->

    <?php global $wp_query; ?>

	<input type="hidden" name="action"  value="filterPosts">
    <input type="hidden" name="pageID"  value="<?php echo $wp_query->post->ID; ?>">
</form>
<div id="response"></div>