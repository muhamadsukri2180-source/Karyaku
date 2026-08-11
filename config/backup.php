<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup
    |--------------------------------------------------------------------------
    */

    'backup' => [

        /*
        |--------------------------------------------------------------------------
        | Nama aplikasi
        |--------------------------------------------------------------------------
        |
        | Diubah menjadi '' agar Spatie Backup tidak mencari sub-folder 'Karyaku'
        | di Google Drive (mencegah error UnableToReadFile / File not found).
        |
        */

        'name' => '',

        /*
        |--------------------------------------------------------------------------
        | Source
        |--------------------------------------------------------------------------
        */

        'source' => [

            /*
            |--------------------------------------------------------------------------
            | Files
            |--------------------------------------------------------------------------
            */

            'files' => [

                /*
                 * Folder project yang dibackup.
                 */
                'include' => [
                    base_path(),
                ],

                /*
                 * Folder yang tidak perlu dibackup.
                 */
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    base_path('.git'),
                ],

                'follow_links' => false,

                'ignore_unreadable_directories' => false,

                'relative_path' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Database
            |--------------------------------------------------------------------------
            */

            'databases' => [
                'mysql',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Database Dump
        |--------------------------------------------------------------------------
        */

        'database_dump_compressor' => null,

        'database_dump_file_timestamp_format' => null,

        'database_dump_filename_base' => 'database',

        'database_dump_file_extension' => 'sql',

        /*
        |--------------------------------------------------------------------------
        | Destination
        |--------------------------------------------------------------------------
        */

        'destination' => [

            'compression_method' => ZipArchive::CM_DEFAULT,

            'compression_level' => 9,

            'filename_prefix' => '',

            /*
            |--------------------------------------------------------------------------
            | Backup disimpan ke:
            | 1. Google Drive
            | 2. Local
            |--------------------------------------------------------------------------
            */

            'disks' => [
                'google',
                'local',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Temporary Directory
        |--------------------------------------------------------------------------
        */

        'temporary_directory' => storage_path('app/backup-temp'),

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        'password' => env('BACKUP_ARCHIVE_PASSWORD'),

        /*
        |--------------------------------------------------------------------------
        | Encryption
        |--------------------------------------------------------------------------
        */

        'encryption' => 'default',

        /*
        |--------------------------------------------------------------------------
        | Retry
        |--------------------------------------------------------------------------
        */

        'tries' => 1,

        'retry_delay' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    'notifications' => [

        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => [],
        ],

        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitor Backups
    |--------------------------------------------------------------------------
    */

    'monitor_backups' => [

        [
            'name' => '', // Diubah juga menjadi '' agar sinkron dengan konfigurasi utama

            'disks' => [
                'google',
                'local',
            ],

            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cleanup
    |--------------------------------------------------------------------------
    */

    'cleanup' => [

        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'keep_monthly_backups_for_months' => 4,
            'keep_yearly_backups_for_years' => 2,
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],

        'tries' => 1,

        'retry_delay' => 0,

    ],

];