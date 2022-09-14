<?php

/*
 * @package   pkg_keensearch
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

namespace Joomla\Component\Keensearch\Site\Search;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Plugin\PluginHelper;

/**
 * Keensearch model for the Joomla Keensearchs component.
 *
 * @since  1.0.0
 */
class KeensearchModel extends ListModel
{
	/**
	 * @var string item
	 */
	protected $_items = null;

	/**
	 * Constructor
	 *
	 * @since  1.5
	 */
	public function __construct()
	{
		parent::__construct();

		// Get configuration
		$app    = Factory::getApplication();
		$config = Factory::getConfig();

		// Get the pagination request variables
		$this->setState('limit', $app->getUserStateFromRequest('com_search.limit', 'limit', $config->get('list_limit'), 'uint'));
		$this->setState('limitstart', $app->input->get('limitstart', 0, 'uint'));

		// Get parameters.
		$params = $app->getParams();

		if ($params->get('searchphrase') == 1)
		{
			$searchphrase = 'any';
		}
		elseif ($params->get('searchphrase') == 2)
		{
			$searchphrase = 'exact';
		}
		else
		{
			$searchphrase = 'all';
		}

		// Set the search parameters
		$keyword  = urldecode($app->input->getString('searchword'));
		$match    = $app->input->get('searchphrase', $searchphrase, 'word');
		$ordering = $app->input->get('ordering', $params->get('ordering', 'newest'), 'word');
		$this->setSearch($keyword, $match, $ordering);
	}

	/**
	 * Method to set the search parameters
	 *
	 * @param   string  $keyword   string search string
	 * @param   string  $match     matching option, exact|any|all
	 * @param   string  $ordering  option, newest|oldest|popular|alpha|category
	 *
	 * @access  public
	 *
	 * @return  void
	 */
	public function setSearch($keyword, $match = 'all', $ordering = 'newest')
	{
		if (isset($keyword))
		{
			$this->setState('origkeyword', $keyword);

			if ($match !== 'exact')
			{
				$keyword = preg_replace('#\xE3\x80\x80#', ' ', $keyword);
			}

			$this->setState('keyword', $keyword);
		}

		if (isset($match))
		{
			$this->setState('match', $match);
		}

		if (isset($ordering))
		{
			$this->setState('ordering', $ordering);
		}
	}

	/**
	 * Method to get weblink item data for the category
	 *
	 * @access  public
	 * @return  array
	 */
	public function getItems()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_items))
		{
			$items  = array();

			// Trigger for get data from provider plugins
            PluginHelper::importPlugin('keensearch');

            $this->app->triggerEvent('onContentSearch', [
				$items,
				$this->getState('keyword'),
				$this->getState('match'),
				$this->getState('ordering'),
            ]);

			$this->_total = count($items);

			if ($this->getState('limit') > 0)
			{
				$this->_items = array_splice($items, $this->getState('limitstart'), $this->getState('limit'));
			}
			else
			{
				$this->_items = $items;
			}
		}

		return $this->_items;
	}

	/**
	 * Method to get the total number of weblink items for the category
	 *
	 * @access  public
	 *
	 * @return  integer
	 */
	public function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Method to set the search areas
	 *
	 * @param   array  $active  areas
	 * @param   array  $search  areas
	 *
	 * @return  void
	 *
	 * @access  public
	 */
	public function setAreas($active = array(), $search = array())
	{
		$this->_areas['active'] = $active;
		$this->_areas['search'] = $search;
	}

	/**
	 * Method to get a pagination object of the weblink items for the category
	 *
	 * @access  public
	 * @return  integer
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			$this->_pagination = new Pagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}
}
