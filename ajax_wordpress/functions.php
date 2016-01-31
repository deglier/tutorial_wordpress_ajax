<?php
// define a versão do site
define('VERSAO_TEMA', 1.0);

// adiciona o CSS ná página
function theme_style() {
	if( !is_admin() ) {
		wp_enqueue_style(
			'style.css', // nome
	        get_stylesheet_directory_uri() . '/style.css', // caminho
            null,
	        VERSAO_TEMA); // versão
	}
}
add_action('init', 'theme_style');

// adcionando o jquery
function register_jquery() {
	wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'register_jquery');

// adiciona o ajaxLoad.js ao tema
function register_ajaxLoad() {
	wp_register_script(
		'ajaxLoad',
		get_stylesheet_directory_uri() . '/js/ajaxLoad.js',
		array('jquery'),
		VERSAO_TEMA,
		true
	);
	wp_enqueue_script('ajaxLoad');
}
add_action('wp_enqueue_scripts', 'register_ajaxLoad');


// funcção que carrega os posts
function load_posts() {

	// variaveis da query
	$numPosts = (isset($_GET['numPosts']) ? $_GET['numPosts'] : 0);
	$page = (isset($_GET['page']) ? $_GET['page'] : 0);

	// modificados do loop
	query_posts(array(
		'post_por_page' => $numPosts,
		'paged' => $page,
		'post_status' => 'publish',
		'post_type' => 'post'
	));

	// loop
	if(have_posts()):
		while(have_posts()) : the_post();
		?>
			<article class="artigos">
				<h1><?php the_title(); ?></h1>
				<?php the_excerpt(); ?>
			</article>
		<?php
		endwhile;
	endif;

	// limpa da memória a query
	wp_reset_query();

	// finaliza o script
	wp_die();
}
add_action('wp_ajax_load_posts', 'load_posts');
add_action('wp_ajax_nopriv_load_posts', 'load_posts');