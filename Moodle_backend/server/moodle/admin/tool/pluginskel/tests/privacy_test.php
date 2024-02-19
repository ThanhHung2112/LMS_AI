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
 * Provides the {@see tool_pluginskel_privacy_testcase} class.
 *
 * @package     tool_pluginskel
 * @category    test
 * @copyright   2018 David Mudrák <david@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_pluginskel;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use tool_pluginskel\local\util\manager;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/'.$CFG->admin.'/tool/pluginskel/vendor/autoload.php');

/**
 * Test case for generating the classes/privacy/provider.php file.
 *
 * @covers    \tool_pluginskel\local\util\manager::prepare_privacy_files
 * @copyright 2018 David Mudrák <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class privacy_test extends \advanced_testcase {

    /**
     * Returns a new instance of the manager utility class suitable for testing.
     *
     * @return \tool_pluginskel\local\util\manager
     */
    protected function get_manager_instance() {

        $logger = new Logger('privacyprovidertest');
        $logger->pushHandler(new NullHandler());
        $manager = manager::instance($logger);

        return $manager;
    }

    /**
     * Return a base recipe for a plugin.
     *
     * @return array
     */
    protected function get_base_recipe() {
        return [
            'component' => 'local_foobar',
            'name' => 'Foo bar',
            'copyright' => '2018 David Mudrák <david@moodle.com>',
        ];
    }

    /**
     * The privacy provider is not generated generated by default.
     */
    public function test_missing() {

        $recipe = $this->get_base_recipe();

        $manager = $this->get_manager_instance();
        $manager->load_recipe($this->get_base_recipe());
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayNotHasKey('classes/privacy/provider.php', $files);
    }

    /**
     * Test generating file that implements the null_provider.
     */
    public function test_null_provider() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => false,
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('Privacy API implementation for the Foo bar plugin.', $providerphp);
        $this->assertStringContainsString('class provider implements \core_privacy\local\metadata\null_provider {', $providerphp);
        $this->assertStringContainsString('public static function get_reason() : string {', $providerphp);

        $langfile = $files['lang/en/local_foobar.php'];

        $this->assertStringContainsString("'privacy:metadata'", $langfile);
    }

    /**
     * Test generating file that implements the null_provider with legacy polyfill.
     */
    public function test_null_provider_legacy() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'uselegacypolyfill' => true,
                'haspersonaldata' => false,
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('Privacy API implementation for the Foo bar plugin.', $providerphp);
        $this->assertStringContainsString('class provider implements \core_privacy\local\metadata\null_provider {', $providerphp);
        $this->assertStringContainsString('use \core_privacy\local\legacy_polyfill;', $providerphp);
        $this->assertStringContainsString('public static function _get_reason() {', $providerphp);
    }

    /**
     * Makes sure that exception is thrown when trying to link an invalid subsystem.
     *
     * @dataProvider data_invalid_subsystems
     * @param string $input Declared subsystem name that should throw exception.
     */
    public function test_invalid_subsystem($input) {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'meta' => [
                    'subsystems' => [
                        $input,
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);

        $this->expectException(\coding_exception::class);
        $this->expectExceptionMessage('Unknown core subsystem: '.$input);
        $manager->make();
    }

    /**
     * Return names of invalid subsystems.
     *
     * @return array
     */
    public static function data_invalid_subsystems() {

        return [
            'non_core_plugin' => ['mod_forum'],
            'unknown_without_prefix' => ['foobar'],
            'unknown_with_prefix' => ['core_foobar'],
        ];
    }

    /**
     * Test generating provider.php for a plugin that stores content in a Moodle subsystem.
     */
    public function test_subsystem_metadata_provider() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'meta' => [
                    'subsystems' => [
                        'files',
                        [
                            'portfolio' => [
                                'firstname',
                                'lastname',
                            ]
                        ],
                        'core_comment',
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString(
            "\$collection->add_subsystem_link('core_files', [], 'privacy:metadata:subsystem:files');",
            $providerphp
        );
        $this->assertStringContainsString(
            "\$collection->add_subsystem_link('core_comment', [], 'privacy:metadata:subsystem:comment');",
            $providerphp
        );

        $langfile = $files['lang/en/local_foobar.php'];

        $this->assertStringContainsString("'privacy:metadata:subsystem:files'", $langfile);
        $this->assertStringContainsString("'privacy:metadata:subsystem:comment'", $langfile);
    }

    /**
     * Test generating provider.php for a plugin that sends data to external locations.
     */
    public function test_external_metadata_provider() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'meta' => [
                    'external' => [
                        [
                            'mahara' => [
                                'firstname',
                                'lastname',
                            ]
                        ],
                        'systemasval',
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString("\$collection->add_external_location_link('mahara', [", $providerphp);
        $this->assertStringContainsString("'firstname' => 'privacy:metadata:external:mahara:firstname',", $providerphp);
        $this->assertStringContainsString(
            "\$collection->add_external_location_link('systemasval', [], 'privacy:metadata:external:systemasval');", $providerphp);

        $langfile = $files['lang/en/local_foobar.php'];

        $this->assertStringContainsString("'privacy:metadata:external:mahara'", $langfile);
        $this->assertStringContainsString("'privacy:metadata:external:mahara:firstname'", $langfile);
        $this->assertStringContainsString("'privacy:metadata:external:mahara:lastname'", $langfile);
        $this->assertStringContainsString("'privacy:metadata:external:systemasval'", $langfile);
    }

    /**
     * Test generating file that implements \core_privacy\local\metadata\provider.
     */
    public function test_dbfields_metadata_provider() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'meta' => [
                    'dbfields' => [
                        'local_foobar_three' => ['foo'],
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString(
            'use core_privacy\local\metadata\collection;',
            $providerphp
        );
        $this->assertStringContainsString(
            'public static function get_metadata(collection $collection) : collection {',
            $providerphp
        );
    }

    /**
     * Test generating file that implements \core_privacy\local\metadata\provider with legacy polyfill.
     */
    public function test_dbfields_metadata_provider_legacy() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'uselegacypolyfill' => true,
                'meta' => [
                    'dbfields' => [
                        'local_foobar_three' => ['foo'],
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('use \core_privacy\local\legacy_polyfill;', $providerphp);
        $this->assertStringContainsString('public static function _get_metadata(collection $collection) {', $providerphp);

        $langfile = $files['lang/en/local_foobar.php'];

        $this->assertStringContainsString("\$string['privacy:metadata:db:three'] = '", $langfile);
        $this->assertStringContainsString("\$string['privacy:metadata:db:three:foo'] = '", $langfile);
    }

    /**
     * Test generating file that implements \core_privacy\local\request\preference_provider
     */
    public function test_preference_provider() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'meta' => [
                    'userpreferences' => [
                        'first',
                        'local_foobar_another',
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('\core_privacy\local\request\user_preference_provider', $providerphp);
        $this->assertStringContainsString(
            "\$collection->add_user_preference('first', 'privacy:metadata:preference:first');",
            $providerphp
        );
        $this->assertStringContainsString(
            "\$collection->add_user_preference('local_foobar_another', 'privacy:metadata:preference:another');",
            $providerphp);
        $this->assertStringContainsString('public static function export_user_preferences(int $userid) {', $providerphp);
        $this->assertStringContainsString("\$first = get_user_preferences('first', null, \$userid);", $providerphp);
        $this->assertStringContainsString("writer::export_user_preference('local_foobar', 'first', \$first,", $providerphp);
        $this->assertStringContainsString(
            "\$another = get_user_preferences('local_foobar_another', null, \$userid);",
            $providerphp
        );
        $this->assertStringContainsString(
            "writer::export_user_preference('local_foobar', 'local_foobar_another', \$another,",
            $providerphp
        );

        $langfile = $files['lang/en/local_foobar.php'];

        $this->assertStringContainsString("'privacy:metadata:preference:first'", $langfile);
        $this->assertStringContainsString("'privacy:metadata:preference:another'", $langfile);
    }

    /**
     * Test generating file that implements \core_privacy\local\request\preference_provider with legacy polyfill.
     */
    public function test_preference_provider_legacy() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'uselegacypolyfill' => true,
                'meta' => [
                    'userpreferences' => [
                        'bar',
                    ],
                ],
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('public static function _export_user_preferences($userid) {', $providerphp);
    }


    /**
     * Test generating method get_contexts_for_userid() with legacy polyfill.
     */
    public function test_get_contexts_for_userid() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('use core_privacy\local\request\contextlist;', $providerphp);
        $this->assertStringContainsString(
            'public static function get_contexts_for_userid(int $userid) : contextlist {',
            $providerphp
        );
    }

    /**
     * Test generating method get_contexts_for_userid() with legacy polyfill.
     */
    public function test_get_contexts_for_userid_legacy() {

        $recipe = $this->get_base_recipe() + [
            'privacy' => [
                'haspersonaldata' => true,
                'uselegacypolyfill' => true,
            ],
        ];

        $manager = $this->get_manager_instance();
        $manager->load_recipe($recipe);
        $manager->make();

        $files = $manager->get_files_content();
        $this->assertArrayHasKey('classes/privacy/provider.php', $files);

        $providerphp = $files['classes/privacy/provider.php'];

        $this->assertStringContainsString('use core_privacy\local\request\contextlist;', $providerphp);
        $this->assertStringContainsString('use \core_privacy\local\legacy_polyfill;', $providerphp);
        $this->assertStringContainsString('public static function _get_contexts_for_userid($userid) {', $providerphp);
    }
}
