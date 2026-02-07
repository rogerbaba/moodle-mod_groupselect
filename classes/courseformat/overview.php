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

namespace mod_groupselect\courseformat;

use core\activity_dates;
use core\output\action_link;
use core\output\local\properties\button;
use core\output\local\properties\text_align;
use core\url;
use core_calendar\output\humandate;
use core_courseformat\local\overview\overviewitem;
use mod_groupselect\manager;

/**
 * Group self-selection overview integration (for Moodle 5.1+)
 *
 * @package   mod_groupselect
 * @copyright 2026 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class overview extends \core_courseformat\activityoverviewbase {
    /**
     * @var manager the groupselect manager.
     */
    private manager $manager;
    /**
     * @var \core\output\renderer_helper $rendererhelper the renderer helper
     */
    private \core\output\renderer_helper $rendererhelper;

    /**
     * Constructor.
     *
     * @param \cm_info $cm the course module instance.
     * @param \core\output\renderer_helper $rendererhelper the renderer helper.
     */
    public function __construct(
        \cm_info $cm,
        \core\output\renderer_helper $rendererhelper
    ) {
        parent::__construct($cm);
        $this->rendererhelper = $rendererhelper;
        $this->manager = manager::create_from_coursemodule($cm);
    }

    /**
     * Get the rating begins at date overview item.
     *
     * @return overviewitem|null
     * @throws \coding_exception
     */
    public function get_extra_rating_accesstimestart_overview(): ?overviewitem {
        global $USER;

        $dates = activity_dates::get_dates_for_module($this->cm, $USER->id);
        $opendate = null;
        foreach ($dates as $date) {
            if ($date['dataid'] === 'timeopen') {
                $opendate = $date['timestamp'];
                break;
            }
        }
        if (empty($opendate)) {
            return new overviewitem(
                get_string('timeavailable', 'groupselect'),
                null,
                '-',
            );
        }

        $content = humandate::create_from_timestamp($opendate);

        return new overviewitem(
            get_string('timeavailable', 'groupselect'),
            $opendate,
            $content,
            text_align::CENTER
        );
    }

    /**
     * Get the rating ends at date overview item.
     *
     * @return overviewitem|null
     * @throws \coding_exception
     */
    public function get_extra_rating_accesstimestop_overview(): ?overviewitem {
        global $USER;

        $dates = activity_dates::get_dates_for_module($this->cm, $USER->id);
        $closedate = null;
        foreach ($dates as $date) {
            if ($date['dataid'] === 'timeclose') {
                $closedate = $date['timestamp'];
                break;
            }
        }
        if (empty($closedate)) {
            return new overviewitem(
                get_string('duedate', 'groupselect'),
                null,
                '-',
            );
        }

        $content = humandate::create_from_timestamp($closedate);

        return new overviewitem(
            get_string('duedate', 'groupselect'),
            $closedate,
            $content,
        );
    }

    #[\Override]
    public function get_extra_overview_items(): array {
        return [
            'rating_begintime' => $this->get_extra_rating_accesstimestart_overview(),
            'rating_endtime' => $this->get_extra_rating_accesstimestop_overview(),
        ];
    }

    #[\Override]
    public function get_actions_overview(): ?overviewitem {
        $url = new url(
            '/mod/groupselect/view.php',
            ['id' => $this->cm->id],
        );

        if (
            class_exists(button::class) &&
            (new \ReflectionClass(button::class))->hasConstant('BODY_OUTLINE')
        ) {
            $bodyoutline = button::BODY_OUTLINE;
            $buttonclass = $bodyoutline->classes();
        } else {
            $buttonclass = "btn btn-outline-secondary";
        }

        $text = get_string('view');
        $content = new action_link(
            $url,
            $text,
            null,
            ['class' => $buttonclass],
        );

        return new overviewitem(
            get_string('actions'),
            $text,
            $content,
            text_align::CENTER,
        );
    }
}
