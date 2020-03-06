$addons = [
    'mod_mobilegroupselect' => [ 
        'handlers' => [
            'coursecertificate' => [
                'displaydata' => [
                    'icon' => $CFG->wwwroot . '/mod/groupselect/pix/icon.svg',
                    'class' => '',
                ],
 
                'delegate' => 'CoreCourseModuleDelegate', // Delegate (where to display the link to the plugin)
                'method' => 'mobile_course_view', // Main function in \mod_certificate\output\mobile
                'offlinefunctions' => [
                    'mobile_course_view' => [],
                    'mobile_issues_view' => [],
                ], // Function that needs to be downloaded for offline.
            ],
        ],
        'lang' => [ // Language strings that are used in all the handlers.
            ['pluginname', 'certificate'],
            ['summaryofattempts', 'certificate'],
            ['getcertificate', 'certificate'],
            ['requiredtimenotmet', 'certificate'],
            ['viewcertificateviews', 'certificate'],
        ],
    ],
];