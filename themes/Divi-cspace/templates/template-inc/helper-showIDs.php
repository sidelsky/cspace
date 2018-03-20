<?php

if(!function_exists('showIds')){ 

    function showIds(){

        //global $post_ids;

        //echo 'remaining ids: <br/> ';
        return print_r($post_ids);

    }

}