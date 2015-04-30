<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

/**
 * This view elements is capable of handling multiple view-modes.
 *
 * If you want to create a separated view element for each view mode
 * take a look to `NodeHook::renderNode()` method.
 */
?>

<article class="node node-<?php echo $node->node_type_slug; ?> viewmode-<?php echo $this->viewMode(); ?>">
    <header>
        <?php if ($this->viewMode() === 'full'): ?>
            <h1><?php echo $this->shortcodes($node->title); ?></h1>
        <?php else: ?>
            <h2><?php echo $this->Html->link($this->shortcodes($node->title), $node->url); ?></h2>
        <?php endif; ?>
        <p><?php echo __d('node', 'Published'); ?>: <time pubdate="pubdate"><?php echo $node->created->timeAgoInWords(); ?></time></p>
    </header>

    <?php if (!empty($node->_fields)): ?>
        <?php foreach ($node->_fields->sortByViewMode($this->viewMode()) as $field): ?>
            <?php echo $this->render($field); ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($this->viewMode() === 'full'): ?>
        <?php echo $this->Comment->render($node); ?>
    <?php endif; ?>
</article>