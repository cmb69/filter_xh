<?php

/**
 * Testing the filter selection command.
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
 * Testing the filter selection command.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class FilterSelectionCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * The test subject.
     *
     * @var Filter_FilterSelectionCommand
     */
    protected $subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global string The script name.
     * @global string The current page URL.
     * @global array  The localization of the plugins.
     */
    public function setUp()
    {
        global $sn, $su, $plugin_tx;

        $sn = '/xh/';
        $su = 'Page';
        $plugin_tx['filter'] = array('label_all' => 'all');
        $this->subject = new Filter_FilterSelectionCommand(
            array('', 'foo', 'bar', 'baz')
        );
    }

    /**
     * Tests that execute returns an UL with four LIs.
     *
     * @return void
     */
    public function testExecuteReturnsUlWith4Lis()
    {
        $matcher = array(
            'tag' => 'ul',
            'attributes' => array('class' => 'filter_categories'),
            'children' => array(
                'count' => 4,
                'only' => array('tag' => 'li')
            )
        );
        $this->assertResultMatches($matcher);
    }

    /**
     * Tests that the result has an anchor.
     *
     * @return void
     */
    public function testResultHasAnchor()
    {
        $matcher = array(
            'tag' => 'a',
            'attributes' => array('href' => '/xh/?Page&filter_category='),
            'content' => 'all',
            'parent' => array('tag' => 'li')
        );
        $this->assertResultMatches($matcher);
    }

    /**
     * Asserts that the result of command execution matches a <var>$matcher</var>.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     */
    protected function assertResultMatches(array $matcher)
    {
        @$this->assertTag($matcher, $this->subject->execute());
    }
}

?>
