<?php

/**
 * The controllers.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Filter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Filter_XH
 */

/**
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_Controller
{
    /**
     * The command factory.
     *
     * @var Filter_CommandFactory
     */
    protected $commandFactory;

    /**
     * The available categories.
     *
     * @var array
     */
    protected $categories;

    /**
     * The requested category.
     *
     * @var string
     */
    protected $category;

    /**
     * Initializes a new instance.
     *
     * @param Filter_CommandFactory $commandFactory A command factory.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function __construct(Filter_CommandFactory $commandFactory)
    {
        global $plugin_cf;

        $this->commandFactory = $commandFactory;
        $this->categories = $this->splitCategories(
            $plugin_cf['filter']['categories']
        );
    }

    /**
     * Splits the category string.
     *
     * @param string $categories A comma separated list of categories.
     *
     * @return array
     */
    protected function splitCategories($categories)
    {
        if (trim($categories) == '') {
            return array();
        } else {
            return array_map('trim', explode(',', ',' . $categories));
        }
    }

    /**
     * Dispatches on current request.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->determineCategory();
        $this->setCookie();
        $this->filterPages();
        if (defined('XH_ADM') && XH_ADM) {
            XH_registerStandardPluginMenuItems(false);
            if ($this->isAdministrationRequested()) {
                $this->administrationDispatch();
            }
        }
    }

    /**
     * Determines the current category.
     *
     * @return void
     */
    protected function determineCategory()
    {
        if (isset($_GET['filter_category'])) {
            $category = stsl($_GET['filter_category']);
        } elseif (isset($_COOKIE['filter_category'])) {
            $category = stsl($_COOKIE['filter_category']);
        } else {
            $category = '';
        }
        if (in_array($category, $this->categories)) {
            $this->category = $category;
        } else {
            $this->category = '';
        }
    }

    /**
     * Sets the category cookie.
     *
     * @return void
     */
    protected function setCookie()
    {
        if (isset($_GET['filter_category'])) {
            setcookie('filter_category', $this->category, 0, CMSIMPLE_ROOT);
        }
    }

    /**
     * Filters the pages.
     *
     * @return void
     */
    protected function filterPages()
    {
        $this->commandFactory->makeFilterPagesCommand($this->category)
            ->execute();
    }

    /**
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin administration is requested.
     */
    protected function isAdministrationRequested()
    {
        global $filter;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('filter')
            || isset($filter) && $filter == 'true';
    }

    /**
     * Dispatches on the current administration request.
     *
     * @return void
     *
     * @global string The value of the action GP parameter.
     * @global string The value of the admin GP parameter.
     * @global string The output of the contents area.
     */
    protected function administrationDispatch()
    {
        global $action, $admin, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $this->commandFactory->makePluginInfoCommand()->execute();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'filter');
        }
    }

    /**
     * Renders the filter selection.
     *
     * @return string (X)HTML.
     */
    public function renderFilterSelection()
    {
        return $this->commandFactory
            ->makeFilterSelectionCommand($this->categories)
            ->execute();
    }
}


?>
