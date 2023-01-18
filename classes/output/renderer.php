<?php

/**
 * Report problem main renderer
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace local_modreportproblem\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;
use renderable;

class renderer extends plugin_renderer_base {
    public function render_html(renderable $page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_modreportproblem/html', $data);
    }
}
