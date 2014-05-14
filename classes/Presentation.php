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
        global $plugin_cf;

        $this->_commandFactory = $commandFactory;
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
        if (isset($_GET['filter_category'])) {
            $this->_category = stsl($_GET['filter_category']);
            $this->_setCookie();
        } elseif (isset($_COOKIE['filter_category'])) {
            $this->_category = stsl($_COOKIE['filter_category']);
        } else {
            $this->_category = '';
        }
        $this->_filterPages();
    }

    /**
     * Sets the category cookie.
     *
     * @return void
     */
    private function _setCookie()
    {
        setcookie('filter_category', $this->_category, 0, CMSIMPLE_ROOT);
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


?>
