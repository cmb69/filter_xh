<?php

/**
 * Testing the command factory.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Filter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Filter_XH
 */

require_once './classes/Model.php';
require_once './classes/Presentation.php';

/**
 * Testing the command factory.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class CommandFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the make filter pages command.
     *
     * @return void
     */
    public function testMakeFilterPagesCommand()
    {
        $factory = new Filter_CommandFactory();
        $this->assertInstanceOf(
            'Filter_FilterPagesCommand', $factory->makeFilterPagesCommand('user')
        );
    }
}

?>
