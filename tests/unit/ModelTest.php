<?php

/**
 * Testing the model.
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

/**
 * Testing the model.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The content of the pages.
     * @global int   The number of pages.
     * @global array The configuration of the core.
     */
    public function setUp()
    {
        global $c, $cl, $cf;

        $c = array(
            '<h1 class="user developer">Welcome</h1>',
            '<h2 class="user">User Manual</h2>',
            '<h3 class="developer">Developer Manual</h3>'
        );
        $cl = count($c);
        $cf = array('menu' => array('levels' => '3'));
    }

    /**
     * Tests that the correct pages are hidden.
     *
     * @param string $category A category.
     * @param array  $expected An expected result.
     *
     * @return array
     *
     * @dataProvider dataForPagesAreHidden
     */
    public function testPagesAreHidden($category, $expected)
    {
        $subject = new Filter_Model($category);
        $subject->hidePages();
        $this->assertEquals($expected, $this->_getVisiblePages());
    }

    /**
     * Provides data for pages are hidden test.
     *
     * @return void
     */
    public function dataForPagesAreHidden()
    {
        return array(
            array('user', array(0, 1)),
            array('developer', array(0, 2)),
            array('', range(0, 2))
        );
    }

    /**
     * Returns the visible pages.
     *
     * @return array
     *
     * @global int The number of pages.
     */
    private function _getVisiblePages()
    {
        global $cl;

        $result = array();
        for ($i = 0; $i < $cl; ++$i) {
            if ($this->_isPageVisible($i)) {
                $result[] = $i;
            }
        }
        return $result;
    }

    /**
     * Returns whether a page is visible.
     *
     * @param int $pageIndex A page index.
     *
     * @return bool
     *
     * @global array The content of the pages.
     */
    private function _isPageVisible($pageIndex)
    {
        global $c;

        return strpos($c[$pageIndex], '#CMSimple hide#') === false;
    }
}

?>
