<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );
	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );
	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

function crear_pokemon_cpt() {
    register_post_type('pokemon', [
        'public' => true,
        'label' => 'Pokemons',
        'menu_icon' => 'dashicons-pets',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'pokemon']
    ]);
}
add_action('init', 'crear_pokemon_cpt');


function actualizar_pokemon_data() {
    $pokemon_api_url = 'https://pokeapi.co/api/v2/pokemon/?limit=50'; 
    
    $response = wp_remote_get($pokemon_api_url);

    if (is_array($response) && !is_wp_error($response)) {
        $pokemon_data = json_decode(wp_remote_retrieve_body($response), true);

        foreach ($pokemon_data['results'] as $pokemon) {
            $pokemon_slug = sanitize_title($pokemon['name']);
            $existing_pokemon = get_posts(array(
                'name'        => $pokemon_slug,
                'post_type'   => 'pokemon',
                'post_status' => 'publish',
                'numberposts' => 1
            ));

            if (empty($existing_pokemon)) {
                $pokemon_details_response = wp_remote_get($pokemon['url']);
                if (is_wp_error($pokemon_details_response)) {
                    continue;
                }

                $pokemon_details = json_decode(wp_remote_retrieve_body($pokemon_details_response), true);
                $image_url = $pokemon_details['sprites']['front_default'];
                $primer_tipo = $pokemon_details['types'][0]['type']['name']; 

                $post_data = array(
                    'post_title' => $pokemon['name'],
                    'post_name' => $pokemon_slug,
                    'post_type' => 'pokemon',
                    'post_status' => 'publish',
                );

                $post_id = wp_insert_post($post_data);

                if (!empty($image_url)) {
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_id = media_sideload_image($image_url, $post_id, null, 'id');
                    if (!is_wp_error($attach_id)) {
                        set_post_thumbnail($post_id, $attach_id);
                    }
                }

                if (!empty($primer_tipo)) {
                    add_post_meta($post_id, 'tipo', $primer_tipo, true);
                }
            }
        }
    }
}

add_action('after_switch_theme', 'actualizar_pokemon_data');

function add_bootstrap_to_theme() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'add_bootstrap_to_theme');


function add_swiper_scripts() {
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'add_swiper_scripts');
