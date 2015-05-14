<?php

/**
 * The plugin info commands.
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
 * The plugin info commands.
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
