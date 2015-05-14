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
    protected $category;

    /**
     * The contents of the pages.
     *
     * @var array
     */
    protected $contents;

    /**
     * The number of pages.
     *
     * @var int
     */
    protected $pageCount;

    /**
     * The menu levels.
     *
     * @var int
     */
    protected $menuLevels;

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

        $this->category = $category;
        $this->contents = $c;
        $this->pageCount = $cl;
        $this->menuLevels = (int) $cf['menu']['levels'];
    }

    /**
     * Hides all pages that don't belong to the category.
     *
     * @return void
     */
    public function hidePages()
    {
        if ($this->category == '') {
            return;
        }
        for ($i = 0; $i < $this->pageCount; ++$i) {
            if (!$this->pageHeadingHasClass($i)) {
                $this->hidePage($i);
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
    protected function pageHeadingHasClass($pageIndex)
    {
        return (bool) preg_match(
            '/<h[1-' . $this->menuLevels . '][^>]+class="(?:[^"]*\s)?'
            . preg_quote($this->category, '/') . '(?:\s[^"]*)?"[^>]*>/u',
            $this->contents[$pageIndex]
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
    protected function hidePage($pageIndex)
    {
        global $c;

        $c[$pageIndex] .= '#CMSimple hide#';
    }
}

?>
