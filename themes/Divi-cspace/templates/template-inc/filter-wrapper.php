<?php

include('filter-select.php');
include('filter-list.php');

$pageId = 157;

function makePromo($post) {
    return [
        'id' => get_the_id(),
        'title' =>get_the_title(),
        'type' => get_post_type()
    ];
}


function renderAllFilters() {
    $featured = [];
    $work = [];
    $thinking = [];

    foreach(get_field('featured', $pageId) as $post) {
        $featured[] = makePromo($post);
    }

    foreach(get_field('working', $pageId) as $post) {
        $work[] = makePromo($post);
    }

    foreach(get_field('thinking', $pageId) as $post) {
        $thinking[] = makePromo($post);
    }

    $html = '';
    $html .= '<div class="filter-wrapper">hello';
    $html .= FilterSelect::render();
    $html .= FilterList::render($featured, $work, $thinking);
    $html .= '</div>';

    return $html;
}

