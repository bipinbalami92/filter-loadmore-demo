<?php
// Selected Category Filter
$selected_cat = isset( $_POST['selected_cat'] ) ? $_POST['selected_cat'] : '';
$cat_slug = get_term_by( 'name', $selected_cat, 'category' );

// Return Selected Page Number
if( isset( $_POST['selected_paged'] ) ){
    // Current page number for pagination
    $selected_paged = isset( $_POST['selected_paged'] ) ? $_POST['selected_paged'] : '';
}else{
    // Current page number for category filter
    $selected_paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
}

// posts per page
$ppr = isset( $_POST['posts_per_page'] ) ? $_POST['posts_per_page'] : 5;

// args
$args = array(
    'posts_per_page' => $ppr,
    'post_type' => 'post',
    'post_status' => 'publish',
    'order'   => 'DESC',
    'paged' => $selected_paged,
);

// Selected Category
if( $cat_slug ){
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => array( $cat_slug->slug ),
        ),
    );
}
$filter_query = new WP_Query( $args );
if ( $filter_query->have_posts() ) : ?>
    <div class="blog-content-section">
        <div class="row">
            <?php while ( $filter_query->have_posts() ) : $filter_query->the_post(); ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="blog-section">
                            <div class="blog-image">
                                <h2 class="entry-title default-max-width"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php
                                    if( has_post_thumbnail() ){
                                        the_post_thumbnail( ' img-responsive' );
                                    }
                                ?>
                                <?php
                                    $category = get_the_category();
                                    $useCatLink = true;
                                    if ( $category ){
                                        $category_display = '';
                                        if ( class_exists( 'WPSEO_Primary_Term' ) ){
                                            $wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
                                            $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
                                            $term = get_term( $wpseo_primary_term );
                                            if ( is_wp_error( $term ) ) {
                                                $category_display = $category[0]->name;
                                                $category_link = get_bloginfo('url') . '/' . 'category/' . $term->slug;
                                            } else {
                                                // Yoast Primary category
                                                $category_display = $term->name;
                                                $category_link = get_term_link( $term->term_id );
                                            }
                                        }
                                        else {
                                            $category_display = $category[0]->name;
                                            $category_link = get_term_link( $category[0]->term_id );
                                        }

                                        // Display category
                                        if ( ! empty( $category_display ) ){
                                            if ( $useCatLink == true && !empty( $category_link ) ){
                                                echo '<a href="'.$category_link.'" class="category-name">'. esc_html( $category_display ) .'</a>';
                                            } else {
                                                echo '<span class="category-name">'.esc_html( $category_display ).'</span>';
                                            }
                                        }
                                    }
                                ?>
                            </div>
                            <div class="blog-content">
                                <div class="admin-section">
                                    <ul>
                                        <li class="author"><?php echo get_the_author(); ?></li>
                                        <li><?php echo get_the_date( 'F j, Y' ); ?></li>
                                    </ul>
                                </div>
                                <p><?php echo get_the_excerpt(); ?></p>
                                <div class="blog-btn butm"><a href="<?php echo get_the_permalink(); ?>"><?php _e( 'Read More', 'care-dent' ); ?></a></div>
                            </div>
                        </div>
                    </div>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="pagination">
        <?php
            $base = trailingslashit( get_home_url( ) .'/blogs/page' ) . "{$filter_query->pagination_base}%#%/";
            echo paginate_links( array(
                'base'         => $base,
                'total'        => $filter_query->max_num_pages,
                'current'      => $selected_paged,
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'plain',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => true,
                'prev_text'    => sprintf( '<i></i> %1$s', __( 'PREVIOUS', 'care-dent' ) ),
                'next_text'    => sprintf( '%1$s <i></i>', __( 'NEXT', 'care-dent' ) ),
                'add_args'     => false,
                'add_fragment' => '',
            ) );
        ?>
    </div>
<?php
    else:
?>
    <h2>No posts found...</h2>
<?php
endif;
wp_reset_postdata();
