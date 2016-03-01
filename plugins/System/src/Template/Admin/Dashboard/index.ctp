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
 * Backend themes should implement both regions "dashboard-main" and "dashboard-
 * sidebar" in order to properly fill this page. Or they can "override" this
 * template creating their own and place it at
 * `MyTheme/Template/Plugin/System/Dashboard/index`, and then implement any desired
 * region.
 */
?>

<div class="row dashboard-container">
    <div class="dashboard-main col-md-8">
        <?= $this->region('dashboard-main'); ?>
    </div>

    <div class="dashboard-sidebar col-md-4">
        <?= $this->region('dashboard-sidebar'); ?>
    </div>
</div>