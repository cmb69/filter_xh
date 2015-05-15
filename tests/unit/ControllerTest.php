<?php

/**
 * Testing the controller.
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
    protected $subject;

    /**
     * The command factory.
     *
     * @var Filter_CommandFactory
     */
    protected $commandFactory;

    /**
     * The setcookie() spy.
     *
     * @var object
     */
    protected $setCookieSpy;

    /**
     * The XH_registerStandardMenuItems mock.
     *
     * @var object
     */
    protected $rspmiMock;

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
        $this->commandFactory = $this->getMock('Filter_CommandFactory');
        $this->subject = new Filter_Controller($this->commandFactory);
        $this->setCookieSpy = new PHPUnit_Extensions_MockFunction(
            'setcookie', $this->subject
        );
        $this->rspmiMock = new PHPUnit_Extensions_MockFunction(
            'XH_registerStandardPluginMenuItems', null
        );
        new PHPUnit_Extensions_MockFunction(
            'plugin_admin_common', $this->subject
        );
        new PHPUnit_Extensions_MockFunction(
            'print_plugin_admin', $this->subject
        );
    }

    /**
     * Tears down the test fixture.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->rspmiMock->restore();
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
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo(''))
            ->will($this->returnValue($command));
        $this->subject->dispatch();
    }

    /**
     * Tests that dispatch executes a filter pages command with a category from
     * a GET parameter.
     *
     * @return void
     */
    public function testDispatchExecutesFilterPagesCommandWithCategoryFromGet()
    {
        $_GET['filter_category'] = 'foobar';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo(''))
            ->will($this->returnValue($command));
        $this->setCookieSpy->expects($this->any());
        $this->subject->dispatch();
    }

    /**
     * Tests that dispatch executes a filter pages command with an invalid
     * category from a GET parameter.
     *
     * @return void
     */
    public function testDispatchExecutesFilterPagesCommandWithInvalidCatFromGet()
    {
        $_GET['filter_category'] = 'foo';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo('foo'))
            ->will($this->returnValue($command));
        $this->setCookieSpy->expects($this->any());
        $this->subject->dispatch();
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
        $this->commandFactory->expects($this->any())
            ->method('makeFilterPagesCommand')
            ->will($this->returnValue($command));
        $this->setCookieSpy->expects($this->once())->with(
            $this->equalTo('filter_category'), $this->equalTo('foo')
        );
        $this->subject->dispatch();
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
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo('foo'))
            ->will($this->returnValue($command));
        $this->subject->dispatch();
    }

    /**
     * Tests that dispatch executes a filter pages command with an invalid
     * category from a cookie..
     *
     * @return void
     */
    public function testDispatchExecutesFilterPagesCommandWithInvalidCatFromCookie()
    {
        $_COOKIE['filter_category'] = 'foobar';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('execute');
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')->with($this->equalTo(''))
            ->will($this->returnValue($command));
        $this->subject->dispatch();
    }

    /**
     * Tests that dispatch executes a plugin info command.
     *
     * @return void
     *
     * @global string Whether the filter plugin administration has been requested.
     */
    public function testDispatchExecutesPluginInfoCommand()
    {
        global $filter;

        $this->defineConstant('XH_ADM', true);
        $filter = 'true';
        $command = $this->getMock('Filter_PluginInfoCommand');
        $command->expects($this->once())->method('execute');
        $this->commandFactory->expects($this->once())
            ->method('makePluginInfoCommand')
            ->will($this->returnValue($command));
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')
            ->will($this->returnValue($command));
        $this->subject = new Filter_Controller($this->commandFactory);
        new PHPUnit_Extensions_MockFunction('print_plugin_admin', $this->subject);
        $this->subject->dispatch();
    }

    /**
     * Tests that dispatch handles common administration.
     *
     * @return void
     *
     * @global string Whether the filter plugin administration is requested.
     * @global string The value of the admin GP parameter.
     */
    public function testDispatchHandlesCommonAdministration()
    {
        global $filter, $admin;

        $this->defineConstant('XH_ADM', true);
        $filter = 'true';
        $admin = 'plugin_config';
        $command = $this->getMockBuilder('Filter_FilterPagesCommand')
            ->disableOriginalConstructor()->getMock();
        $this->commandFactory->expects($this->once())
            ->method('makeFilterPagesCommand')
            ->will($this->returnValue($command));
        $this->subject = new Filter_Controller($this->commandFactory);
        $pluginAdminCommonSpy = new PHPUnit_Extensions_MockFunction(
            'plugin_admin_common', $this->subject
        );
        $pluginAdminCommonSpy->expects($this->once());
        new PHPUnit_Extensions_MockFunction('print_plugin_admin', $this->subject);
        $this->subject->dispatch();
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
        $this->commandFactory->expects($this->once())
            ->method('makeFilterSelectionCommand')
            ->with($this->equalTo(array('', 'foo', 'bar', 'baz')))
            ->will($this->returnValue($command));
        $this->subject->renderFilterSelection();
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
        $this->commandFactory->expects($this->once())
            ->method('makeFilterSelectionCommand')
            ->with($this->equalTo(array()))
            ->will($this->returnValue($command));
        $this->subject = new Filter_Controller($this->commandFactory);
        $this->subject->renderFilterSelection();
    }

    /**
     * Redefines a constant.
     *
     * @param string $name  A name.
     * @param string $value A value.
     *
     * @return void
     */
    protected function defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}

?>
