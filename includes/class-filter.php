<?php

/**
 * @version		1.1 r247 2010-02-23 20:10:00 $
 * @package		SkyBlueCanvas
 * @copyright	Copyright (C) 2005 - 2008 Scott Edwin Lewis. All rights reserved.
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

class Filter {
    
    function get($subject, $key, $default=null, $scrub=true) {
		if (is_array($subject) && isset($subject[$key])) {
            return $scrub ? Filter::scrub($subject[$key]) : $subject[$key];
		}
		else if (is_object($subject) && isset($subject->$key)) {
			return $subject->$key;
		}
		return $default;
	}
	
	function strip($shred, $strip=array()) {
      	return str_replace($strip, "", $shred);
	}
	
	function scrub($data) {
	   if (is_array($data)) {
		   foreach($data as $k=>$v) {
			   $data[$k] = Filter::scrub($v);
		   }
	   }
	   else if (is_string($data)) {
	       $data = trim(strip_tags($data));
	   }
	   return $data;
	}
}