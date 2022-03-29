<?php
/* Template Name: 404 */
?>

<?php
	get_header();
?>
<main>

<?php
$not_found_page = get_posts( [
	'name'      => '404-page',
	'post_type' => 'page',
] );

if ( ! empty( $not_found_page ) ) {
	echo wp_kses( $not_found_page[0]->post_content, 'post' );
} else {
	?>
		<section class="section section--alignment-center">
			<div class="container">
				<h1><?php _e( 'Page Not Found', 'dff' ); ?></h1>
				<a class="u-mt2 button button--ghost" href="<?php echo esc_url( get_home_url() ); ?>"><?php _e( 'Back to Home', 'dff' ); ?></a>
			</div>
		</section>
	<?php
}
?>
</main>
<?php
get_footer();
