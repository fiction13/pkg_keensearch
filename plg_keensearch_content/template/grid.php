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

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

?>

<?php if (!empty($items)): ?>
    <div class="keensearch_content">
        <?php if ($params->get('show_group_title', 0)) : ?>
            <?php echo Text::_($field->title); ?>
        <?php endif; ?>

        <div class="uk-grid-divider uk-grid-medium radicalmart-related__list" uk-grid
             uk-height-match="target: > div > .uk-card >.uk-card-body,> div > .uk-card >.uk-card-footer > .uk-grid; row:false">
            <?php foreach ($items as $i => $item): ?>
                <div class="uk-width-1-<?php echo $params->get('cols'); ?>@s">
                    <?php echo LayoutHelper::render('plugins.keensearch.content.grid',
                        array('item' => $item)); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
