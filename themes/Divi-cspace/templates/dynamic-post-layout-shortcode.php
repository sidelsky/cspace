<?php

$post_id = [];

global $post_ids;
global $post_types;
global $layout_style;

//ACF Fields
$fields = get_fields($filterPageID);

if($fields){
    $post_types                     = $fields['post-types'];
    $custom_category_filtering      = $fields['custom-category-filtering'];// true?
    $filter_selection               = $fields['filter-criteria'];
    $manual_post_selection          = $fields['post-selections'];
    $manually_selected_work         = $fields['work-manual-selection'];
    $amount                         = $fields['show-post-amount'];
    $layout_pattern                 = $fields['layout-pattern'];
    $layout_style                   = $fields['layout-style'];
    $manually_selected_batch_number = $fields['post-manual-loop-group'];
    $load_more_label                = $fields['load-more-btn-label'];
    $post_filter_dropdown           = $fields['post-filter-dropdown'];
    $manual_posts_filtered          = ($fields['manual-posts-filtered']== 'true') ? true : false;
}

// get all posts from selected post types
$args = array(
    'post_type'		  => $post_types,
    'post_status'     => 'publish',
    'fields'          => 'ids',
    'posts_per_page'  => -1
);

//if filtered by cat via the filter dropdown
if($filterCatID > 0){
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $filterCatID
        )
    );
};

//getting all ids of all post types
$post_ids = get_posts($args);
$post_ids_all = get_posts($args);

//getting all of the manually selected post ids
$selected_post_ids = [];
foreach($manual_post_selection as $post){
    array_push($selected_post_ids, $post->ID);
}


//check that the selected posts are acually in the filtered output, so only posts even if manually selected are not shown on wrong categories
$selected_post_ids = array_intersect($post_ids, $selected_post_ids);

//post_ids with manual posts removed, ready for selected posts to be reshuffled into final array
$manual_post_ids_removed = array_diff($post_ids, $selected_post_ids);

//use the pattern value to make an array, which is later looped to create the posts per row order
$insertOffset = array_sum(explode(",",$layout_pattern));

//convert selected post ids into batches
$batch_amount = $manually_selected_batch_number;
if($manually_selected_batch_number){
    $batch_amount = $manually_selected_batch_number;
}else{
    $batch_amount = 1;
};

//break down the post ids into chunks using the 'posts per batch' value
$selected_post_ids = array_chunk($selected_post_ids, $batch_amount);
$i = 0;// all posts with selected post removed
$ii = 0;// to go to the next array_chunk
foreach($manual_post_ids_removed as $post){
    if($i % $batch_amount == 0){
        array_splice($manual_post_ids_removed, $i , 0, $selected_post_ids[$ii]);
        $ii ++;
    }
    $i += $insertOffset;
};

//reset the post id array
$post_ids = $manual_post_ids_removed;

if($manually_selected_work){

    $insertOffset = 2;
    $batch_amount = 6;

    if(!in_array('work', $post_types)){
        array_push($post_types, 'work');
    }

    //get the ids of the manually selected work posts:
    $work_selected_post_ids = [];

    foreach($manually_selected_work as $post){
        array_push($work_selected_post_ids, $post->ID);
    }

    //check the selected posts are in the main list (to filter categories)
    $work_selected_post_ids = array_intersect($post_ids_all, $work_selected_post_ids);

    //break into chunks
    $work_selected_post_ids = array_chunk($work_selected_post_ids, $batch_amount);

    $i = $insertOffset;// all posts with selected post removed
    $ii = 0;// to go to the next array_chunk
    foreach($post_ids as $post){
        if($i % $insertOffset == 0){
            array_splice($post_ids, $i , 0, $work_selected_post_ids[$ii]);
            $ii ++;
        }
        $i += $insertOffset + $batch_amount;
    };

}

//splice off posts that go higher than the show post amount and send back to the post_id var
if($filterCatID > 0 && !$manual_posts_filtered /*manual posts kept in or not*/){
    $post_ids = $post_ids_all;
}else{
    $post_ids = array_splice($post_ids , 0, $amount);
}

//external files:
global $templatePath;
$templatePath = 'templates/';

//helpers
include( locate_template( $templatePath . 'template-inc/helper-showIDs.php', false,false ));

//post functions
include( locate_template( $templatePath . 'template-inc/posts.php', false,false ));

//output posts:
if($post_ids){
    //if ACF option for filter click show the dropdown and page is being filtered
    if($post_filter_dropdown && !$filterCatID){
        include( locate_template( $templatePath . 'template-parts/post-dropdown-filter.php', false,false ));
        echo '<br/>';
    }
    if(!$filterCatID){
        echo '<h2>START: AUTO LAYOUT (No. Posts: <strong>' . $amount . '</strong> Post Per Row: <strong>' . $layout_pattern . '</strong> In Style: <strong>' . $layout_style . '</strong>)</h2>';
        echo '<hr></hr>';
        echo '<div class="auto-layout">';
    }
    outputHtml($layout_pattern);
}else{
    echo 'NO POSTS<br/>';
}

function outputHtml($layout_pattern){

    //convert the layout pattern string from ACF into array
    global $layoutRows;
    $layoutRows = explode(",",$layout_pattern);

    global $loopedRow;
    $loopedRow = 0;

    global $layout_style;
   // echo 'layout-style: ' . $layout_style . '<br/>';

    function loopRow(){

        global $post_ids;
        global $loopedRow; //current loop inside this function
        global $layoutRows; //array with pattern like 1-4 (this loops to keep the pattern going)

        $row_post_amount = intval($layoutRows[$loopedRow]);

        outputPosts($row_post_amount, $loopedRow);

        $loopedRow ++;

        //reset row array to 0 when end to keep the looped row column pattern going
        if($loopedRow == count($layoutRows)){
            $loopedRow = 0;
        }

        //loop over rows and posts if there are still posts left inthe $post_id array
        if($post_ids){
            loopRow();
        }else{
            echo '<br/><div style="width:100%;clear:both;">';
            echo '<hr></hr>';
            echo '<h2>END: AUTO LAYOUT</h2>';
            echo '</div>';
            echo '</div><!-- /auto-layout -->';
        }

    }

    //start post and row loop
    loopRow();

}