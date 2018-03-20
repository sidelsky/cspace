<?php
//if amount per row is:
if($amount == 1){
    //if how many rows into the loop we are
    if($loopedRow == 1){ ?>

        <div class="<?php echo $divi_column_class . $one_third; ?>">
            <?php echo renderPhpToString($post_title) . renderPhpToString($post_excerpt) . renderPhpToString($post_view_more_btn); ?>
        </div>

        <div class="<?php echo $divi_column_class . $two_thirds . $last_child ; ?>">
            <?php echo renderPhpToString($post_thumbnail); ?>
        </div>

    <?php
    }//if looped row = 1

    if($loopedRow == 2){ ?>

        <div class="<?php echo $divi_column_class . $two_thirds ; ?>">
            <?php echo renderPhpToString($post_thumbnail); ?>
        </div>

        <div class="<?php echo $divi_column_class . $one_third . $last_child; ?>">
            <?php echo renderPhpToString($post_title) . renderPhpToString($post_excerpt) . renderPhpToString($post_view_more_btn); ?>
        </div>

    <?php

    }//if looped row = 2

}//if amount = 1

if($amount == 2){ ?>

    <?php if ($column_position == 1) { ?>

        <div class="<?php echo $divi_column_class . $two_column_class; ?>">
            <?php echo renderPhpToString($post_half_width); ?>
        </div>

    <?php } ?>

    <?php if ($column_position == 2) { ?>

        <div class="<?php echo $divi_column_class . $two_column_class . $last_child;?>">
            <?php echo renderPhpToString($post_half_width); ?>
        </div>

<?php } 

}

if($loopedRow == 4){ ?>

    <div class="<?php echo $divi_column_class . $two_column_class; ?>">
        <?php echo renderPhpToString($post_title) . renderPhpToString($post_excerpt) ; ?>
    </div>

    <div class="<?php echo $divi_column_class . $two_column_class . $last_child; ?>">
        <?php echo renderPhpToString($post_thumbnail); ?>
    </div>

<?php    
}