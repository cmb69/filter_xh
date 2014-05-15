<?php

/**
 * Testing the plugin info command.
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

require_once '../../cmsimple/adminfuncs.php';

require_once './classes/Presentation.php';

/**
 * Testing the plugin info command.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class PluginInfoCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that print plugin admin is called.
     *
     * @return void
     */
    public function testShowsHeading()
    {
        $matcher = array(
            'tag' => 'h1',
            'content' => 'Filter'
        );
        $subject = new Filter_PluginInfoCommand();
        $subject->execute();
        $this->_assertOutputMatches($matcher);
    }

    /**
     * Asserts that the output matches a matcher.
     *
     * @param array $matcher A matcher.
     *
     * @return void
     *
     * @global string The contents area.
     */
    private function _assertOutputMatches($matcher)
    {
        global $o;

        $this->assertTag($matcher, $o);
    }
}

?>
