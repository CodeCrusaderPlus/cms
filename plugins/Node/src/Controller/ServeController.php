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
namespace Node\Controller;

use Cake\I18n\I18n;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;

/**
 * Node serve controller.
 *
 * For handling all public node-rendering requests.
 */
class ServeController extends AppController
{

    /**
     * Components used by this controller.
     *
     * @var array
     */
    public $components = [
        'Comment.Comment',
        'Paginator',
        'RequestHandler',
    ];

    /**
     * An array containing the names of helpers controllers uses.
     *
     * @var array
     */
    public $helpers = [
        'Time',
        'Paginator' => [
            'className' => 'QuickApps\View\Helper\PaginatorHelper',
            'templates' => 'System.paginator-templates',
        ],
    ];

    /**
     * Paginator settings.
     *
     * Used by `search()` and `rss()`.
     *
     * @var array
     */
    public $paginate = [
        'limit' => 10,
    ];

    /**
     * Redirects to ServeController::home()
     *
     * @return void
     */
    public function index()
    {
        $this->redirect(['plugin' => 'node', 'controller' => 'serve', 'action' => 'home']);
    }

    /**
     * Site's home page.
     *
     * Gets a list of all promoted nodes, so themes may render them in their
     * front-page layout. The view-variable `nodes` holds all promoted contents,
     * themes might render this nodes using this variable.
     *
     * @return void
     */
    public function home()
    {
        $this->loadModel('Node.Nodes');
        $nodes = $this->Nodes
            ->find('all')
            ->where([
                'Nodes.promote' => 1,
                'Nodes.status >' => 0,
                'Nodes.language IN' => ['', I18n::locale(), null],
            ])
            ->order(['Nodes.sticky' => 'DESC', 'Nodes.created' => 'DESC'])
            ->limit((int)option('site_nodes_home'));

        $this->set('nodes', $nodes);
        $this->viewMode('teaser');
    }

    /**
     * Node's detail page.
     *
     * @param string $nodeTypeSlug Node's type-slug. e.g. `article`, `basic-page`
     * @param string $nodeSlug Node's slug. e.g. `this-is-an-article`
     * @return \Cake\Network\Response|null
     * @throws \Cake\Network\Exception\NotFoundException When content is not found
     * @throws \Cake\Network\Exception\ForbiddenException When user can't access
     *  this content due to role restrictions
     */
    public function details($nodeTypeSlug, $nodeSlug)
    {
        $this->loadModel('Node.Nodes');
        $conditions = [
            'Nodes.slug' => $nodeSlug,
            'Nodes.node_type_slug' => $nodeTypeSlug,
        ];

        if (!$this->request->is('userAdmin')) {
            $conditions['Nodes.status >'] = 0;
        }

        $node = $this->Nodes->find()
            ->where($conditions)
            ->contain(['NodeTypes', 'Roles'])
            ->first();

        if (!$node) {
            throw new NotFoundException(__d('node', 'The requested page was not found.'));
        } elseif (!$node->isAccessible()) {
            throw new ForbiddenException(__d('node', 'You have not sufficient permissions to see this page.'));
        }

        if (!empty($node->language) && $node->language != I18n::locale()) {
            $translation = $node->hasTranslation();
            if ($translation) {
                $url = "/{$translation->node_type_slug}/{$translation->slug}.html";
                if (option('url_locale_prefix')) {
                    $url = "/{$translation->language}{$url}";
                }
                $this->redirect($url);
                return $this->response;
            }

            $parentLocale = $node->parentLocale();
            if ($parentLocale) {
                $url = "/{$translation->node_type_slug}/{$parentLocale->slug}.html";
                if (option('url_locale_prefix')) {
                    $url = "/{$parentLocale->language}{$url}";
                }
                $this->redirect($url);
                return $this->response;
            }

            throw new NotFoundException(__d('node', 'The requested page was not found.'));
        }

        // Post new comment logic
        if ($node->comment_status > 0) {
            $node->set('comments', $this->Nodes->find('comments', ['for' => $node->id]));
            $this->Comment->config('visibility', $node->comment_status);
            $this->Comment->post($node);
        }

        $this->set('node', $node);
        $this->viewMode('full');
    }

    /**
     * Node search engine page.
     *
     * @param string $criteria A search criteria. e.g.: `"this phrase" -"but not this" OR -hello`
     * @return void
     */
    public function search($criteria)
    {
        $this->loadModel('Node.Nodes');

        try {
            $nodes = $this->Nodes->search($criteria);

            if ($nodes->clause('limit')) {
                $this->paginate['limit'] = $nodes->clause('limit');
            }

            $nodes = $this->paginate($nodes);
        } catch (\Exception $e) {
            $nodes = [];
        }

        $this->set(compact('nodes', 'criteria'));
        $this->viewMode('search-result');
    }

    /**
     * RSS feeder.
     *
     * Similar to `ServeController::search()` but it uses `rss` layout instead of
     * default layout.
     *
     * @param string $criteria A search criteria. e.g.: `"this phrase" -"but not this" OR -hello`
     * @return void
     */
    public function rss($criteria)
    {
        $this->loadModel('Node.Nodes');

        try {
            $nodes = $this->Nodes
                ->search($criteria)
                ->limit(10);
        } catch (\Exception $e) {
            $nodes = [];
        }

        $this->set(compact('nodes', 'criteria'));
        $this->viewMode('rss');
        $this->RequestHandler->renderAs($this, 'rss');
        $this->RequestHandler->respondAs('xml');
    }
}
