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

<style>.startup-menu { display:none; }</style>
<p><h2><span class="welcome">Welcome to QuickAppsCMS</span></h2></p>
<p>&nbsp;</p>

<ul class="nav nav-pills nav-stacked languages">
    <?php foreach ($languages as $code => $link): ?>
    <li class="<?= $code === 'eng' ? "active locale-{$code}" : "locale-{$code}"; ?>">
        <?= $this->Html->link($link['action'], $link['url'], ['title' => $link['action'], 'data-welcome' => $link['welcome']]); ?>
    </li>
    <?php endforeach; ?>
</ul>

<script type="text/javascript" charset="utf-8">
    function changeHeader() {
        active = $('ul.languages li.active');
        next = $(active).next();
        if (!next.length) {
            next = $('ul.languages li')[0];
        }
        $(active).toggleClass('active');
        $(next).toggleClass('active');
        $('span.welcome').fadeOut(300, function() {
            var welcome = $(next).children('a').attr('data-welcome');
            $('title').html(welcome);
            $('span.welcome').html(welcome);
            $('span.welcome').fadeIn(300);
        });
        window.setTimeout(changeHeader, 3000);
    }
    window.setTimeout(changeHeader, 3000);
</script>