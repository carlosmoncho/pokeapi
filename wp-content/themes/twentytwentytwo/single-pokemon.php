<?php
get_header();

?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <?php while (have_posts()) : the_post();
            $pokemon_name = get_the_title();
            $pokemon_api_url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemon_name);
            $pokemon_response = wp_remote_get($pokemon_api_url);

            if (is_wp_error($pokemon_response)) : ?>
                <p class="text-center">Error al obtener datos del Pokémon.</p>
            <?php else :
                $pokemon_data = json_decode(wp_remote_retrieve_body($pokemon_response), true); ?>
                <div class="col-md-6">
                    <div class="pokemon-container text-center">
                        <img src="<?= esc_url($pokemon_data['sprites']['front_default']) ?>" alt="<?= esc_attr($pokemon_name) ?>" class="img-fluid pokemon-image">
                        <h1 class="pokemon-title"><?= esc_html($pokemon_name) ?></h1>
                        <p class="pokemon-detail">Altura: <?= esc_html($pokemon_data['height']) ?> | Peso: <?= esc_html($pokemon_data['weight']) ?></p>
                        <div class="pokemon-types mb-3">
                            <?php foreach ($pokemon_data['types'] as $type) : ?>
                                <span class="badge badge-secondary mr-2 pokemon-type"><?= esc_html(ucfirst($type['type']['name'])) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="pokemon-stats">
                            <?php foreach ($pokemon_data['stats'] as $stat) : ?>
                                <div class="pokemon-stat">
                                    <strong><?= esc_html(ucfirst($stat['stat']['name'])) ?>:</strong> <?= esc_html($stat['base_stat']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif;
        endwhile; ?>
    </div>
</div>
<?php
get_footer();
?>
