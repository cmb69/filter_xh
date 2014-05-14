<?php

/**
 * Testing the controller.
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

require_once './vendor/autoload.php';

require_once './classes/Model.php';
require_once './classes/Presentation.php';

/**
 * Testing the controller.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class ControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Filter_Controller
     */
    private $_subject;

    /**
     * The command factory.
     *
     * @var Filter_CommandFactory
     */
    private $_commandFactory;

    /**
     * The setcookie() spy.
     *
     * @var object
     */
    private $_setCookieSpy;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function setUp()
    {
        global $plugin_cf;

        if (!defined('CMSIMPLE_ROOT')) {
            define('CMSIMPLE_ROOT', '');
        }
        $plugin_cf = array(
            'filter' => array('categories' => 'foo, bar, baz')
        );
        $this->_commandFactory = $this->getMock('Filter_CommandFactory');
        $this->_subject = new Filter_Controller($this->_commandFactory);
        $stslStub = new PHPUnit_Extensions_MockFunction('stsl', $this->_subject);
        $stslStub->expects($this->any())->will($this->returnArgument(0));
        $this->_setCookieSpy = new PHPUnit_Extensions_MockFunction(
            'setcookie', $this->_subject
        );
    }

    /**
     * Tests that dispatch executes a filter pages command.
     *
     * @return void
     */
    public function testDispatchExecutesFilterPagesCommand()
    {
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->_commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo(''))
            ->will($this->returnValue($command));
        $this->_subject->dispatch();
    }

    /**
     * Tests that dispatch executes a filter pages command with a category from
     * a GET parameter.
     *
     * @return void
     */
    public function testDispatchExecutesFilterPagesCommandWithCategoryFromGet()
    {
        $_GET['filter_category'] = 'foo';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->_commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo('foo'))
            ->will($this->returnValue($command));
        $this->_setCookieSpy->expects($this->any());
        $this->_subject->dispatch();
    }

    /**
     * Tests that dispatch sets a cookie with a category from a GET parameter.
     *
     * @return void
     */
    public function testDispatchSetsCookieWithCategoryFromGet()
    {
        $_GET['filter_category'] = 'foo';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->_commandFactory->expects($this->any())
            ->method('makeFilterPagesCommand')
            ->will($this->returnValue($command));
        $this->_setCookieSpy->expects($this->once())->with(
            $this->equalTo('filter_category'), $this->equalTo('foo')
        );
        $this->_subject->dispatch();
    }

    /**
     * Tests that dispatch executes a filter pages command with a category from
     * a cookie..
     *
     * @return void
     */
    public function testDispatchExecutesFilterPagesCommandWithCategoryFromCookie()
    {
        $_COOKIE['filter_category'] = 'foo';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->_commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo('foo'))
            ->will($this->returnValue($command));
        $this->_subject->dispatch();
    }

    /**
     * Tests render filter selection.
     *
     * @return void
     */
    public function testRenderFilterSelection()
    {
        $command = $this->getMockBuilder('Filter_FilterSelectionCommand')
            ->disableOriginalConstructor()->getMock();
        $this->_commandFactory->expects($this->once())
            ->method('makeFilterSelectionCommand')
            ->with($this->equalTo(array('', 'foo', 'bar', 'baz')))
            ->will($this->returnValue($command));
        $this->_subject->renderFilterSelection();
    }

    /**
     * Tests render empty filter selection.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testRenderEmptyFilterSelection()
    {
        global $plugin_cf;

        $plugin_cf['filter']['categories'] = '';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $this->_commandFactory->expects($this->once())
            ->method('makeFilterSelectionCommand')
            ->with($this->equalTo(array()))
            ->will($this->returnValue($command));
        $this->_subject = new Filter_Controller($this->_commandFactory);
        $this->_subject->renderFilterSelection();
    }
}

?>
