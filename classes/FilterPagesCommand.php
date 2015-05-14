<?php

/**
 * The filter pages command.
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
    protected $model;

    /**
     * Whether we're in view mode.
     *
     * @var bool
     */
    protected $isViewMode;

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

        $this->model = $model;
        $this->isViewMode = !($adm && $edit);
    }

    /**
     * Executes the command.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->isViewMode) {
            $this->model->hidePages();
        }
    }
}

?>
