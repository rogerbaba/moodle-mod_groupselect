<?php

/**
 * contains the renderer for the overview page
 * @package    mod_groupselect
 * @copyright  2017 Blended Learning Center (blc@htwchur.ch)
 * @author     Christian Glahn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_groupselect\output;

defined('MOODLE_INTERNAL') || die();

class group_overview implements \renderable, \templatable {

    protected $meta_data;
    protected $groups;
    protected $message;

    /**
    * Contruct
    *
    * @param array $headings An array of renderable headings
    */
   public function __construct($message = "", array $settings = null, array $groups = []) {
   }

    /**
     * Prepare data for use in a template
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(\renderer_base $output) {
        $data = ['message' => "", 'meta_data' => [], 'groups' => [], ];

        $data["message"] = $this->message;

        foreach ($this->meta_data as $key => $heading) {
            $data['meta_data'][$key] = $heading;
        }

        return $data;
    }
}
