<?php

Class FilterSelect {
    public static function render() {
        $html = '';
        
        if( $terms = get_terms( 'category', 'orderby=name' ) ) : // to make it simple I use default categories
            $html .= '<select id="filter" name="categoryfilter"><option>Select category...</option>';
            foreach ( $terms as $term ) :
                $html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
            endforeach;
            $html .= '</select>';
        endif;

        return $html;
    }
}