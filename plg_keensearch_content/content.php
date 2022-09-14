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

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Utilities\ArrayHelper;

class plgKeenSearchContent extends CMSPlugin
{
	/**
	 * Loads the application object.
	 *
	 * @var  CMSApplication
	 *
	 * @since  1.0.0
	 */
	protected $app = null;

	/**
	 * Loads the database object.
	 *
	 * @var  JDatabaseDriver
	 *
	 * @since  1.0.0
	 */
	protected $db = null;

	/**
	 * Affects constructor behavior.
	 *
	 * @var  boolean
	 *
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Method to change forms.
	 *
	 * @param   Form   $form  The form to be altered.
	 * @param   mixed  $data  The associated data for the form.
	 *
	 * @throws  Exception
	 *
	 * @since  1.0.0
	 */
	public function onKeenSearchPrepareForm($form, $data)
	{
		$formName = $form->getName();
		$forms    = array(
			'com_config.component' => 'config',
		);

		if (isset($forms[$formName]))
		{
			Form::addFormPath(__DIR__ . '/forms');
			$form->loadFile($forms[$formName]);
		}
	}
}
