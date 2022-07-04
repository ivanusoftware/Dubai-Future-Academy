<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-PFX38DR');
	</script>
	<!-- End Google Tag Manager -->
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/apple-touch-icon.png?v2">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/favicon-32x32.png?v2">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/favicon-16x16.png?v2">
	<link rel="manifest" href="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/site.webmanifest">
	<link rel="mask-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/safari-pinned-tab.svg?v2" color="#333333">
	<link rel="shortcut icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/favicon.ico?v2">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-config" content="<?php echo esc_url(get_template_directory_uri()); ?>/dist/static/img/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700;900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
	<script>
		(function() {
			if (!window.CSS || !CSS.supports('color', 'var(--test-var)')) {
				return;
			}

			window.matchMedia('(prefers-color-scheme: dark)').addListener(function(e) {
				if (e.matches) {
					document.documentElement.classList.add('dark-mode');
				} else {
					document.documentElement.classList.remove('dark-mode');
				}

				Cookies.remove('color-scheme');
			});

			var theme = Cookies.get('color-scheme');

			if (theme === 'dark' || ((!theme || theme === 'auto') && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
				document.documentElement.classList.add('dark-mode');
			}
		})();
	</script>
	<?php
	$theme        = get_post_meta(get_the_ID(), 'theme_primary', true);
	$body_classes = [];

	if ($theme && '#fff' !== $theme) :
		$body_classes[] = 'has-theme';
	?>
		<style>
			:root {
				--theme-primary: <?php echo esc_html($theme); ?>
			}
		</style>
	<?php endif; ?>
</head>

<body <?php body_class($body_classes); ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFX38DR" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php

	/* phpcs:disable */
	$languages = multilingualpress_get_translations();
	/* phpcs:enable */
	?>
	<a class="skip-to-content" tabindex="0" href="#content">
		<span><?php _e('Skip to content', 'dff'); ?></span>
		<?php dff_asset('img/arrow-right.svg'); ?>
	</a>

	<header class="page-head">
		<div class="page-headOuterContainer">
			<div class="page-headContainer">
				<div class="menuButton-container">
					<button class="menuButton-toggle" aria-label="Open Menu">
						<span class="menuButton"></span>
					</button>
				</div>
				<a href="<?php echo esc_url(get_bloginfo('url')); ?>" class="page-headLogo">
					<span class="u-hiddenVisually"><?php _e('Go to the homepage', 'dff'); ?></span>
					<?php dff_asset('img/logo.svg'); ?>
				</a>
				<nav class="page-nav" aria-label="<?php _e('Main Menu Navigation', 'dff'); ?>">
					<?php
					if (has_nav_menu('primary')) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_class'     => 'primaryNav',
								'container'      => true,
								'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'walker'         => new Nav_MegaMenu_Walker(),
							)
						);
					}
					?>
				</nav>
				<div class="page-headActions">
					<?php
					$course_slug = get_query_var('course_slug');
					if ($course_slug) {
						echo dff_courses_switcher_lang($course_slug);
					} else {						
						if (count($languages) > 0) {
							foreach ($languages as $language) {
								// print_r($language);
								if (!$language['is_current']) {
									printf(
										'<a class="button button--ghost is-icon is-language" href="%s">%s</a>',
										esc_url($language['site']),
										esc_html($language['name'] ?? '')
									);
									break;
								}
							}
						}
					}
					?>
					<button class="button button--ghost is-icon" data-toggle-search tabindex="0" aria-controls="search-input">
						<span class="u-hiddenVisually"><?php _e('Open Search', 'dff'); ?></span>
						<?php dff_asset('img/ico-search.svg'); ?>
					</button>
					<!-- <button class="button button--ghost button--futureId is-icon">
						<span class="u-hiddenVisually"><?php _e('Future ID menu', 'dff'); ?></span>
						<?php //dff_asset('img/futureid-icon.svg'); ?>
					</button> -->
					<button class="button button--ghost button--futureId is-icon">
						<span class="u-hiddenVisually"><?php _e('Future ID menu', 'dff'); ?></span>
						<?php dff_asset('img/futureid-icon.svg'); ?>
						<ul class="futureId">
							<li><a href="http://dubaifuture.loc/future-id/">About Future ID</a></li>
							<?php		
							// echo 'test'. $_COOKIE['future_ID'];	
											
							if (!$_COOKIE['user'] && !$_COOKIE['fid-is-loggedin']) {
							?>
								<li class="open-auth-popup">
									<a href="<?php echo site_url('login'); ?>">Login / Register</a>
								</li>
							<?php
							} else {
							?>
								<li><a href="https://dev.id.dubaifuture.ae/">My Profile</a></li>
								<li><a href="https://dev.programs.dubaifuture.ae/">Programmes Dashboard</a></li>
								<li><a id="dff_logout" href="<?php echo site_url('my-courses'); ?>" title="Logout">My Cources</a></li>
								<li><a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Logout">Logout</a></li>
								<!-- <li><a href="https://dev-auth.id.dubaifuture.ae/api/v1/oauth2/logout?client_id=<?php echo $_COOKIE['future_ID']; ?>&redirect_uri=http://dubaifuture.loc/login/&accessToken=<?php echo $_COOKIE['auth_Token']; ?>" title="Logout">Logout</a></li> -->
							<?php
							}
							?>
						</ul>
					</button>
					<button class="button is-icon" data-toggle-darkmode>
						<div class="hide-dark">
							<span class="u-hiddenVisually"><?php _e('Turn darkmode on', 'dff'); ?></span>
							<?php dff_asset('img/ico-moon.svg'); ?>
						</div>
						<div class="show-dark">
							<span class="u-hiddenVisually"><?php _e('Turn darkmode off', 'dff'); ?></span>
							<?php dff_asset('img/ico-sun.svg'); ?>
						</div>
					</button>
				</div>
				<div class="page-headSearch" aria-hidden="true">
					<button data-toggle-search="close" tabindex="-1" aria-label="<?php _e('Close Search', 'dff'); ?>">
						<?php dff_asset('img/ico-close.svg'); ?>
					</button>
				</div>
			</div>
		</div>
	</header>

	<?php if (!has_block('dff/hero') && !is_singular(['post', 'future-talk'])) : ?>
		<div id="content">
		<?php endif; ?>