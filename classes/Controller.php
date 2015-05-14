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
    private $_commandFactory;

    /**
     * Whether the plugin adminstration is requested.
     *
     * @var bool
     */
    private $_isAdministration;

    /**
     * The available categories.
     *
     * @var array
     */
    private $_categories;

    /**
     * The requested category.
     *
     * @var string
     */
    private $_category;

    /**
     * Initializes a new instance.
     *
     * @param Filter_CommandFactory $commandFactory A command factory.
     *
     * @return void
     */
    public function __construct(Filter_CommandFactory $commandFactory)
    {
        global $adm, $filter, $plugin_cf;

        $this->_commandFactory = $commandFactory;
        $this->_isAdministration =  ($adm && $filter == 'true');
        $this->_categories = $this->_splitCategories(
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
    private function _splitCategories($categories)
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
        $this->_determineCategory();
        $this->_setCookie();
        $this->_filterPages();
        if ($this->_isAdministration) {
            $this->_administrationDispatch();
        }
    }

    /**
     * Determines the current category.
     *
     * @return void
     */
    private function _determineCategory()
    {
        if (isset($_GET['filter_category'])) {
            $category = stsl($_GET['filter_category']);
        } elseif (isset($_COOKIE['filter_category'])) {
            $category = stsl($_COOKIE['filter_category']);
        } else {
            $category = '';
        }
        if (in_array($category, $this->_categories)) {
            $this->_category = $category;
        } else {
            $this->_category = '';
        }
    }

    /**
     * Sets the category cookie.
     *
     * @return void
     */
    private function _setCookie()
    {
        if (isset($_GET['filter_category'])) {
            setcookie('filter_category', $this->_category, 0, CMSIMPLE_ROOT);
        }
    }

    /**
     * Filters the pages.
     *
     * @return void
     */
    private function _filterPages()
    {
        $this->_commandFactory->makeFilterPagesCommand($this->_category)
            ->execute();
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
    private function _administrationDispatch()
    {
        global $action, $admin, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $this->_commandFactory->makePluginInfoCommand()->execute();
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
        return $this->_commandFactory
            ->makeFilterSelectionCommand($this->_categories)
            ->execute();
    }
}


?>
