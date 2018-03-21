<?php 


class ApiEndpoints {
    public function registerRoutes() 
    {
        register_rest_route(
            'cs-api/',
            'filter',
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'filter']
            ]
        );
    }

    private function createQuery($categoryId)
    {
        return new WP_Query(array(
            'post_type' => ['thinking', 'work', 'post'],
            'cat' => $categoryId
        ));
    }

    public function filter($request) 
    {
        $categoryId = $request['categoryid'];
        $query = $this->createQuery($categoryId);

        $featured = [];
        $thinking = [];
        $work = [];

      if ($query->have_posts()) {
          while ($query->have_posts()) {
              $query->the_post();

              global $post;

              $promo = [
                  'id' => get_the_id(),
                  'title' =>get_the_title(),
                  'type' => get_post_type()
              ];

              $categories = get_categories();

              foreach($categories as $category) {
                  if ($category->slug === 'featured') {
                    $featured[] = $promo;
                    break;
                  } else {
                      switch(get_post_type()){
                          case 'work':
                          $work[] = $promo;
                          break;
                          
                          case 'thinking':
                          $thinking[] = $promo;
                          break;
                      }
                  }
              }
          }
          
          wp_reset_postdata();

          return new WP_REST_Response(FilterList::render($featured, $work, $thinking), 200);
      }
    }
}

