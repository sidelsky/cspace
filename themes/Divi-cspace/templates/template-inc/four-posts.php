<?php

if(!function_exists('fourPosts')){ 

    function fourPosts($id,/*$post_type,$cat,*/$class){

        global $post_ids;

        $wp_post_ids= array_slice($post_ids, 0, 4);

        $args = array(
        'post_type'		=> array('post', 'podcast', 'press', 'report'),
        //'cat'			=> $cat,
        'post__in'		=> $wp_post_ids,
        'orderby'		=> 'post__in'
        );
        
        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) {

            while ( $the_query->have_posts() ) {

                $the_query->the_post();

                echo '<li class="' . $class . '">';
                    include( locate_template( 'templates/template-parts/list-item-single.php', false, false ) );
                echo '</li>';

            }

            //remove post id from the $post_ids array
            $post_ids = array_values(array_diff($post_ids, $wp_post_ids));

            wp_reset_postdata();

        };// if has posts();

        
    };// end fourPosts;

};