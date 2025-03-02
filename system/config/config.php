<?php

/**
 * Config array, used for specify the Services properties, and allows you to have different environments configuration
 *
 * Database credentials
 * Session variables
 * Email credentials
 * TimeZone
 * Debug console (for outputting php errors and debug messages)
 * Cache number (in case you update the browser resources)
 */

return [
    "development" => [
        "database_host" => "localhost:3306",
        "database_user" => "root",
        "database_pass" => "",
        "database_name" => "qlsach",
        "database_auto_init" => true,

        "system_allow_forms_without_csrf" => true,
        "allow_forms_without_csrf_input" => true,

        "session_name" => "session",
        "session_encrypt_method" => "aes-256-cbc",
        "session_iv" => "OeJgOBOMWu6Gw7fW", // Generated in the install process
        "session_key" => "Uyl9HJNTeFaPMnXiwPCf1gmaQ4oKY0uX", // Generated in the install process

        "email_host" => "",
        "email_name" => "",
        "email_pass" => "",
        "email_port" => "",
        "email_from" => "",

        "system_default_time_zone" => "Europe/Madrid",
        "system_debug_console" => true,
        "system_execution_time" => microtime(true),
        "system_cache_version" => 0001, // Refresh frontend cache

        "template_header" => "src/common/view/Header.php",
        "template_footer" => "src/common/view/Footer.php",

        "log_path_warning" => "system/logs/warning.log",
        "log_path_error" => "system/logs/error.log",
        "log_path_notice" => "system/logs/notice.log",
        "log_path_unknown_error" => "system/logs/unknown_error.log"
    ],

    "production" => [
        "database_host" => "localhost:3306",
        "database_user" => "root",
        "database_pass" => "",
        "database_name" => "qlsach",
        "database_auto_init" => true,

        "system_allow_forms_without_csrf" => true,
        "allow_forms_without_csrf_input" => true,

        "session_name" => "session",
        "session_encrypt_method" => "aes-256-cbc",
        "session_iv" => "OeJgOBOMWu6Gw7fW", // Generated in the install process
        "session_key" => "Uyl9HJNTeFaPMnXiwPCf1gmaQ4oKY0uX", // Generated in the install process

        "email_host" => "",
        "email_name" => "",
        "email_pass" => "",
        "email_port" => "",
        "email_from" => "",

        "system_default_time_zone" => "Europe/Madrid",
        "system_debug_console" => false,
        "system_execution_time" => microtime(true),
        "system_cache_version" => 0001, // Refresh frontend cache

        "template_header" => "src/common/view/Header.php",
        "template_footer" => "src/common/view/Footer.php",

        "log_path_warning" => "system/logs/warning.log",
        "log_path_error" => "system/logs/error.log",
        "log_path_notice" => "system/logs/notice.log",
        "log_path_unknown_error" => "system/logs/unknown_error.log"
    ]
];