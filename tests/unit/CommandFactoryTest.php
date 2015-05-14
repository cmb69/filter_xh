<?php

/**
 * Testing the command factory.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Filter
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
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
     * The subject under test.
     *
     * @var Filter_CommandFactory
     */
    private $_subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_subject = new Filter_CommandFactory();
    }

    /**
     * Tests make filter pages command.
     *
     * @return void
     */
    public function testMakeFilterPagesCommand()
    {
        $this->assertInstanceOf(
            'Filter_FilterPagesCommand',
            $this->_subject->makeFilterPagesCommand('user')
        );
    }

    /**
     * Tests make filter selection command.
     *
     * @return void
     */
    public function testMakeFilterSelectionCommand()
    {
        $this->assertInstanceOf(
            'Filter_FilterSelectionCommand',
            $this->_subject->makeFilterSelectionCommand(array())
        );
    }

    /**
     * Tests make plugin info command.
     *
     * @return void
     */
    public function testMakePluginInfoCommand()
    {
        $this->assertInstanceOf(
            'Filter_PluginInfoCommand',
            $this->_subject->makePluginInfoCommand()
        );
    }
}

?>
