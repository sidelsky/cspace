<?php
class FilterList {
    public static function render($featured, $work, $thinking) {
        $html .= '<div id="filter-list">';
        if(count($featured) > 0) {
            $html .= '<div>';
            $html .= '<h1>Featured</h1>';
                foreach ($featured as $promo) {
                    $html .= '<h3>' . $promo['title'] .  '</h3>';
                }
            $html .= '</div>';
        }

        if (count($work) > 0) {
            $html .= '<div>';
            $html .= '<h1>work</h1>';
                foreach ($work as $promo) {
                    $html .= '<h3>' . $promo['title'] .  '</h3>';
                }
            $html .= '</div>';
        }

        if (count($thinking) > 0) {
            $html .= '<div>';
            $html .= '<h1>thinking</h1>';
                foreach ($thinking as $promo) {
                    $html .= '<h3>' . $promo['title'] .  '</h3>';
                }
            $html .= '</div>';
        }

        return $html;
    }
}