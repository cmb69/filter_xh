<?php

/**
 * The presentation layer.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Filter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Filter_XH
 */

/**
 * The controller.
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

/**
 * The command factory.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_CommandFactory
{
    /**
     * Returns a new filter pages command.
     *
     * @param string $category A category.
     *
     * @return Filter_FilterPagesCommand
     */
    public function makeFilterPagesCommand($category)
    {
        return new Filter_FilterPagesCommand(new Filter_Model($category));
    }

    /**
     * Returns a new filter selection command.
     *
     * @param array $categories An array of categories.
     *
     * @return Filter_FilterSelectionCommand
     */
    public function makeFilterSelectionCommand($categories)
    {
        return new Filter_FilterSelectionCommand($categories);
    }

    /**
     * Returns a new plugin info command.
     *
     * @return Filter_PluginInfoCommand
     */
    public function makePluginInfoCommand()
    {
        return new Filter_PluginInfoCommand();
    }
}

/**
 * The filter pages command.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_FilterPagesCommand
{
    /**
     * The model.
     *
     * @var Filter_Model
     */
    private $_model;

    /**
     * Whether we're in view mode.
     *
     * @var bool
     */
    private $_isViewMode;

    /**
     * Initializes a new instance.
     *
     * @param Filter_Model $model A filter model.
     *
     * @return void
     *
     * @global bool Whether we're in admin mode.
     * @global bool Whether we're in edit mode.
     */
    public function __construct(Filter_Model $model)
    {
        global $adm, $edit;

        $this->_model = $model;
        $this->_isViewMode = !($adm && $edit);
    }

    /**
     * Executes the command.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->_isViewMode) {
            $this->_model->hidePages();
        }
    }
}

/**
 * The filter selection command.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_FilterSelectionCommand
{
    /**
     * The available categories.
     *
     * @var array
     */
    private $_categories;

    /**
     * The current page URL.
     *
     * @var string
     */
    private $_pageUrl;

    /**
     * Initializes a new instance.
     *
     * @param array $categories An array of categories.
     *
     * @return void
     *
     * @global string The script name.
     * @global string The current page URL.
     */
    public function __construct($categories)
    {
        global $sn, $su;

        $this->_pageUrl = $sn . '?' . $su;
        $this->_categories = (array) $categories;
    }

    /**
     * Executes the command.
     *
     * @return string (X)HTML.
     */
    public function execute()
    {
        $result = '<ul class="filter_categories">';
        foreach ($this->_categories as $category) {
            $result .= $this->_renderListItem($category);
        }
        $result .= '</ul>';
        return $result;
    }

    /**
     * Renders a single list item.
     *
     * @param string $category A category.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    private function _renderListItem($category)
    {
        global $plugin_tx;

        $href = $this->_pageUrl . '&amp;filter_category=' . $category;
        $label = $category != '' ? $category : $plugin_tx['filter']['label_all'];
        $result = '<li><a href="' . $href. '">' . $label . '</a></li>';
        return $result;
    }
}

/**
 * The plugin info command.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_PluginInfoCommand
{
    /**
     * Executes the command.
     *
     * @return void
     *
     * @global string The contents area.
     */
    public function execute()
    {
        global $o;

        $o .= $this->_render();
    }

    /**
     * Renders the plugin info.
     *
     * @return string (X)HTML.
     */
    private function _render()
    {
        return '<h1>Filter &ndash; Info</h1>';
    }
}


?>
