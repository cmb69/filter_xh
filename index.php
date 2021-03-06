<?php

/**
 * The "main program".
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
 * Renders the filter selection.
 *
 * @return string (X)HTML.
 */
function Filter_selection()
{
    global $_Filter_controller;

    return $_Filter_controller->renderFilterSelection();
}

/**
 * The filter controller.
 */
$_Filter_controller = new Filter_Controller(new Filter_CommandFactory());
$_Filter_controller->dispatch();

?>
