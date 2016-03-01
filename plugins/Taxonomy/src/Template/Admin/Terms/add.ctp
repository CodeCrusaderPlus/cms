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

<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create($term); ?>
            <fieldset>
                <legend><?= __d('taxonomy', "Add New Vocabulary's Term"); ?></legend>
                <?= $this->Form->input('name', ['label' => __d('taxonomy', "Term's name *")]); ?>
                <em class="help-block"><?= __d('taxonomy', 'The name for this term. e.g. "cat", "dog" or "bird" for the "animals" vocabulary.'); ?></em>

                <?=
                    $this->Form->input('parent_id', [
                        'type' => 'select',
                        'label' => __d('taxonomy', 'Parent term'),
                        'options' => $parentsTree,
                        'empty' => __d('taxonomy', '-- NONE --')
                    ]);
                ?>

                <?= $this->Form->submit(__d('taxonomy', '&laquo; Save & go back to terms tree'), ['name' => 'action_vocabulary', 'escape' => false]); ?>
                <?= $this->Form->submit(__d('taxonomy', 'Save & add another &raquo;'), ['name' => 'action_add', 'escape' => false]); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>