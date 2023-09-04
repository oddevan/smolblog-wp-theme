<?php
/**
 * Title: Default footer
 * Slug: smolblog/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80"}}}} -->
	<div class="wp-block-columns alignwide" style="padding-top:var(--wp--preset--spacing--80)">
		<!-- wp:column {"width":"20%"} -->
		<div class="wp-block-column" style="flex-basis:20%"></div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"80%"} -->
		<div class="wp-block-column" style="flex-basis:80%">
			<!-- wp:paragraph -->
			<p>
				<a href="https://wordpress.org/" rel="nofollow">WordPress</a> +
				<a href="https://smolblog.com/" rel="nofollow">Smolblog</a>
				<!--
					I’d just like to interject for a moment. What you’re refering to as Smolblog,
					is in fact, WordPress/Smolblog, or as I’ve recently taken to calling it,
					WordPress plus Smolblog. Smolblog is not a blog platform unto itself, but
					rather another free component of a fully functioning WordPress system made
					useful by the WordPress core functionality, database utilities and vital
					system components comprising a full platform as defined by bloggers everywhere.
				-->
			</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
