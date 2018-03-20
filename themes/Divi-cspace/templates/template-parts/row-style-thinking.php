<?php
//if amount per row is:
if($amount == 1){ ?>

    <div class="<?php echo $divi_column_class .  $one_column_class; ?>">
        <?php echo renderPhpToString($post_full_width); ?>
    </div>

<?php
}

if($amount == 4){ 
    // add the last child class every 2 cols
    $class = $divi_column_class . $two_column_class;
    if($column_position == $amount / 2){
        $class = $class . $last_child;
    }

    //output column
    ?>
    <div class="<?php echo $class; ?>">
        <?php echo renderPhpToString($post_half_width); ?>
    </div>

    <?php // split four into 2 x 2 in rows 
        if($column_position == $amount / 2){ ?>
        </div><div class="<?php echo $row_class . ' auto-layout-generated-row'; ?>">
    <?php } 

}





