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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->Html->head(['bootstrap' => false]); ?>
        <?= $this->Html->css('Installer.bootstrap.min'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <div class="well clearfix">
                <p class="text-center"><?= $this->Html->image('Installer.logo.png'); ?></p>
                <?= $this->element('Installer.startup_menu', compact($menu)); ?>
                <p><?= $this->fetch('content'); ?></p>
            </div>
        </div>
    </body>
</html>