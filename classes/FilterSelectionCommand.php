<?php

/**
 * The filter selection commands.
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
 * The filter selection commands.
 *
 * @category CMSimple_XH
 * @package  Filter
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Filter_XH
 */
class Filter_FilterSelectionCommand
{
    /**
     * The available categories.
     *
     * @var array
     */
    private $_categories;

    /**
     * The current page URL.
     *
     * @var string
     */
    private $_pageUrl;

    /**
     * Initializes a new instance.
     *
     * @param array $categories An array of categories.
     *
     * @return void
     *
     * @global string The script name.
     * @global string The current page URL.
     */
    public function __construct($categories)
    {
        global $sn, $su;

        $this->_pageUrl = $sn . '?' . $su;
        $this->_categories = (array) $categories;
    }

    /**
     * Executes the command.
     *
     * @return string (X)HTML.
     */
    public function execute()
    {
        $result = '<ul class="filter_categories">';
        foreach ($this->_categories as $category) {
            $result .= $this->_renderListItem($category);
        }
        $result .= '</ul>';
        return $result;
    }

    /**
     * Renders a single list item.
     *
     * @param string $category A category.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    private function _renderListItem($category)
    {
        global $plugin_tx;

        $href = $this->_pageUrl . '&amp;filter_category=' . $category;
        $label = $category != '' ? $category : $plugin_tx['filter']['label_all'];
        $result = '<li><a href="' . $href. '">' . $label . '</a></li>';
        return $result;
    }
}

?>
