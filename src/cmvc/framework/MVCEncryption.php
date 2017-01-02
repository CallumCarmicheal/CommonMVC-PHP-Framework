<?php
/**
 * Created by PhpStorm.
 * User: CallumCarmicheal
 * Date: 18/12/2016
 * Time: 16:37
 */

namespace lib\CMVC\mvc;


use CommonMVC\MVC\MVCResult;

/**
 * Interface MVCEncryption
 * @package lib\CMVC\mvc
 */
interface MVCEncryption {
	/**
	 * Preprocesses content and encrypts it
	 * @param $content mixed
	 * @return mixed
	 */
	public function EncryptContent($content);
}