<section class="entry-content" id="blog-section">
    <input type="hidden" id="bpn_data_values" data-ppr="<?php echo (int) $args['posts_per_page']; ?>">
    <div class="recent-article">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="recent-cat-name"><?php esc_html_e( 'All' );?></h3>
            </div>
            <div class="col-sm-4">
                <?php
                    wp_dropdown_categories( array(
                        'show_option_all' => 'All',
                        'orderby' => 'name',
                        'value_field' => 'name',
                        'order'   => 'ASC',
                        'id' => 'selected-category',
                        'exclude' => array( 1 ),
                        'hierarchical' => 1,
                        'echo'    => 1,
                        'hide_empty' => false
                    ) );
                ?>
            </div>
        </div>
    </div>
	<div class="spinner" style="display: none;">
	  <div class="bounce1"></div>
	  <div class="bounce2"></div>
	  <div class="bounce3"></div>
	</div>
	<div class="main-blog-content">
		<div class="blog-content-section">
			<div class="row">
				<?php
					$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
					$args = array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'posts_per_page' => $args['posts_per_page'],
						'order'   => 'DESC',
						'paged' => $paged
					);
					$query = new WP_Query( $args );
					if( $query->have_posts() ):
						while( $query->have_posts() ):
							$query->the_post();
				?>
                    <article>
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
                                                    echo '<a href="'.$category_link.'" class="category-name">'.$category_display.'</a>';
                                                } else {
                                                    echo '<span class="category-name">'.$category_display.'</span>';
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
                                    <div class="blog-btn butm"><a href="<?php echo get_the_permalink(); ?>"><?php esc_html_e( 'Read More', 'care-dent' ); ?></a></div>
                                </div>
                            </div>
                        </div>
                    </article>
				<?php
					endwhile;
					endif;
					wp_reset_postdata();
				?>
			</div>
		</div>
		<div class="pagination">
		    <?php
		        echo paginate_links( array(
		            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
		            'total'        => $query->max_num_pages,
		            'current'      => $paged,
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
	</div>
</section>
