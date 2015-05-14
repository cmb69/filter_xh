<?php

/**
 * Testing the filter pages command.
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

require_once './classes/required_classes.php';

/**
 * Testing the filter pages command.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class FilterPagesCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * The model.
     *
     * @var Filter_Model
     */
    protected $model;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        $this->model = $this->getMockBuilder('Filter_Model')
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * Tests that pages are filtered in view mode.
     *
     * @return void
     */
    public function testPagesAreFilteredInViewMode()
    {
        $this->model->expects($this->once())->method('hidePages');
        $subject = new Filter_FilterPagesCommand($this->model);
        $subject->execute();
    }

    /**
     * Tests that pages are not filtered in edit mode.
     *
     * @return void
     *
     * @global bool Whether we're in edit mode.
     */
    public function testPagesAreNotFilteredInEditMode()
    {
        global $edit;

        $this->defineConstant('XH_ADM', true);
        $edit = true;
        $this->model->expects($this->never())->method('hidePages');
        $subject = new Filter_FilterPagesCommand($this->model);
        $subject->execute();
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
