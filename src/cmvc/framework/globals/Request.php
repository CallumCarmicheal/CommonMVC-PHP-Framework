<?php

/**
 *  CMVC  PHP | A hackable php mvc framework written
 *  FRAMEWORK | from scratch with love
 * -------------------------------------------------------
 *   _______  ____   _______                ___  __ _____
 *  / ___/  |/  | | / / ___/               / _ \/ // / _ \
 * / /__/ /|_/ /| |/ / /__                / ___/ _  / ___/
 * \___/_/  /_/ |___/\___/               /_/  /_//_/_/
 *    _______  ___   __  ________      ______  ___  __ __
 *   / __/ _ \/ _ | /  |/  / __| | /| / / __ \/ _ \/ //_/
 *  / _// , _/ __ |/ /|_/ / _/ | |/ |/ / /_/ / , _/ ,<
 * /_/ /_/|_/_/ |_/_/  /_/___/ |__/|__/\____/_/|_/_/|_|
 *
 * -------------------------------------------------------
 * Programmed by Callum Carmicheal
 *		<https://github.com/CallumCarmicheal>
 * GitHub Repository
 *		<https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework>
 *
 * Contributors:
 *
 *
 * LICENSE: MIT License
 *      <http://www.opensource.org/licenses/mit-license.html>
 *
 * You cannot remove this header from any CMVC framework files
 * which are under the following directory cmvc->framework.
 * if you are unsure what directory that is, please refer to
 * GitHub:
 * <https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework/tree/master/src>
 *
 * -------------------------------------------------------
 * MIT License
 *
 * Copyright (c) 2017 Callum Carmicheal
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class Request {
	
	// <editor-fold desc="IP ADDRESS HANDLING">
	private static function ip_in_range($ip, $range) {
		if (strpos($range, '/') == false)
			$range .= '/32';
		
		// $range is in IP/CIDR format eg 127.0.0.1/24
		list($range, $netmask) = explode('/', $range, 2);
		$range_decimal = ip2long($range);
		$ip_decimal = ip2long($ip);
		$wildcard_decimal = pow(2, (32 - $netmask)) - 1;
		$netmask_decimal = ~ $wildcard_decimal;
		return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
	}
	
	private static function _cloudflare_CheckIP($ip) {
		$cf_ips = array(
			'199.27.128.0/21',
			'173.245.48.0/20',
			'103.21.244.0/22',
			'103.22.200.0/22',
			'103.31.4.0/22',
			'141.101.64.0/18',
			'108.162.192.0/18',
			'190.93.240.0/20',
			'188.114.96.0/20',
			'197.234.240.0/22',
			'198.41.128.0/17',
			'162.158.0.0/15',
			'104.16.0.0/12',
		);
		$is_cf_ip = false;
		foreach ($cf_ips as $cf_ip) {
			if (self::ip_in_range($ip, $cf_ip)) {
				$is_cf_ip = true;
				break;
			}
		} return $is_cf_ip;
	}
	
	private static function _cloudflare_Requests_Check() {
		$flag = true;
		
		if(!isset($_SERVER['HTTP_CF_CONNECTING_IP']))   $flag = false;
		if(!isset($_SERVER['HTTP_CF_IPCOUNTRY']))       $flag = false;
		if(!isset($_SERVER['HTTP_CF_RAY']))             $flag = false;
		if(!isset($_SERVER['HTTP_CF_VISITOR']))         $flag = false;
		return $flag;
	}
	
	public static function IP_IsCloudflare() {
		$ipCheck        = self::_cloudflare_CheckIP($_SERVER['REMOTE_ADDR']);
		$requestCheck   = self::_cloudflare_Requests_Check();
		return ($ipCheck && $requestCheck);
	}
	
	public static function IP() {
		// Cloudflare support
		if (self::IP_IsCloudflare()) {
			return $_SERVER['HTTP_CF_CONNECTING_IP'];
		}
		
		// Add more support
		
		
		// Fallback to default
		return $_SERVER['REMOTE_ADDR'];
	}
	
	// </editor-fold>
	
	/**
	 * Checks if the request is of type
	 *
	 * post, p = $_POST
	 * get,  g = $_GET
	 * @param $type
	 * @return bool
	 */
	public static function isType($type) {
		$t = mb_strtolower($type);
		$tIsPost = ($t == 'post' || $t == 'p');
		$tIsGet  = ($t == 'get'  || $t == 'g');
			
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tIsPost)
			return true;
		else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $tIsGet)
			return true;
		return false;
	}
	
	/**
	 * Returns the
	 * @param $short bool States if to return the long or short request type
	 * @return string
	 */
	public static function getType($short = false) {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $short)
			return 'p';
		else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $short)
			return 'g';
		
		return mb_strtolower($_SERVER['REQUEST_METHOD']);
	}
}