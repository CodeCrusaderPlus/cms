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
namespace Node\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestCase;

/**
 * ManageControllerTest class.
 */
class ManageControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        //'app.acos',
        'app.block_regions',
        'app.blocks',
        'app.blocks_roles',
        'app.comments',
        'app.eav_attributes',
        'app.eav_values',
        'app.entities_terms',
        'app.field_instances',
        'app.languages',
        'app.menu_links',
        'app.menus',
        'app.node_revisions',
        'app.nodes',
        'app.nodes_roles',
        'app.node_types',
        'app.options',
        'app.permissions',
        'app.plugins',
        'app.roles',
        'app.search_datasets',
        'app.terms',
        'app.users',
        'app.users_roles',
        'app.vocabularies',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->session(mockUserSession());
    }

    /**
     * test index action.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/admin/node/manage');
        $this->assertResponseOk();
    }

    /**
     * test create action.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->get('/admin/node/manage/create');
        $this->assertResponseOk();
    }

    /**
     * test add action.
     *
     * @return void
     */
    public function testAdd()
    {
        $this->post('/admin/node/manage/add/article', [
            'title' => 'Test Article',
            'description' => 'this is a test node',
            'status' => 1,
            'comment_status' => 1,

            // custom fields
            'article-introduction' => 'Intro text',
            'article-body' => 'Article body',
            'article-category' => [],
        ]);
        $node = $this->_controller
            ->Nodes
            ->find()
            ->where(['title' => 'Test Article'])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($node);
    }

    /**
     * test edit action.
     *
     * @return void
     */
    public function testEdit()
    {
        $this->get('/admin/node/manage/edit/1');
        $this->assertResponseOk();

        $this->post('/admin/node/manage/edit/1', [
            'title' => 'Modified Article',
            'description' => 'this node was modified',
            'status' => 1,
            'comment_status' => 1,

            // custom fields
            'article-introduction' => 'Intro text',
            'article-body' => 'Article body',
        ]);

        $node = $this->_controller
            ->Nodes
            ->find()
            ->where(['title' => 'Modified Article'])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($node);
    }

    /**
     * test delete action.
     *
     * @return void
     */
    public function testDelete()
    {
        foreach ([1, 2] as $id) {
            $this->get("/admin/node/manage/delete/{$id}");
            $exists = $this->_controller
                ->Nodes
                ->find()
                ->where(['id' => $id])
                ->limit(1)
                ->count();
            $this->assertEquals(0, $exists);
        }
    }
}
