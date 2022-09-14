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

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Menu\AdministratorMenuItem;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

class plgSystemKeenSearch extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var CMSApplicationInterface
	 *
	 * @since  2.0.0
	 */
	protected $app;

	/**
	 * Affects constructor behavior.
	 *
	 * @var  boolean
	 *
	 * @since  2.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Is Joomla 4.
	 *
	 * @var  boolean
	 *
	 * @since  2.14.5
	 */
	protected $joomla4 = false;

	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array    $config   An optional associative array of configuration settings.
	 *
	 * @since  2.14.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$this->joomla4 = (new Version())->isCompatible('4.0');

		parent::__construct($subject, $config);
	}

	/**
	 * Add onKeenSearchPrepareForm trigger.
	 *
	 * @param   Form   $form  The form to be altered.
	 * @param   mixed  $data  The associated data for the form.
	 *
	 * @throws  Exception
	 *
	 * @since  2.0.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		$formName = $form->getName();

		if ($formName === 'com_config.component' && $this->app->input->get('component') === 'com_keensearch')
		{
			PluginHelper::importPlugin('keensearch');
			$this->app->triggerEvent('onKeenSearchPrepareForm', array($form, $data));
		}
	}

	/**
	 * Method to change Joomla 4 administartor menu.
	 *
	 * @param   string  $context   Context selector string.
	 * @param   array   $children  Menu items array.
	 *
	 * @since  2.14.5
	 */
	public function onPreprocessMenuItems($context, $children)
	{
		if ($this->joomla4 && $this->app->isClient('administrator') && $context === 'com_menus.administrator.module')
		{
			if ($this->loadAdminMenu === false)
			{
				$parent = new AdministratorMenuItem (array(
					'title'     => 'COM_KEENSEARCH',
					'type'      => 'component',
					'link'      => 'index.php?option=com_keensearch',
					'element'   => 'com_jatoms',
					'class'     => 'class:search',
					'ajaxbadge' => null,
					'dashboard' => 'jatoms'
				));

				// Add config view
				$parent->addChild(new AdministratorMenuItem (array(
					'title'     => 'COM_KEENSEARCH_CONFIG',
					'type'      => 'component',
					'link'      => 'index.php?option=com_config&view=component&component=com_keensearch',
					'element'   => 'com_config',
					'class'     => null,
					'ajaxbadge' => null,
					'dashboard' => null,
					'scope'     => 'default',
				)));

				/* @var $root AdministratorMenuItem */
				$root = $children[0]->getParent();
				$root->addChild($parent);
				$this->loadAdminMenu = true;

			}
			elseif ($this->removeAdminMenu === false)
			{
				foreach ($children as $child)
				{
					if ($child->type === 'component'
						&& (int) $child->component_id === ComponentHelper::getComponent('com_keensearch')->id)
					{
						$child->getParent()->removeChild($child);
						$this->removeAdminMenu = true;
					}
				}
			}
		}
	}
}