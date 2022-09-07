<?php
/*
 * @package   pkg_keensearch
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Module\Finder\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Finder\Administrator\Indexer\Query;
use Joomla\Utilities\ArrayHelper;

/**
 * Finder module helper.
 *
 * @since  2.5
 */
class FinderHelper
{
	/**
	 * Method to get hidden input fields for a get form so that control variables
	 * are not lost upon form submission.
	 *
	 * @param   string   $route      The route to the page. [optional]
	 * @param   integer  $paramItem  The menu item ID. (@since 3.1) [optional]
	 *
	 * @return  string  A string of hidden input form fields
	 *
	 * @since   2.5
	 */
	public static function getGetFields($route = null, $paramItem = 0)
	{
		// Determine if there is an item id before routing.
		$needId = !Uri::getInstance($route)->getVar('Itemid');

		$fields = array();
		$uri = Uri::getInstance(Route::_($route));
		$uri->delVar('q');

		// Create hidden input elements for each part of the URI.
		foreach ($uri->getQuery(true) as $n => $v)
		{
			$fields[] = '<input type="hidden" name="' . $n . '" value="' . $v . '">';
		}

		// Add a field for Itemid if we need one.
		if ($needId)
		{
			$id       = $paramItem ?: Factory::getApplication()->input->get('Itemid', '0', 'int');
			$fields[] = '<input type="hidden" name="Itemid" value="' . $id . '">';
		}

		return implode('', $fields);
	}

	/**
	 * Get Smart Search query object.
	 *
	 * @param   \Joomla\Registry\Registry  $params  Module parameters.
	 *
	 * @return  Query object
	 *
	 * @since   2.5
	 */
	public static function getQuery($params)
	{
		$request = Factory::getApplication()->input->request;
		$filter  = InputFilter::getInstance();

		// Get the static taxonomy filters.
		$options = array();
		$options['filter'] = ($request->get('f', 0, 'int') !== 0) ? $request->get('f', '', 'int') : $params->get('searchfilter');
		$options['filter'] = $filter->clean($options['filter'], 'int');

		// Get the dynamic taxonomy filters.
		$options['filters'] = $request->get('t', '', 'array');
		$options['filters'] = $filter->clean($options['filters'], 'array');
		$options['filters'] = ArrayHelper::toInteger($options['filters']);

		// Instantiate a query object.
		return new Query($options);
	}
}
