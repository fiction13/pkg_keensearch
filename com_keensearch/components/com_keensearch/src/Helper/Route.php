<?php
/*
 * @package   pkg_keensearch
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Component\Keensearchs\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Keensearchs Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_keensearchs
 * @since       1.5
 */
abstract class Route
{
	/**
	 * Get the URL route for a keensearchs from a keensearch ID, keensearchs category ID and language
	 *
	 * @param   integer  $id        The id of the keensearchs
	 * @param   integer  $catid     The id of the keensearchs's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the keensearchs
	 *
	 * @since   1.5
	 */
	public static function getKeensearchsRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_keensearchs&view=keensearch&id=' . $id;

		if ($catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a keensearchs category from a keensearchs category ID and language
	 *
	 * @param   mixed  $catid     The id of the keensearchs's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the keensearchs
	 *
	 * @since   1.5
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof CategoryNode)
		{
			$id = $catid->id;
		}
		else
		{
			$id = (int) $catid;
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			// Create the link
			$link = 'index.php?option=com_keensearchs&view=category&id=' . $id;

			if ($language && $language !== '*' && Multilanguage::isEnabled())
			{
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
}
