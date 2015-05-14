<?php
/**
 * The autoloader.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Filter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Filter_XH
 */

/**
 * Autoloads the plugin classes.
 *
 * @param string $class A class name.
 *
 * @return void
 */
function Filter_autoload($class)
{
    $parts = explode('_', $class, 2);
    if ($parts[0] == 'Filter') {
        include_once dirname(__FILE__) . '/' . $parts[1] . '.php';
    }
}

spl_autoload_register('Filter_autoload');

?>