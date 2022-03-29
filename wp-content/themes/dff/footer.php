<?php if ( ! has_block( 'dff/hero' ) && ! is_singular( [ 'post', 'future-talk' ] ) ) : ?>
	</div>
<?php endif; ?>
<!-- Begin Mailchimp Signup Form -->
<section class="section newsletter-section section--8columns section--tinted u-mt0 u-mb0 u-ma0" id="mc_embed_signup">
	<div class="container">
		<div class="newsletter">
			<header>
				<h1><?php _e( 'Subscribe for updates', 'dff' ); ?></h1>
				<p><?php _e( 'A weekly update from DFF', 'dff' ); ?></p>
			</header>
			<form action="https://dubaifuture.us18.list-manage.com/subscribe/post?u=0426a052e74ecbb3b4f57ddd2&amp;id=c4e5ba2aae" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<label for="mce-EMAIL" class="u-hiddenVisually"><?php _e( 'Email Address', 'dff' ); ?></label>
				<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php _e( 'Email Address', 'dff' ); ?>" required>
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_0426a052e74ecbb3b4f57ddd2_c4e5ba2aae" tabindex="-1" value=""></div>
				<input type="submit" value="<?php _e( 'Subscribe', 'dff' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button">
			</form>
		</div>
	</div>
</section>
<!--End mc_embed_signup-->
<footer class="page-foot">
	<div class="wrapper page-footerMain">
		<div class="span-12 span-6--small">
			<div class="page-footLogo">
				<?php dff_asset( 'img/logo.svg' ); ?>
			</div>
		</div>
		<div class="span-12 span-6--small flexy-wrapper is-spaced">
			<?php
			$classes = [
				'1' => 'span-6--x-small span-12--xx-small span-12',
				'2' => 'span-3--x-small span-6--xx-small span-6',
				'3' => 'span-3--x-small span-6--xx-small span-6',
			];

			for ( $footer_index = 1; $footer_index <= 3; $footer_index++ ) :
				if ( has_nav_menu( 'footer-' . $footer_index ) ) :
					$footer_menu = dff_get_menu( 'footer-' . $footer_index );
					?>
					<nav class="footerNav-container <?php echo esc_attr( $classes[ $footer_index ] ); ?>">
						<header>
							<h1 class="footerNav-title"><?php echo esc_html( $footer_menu->name ); ?></h1>
						</header>
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer-' . $footer_index,
									'menu_class'     => 'footerNav',
									'container'      => false,
									'items_wrap'     => '<ul aria-label="' . $footer_menu->name . '" id="%1$s" class="%2$s">%3$s</ul>',
								)
							);
						?>
					</nav>
					<?php
				endif;
			endfor;
			?>
		</div>
	</div>
	<div class="wrapper page-footBottom">
		<div class="span-6--small span-12">&copy; 2016 - <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo() ); ?></div>
		<div class="span-6--small span-12 flexy-wrapper is-spaced">
			<?php if ( has_nav_menu( 'footer-bottom' ) ) : ?>
				<div class="span-6--x-small span-6--xx-small span-12">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-bottom',
							'menu_class'     => 'footerBottomNav',
							'container'      => false,
							'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						)
					);
				?>
				</div>
			<?php endif; ?>
			<?php if ( has_nav_menu( 'footer-bottom-2' ) ) : ?>
				<div class="span-6--x-small span-6--xx-small span-12">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-bottom-2',
							'menu_class'     => 'footerBottomNav',
							'container'      => false,
							'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						)
					);
				?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
