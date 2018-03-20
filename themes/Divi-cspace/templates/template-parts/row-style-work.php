<?php
//if amount per row is:
if($amount == 1){
    //if how many rows into the loop we are
    if($loopedRow == 1){ ?>

        <div class="<?php echo $divi_column_class . $one_column_class; ?>">
            <?php echo renderPhpToString($post_full_width); ?>
        </div>

    <?php
    }//if looped row = 1

    if($loopedRow == 2){ ?>

        <div class="<?php echo $divi_column_class . $one_third ?>">
            <?php echo renderPhpToString($post_title) . renderPhpToString($post_excerpt) . renderPhpToString($post_view_more_btn); ?>
        </div>

        <div class="<?php echo $divi_column_class . $two_thirds . $last_child; ?>">
            <?php echo renderPhpToString($post_thumbnail); ?>
        </div>

    <?php

    }//if looped row = 2

}//if amount = 1

if($amount == 3){

    $class = $divi_column_class . $one_third;
    if ($column_position == $amount){
        $class = $class . $last_child;
    }
    ?>

    <div class="<?php echo $class;?>">
        <?php echo renderPhpToString($post_thumbnail) . renderPhpToString($post_title) . renderPhpToString($post_excerpt) . renderPhpToString($post_link); ?>
    </div>

   
<?php } 

//} 