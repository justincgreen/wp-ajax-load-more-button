<?php
/**
 * The template for displaying webinars archive page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
   http_response_code(403);
   die( '403 Forbidden' );
 }
 
 get_header();
 
 require_once('template-react-loader.php');
 
 if ( post_password_required() ) {
   echo '<div class="password-protected">' . get_the_password_form() . '</div>';
   return;
 }
?>

<div class="template-content">
  <h1 class="archive__page-title"><?php post_type_archive_title(); ?>s</h1>
  
  <div class="section-container">    
    <div class="archive__list">
      <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <?php
          $post_ID = get_the_id();
          $registration_link = get_field('registration_link', $post_ID);
        ?> 
      
        <a href="<?php echo $registration_link ? $registration_link : '/webinars' ?>" class="archive__post" target="_blank">
          <div class="archive__post-thumbnail">
            <?php if(has_post_thumbnail()):?>
              <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" />
            <?php endif; ?>
          </div>          
          <h2 class="archive__post-title"><?php the_title(); ?></h2>
        </a>
      <?php endwhile; else: _e('Sorry, no content found', 'theme'); endif; ?>            
    </div>
    
    <?php previous_posts_link('Previous Page'); ?>
    <?php next_posts_link('Next Page'); ?> 
  </div>
</div>

<?php
if ( isset( $inline_script ) ) {
  // Output the inline script that contains all the page data that the React frontend will use to render the page
  echo '<script>' . $inline_script . '</script>';
}
?>
<?php get_footer(); ?>