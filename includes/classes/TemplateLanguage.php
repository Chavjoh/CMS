<?php

/**
 * Language class for CMS translation in template files
 *
 * @package CMS
 * @subpackage Language
 * @author Chavjoh
 * @since 1.0.0
 */
class TemplateLanguage extends LanguageCache
{
	/**
	 * Set file cache path.
	 * We use a function because expressions are forbidden in variable declaration
	 */
	public static function setCacheFile()
	{
		// TODO: Change to real value

		// BACKEND
		if (ADMIN_SECTION)
			self::$cacheFile = PATH_SKIN.TemplateModel::getActivePath(TemplateSide::BACKEND).DS.TEMPLATE_LANGUAGE."en.txt";

		// FRONTEND
		else
			self::$cacheFile = PATH_SKIN.TemplateModel::getActivePath(TemplateSide::FRONTEND).DS.TEMPLATE_LANGUAGE."en.txt";
	}
}
