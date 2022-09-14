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

        <div class="keensearch_content__list">
            <?php foreach ($items as $i => $item): ?>
                <?php if ($i > 0) echo '<hr class="uk-margin-remove">'; ?>
                <div class="item-<?php echo $item->id; ?>">
                    <?php echo LayoutHelper::render('plugins.keensearch.content.list',
                        array('item' => $item)); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
