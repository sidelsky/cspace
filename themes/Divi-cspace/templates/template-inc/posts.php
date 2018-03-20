<?php

if(!function_exists('outputPosts')){

    function formatRowHtml($layout_style, $amount, $column_position, $loopedRow){

        global $templatePath;

        $loopedRow = $loopedRow + 1;

        //ROW Class
        $row_class = 'et_pb_row';

        //DIVI Column Classes
        $divi_column_class  = 'et_pb_column ';//note space
        $one_column_class   = 'et_pb_column_4_4';
        $two_column_class   = 'et_pb_column_1_2';

        $one_third  = 'et_pb_column_1_3';
        $two_thirds = 'et_pb_column_2_3';

        //$three_column_class = 'et_pb_column_2_3';

        $last_child = ' et-last-child';//note space

        //DIVI Template Partials
        //complete columns
        $post_text_image = 'templates/template-parts/post-text-image.php';
        $post_half_width = 'templates/template-parts/post-half-width.php';
        $post_full_width = 'templates/template-parts/post-full-width.php';

        //little bits
        $post_thumbnail  = 'templates/template-parts/post-thumbnail.php';
        $post_title      = 'templates/template-parts/post-title.php';
        $post_excerpt    = 'templates/template-parts/post-excerpt.php';
        $post_link       = 'templates/template-parts/post-link.php';
        $post_view_more_btn = 'templates/template-parts/post-view-more-btn.php';
        
        //open row if first column
        if($column_position == 1){
            echo '<div class="' . $row_class . ' auto-layout-generated-row">';
        }

        //columns
            //homepage style
            if($layout_style == 'homepage'){
                include( locate_template( $templatePath . 'template-parts/row-style-home.php', false,false ));
            }
            //thinking style
            if($layout_style == 'thinking'){
                include( locate_template( $templatePath . 'template-parts/row-style-thinking.php', false,false ));
            }
            //work style
            if($layout_style == 'work'){
                include( locate_template( $templatePath . 'template-parts/row-style-work.php', false,false ));
            }

        //close row if last column in row
        if($amount == $column_position){
            echo '</div>';
        }

    };

    function renderPhpToString($file, $vars=null){
        if (is_array($vars) && !empty($vars)) {
            extract($vars);
        }
        ob_start();
        include ( locate_template( $file, false, false ) );
        return ob_get_clean();
    };

    function outputPosts($amount, $loopedRow){

        global $post_ids;
        global $post_types;
        global $layout_style;

        $wp_post_ids = array_slice($post_ids, 0, $amount);

        $args = array(
        'post_type'		=> $post_types,
        //'cat'			=> $cat,
        'post__in'		=> $wp_post_ids,
        'orderby'		=> 'post__in'
        );
        
        $the_query = new WP_Query( $args );

        $column_position = 1;

        if ( $the_query->have_posts() ) {

            while ( $the_query->have_posts() ) {

                $the_query->the_post();

                formatRowHtml($layout_style, $amount, $column_position, $loopedRow);

                $column_position++;
                    
            };

            //remove post id from the $post_ids array
            $post_ids = array_values(array_diff($post_ids, $wp_post_ids));

            wp_reset_postdata();

        };// if has posts();
        
    };// end fourPosts;

};