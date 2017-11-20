<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
*
* @package    LCARS
* @version    0.1
* @author     Andreas Dziemba
* @license    DNG-Bluegreen License
* @copyright  2016 - 2017 Star Trek - Die neue Grenze
* @link       http://www.die-neue-grenze.de
*/
namespace core\classes\environment;

class System {

	public static function getPhpVersion() {
		return phpversion();
	}
	
	public static function esc_url($url) {
 
	    if ('' == $url) {
	        return $url;
	    }
	 
	    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
	 
	    $strip = array('%0d', '%0a', '%0D', '%0A');
	    $url = (string) $url;
	 
	    $count = 1;
	    while ($count) {
	        $url = str_replace($strip, '', $url, $count);
	    }
	 
	    $url = str_replace(';//', '://', $url);
	 
	    $url = htmlentities($url);
	 
	    $url = str_replace('&amp;', '&#038;', $url);
	    $url = str_replace("'", '&#039;', $url);
	 
	    if ($url[0] !== '/') {
	        // We're only interested in relative links from $_SERVER['PHP_SELF']
	        return '';
	    } else {
	        return $url;
	    }
	}
	
	public static function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	
	public static function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}
	
		return (substr($haystack, -$length) === $needle);
	}
}