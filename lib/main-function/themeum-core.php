<?php

/*-------------------------------------------------------
*              Themeum Supports and Image Cut
*-------------------------------------------------------*/
if(!function_exists('melody_setup')):
    function melody_setup(){
        load_theme_textdomain( 'melody', get_template_directory() . '/languages' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'melody-large', 1110, 570, true );
        add_image_size( 'ico-blog', 340, 255, true );
        add_image_size( 'melody-feature-artist', 345, 240, true ); # feature Artist
        add_theme_support( 'post-formats', array( 'audio','gallery','image','link','quote','video' ) );
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
        add_theme_support( 'automatic-feed-links' );
    }
    add_action('after_setup_theme', 'melody_setup');

endif;

/*-------------------------------------------------------
*              Themeum Pagination
*-------------------------------------------------------*/
if(!function_exists('icocrypt_pagination')):

    function icocrypt_pagination( $page_numb , $max_page ){
        $big = 999999999;
        echo '<div class="themeum-pagination">';
        echo paginate_links( array(
            'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'        => '?paged=%#%',
            'current'       => $page_numb,
            'prev_text'     => __('<i class="fa fa-angle-left" aria-hidden="true"></i> Previous','melody'),
            'next_text'     => __('Next <i class="fa fa-angle-right" aria-hidden="true"></i>','melody'),
            'total'         => $max_page,
            'type'          => 'list',
        ) );
        echo '</div>';
    }

endif;

/*-------------------------------------------------------
*              Social Share
*-------------------------------------------------------*/
if(!function_exists('themeum_social_share_cat')):
    
    function themeum_social_share_cat( $post_id ){
        global $themeum_options;
        $output ='';
        $media_url = '';
        $title = get_the_title( $post_id );
        $permalink = get_permalink( $post_id );

        if( has_post_thumbnail( $post_id ) ){
            $thumb_src =  wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' ); 
            $media_url = $thumb_src[0];
        }

        $output .= '<div class="melody-post-share-social">';
            $output .= '<div class="share-icon"><span class="icon-big"><i class="fa fa-share-square-o"></i></span>';
            if(get_theme_mod( 'blog_share_fb', true )){
            $output .= '<a href="#" data-type="facebook" data-url="'.esc_url( $permalink ).'" data-title="'.esc_html( $title ).'" data-description="'. esc_html( $title ).'" data-media="'.esc_url( $media_url ).'" class="prettySocial fa fa-facebook"></a>';
             }
             if(get_theme_mod( 'blog_share_tw', true )){
            $output .= '<a href="#" data-type="twitter" data-url="'.esc_url( $permalink ).'" data-description="'.esc_html( $title ).'" data-via="'.$themeum_options['twitter-username'].'" class="prettySocial fa fa-twitter"></a>';
            }
            if(get_theme_mod( 'blog_share_gp', true )){
            $output .= '<a href="#" data-type="googleplus" data-url="'.esc_url( $permalink ).'" data-description="'.esc_html( $title ).'" class="prettySocial fa fa-google-plus"></a>';
            }
            if(get_theme_mod( 'blog_share_pn', false )){
            $output .= '<a href="#" data-type="pinterest" data-url="'.esc_url( $permalink ).'" data-description="'.esc_html( $title ).'" class="prettySocial fa fa-pinterest"></a>';
            }

            if(get_theme_mod( 'blog_share_ln', false )){
            $output .= '<a href="#" data-type="linkedin" data-url="'.esc_url( $permalink ).'" data-description="'.esc_html( $title ).'" class="prettySocial fa fa-linkedin"></a>';
            }
        
        $output .= '</div>';
        
        $output .= '</div>';

        return $output;
    }

endif;


/*-------------------------------------------------------
*              Themeum Comment
*-------------------------------------------------------*/
if(!function_exists('melody_comment')):

    function melody_comment($comment, $args, $depth){
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
            case 'pingback' :
            case 'trackback' :
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <?php
            break;
            default :
            global $post;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-body row">
                <div class="comment-avartar col-1">
                    <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
                </div>
                
                <div class="comment-content col-11">
                    <div class="comment-context">
                        <div class="comment-head">
                            <?php
                                printf( '<span class="comment-author">%1$s</span>',
                                    get_comment_author_link());
                            ?>
                            <span class="comment-date"><?php echo get_comment_date() ?></span>
                            <span><?php echo comment_time(); ?></span>
                            <?php edit_comment_link( esc_html__( 'Edit', 'melody' ), '<span class="edit-link">', '</span>' ); ?>
                        </div>
                        <?php if ( '0' == $comment->comment_approved ) : ?>
                            <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'melody' ); ?></p>
                        <?php endif; ?>
                        
                    </div>
                    <?php comment_text(); ?>
                    <span class="comment-reply">
                        <?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="fa fa-reply" aria-hidden="true"></i> '.esc_html__( 'Reply', 'melody' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    </span>
                </div>
            </div>
        <?php
            break;
        endswitch;
    }

endif;

/*-----------------------------------------------------
*              Coming Soon Page Settings
*----------------------------------------------------*/
if ( get_theme_mod( 'comingsoon_en', false ) ) {
    if(!function_exists('melody_my_page_template_redirect')):
        function melody_my_page_template_redirect()
        {
            if( is_page() || is_home() || is_category() || is_single() )
            {
                if( !is_super_admin( get_current_user_id() ) ){
                    get_template_part( 'coming','soon');
                    exit();
                }
            }
        }
        add_action( 'template_redirect', 'melody_my_page_template_redirect' );
    endif;

    if(!function_exists('melody_cooming_soon_wp_title')):
        function melody_cooming_soon_wp_title(){
            return 'Coming Soon';
        }
        add_filter( 'wp_title', 'melody_cooming_soon_wp_title' );
    endif;
}

/*-----------------------------------------------------
 *              CSS Generator
 *----------------------------------------------------*/
if(!function_exists('melody_css_generator')){
    function melody_css_generator(){

        $output = '';

        $preset = get_theme_mod( 'preset', '1' );
        if( $preset ){

            if( get_theme_mod( 'custom_preset_en', true ) ) {

                // CSS Color
                $major_color = get_theme_mod( 'major_color', '#FF0078' );

                if($major_color){
                    $output .= '
                    a,
                    .icocrypt-blog-title h3 a:hover,
                    .blog-post-meta-bottom li a:hover,
                    .footer-menu ul.footer-nav li a:hover,
                    .bottom-widget .widget ul li a:hover,
                    .thm-default-postbox h2 a:hover,
                    .widget ul li a:hover,
                    .map-single-content i,
                    .entry-header h2.entry-title.blog-entry-title a:hover,
                    .entry-summary .wrap-btn-style a.btn-style:hover,
                    .main-menu-wrap .custom-navbar-toggle:hover,
                    .melody-post .blog-post-meta li a:hover,
                    .melody-post .content-item-title a:hover,
                    #mobile-menu ul li.active>a,
                    #mobile-menu ul li a:hover,
                    .btn.btn-border-melody,
                    .entry-summary .wrap-btn-style a.btn-style,
                    .social-share-wrap ul li a:hover,
                    .mc4wp-form-fields p i,
                    .category-feature-post .common-post-item-intro .entry-title a:hover,
                    .overlay-btn,
                    .melody-post .blog-post-meta .meta-category a:hover,
                    .melody-post-share-social .share-icon .icon-big,
                    .melody-blog-content h2 a:hover,
                    .melody-post .content-item-title a:hover,
                    .melody-widgets a:hover,.elementor-accordion-title.active .elementor-accordion-icon i,
                    .header-solid .common-menu-wrap .custom-nav>li.active>a:after,
                    .theme-color,
                    .row.performers-container.events-ticket-price:hover span.price,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li ul.wp-megamenu-sub-menu li.current-menu-item a,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li>a:hover,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li.current-menu-parent>a,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li.current-menu-item>a,
                    .main-menu-wrap .custom-navbar-toggle,
                    .thm-heading-wrap .total-content h2 a:hover,
                    .article-details .article-title a:hover,
                    .common-menu-wrap .custom-nav>li.current-menu-item a,
                    .header-borderimage .common-menu-wrap .custom-nav>li.active>a,
                    .site-header .common-menu-wrap .custom-nav>li .sub-menu li a:hover,
                    .common-post-item-intro h4.entry-title a:hover,
                    .widget.widget_themeum_about_widget .themeum-about-info li span.themeum-widget-address,
                    .btn.btn-melody:hover,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li ul.wp-megamenu-sub-menu li a:hover
                    { color: '. esc_attr($major_color) .'; }';
                }

                //Css Color Important
                if($major_color){
                    $output .= '.social-share a:hover, .login-link a:hover,
                    .header-borderimage .common-menu-wrap .custom-nav>li.menu-item-has-children:after,
                    .common-menu-wrap .custom-nav>li>a:hover,
                    .common-menu-wrap .custom-nav>li>a:focus,
                    .common-menu-wrap .custom-nav>li.current_page_item a,
                    .common-menu-wrap .custom-nav>li>ul li.current-menu-item a,
                    .common-menu-wrap .custom-nav>li>ul li.current-menu-parent a,
                    .widget.widget_themeum_about_widget .themeum-about-info li span.themeum-widget-address,
                    .widget.widget_themeum_social_share_widget .themeum-social-share li a:hover
                    {color: '.$major_color.' !important}';
                }

                // CSS Background Color
                if($major_color){
                    $output .= '
                    .entry-link-post-format, .entry-quote-post-format,
                    .wpmm-gridcontrol-left:hover, .wpmm-gridcontrol-right:hover,
                    .single-author-meta .btn-follow:hover,
                    .melody-widgets .meta-category a,
                    .melody-event input[type=submit],
                    #wp-megamenu-primary>.wpmm-nav-wrap .wpmm-tab-btns li:hover,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li.menu-item-has-children ul.wp-megamenu-sub-menu li:hover a,
                    .slider-content-inner .btn.btn-transparent:hover,
                    .slider_content .btn,
                    .modal .modal-content .modal-body input[type="submit"]:hover,
                    .newsletter_contact_modal .btn-newsletter,
                    .wpmm_mobile_menu_btn,
                    .thm-search-icon span.search-close::before,
                    .coming-soon-contact-head::before,
                    .newsletter_contact_modal button.btn:active,
                    #comments input[type=submit],
                    .themeum-pagination .page-numbers li span.current,
                    .themeum-pagination .page-numbers li a:hover,
                    .wpmm-mobile-menu a.wpmm_mobile_menu_btn:hover,
                    .melody-post-share-social .share-icon .icon-big:hover,
                    .classic-slider .owl-dots .active>span,
                    .thm-default-postbox ul li.meta-category a,
                    .post-meta-info-list-in a:hover,
                    .widget .tagcloud a:hover,
                    .thm-slide-control li.slick-active button,
                    .btn.btn-melody,
                    .modal .modal-content .modal-body input[type="submit"],
                    .modal .modal-content .modal-body input[type="submit"]:hover,
                    .thm-default-postbox .thm-post-bg,
                    .thm-post-top .meta-category a:hover,
                    .melody-quote,
                    #sidebar h3.widget_title::before,
                    .featured-wrap-link .entry-link-post-format,
                    .featured-wrap-quite .entry-quote-post-format
                    { background: '. esc_attr($major_color) .'; }';
                }
              
                // CSS Border
                if($major_color){
                    $output .= '
                    input:focus,
                    .row.performers-container:hover,
                    textarea:focus,
                    .wpmm-gridcontrol-left:hover, .wpmm-gridcontrol-right:hover,
                    div.wpcf7-validation-errors,
                    .melody-event .wpcf7-form-control:focus,
                    .slider-content-inner .btn.btn-transparent:hover,
                    keygen:focus,
                    .mc4wp-form-fields p input:focus,
                    select:focus,
                    .modal .modal-content .modal-body input[type="submit"],
                    .comments-area textarea:focus,
                    .common-menu-wrap .custom-nav>li.current>a,
                    .header-solid .common-menu-wrap .custom-nav>li.current>a,
                    .blog-arrows a:hover,
                    .wpcf7-submit,
                    .themeum-pagination .page-numbers li a:hover,
                    .themeum-pagination .page-numbers li span.current,
                    .wpcf7-form input:focus,
                    .thm-post-top .meta-category a:hover,
                    .thm-fullscreen-search form input[type="text"],
                    .btn.btn-melody
                    { border-color: '. esc_attr($major_color) .'; }';
                }


            }

            // Custom Color
            if( get_theme_mod( 'custom_preset_en', true ) ) {
                $hover_color = get_theme_mod( 'hover_color', '#FF0078' );
                if( $hover_color ){
                    $output .= 'a:hover,
                    .bottom-widget .widget ul li a:hover,
                    .thm-default-postbox h2 a:hover,
                    .widget ul li a:hover,
                    .entry-header h2.entry-title.blog-entry-title a:hover,
                    .entry-summary .wrap-btn-style a.btn-style:hover,
                    .main-menu-wrap .custom-navbar-toggle:hover,
                    .melody-post .blog-post-meta li a:hover,
                    .melody-post .content-item-title a:hover,
                    #mobile-menu ul li a:hover,
                    .social-share-wrap ul li a:hover,
                    .category-feature-post .common-post-item-intro .entry-title a:hover,
                    .melody-blog-content h2 a:hover,
                    .melody-post .content-item-title a:hover,
                    .thm-heading-wrap .total-content h2 a:hover,
                    .article-details .article-title a:hover,
                    .site-header .common-menu-wrap .custom-nav>li .sub-menu li a:hover,
                    .common-post-item-intro h4.entry-title a:hover,
                    .widget.widget_themeum_about_widget .themeum-about-info li span.themeum-widget-address,
                    .btn.btn-melody:hover,
                    .widget.widget_rss ul li a,
                    .thm-heading-wrap h2 a:hover,
                    #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li>a:hover,
                    .thm-slider-wrap .total-content h2 a:hover,
                    .footer-copyright a:hover,
                    .entry-summary .wrap-btn-style a.btn-style:hover, .thm-single-event:hover .up_event_date{ color: '.esc_attr( $hover_color ) .'; }';

                    $output .= '.error-page-inner a.btn.btn-primary.btn-lg:hover,
                    .btn.btn-primary:hover,
                    input[type=button]:hover,
                    .widget.widget_search #searchform .btn-search:hover,
                     .order-view .label-info:hover{ background-color: '.esc_attr( $hover_color ) .'; }';

                    $output .= '.woocommerce a.button:hover{ border-color: '.esc_attr( $hover_color ) .'; }';
                }
            }
        }

        $bstyle = $mstyle = $h1style = $h2style = $h3style = $h4style = $h5style = '';
        //body
        if ( get_theme_mod( 'body_font_size', '14' ) ) { $bstyle .= 'font-size:'.get_theme_mod( 'body_font_size', '14' ).'px;'; }
        if ( get_theme_mod( 'body_google_font', 'Montserrat' ) ) { $bstyle .= 'font-family:'.get_theme_mod( 'body_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'body_font_weight', '400' ) ) { $bstyle .= 'font-weight: '.get_theme_mod( 'body_font_weight', '400' ).';'; }
        if ( get_theme_mod('body_font_height', '24') ) { $bstyle .= 'line-height: '.get_theme_mod('body_font_height', '24').'px;'; }
        if ( get_theme_mod('body_font_color', '#707070') ) { $bstyle .= 'color: '.get_theme_mod('', '#707070').';'; }

        //menu
        $mstyle = '';
        if ( get_theme_mod( 'menu_font_size', '16' ) ) { $mstyle .= 'font-size:'.get_theme_mod( 'menu_font_size', '16' ).'px;'; }
        if ( get_theme_mod( 'menu_google_font', 'Montserrat' ) ) { $mstyle .= 'font-family:'.get_theme_mod( 'menu_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'menu_font_weight', '300' ) ) { $mstyle .= 'font-weight: '.get_theme_mod( 'menu_font_weight', '300' ).';'; }
        if ( get_theme_mod('menu_font_height', '54') ) { $mstyle .= 'line-height: '.get_theme_mod('menu_font_height', '34').'px;'; }

        //heading1
        $h1style = '';
        if ( get_theme_mod( 'h1_font_size', '42' ) ) { $h1style .= 'font-size:'.get_theme_mod( 'h1_font_size', '42' ).'px;'; }
        if ( get_theme_mod( 'h1_google_font', 'Montserrat' ) ) { $h1style .= 'font-family:'.get_theme_mod( 'h1_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'h1_font_weight', '400' ) ) { $h1style .= 'font-weight: '.get_theme_mod( 'h1_font_weight', '700' ).';'; }
        if ( get_theme_mod('h1_font_height', '42') ) { $h1style .= 'line-height: '.get_theme_mod('h1_font_height', '42').'px;'; }
        if ( get_theme_mod('h1_font_color', '#333') ) { $h1style .= 'color: '.get_theme_mod('h1_font_color', '#333').';'; }

        # heading2
        $h2style = '';
        if ( get_theme_mod( 'h2_font_size', '36' ) ) { $h2style .= 'font-size:'.get_theme_mod( 'h2_font_size', '36' ).'px;'; }
        if ( get_theme_mod( 'h2_google_font', 'Montserrat' ) ) { $h2style .= 'font-family:'.get_theme_mod( 'h2_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'h2_font_weight', '400' ) ) { $h2style .= 'font-weight: '.get_theme_mod( 'h2_font_weight', '600' ).';'; }
        if ( get_theme_mod('h2_font_height', '36') ) { $h2style .= 'line-height: '.get_theme_mod('h2_font_height', '36').'px;'; }
        if ( get_theme_mod('h2_font_color', '#222538') ) { $h2style .= 'color: '.get_theme_mod('h2_font_color', '#222538').';'; }

        //heading3
        $h3style = '';
        if ( get_theme_mod( 'h3_font_size', '26' ) ) { $h3style .= 'font-size:'.get_theme_mod( 'h3_font_size', '26' ).'px;'; }
        if ( get_theme_mod( 'h3_google_font', 'Montserrat' ) ) { $h3style .= 'font-family:'.get_theme_mod( 'h3_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'h3_font_weight', '400' ) ) { $h3style .= 'font-weight: '.get_theme_mod( 'h3_font_weight', '600' ).';'; }
        if ( get_theme_mod('h3_font_height', '28') ) { $h3style .= 'line-height: '.get_theme_mod('h3_font_height', '28').'px;'; }
        if ( get_theme_mod('h3_font_color', '#222538') ) { $h3style .= 'color: '.get_theme_mod('h3_font_color', '#222538').';'; }

        //heading4
        $h4style = '';
        if ( get_theme_mod( 'h4_font_size', '18' ) ) { $h4style .= 'font-size:'.get_theme_mod( 'h4_font_size', '18' ).'px;'; }
        if ( get_theme_mod( 'h4_google_font', 'Montserrat' ) ) { $h4style .= 'font-family:'.get_theme_mod( 'h4_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'h4_font_weight', '400' ) ) { $h4style .= 'font-weight: '.get_theme_mod( 'h4_font_weight', '600' ).';'; }
        if ( get_theme_mod('h4_font_height', '26') ) { $h4style .= 'line-height: '.get_theme_mod('h4_font_height', '26').'px;'; }
        if ( get_theme_mod('h4_font_color', '#222538') ) { $h4style .= 'color: '.get_theme_mod('h4_font_color', '#222538').';'; }

        //heading5
        $h5style = '';
        if ( get_theme_mod( 'h5_font_size', '14' ) ) { $h5style .= 'font-size:'.get_theme_mod( 'h5_font_size', '14' ).'px;'; }
        if ( get_theme_mod( 'h5_google_font', 'Montserrat' ) ) { $h5style .= 'font-family:'.get_theme_mod( 'h5_google_font', 'Montserrat' ).';'; }
        if ( get_theme_mod( 'h5_font_weight', '400' ) ) { $h5style .= 'font-weight: '.get_theme_mod( 'h5_font_weight', '600' ).';'; }
        if ( get_theme_mod('h5_font_height', '26') ) { $h5style .= 'line-height: '.get_theme_mod('h5_font_height', '26').'px;'; }
        if ( get_theme_mod('h5_font_color', '#222538') ) { $h5style .= 'color: '.get_theme_mod('h5_font_color', '#222538').';'; }

        $output .= 'body{'.$bstyle.'}';
        // $output .= '.common-menu-wrap .custom-nav>li>a{'.$mstyle.'}';
        $output .= 'h1{'.$h1style.'}';
        $output .= 'h2{'.$h2style.'}';
        $output .= 'h3{'.$h3style.'}';
        $output .= 'h4{'.$h4style.'}';
        $output .= 'h5{'.$h5style.'}';
        $output .= '.heading-font, .secondary-font{font-family: '. get_theme_mod( 'h1_google_font', 'Montserrat' ).'}';
        $output .= '.body-font, .main-font{font-family: '. get_theme_mod( 'body_google_font', 'Montserrat' ).'}';


        //Header
        $header_bgc = get_post_meta( get_the_ID() , 'themeum_header_bgc', true);
        if($header_bgc){
            $output .= '.site-header{ background-color: '. $header_bgc .'; }';
        }else{
            $output .= '.site-header{ background-color: '.esc_attr( get_theme_mod( 'header_color', '#4a12f6' ) ) .'; }';
        }

        //Body
        $body_bgc = get_post_meta( get_the_ID() , 'themeum_body_bgc', true );
        if($body_bgc){
            $output .= 'body{ background-color: '. $body_bgc .'; }';
        }else{
            $output .= 'body{ background-color: '.esc_attr( get_theme_mod( 'body_bg_color', '#fff' ) ) .'; }';
        }

        $output .= '.header-wrapper{ padding-top: '. (int) esc_attr( get_theme_mod( 'header_padding_top', '10' ) ) .'px; }';
        $output .= '.header-wrapper{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'header_padding_bottom', '10' ) ) .'px; }';
        $output .= '.site-header{ margin-bottom: '. (int) esc_attr( get_theme_mod( 'header_margin_bottom', '0' ) ) .'px; }';

        // if(get_theme_mod( 'header_color', '' )){
        //     $headerbg = get_theme_mod( 'header_color', '');
        //     $output .= '.site-header .header-wrapper{ background-color: '.$headerbg.';}';
        // }
        //sticky Header
        if ( get_theme_mod( 'header_fixed', false ) ){
            $output .= '.site-header.sticky{ position:fixed;top:0;left:auto; z-index:99999;margin:0 auto; width:100%;-webkit-animation: fadeInDown 300ms;animation: fadeInDown 300ms;}';
            $output .= '.site-header.sticky.header-transparent .main-menu-wrap{ margin-top: 0;}';
            if ( get_theme_mod( 'sticky_header_color', '' ) ){
                $sticybg = get_theme_mod( 'sticky_header_color', '#fff');
                $output .= '.site-header.enable-sticky .header-wrapper{ background-color: '.$sticybg.';}';
            }
        }

        if (get_theme_mod( 'logo_width' )) {
            $output .= '.themeum-navbar-header .themeum-navbar-brand img{width:'.get_theme_mod( 'logo_width' ).'px;max-width:none;}';
        }

        if (get_theme_mod( 'logo_height' )) {
            $output .= '.themeum-navbar-header .themeum-navbar-brand img{height:'.get_theme_mod( 'logo_height' ).'px;}';
        }

         // sub header
        $output .= '.subtitle-cover h3{font-size:'.get_theme_mod( 'sub_header_title_size', '46' ).'px;color:'.get_theme_mod( 'sub_header_title_color', '#fff' ).';}';
        $output .= '.subtitle-cover{padding:'.get_theme_mod( 'sub_header_padding_top', '215' ).'px 0 '.get_theme_mod( 'sub_header_padding_bottom', '115' ).'px; margin-bottom: '.get_theme_mod( 'sub_header_margin_bottom', '60' ).'px;}';
        
        //body
        if (get_theme_mod( 'body_bg_img')) {
            $output .= 'body{ background-image: url("'.esc_attr( get_theme_mod( 'body_bg_img' ) ) .'");background-size: '.esc_attr( get_theme_mod( 'body_bg_size', 'cover' ) ) .';    background-position: '.esc_attr( get_theme_mod( 'body_bg_position', 'left top' ) ) .';background-repeat: '.esc_attr( get_theme_mod( 'body_bg_repeat', 'no-repeat' ) ) .';background-attachment: '.esc_attr( get_theme_mod( 'body_bg_attachment', 'fixed' ) ) .'; }';
        }

         $output .= '.melody-login-register a.melody-dashboard{ background-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#FF0078' ) ) .'; }';

        //menu color

        $nav_color = get_theme_mod( 'navbar_text_color', '#fff' );
       
        if ( $nav_color ) {
            $output .= '.logo-wrapper a, .site-header.header-borderimage .header-top-contact, .header-borderimage .common-menu-wrap .custom-nav>li.menu-item-has-children:after, .header-solid .common-menu-wrap .custom-nav>li>a, .header-borderimage .common-menu-wrap .custom-nav>li>a,
            .header-solid .common-menu-wrap .custom-nav>li>a:after, .header-borderimage .common-menu-wrap .custom-nav>li>a:after,.melody-search, .header-top-contact, .site-header.header-borderimage .header-top-contact, .social-share a, #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li>a{ color: '.esc_attr( $nav_color ) .'; }';

            $output .= '.login-link a:first-child::after{ background: '.esc_attr( $nav_color ) .' }';
        }


        //menu Hover color

        $nav_color = get_theme_mod( 'navbar_hover_text_color', '#FF0078' );
       
        if ( $nav_color ) {
            $output .= '.logo-wrapper a:hover, .site-header.header-borderimage .header-top-contact, .header-borderimage .common-menu-wrap .custom-nav>li.menu-item-has-children:after, .header-solid .common-menu-wrap .custom-nav>li>a, .header-borderimage .common-menu-wrap .custom-nav>li>a:hover,
            .header-solid .common-menu-wrap .custom-nav>li>a:after, .header-borderimage .common-menu-wrap .custom-nav>li>a:after,.melody-search, .site-header.header-transparent .common-menu-wrap .custom-nav>li>a:hover, .header-top-contact, .site-header.header-borderimage .header-top-contact, .social-share a, #wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li>a:hover{ color: '.esc_attr( $nav_color ) .'; }';

            $output .= '.login-link a:first-child::after{ background: '.esc_attr( $nav_color ) .' }';
        }

        // Active menu color

        $active_menu = get_theme_mod( 'navbar_active_text_color', '#FF0078' );
        if($active_menu){
            $output .= '#wp-megamenu-primary>.wpmm-nav-wrap ul.wp-megamenu>li.current-menu-item>a{color: '.esc_attr($active_menu).'}';
        }


        //submenu color
        $output .= '.common-menu-wrap .custom-nav>li ul{ background-color: '.esc_attr( get_theme_mod( 'sub_menu_bg', '#fff' ) ) .'; }';
        $output .= '.site-header .common-menu-wrap .custom-nav>li .sub-menu li a{ color: '.esc_attr( get_theme_mod( 'sub_menu_text_color', '#000' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'sub_menu_border', '#eef0f2' ) ) .'; }';
        $output .= '.site-header .common-menu-wrap .custom-nav>li .sub-menu li a:hover,
.site-header .common-menu-wrap .custom-nav>li .sub-menu li a:hover{ color: '.esc_attr( get_theme_mod( 'sub_menu_text_color_hover', '#FF0078' ) ) .';}';
        $output .= '.common-menu-wrap .custom-nav>li > ul::after{ border-color: transparent transparent '.esc_attr( get_theme_mod( 'sub_menu_bg', '#fff' ) ) .' transparent; }';

        //bottom
        $output .= '#bottom-wrap{ background-color: '.esc_attr( get_theme_mod( 'bottom_color', '#111111' ) ) .'; }';
        $output .= '#bottom-wrap,.bottom-widget .widget h3.widget-title{ color: '.esc_attr( get_theme_mod( 'bottom_text_color', '#f9f9f9' ) ) .'; }';
        $output .= '.widget_nav_menu ul li a{ color: '.esc_attr( get_theme_mod( 'bottom_link_color', '#7f7f7f' ) ) .'; }';
        //$output .= '#bottom-wrap a:hover i{ color: '.esc_attr( get_theme_mod( 'bottom_hover_color', '#FF0078' ) ) .'; }';
        $output .= '#bottom-wrap{ padding-top: '. (int) esc_attr( get_theme_mod( 'bottom_padding_top', '60' ) ) .'px; }';
        $output .= '#bottom-wrap{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'bottom_padding_bottom', '60' ) ) .'px; }';

        //Bottom Link Color
        $bottom_social_color = get_theme_mod('bottom_link_color', 'rgba(249,249,249,.8)');
        if($bottom_social_color){
            $output .= '.widget.widget_themeum_social_share_widget .themeum-social-share li a,
            .widget_nav_menu ul li a,
            #bottom-wrap .footer-single-wrap.footer-col-one ul li a
            { color: '.esc_attr( $bottom_social_color ) .'}';
        }

        //Bottom Link Hover Color
        $bottom_social_color = get_theme_mod('bottom_hover_color', '#232323');
        if($bottom_social_color){
            $output .= '.widget.widget_themeum_social_share_widget .themeum-social-share li a:hover,
            .widget_nav_menu ul li a:hover,
            #bottom-wrap .footer-single-wrap.footer-col-one ul li a:hover
            { color: '.esc_attr( $bottom_social_color ) .'}';
        }

        //footer
        $output .= '#footer-wrap{ background-color: '.esc_attr( get_theme_mod( 'copyright_bg_color', '#2B1CCA' ) ) .'; }';
        $output .= '.footer-copyright, .template-credit, #bottom-wrap ul.themeum-social-share { color: '.esc_attr( get_theme_mod( 'copyright_text_color', '#fff' ) ) .'; }';
        $output .= '.menu-footer-menu a{ color: '.esc_attr( get_theme_mod( 'copyright_link_color', '#7d91aa' ) ) .'; }';
        $output .= '.template-credit a:hover{ color: '.esc_attr( get_theme_mod( 'copyright_hover_color', '#FF0078' ) ) .'; }';
        $output .= '#footer-wrap{ padding-top: '. (int) esc_attr( get_theme_mod( 'copyright_padding_top', '30' ) ) .'px; }';
        $output .= '#footer-wrap{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'copyright_padding_bottom', '30' ) ) .'px; }';

        // 404 Page
        $output .= "body.error404,body.page-template-404{ width: 100%; height: 100%; min-height: 100%; }";

        return $output;
    }
}


/*-----------------------------------------------------
 *              Author Info
 *----------------------------------------------------*/

function melody_modify_user_contact_profile($profile_fields) {
        # Add new fields
        $profile_fields['follow']     = 'Follow URL';
        return $profile_fields;
    }
add_filter('user_contactmethods', 'melody_modify_user_contact_profile');
