<?php
/**
 * The template for displaying reports archive page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
   http_response_code(403);
   die( '403 Forbidden' );
 }
 
 get_header();
 global $wp_query;
 
 require_once('template-react-loader.php');
 
 if ( post_password_required() ) {
   echo '<div class="password-protected">' . get_the_password_form() . '</div>';
   return;
 }
?>

<div class="template-content">
  <h1 class="archive__page-title"><?php post_type_archive_title(); ?>s</h1>
  
  <div class="section-container">    
    <div class="archive__list" data-page="<?= get_query_var('paged') ? get_query_var('paged') : 1; ?>" data-max="<?php echo $wp_query->max_num_pages; ?>">
      <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/content-archive-reports'); ?>
      <?php endwhile; else: _e('Sorry, no content found', 'theme'); endif; ?>             
    </div>
    
    <?php previous_posts_link('Previous Page'); ?>
    <?php next_posts_link('Next Page'); ?> 
    
    <div class="archive__button-wrapper">
      <button class="button__load-more-posts button--orange">Load More Posts</button>
    </div>
  </div>
</div>

<?php
if ( isset( $inline_script ) ) {
  // Output the inline script that contains all the page data that the React frontend will use to render the page
  echo '<script>' . $inline_script . '</script>';
}
?>

<script>
  const loadMoreBtn = document.querySelector('.button__load-more-posts');
  
  if(loadMoreBtn) {
    loadMoreBtn.addEventListener('click', (e) => {
      e.preventDefault();
      
      let current_page = document.querySelector('.archive__list').dataset.page;
      let max_pages = document.querySelector('.archive__list').dataset.max;      
      let params = new URLSearchParams();
      
      params.append('action', 'load_more_posts');
      params.append('current_page', current_page);
      params.append('max_pages', max_pages);
      
      fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
         'Content-Type': 'application/x-www-form-urlencoded',
         'Cache-Control': 'no-cache',
        },
        body: params
      }).then(response => {
        return response.json();               
      })
      .then(response => {
        let archiveList = document.querySelector('.archive__list');         
        
         if(archiveList.dataset.page === archiveList.dataset.max) {
           // If there isn't any more posts to show, hide load more posts button
          loadMoreBtn.style.display = 'none';
         }else {
           // Add data to page and update page count
           archiveList.innerHTML += response.data;
           archiveList.dataset.page++;
           
           // Update page url
           let getUrl = window.location;
           let baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
           
           window.history.pushState('', '', baseUrl + 'reports/page/' + archiveList.dataset.page);           
         }                  
         
         // read data here
         console.log(response);
       }).catch(err => { 
         console.log(err);
       });          
    });
  }
</script>
<?php get_footer(); ?>