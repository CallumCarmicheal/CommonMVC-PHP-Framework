<?php

function url($url) {
	if ($url == "#")
		return $url;
	
	if ($url == "/")
		return CMVC_ROOT_URL;
	
	$url = ltrim($url, '/');
	
	return CMVC_ROOT_URL. trim($url);
}
