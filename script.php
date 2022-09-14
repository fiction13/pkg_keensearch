<?php
/*
 * @package   pkg_keensearch
 * @version   __DEPLOY_VERSION__
 * @author    Dmitriy Vasyukov - https://fictionlabs.ru
 * @copyright Copyright (c) 2022 Fictionlabs. All rights reserved.
 * @license   GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link      https://fictionlabs.ru/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Installer\Adapter\PackageAdapter;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;

class pkg_keensearchInstallerScript
{
	/**
	 * Minimum PHP version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  0.0.1
	 */
	protected $minimumPhp = '7.0';

	/**
	 * Minimum Joomla version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  0.0.1
	 */
	protected $minimumJoomla = '4.1.0';

	/**
	 * Minimum MariaDb version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  0.0.1
	 */
	protected $minimumMariaDb = '10.4.1';

	/**
	 * Runs right before any installation action.
	 *
	 * @param   string                           $type    Type of PostFlight action.
	 * @param   InstallerAdapter|PackageAdapter  $parent  Parent object calling object.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, False on failure.
	 *
	 * @since  0.0.1
	 */
	function preflight($type, $parent)
	{
		// Check compatible
		if (!$this->checkCompatible()) return false;

		return true;
	}

	/**
	 * Method to check compatible.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, False on failure.
	 *
	 * @since  0.0.1
	 */
	protected function checkCompatible()
	{
		// Check old Joomla
		if (!class_exists('Joomla\CMS\Version'))
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('PKG_KEENSEARCH_ERROR_COMPATIBLE_JOOMLA',
				$this->minimumJoomla), 'error');

			return false;
		}

		$app = Factory::getApplication();

		// Check PHP
		if (!(version_compare(PHP_VERSION, $this->minimumPhp) >= 0))
		{
			$app->enqueueMessage(Text::sprintf('PKG_KEENSEARCH_ERROR_COMPATIBLE_PHP', $this->minimumPhp),
				'error');

			return false;
		}

		// Check joomla version
		if (!(new Version())->isCompatible($this->minimumJoomla))
		{
			$app->enqueueMessage(Text::sprintf('PKG_KEENSEARCH_ERROR_COMPATIBLE_JOOMLA', $this->minimumJoomla),
				'error');

			return false;
		}

		return true;
	}

	/**
	 * Runs right after any installation action.
	 *
	 * @param   string            $type    Type of PostFlight action.
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @throws  Exception
	 *
	 * @since  0.0.1
	 */
	public function postflight($type, $parent)
	{
		#TODO Replace old search
	}
}