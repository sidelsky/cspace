<?php
class FilterList {
    public static function render($featured, $thinking, $work) {
        $html .= '<div id="filter-list">';
        $html .= '<h1>Featured</h1>';
            foreach ($featured as $promo) {
                $html .= '<h3>' . $promo['title'] .  '</h3>';
            }
        $html .= '</div>';
    
        $html .= '<div>';
        $html .= '<h1>Work</h1>';
            foreach ($work as $promo) {
                $html .= '<h3>' . $promo['title'] .  '</h3>';
            }
        $html .= '</div>';
    
        $html .= '<div>';
            $html .= '<h1>Thinking</h1>';
            foreach ($thinking as $promo) {
                $html .= '<h3>' . $promo['title'] .  '</h3>';
            }
        $html .= '</div>';

        return $html;
    }
}