<?php
/**
 * Proj: cmvc
 * User: CallumCarmicheal
 * Date: 15/01/2017
 * Time: 23:48
 */

namespace App\Libraries;


use CommonMVC\MVC\MVCResult;
use eftec\bladeone\BladeOne;

class Blade {
	
	/** @var $blade BladeOne */
	static $blade;
	
	
	/**
	 * Runs a blade view.
	 *
	 * @param $view string
	 * @param $variables []
	 * @param bool $clean
	 * @return MVCResult
	 */
	public static function run($view, $variables = [], $clean = true) {
		self::setupBladeOne();
		
		$mvc = new MVCResult();
		$content = self::get($view, $variables);
		
		$mvc->setPageContent($content);
		$mvc->setHttpResult(MVCResult::$E_HTTP_RESULT_OK);
		$mvc->setPageResult(MVCResult::$E_RESULT_SUCCESS);
		
		if ($clean)
			$mvc->setHttpClean(MVCResult::$E_HTTP_CLEAN_CONTENT);
		
		return $mvc;
	}
	
	/**
	 * Execute a blade view and return its contents.
	 *
	 * @param $view string
	 * @param $variables []
	 * @return string
	 */
	public static function get($view, $variables = []) {
		self::setupBladeOne();
		return self::$blade->run($view, $variables);
	}
	
	public static function getEngineInstance() {
		return self::$blade;
	}
	
	private static function setupBladeOne() {
		if (is_null(self::$blade))
			self::$blade = new BladeOne(VENDORS_BLADEONE_DIR_VIEWS, VENDORS_BLADEONE_DIR_CACHE);
	}
}