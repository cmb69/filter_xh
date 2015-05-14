<?php

/**
 * The model.
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
 * The model.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_Model
{
    /**
     * The category to display.
     *
     * @var string
     */
    private $_category;

    /**
     * The contents of the pages.
     *
     * @var array
     */
    private $_contents;

    /**
     * The number of pages.
     *
     * @var int
     */
    private $_pageCount;

    /**
     * The menu levels.
     *
     * @var int
     */
    private $_menuLevels;

    /**
     * Initializes a new instance.
     *
     * @param string $category A category to display.
     *
     * @return void
     *
     * @global array The content of the pages.
     * @global int   The number of pages.
     * @global array The configuration of the core.
     */
    public function __construct($category)
    {
        global $c, $cl, $cf;

        $this->_category = $category;
        $this->_contents = $c;
        $this->_pageCount = $cl;
        $this->_menuLevels = (int) $cf['menu']['levels'];
    }

    /**
     * Hides all pages that don't belong to the category.
     *
     * @return void
     */
    public function hidePages()
    {
        if ($this->_category == '') {
            return;
        }
        for ($i = 0; $i < $this->_pageCount; ++$i) {
            if (!$this->_pageHeadingHasClass($i)) {
                $this->_hidePage($i);
            }
        }
    }

    /**
     * Returns whether a page heading has the category as CSS class.
     *
     * @param int $pageIndex A page index.
     *
     * @return bool
     */
    private function _pageHeadingHasClass($pageIndex)
    {
        return (bool) preg_match(
            '/<h[1-' . $this->_menuLevels . '][^>]+class="(?:[^"]*\s)?'
            . preg_quote($this->_category, '/') . '(?:\s[^"]*)?"[^>]*>/u',
            $this->_contents[$pageIndex]
        );
    }

    /**
     * Hides a page.
     *
     * @param int $pageIndex A page index.
     *
     * @return void
     *
     * @global array The content of the pages.
     */
    private function _hidePage($pageIndex)
    {
        global $c;

        $c[$pageIndex] .= '#CMSimple hide#';
    }
}

?>
