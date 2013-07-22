<?php

/**
 * Template side enumeration
 *
 * @package CMS
 * @subpackage Template
 * @author Chavjoh
 * @since 1.0.0
 */
class TemplateSide extends Enumeration
{
	const __default = self::FRONTEND;

	const BACKEND = 'BACKEND';
	const FRONTEND = 'FRONTEND';
}
