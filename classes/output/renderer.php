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
 * Report problem main renderer
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace local_modreportproblem\output;

use plugin_renderer_base;
use renderable;

class renderer extends plugin_renderer_base {
    public function render_html(renderable $page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_modreportproblem/html', $data);
    }
}
