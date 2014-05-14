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
        $this->_commandFactory = $commandFactory;
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

?>
