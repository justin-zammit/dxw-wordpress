<?php
include_once( ABSPATH . WPINC . '/feed.php' );

$args = array(
	'post_type' => 'rss_feed'
	);

fdf
$rssLinks = array(); // Stores RSS links list.
$allRssPosts = array(); // Stores all RSS items.

$wpQuery = new WP_Query($args);

while ($wpQuery->have_posts()) : $wpQuery->the_post();
	array_push($rssLinks, get_the_title());
endwhile;

foreach ($rssLinks as $rssLink) {
	$rss = fetch_feed($rssLink);
	$title = $rss->get_title();
	$items = $rss->get_items(0, 0);

	foreach ($items as $item) {	
		$link = '<a target="_blank" href="'.$item->get_link().'"><div class="post-title">'.$title.'</div> - '.$item->get_title().'</a><br/>';
		$originalDate = $item->get_date();		
		$singlePostArray = array('link' => $link, 'date' => $originalDate);		
		array_push($allRssPosts, $singlePostArray); 			
		}	
}

function sortFunction($a, $b) {
    return strtotime($a["date"]) - strtotime($b["date"]);
}

usort($allRssPosts, "sortFunction");		
$displayAllRssPosts = array_reverse($allRssPosts); // Descending order.

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<style type="text/css">
#rss-list-index
{
	padding: 10px;
	font-size: 12px;
	font-family: Georgia, Verdana;
}

#rss-list-index .rss-post-wrapper
{
	margin-bottom: 25px;
}

#rss-list-index .post-title
{
	font-style: italic;
	display: inline;
}

#rss-list-index .rss-post-wrapper h1
{
	font-weight: bold;
	font-size: 14px;
}
</style>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div id="rss-list-index">
				<?php	
					foreach ($displayAllRssPosts as $rssPost) {
						echo '<div class="rss-post-wrapper"><h1>'.$rssPost['link'].'</h1>'.$rssPost['date'].'</div>';
					}			
				?>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>