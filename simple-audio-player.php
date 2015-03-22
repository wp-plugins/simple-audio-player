<?php
/*
Plugin Name: Simple Audio Player
Version: 0.1
Plugin URI: http://www.beliefmedia.com/wp-plugins/audio.php
Description: Displays the well known and highly configurable WP Audio Player with very simple shortcode. Fallback to browser HTML5 player if flash is not supported. Full details on our website.
Author: Marty
Author URI: http://www.internoetics.com/2010/12/14/wordpress-audio-player-and-shortcode/
*/

/*

	The "flash audio player" (http://wpaudioplayer.com/license/) is copyright (2008) to Martin Laine.
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files 
	(the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, 	
	publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
	subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
	OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE 
	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR 
	IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


function internoetics_audioplayer($atts) {

 $atts = shortcode_atts(
  array(
   'defaultdirectory' => '', /* No trailing slash */
   'file' => '',
   'id' => '',
   'artists' => '',
   'titles' => '',

   /* Options */
   'autostart' => 'no',
   'loop' => 'no',
   'animation' => 'yes',
   'remaining' => 'yes',
   'noinfo' => 'no',
   'initialvolume' => '70',
   'buffer' => '5',
   'encode' => 'no',
   'checkpolicy' => 'no',
   'rtl' => 'no',

   /* Flash Player Options */
   'width' => '100%',
   'transparentpagebg' => 'no',
   'pagebg' => '',

   /* Colour Options */
   'bg' => 'E5E5E5',
   'leftbg' => 'CCCCCC',
   'lefticon' => '333333',
   'voltrack' => 'F2F2F2',
   'volslider' => '666666',
   'rightbg' => 'B4B4B4',
   'rightbghover' => '999999',
   'righticon' => '333333',
   'righticonhover' => 'FFFFFF',
   'loader' => '009900',
   'track' => 'FFFFFF',
   'tracker' => 'DDDDDD',
   'border' => 'CCCCCC',
   'skip' => '666666',
   'text' => '333333'
     ), $atts, 'audioplayer' );

    /* If no titles(s) defined we'll replace it with the blog name */
    if ($atts['titles'] =='') $atts['titles'] = get_bloginfo('name');

    foreach ($atts AS $opt => $att) {
	 if ( ($att) && ($opt != 'id') && ($opt != 'file') ) $attributes .= ' ' . $opt . ': "' . $att . '",';
    }
     $attributes = rtrim($attributes, ',');
     if ($default_directory) $file = "$defaultdirectory/$file";

    /* Create a random ID if one not provided */
    if ($atts['id'] == '') $atts['id'] = substr(str_shuffle(str_repeat("123456789", 4)), 0, 4);

	global $add_audioplayer;
	$add_audioplayer = true;

	$return .= '<script type="text/javascript">AudioPlayer.setup("' . plugins_url('player/player.swf', __FILE__) . '", { width: 290 });</script>';
	$return .= '<p id="audioplayer_' . $atts['id'] . '"><audio controls><source src="' . $atts['file'] . '" type="audio/mpeg"></audio></p>'; 
	$return .= '<p><script type="text/javascript">';
	$return .= 'AudioPlayer.embed("audioplayer_' . $atts['id'] . '", {soundFile: "' . $atts['file'] . '", ' . $attributes;
        $return .= '});'; 
	$return .= '</script></p>';
 
    return $return;
}
add_shortcode('audioplayer', 'internoetics_audioplayer');


function add_audioplayer_js($posts) {

  if (empty($posts)) return $posts;
   $shortcode_audioplayer_true = false;
    foreach ($posts as $post) {
     if (stripos($post->post_content, '[audioplayer') !== false) {
      $shortcode_audioplayer_true = true;
      break;
      }
    }

    if ($shortcode_audioplayer_true) {
	wp_enqueue_script('audioplayer', plugins_url('js/swfobject.js', __FILE__), true);
	wp_enqueue_script('audio-player', plugins_url('js/audio-player.js', __FILE__), true);
     }

   return $posts;
}
add_filter('the_posts', 'add_audioplayer_js');


/*
	Menu Links
*/


function internoetics_audioplayer_action_links($links, $file) {
  static $this_plugin;
  if (!$this_plugin) {
   $this_plugin = plugin_basename(__FILE__);
  }

  if ($file == $this_plugin) {
	$links[] = '<a href="http://www.beliefmedia.com/wp-plugins/simple-audio-player.php" target="_blank">Support</a>';
	$links[] = '<a href="http://www.internoetics.com/" target="_blank">Internoetics</a>';
  }
 return $links;
}
add_filter('plugin_action_links', 'internoetics_audioplayer_action_links', 10, 2);
?>