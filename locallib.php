<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Internal library of functions for module xkcd
 *
 * All the xkcd specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod
 * @subpackage xkcd
 * @copyright  2012 Paul Vaughan
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Fetches and parses the html from xkcd.com and returns an array of the strings we need.
 *
 * @return array $result (imgurl, title, alt)
 */
function xkcd_details() {

    // Variables.
    $result     = array();

    // xkcd url.
    $url = 'http://xkcd.com/';
    // get the html page into a string.
    $src = file_get_contents($url);

    // DOM things.
    $dom = new DOMDocument();
    @$dom->loadHTML($src);
    $xpath = new DOMXPath($dom);

    // Get the image url.
    $nodelist = $xpath->query("//body/div/div[@id='comic']/img/@src");
    $result['imgurl'] = $nodelist->item(0)->nodeValue;

    // Get the title attribute.
    $nodelist = $xpath->query("//body/div/div[@id='comic']/img/@title");
    $result['title'] = $nodelist->item(0)->nodeValue;

    // Get the alt attribute.
    $nodelist = $xpath->query("//body/div/div[@id='comic']/img/@alt");
    $result['alt'] = $nodelist->item(0)->nodeValue;

    // Go!
    return $result;
}