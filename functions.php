<?php
// AJAX Set up for load more button
//-----------------------------------------------------------------
function load_more_posts() {
  $next_page = $_POST['current_page'] + 1;
  
  $query = new WP_Query([
    'post_type' => 'report',
    'posts_per_page' => 15,
    'paged' => $next_page
  ]);
  
  //wp_send_json_success($query);
  
  // wp_send_json_error($query);
  
  if ($query->have_posts()) :
    ob_start();
    while ($query->have_posts()) : $query->the_post();
      get_template_part('template-parts/content-archive-reports');
    endwhile;
    
    wp_send_json_success(ob_get_clean());
  else :
    wp_send_json_error('No more posts!');
  endif;
}
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

//-----------------------------------------------------------------

// Redirect webinar cpt single view pages to webinar archive page (Not related to AJAX code above)

//-----------------------------------------------------------------
function redirect_single_webinar() {
  if ( ! is_singular( 'webinar' ) ) {
    return; 
  }      
  wp_redirect( get_post_type_archive_link( 'webinar' ), 301 );
  exit;
}
add_action( 'template_redirect', 'redirect_single_webinar' );

// Redirect report cpt single view pages to report archive page
function redirect_single_report() {
  if ( ! is_singular( 'report' ) ) {
    return; 
  }      
  wp_redirect( get_post_type_archive_link( 'report' ), 301 );
  exit;
}
add_action( 'template_redirect', 'redirect_single_report' );
?>