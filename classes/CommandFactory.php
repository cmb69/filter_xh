<?php

/**
 * The command factories.
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
 * The command factories.
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

?>
