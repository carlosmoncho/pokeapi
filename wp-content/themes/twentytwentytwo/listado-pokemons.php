<?php
/*
Template Name: Lista de Pokémon
*/

get_header();
?>

<div class="container mt-5">
    <div class="row">
        <?php
        $args = array(
            'post_type' => 'pokemon',
            'posts_per_page' => 12
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $pokemon_slug = sanitize_title(get_the_title());
                ?>
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><a href="<?php echo site_url('/pokemon/' . $pokemon_slug); ?>"><?php the_title(); ?></a></h5>
                        </div>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p class="text-center">No se encontraron Pokémon.</p>';
        }
        ?>
    </div>
</div>

<?php
get_footer();
?>
