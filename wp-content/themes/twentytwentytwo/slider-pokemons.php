<?php
/*
Template Name: Slider de Pokémon
*/

get_header();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    html, body {
        position: relative;
        height: 100%;
    }

    body {
        background: #fff;
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color: #000;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper {
        width: 240px;
        height: 320px;
    }

    .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 22px;
        font-weight: bold;
        color: #fff;
    }

    .tipo-fire { background-color: #F08030; } 
    .tipo-water { background-color: #6890F0; } 
    .tipo-grass { background-color: #78C850; } 
    .tipo-electric { background-color: #F8D030; } 
    .tipo-ice { background-color: #98D8D8; } 
    .tipo-fighting { background-color: #C03028; } 
    .tipo-poison { background-color: #A040A0; } 
    .tipo-ground { background-color: #E0C068; } 
    .tipo-flying { background-color: #A890F0; } 
    .tipo-psychic { background-color: #F85888; }
    .tipo-bug { background-color: #A8B820; } 
    .tipo-rock { background-color: #B8A038; } 
    .tipo-ghost { background-color: #705898; } 
    .tipo-dark { background-color: #705848; } 
    .tipo-dragon { background-color: #7038F8; } 
    .tipo-steel { background-color: #B8B8D0; }
    .tipo-fairy { background-color: #F0B6BC; } 
    .tipo-normal { background-color: #A8A878; } 
    .tipo-flying { background-color: #A890F0; }
</style>

<div class="container mt-5">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php
            $args = array(
                'post_type' => 'pokemon',
                'posts_per_page' => 50
            );
            $pokemon_query = new WP_Query($args);

            if ($pokemon_query->have_posts()) {
                while ($pokemon_query->have_posts()) {
                    $pokemon_query->the_post();
                    $pokemon_slug = sanitize_title(get_the_title());
                    $tipo = get_post_meta(get_the_ID(), 'tipo', true); 
                    $type_class = 'tipo-' . $tipo;
                    ?>
                    <div class="swiper-slide <?php echo esc_attr($type_class); ?>">
                        <a href="<?php echo esc_url(site_url('/pokemon/' . $pokemon_slug)); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </a>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
        effect: "cards",
        grabCursor: true,
    });
</script>

<?php
get_footer();
?>