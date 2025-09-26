<?php
if(!$blogpost || !is_countable($blogpost) || count((array)$blogpost) <= 0){
    echo '<div class="alert alert-danger">This post does not exist or has been removed.</div>';
} else {
?>
<div class="page-heading-two">
    <div class="container">
        <div class="col-md-7">
            <h5><?php echo get_blog_data_by_lang($blogpost,'title'); ?></h5>
        </div>
        <div class="col-md-5">
            <div class="breads">
                <a href="<?php echo site_url(); ?>"><?php echo lang_key('home'); ?></a> / <?php echo get_blog_data_by_lang($blogpost,'title'); ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="container">
    <div class="blog-two">
        <div class="row">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="blog-two-item">
                    <div class="blog-two-content">
                        <div class="blog-meta">
                            <i class="fa fa-calendar"></i> &nbsp; <?php echo translatedDate($blogpost->create_time);?> &nbsp;
                            <i class="fa fa-user"></i> &nbsp; <?php echo get_user_fullname_by_id($blogpost->created_by); ?> &nbsp;
                        </div>
                        <p>
                            <img src="<?php echo base_url('uploads/images/' . $blogpost->featured_img); ?>" class="blog-image" alt="">
                            <?php echo get_blog_data_by_lang($blogpost,'description');?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <?php render_widgets('right_bar_blog_posts');?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
