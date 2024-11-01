<?php
/**
 * @package Youtube Video Fetcher
 * @version 0.2
 */
/*
Plugin Name: Youtube Video Fetcher
Plugin URI: http://wordpress.org/extend/plugins/youtube-video-fetcher/
Description: Youtube Video Fetcher is a wordpress plugin which is designed to fetch the videos from youtube and display it on your wordpress site.
It fetches the title of the post and then it searches on the youtube, after this it fecthes the youtube video and displays it on the site after the post.
Author: Niraj Chauhan
Version: 0.2
Author URI: http://nirajchauhan.co.cc/
*/
require_once ('youtube_video_admin.php');//for calling admin options
function youtube_video()
{
	global $post;
	
	$check = get_post_meta($post->ID, 'youtube_check', true);
	
	
	if($check == '' || $check == 'on')
	{
	
		$ret = "";
	
		$ret .= "<div class='post'>";// wrap it into div
	
	
		$newvar = urlencode(get_the_title());//removing blank spacesfrom the title
		include_once(ABSPATH . WPINC . '/rss.php');
		$rss = fetch_rss('http://gdata.youtube.com/feeds/base/videos?q='.$newvar.'&client=ytapi-youtube-search&v=2');
		$maxitems = get_option('novideos');//Set the number of videos to be displayed
	
		$items =is_array($rss->items) ? array_slice($rss->items, 0, $maxitems) : '' ;
	
		$ret .= "<ul>";
	
		//Displaying the video code starts from here.
	
		if (empty($items)) {$ret .= " 'No new videos.'";}
		else foreach ( $items as $item ) :
		$youtubeid = strchr($item['link'],'=');
		$youtubeid = substr($youtubeid,1);
	
		$ret .= "<p>Latest Videos of <b>" . get_the_title() . ":</b></p>";
		$ret .= "<br>";
		$ret .= "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='320' height='265'>";
		$ret .= "<param name='movie' value='http://www.youtube.com/v/" . $youtubeid ."&hl=en&fs=1' />";
		$ret .= "<!--[if !IE]>-->";
		$ret .= "<object type='application/x-shockwave-flash' data='http://www.youtube.com/v/" . $youtubeid ."&hl=en&fs=1' width='320' height='265'>";
		$ret .= "<!--<![endif]-->";
		$ret .= "<p><a href='http://www.youtube.com/v/" . $youtubeid ."'>View movie&raquo;</a></p>";
		$ret .= "<!--[if !IE]>-->";
		$ret .= "</object>";
		$ret .= "<!--<![endif]-->";
		$ret .= "</object>";
		$ret .= "</li>";
	
		endforeach;
		$ret .= "</ul>";
		$ret .= "</div>";
	
		return $ret;
	
	}
}
//The below function is used to display the video after the post.
function append_the_video($content){
	return $content.youtube_video();
}
add_filter('the_content', 'append_the_video');

?>