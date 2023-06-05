<?php
  $post_ID = get_the_id();
  $registration_link = get_field('registration_link', $post_ID);
?>        
<a href="<?php echo $registration_link ? $registration_link : '/reports' ?>" class="archive__post" target="_blank">
  <div class="archive__post-thumbnail">
    <?php if(has_post_thumbnail()):?>
      <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" />
    <?php endif; ?>
  </div>                  
  <span class="archive__post-category">Report</span>                     
  <h2 class="archive__post-title"><?php the_title(); ?></h2>
  <span class="archive__post-meta">
    <span class="archive__post-date"><?php echo get_the_date(); ?></span>
  </span>          
</a>