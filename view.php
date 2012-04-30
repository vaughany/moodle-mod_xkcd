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
 * Prints a particular instance of xkcd
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage xkcd
 * @copyright  2012 Paul Vaughan
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace xkcd with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // xkcd instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('xkcd', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $xkcd  = $DB->get_record('xkcd', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $xkcd  = $DB->get_record('xkcd', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $xkcd->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('xkcd', $xkcd->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'xkcd', 'view', "view.php?id={$cm->id}", $xkcd->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/xkcd/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($xkcd->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('xkcd-'.$somevar);

/**
 * Block stuff.
 */
$details = xkcd_details();
if (!$details) {
    die('Write better error handling code.');
}

$build  = '<a href="'.$details['imgurl'].'">'."\n";
$build .= '    <img src="'.$details['imgurl'].'" title="'.$details['title'].'" alt="'.$details['alt'].'" />'."\n";
$build .= '</a>'."\n";

// Output starts here
echo $OUTPUT->header();

// Heading.
echo $OUTPUT->heading($details['alt']);

// Comic in a pretty 'box'.
echo $OUTPUT->box_start('generalbox', 'xkcdimage');
echo $build;
echo $OUTPUT->box_end();

// Finish the page.
echo $OUTPUT->footer();
