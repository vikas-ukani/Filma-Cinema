<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 12:50:34',
                'updated_at' => '2021-09-01 12:50:34',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'users.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 12:50:34',
                'updated_at' => '2021-09-01 12:50:34',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'users.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 12:50:34',
                'updated_at' => '2021-09-01 12:50:34',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'users.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 12:50:34',
                'updated_at' => '2021-09-01 12:50:34',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'menu.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 13:08:10',
                'updated_at' => '2021-09-01 13:08:10',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'menu.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 13:08:10',
                'updated_at' => '2021-09-01 13:08:10',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'menu.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 13:08:10',
                'updated_at' => '2021-09-01 13:08:10',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'menu.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-01 13:08:10',
                'updated_at' => '2021-09-01 13:08:10',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'movies.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:20',
                'updated_at' => '2021-09-02 12:14:20',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'movies.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:20',
                'updated_at' => '2021-09-02 12:14:20',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'movies.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:20',
                'updated_at' => '2021-09-02 12:14:20',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'movies.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:20',
                'updated_at' => '2021-09-02 12:14:20',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'tvseries.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:28',
                'updated_at' => '2021-09-02 12:14:28',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'tvseries.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:28',
                'updated_at' => '2021-09-02 12:14:28',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'tvseries.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:28',
                'updated_at' => '2021-09-02 12:14:28',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'tvseries.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:29',
                'updated_at' => '2021-09-02 12:14:29',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'livetv.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:37',
                'updated_at' => '2021-09-02 12:14:37',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'livetv.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:37',
                'updated_at' => '2021-09-02 12:14:37',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'livetv.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:37',
                'updated_at' => '2021-09-02 12:14:37',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'livetv.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:37',
                'updated_at' => '2021-09-02 12:14:37',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'liveevent.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:44',
                'updated_at' => '2021-09-02 12:14:44',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'liveevent.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:44',
                'updated_at' => '2021-09-02 12:14:44',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'liveevent.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:44',
                'updated_at' => '2021-09-02 12:14:44',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'liveevent.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:44',
                'updated_at' => '2021-09-02 12:14:44',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'audio.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:55',
                'updated_at' => '2021-09-02 12:14:55',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'audio.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:55',
                'updated_at' => '2021-09-02 12:14:55',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'audio.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:55',
                'updated_at' => '2021-09-02 12:14:55',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'audio.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:14:55',
                'updated_at' => '2021-09-02 12:14:55',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'package.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:22',
                'updated_at' => '2021-09-02 12:17:22',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'package.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:23',
                'updated_at' => '2021-09-02 12:17:23',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'package.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:23',
                'updated_at' => '2021-09-02 12:17:23',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'package.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:23',
                'updated_at' => '2021-09-02 12:17:23',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'blog.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:53',
                'updated_at' => '2021-09-02 12:17:53',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'blog.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:53',
                'updated_at' => '2021-09-02 12:17:53',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'blog.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:53',
                'updated_at' => '2021-09-02 12:17:53',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'blog.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:17:54',
                'updated_at' => '2021-09-02 12:17:54',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'coupon.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:19:58',
                'updated_at' => '2021-09-02 12:19:58',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'coupon.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:19:58',
                'updated_at' => '2021-09-02 12:19:58',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'coupon.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:19:58',
                'updated_at' => '2021-09-02 12:19:58',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'coupon.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:19:58',
                'updated_at' => '2021-09-02 12:19:58',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'addon-manager.manage',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 12:40:25',
                'updated_at' => '2021-09-02 12:40:25',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'actor.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:09',
                'updated_at' => '2021-09-02 16:44:09',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'actor.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:09',
                'updated_at' => '2021-09-02 16:44:09',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'actor.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:10',
                'updated_at' => '2021-09-02 16:44:10',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'actor.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:10',
                'updated_at' => '2021-09-02 16:44:10',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'genre.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:19',
                'updated_at' => '2021-09-02 16:44:19',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'genre.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:19',
                'updated_at' => '2021-09-02 16:44:19',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'genre.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:19',
                'updated_at' => '2021-09-02 16:44:19',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'genre.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:19',
                'updated_at' => '2021-09-02 16:44:19',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'director.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:48',
                'updated_at' => '2021-09-02 16:44:48',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'director.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:48',
                'updated_at' => '2021-09-02 16:44:48',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'director.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:49',
                'updated_at' => '2021-09-02 16:44:49',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'director.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:44:49',
                'updated_at' => '2021-09-02 16:44:49',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'label.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:45:23',
                'updated_at' => '2021-09-02 16:45:23',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'label.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:45:23',
                'updated_at' => '2021-09-02 16:45:23',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'label.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:45:23',
                'updated_at' => '2021-09-02 16:45:23',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'label.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 16:45:23',
                'updated_at' => '2021-09-02 16:45:23',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'audiolanguage.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:07:35',
                'updated_at' => '2021-09-02 17:07:35',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'audiolanguage.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:07:35',
                'updated_at' => '2021-09-02 17:07:35',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'audiolanguage.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:07:35',
                'updated_at' => '2021-09-02 17:07:35',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'audiolanguage.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:07:36',
                'updated_at' => '2021-09-02 17:07:36',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'manual-payment.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:38',
                'updated_at' => '2021-09-02 17:49:38',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'manual-payment.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:38',
                'updated_at' => '2021-09-02 17:49:38',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'manual-payment.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:38',
                'updated_at' => '2021-09-02 17:49:38',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'manual-payment.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:38',
                'updated_at' => '2021-09-02 17:49:38',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'pages.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:51',
                'updated_at' => '2021-09-02 17:49:51',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'pages.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:51',
                'updated_at' => '2021-09-02 17:49:51',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'pages.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:52',
                'updated_at' => '2021-09-02 17:49:52',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'pages.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:49:52',
                'updated_at' => '2021-09-02 17:49:52',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'faq.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:50:01',
                'updated_at' => '2021-09-02 17:50:01',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'faq.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:50:01',
                'updated_at' => '2021-09-02 17:50:01',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'faq.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:50:01',
                'updated_at' => '2021-09-02 17:50:01',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'faq.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:50:02',
                'updated_at' => '2021-09-02 17:50:02',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'site-settings.language',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:52:36',
                'updated_at' => '2021-09-02 17:52:36',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'pushnotification.settings',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:53:18',
                'updated_at' => '2021-09-02 17:53:18',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'front-settings.sliders',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:54:07',
                'updated_at' => '2021-09-02 17:54:07',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'reports.viewtraker',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:54:38',
                'updated_at' => '2021-09-02 17:54:38',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'site-settings.genral-settings',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:54:54',
                'updated_at' => '2021-09-02 17:54:54',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'site-settings.mail-settings',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:55:10',
                'updated_at' => '2021-09-02 17:55:10',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'site-settings.social-login-settings',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:55:48',
                'updated_at' => '2021-09-02 17:55:48',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'site-settings.style-settings',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:58:12',
                'updated_at' => '2021-09-02 17:58:12',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'site-settings.seo',
                'guard_name' => 'web',
                'created_at' => '2021-09-02 17:58:31',
                'updated_at' => '2021-09-02 17:58:31',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'comment-settings.comments',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:33:08',
                'updated_at' => '2021-09-04 14:33:08',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'help.db-backup',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:33:42',
                'updated_at' => '2021-09-04 14:33:42',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'producer-content.manage',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:34:07',
                'updated_at' => '2021-09-04 14:34:07',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'notification.manage',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:34:33',
                'updated_at' => '2021-09-04 14:34:33',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'front-settings.landing-page',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:35:38',
                'updated_at' => '2021-09-04 14:35:38',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'front-settings.auth-customization',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:36:46',
                'updated_at' => '2021-09-04 14:36:46',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'front-settings.short-promo',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:37:17',
                'updated_at' => '2021-09-04 14:37:17',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'site-settings.player-setting',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:43:34',
                'updated_at' => '2021-09-04 14:43:34',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'site-settings.pwa',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 14:48:59',
                'updated_at' => '2021-09-04 14:48:59',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'comment-settings.subcomments',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:32:14',
                'updated_at' => '2021-09-04 17:32:14',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'site-settings.color-option',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:35:06',
                'updated_at' => '2021-09-04 17:35:06',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'site-settings.adsense',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:35:25',
                'updated_at' => '2021-09-04 17:35:25',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'site-settings.chat-setting',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:35:41',
                'updated_at' => '2021-09-04 17:35:41',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'site-settings.api-settings',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:36:05',
                'updated_at' => '2021-09-04 17:36:05',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'site-settings.termsandcondition',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:36:44',
                'updated_at' => '2021-09-04 17:36:44',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'site-settings.privacy-policy',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:37:07',
                'updated_at' => '2021-09-04 17:37:07',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'site-settings.refund-policy',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:37:51',
                'updated_at' => '2021-09-04 17:37:51',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'site-settings.copyrights',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:38:12',
                'updated_at' => '2021-09-04 17:38:12',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'reports.user-subscription',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:38:33',
                'updated_at' => '2021-09-04 17:38:33',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'reports.device-history',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:38:47',
                'updated_at' => '2021-09-04 17:38:47',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'reports.revenue',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:39:10',
                'updated_at' => '2021-09-04 17:39:10',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'help.system-status',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:40:52',
                'updated_at' => '2021-09-04 17:40:52',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'help.remove-public',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:41:10',
                'updated_at' => '2021-09-04 17:41:10',
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'help.clear-cache',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:41:28',
                'updated_at' => '2021-09-04 17:41:28',
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'ads.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:23',
                'updated_at' => '2021-09-04 17:42:23',
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'ads.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:23',
                'updated_at' => '2021-09-04 17:42:23',
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'ads.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:24',
                'updated_at' => '2021-09-04 17:42:24',
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'ads.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:24',
                'updated_at' => '2021-09-04 17:42:24',
            ),
            110 => 
            array (
                'id' => 111,
                'name' => 'googleads.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:34',
                'updated_at' => '2021-09-04 17:42:34',
            ),
            111 => 
            array (
                'id' => 112,
                'name' => 'googleads.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:34',
                'updated_at' => '2021-09-04 17:42:34',
            ),
            112 => 
            array (
                'id' => 113,
                'name' => 'googleads.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:34',
                'updated_at' => '2021-09-04 17:42:34',
            ),
            113 => 
            array (
                'id' => 114,
                'name' => 'googleads.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:34',
                'updated_at' => '2021-09-04 17:42:34',
            ),
            114 => 
            array (
                'id' => 115,
                'name' => 'package-feature.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:48',
                'updated_at' => '2021-09-04 17:42:48',
            ),
            115 => 
            array (
                'id' => 116,
                'name' => 'package-feature.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:48',
                'updated_at' => '2021-09-04 17:42:48',
            ),
            116 => 
            array (
                'id' => 117,
                'name' => 'package-feature.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:48',
                'updated_at' => '2021-09-04 17:42:48',
            ),
            117 => 
            array (
                'id' => 118,
                'name' => 'package-feature.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-04 17:42:48',
                'updated_at' => '2021-09-04 17:42:48',
            ),
            118 => 
            array (
                'id' => 119,
                'name' => 'front-settings.social-icon',
                'guard_name' => 'web',
                'created_at' => '2021-09-06 10:44:57',
                'updated_at' => '2021-09-06 10:44:57',
            ),
            119 => 
            array (
                'id' => 120,
                'name' => 'site-settings.manualpayment',
                'guard_name' => 'web',
                'created_at' => '2021-09-06 11:01:27',
                'updated_at' => '2021-09-06 11:01:27',
            ),
            120 => 
            array (
                'id' => 121,
                'name' => 'reports.stripe-report',
                'guard_name' => 'web',
                'created_at' => '2021-09-06 12:28:20',
                'updated_at' => '2021-09-06 12:28:20',
            ),
            121 => 
            array (
                'id' => 122,
                'name' => 'help.import-demo',
                'guard_name' => 'web',
                'created_at' => '2021-09-22 11:56:05',
                'updated_at' => '2021-09-22 11:56:05',
            ),
            122 => 
            array (
                'id' => 123,
                'name' => 'site-settings.currency',
                'guard_name' => 'web',
                'created_at' => '2021-09-22 11:56:49',
                'updated_at' => '2021-09-22 11:56:49',
            ),
            123 => 
            array (
                'id' => 124,
                'name' => 'roles.view',
                'guard_name' => 'web',
                'created_at' => '2021-09-22 12:05:12',
                'updated_at' => '2021-09-22 12:05:12',
            ),
            124 => 
            array (
                'id' => 125,
                'name' => 'roles.create',
                'guard_name' => 'web',
                'created_at' => '2021-09-22 12:06:30',
                'updated_at' => '2021-09-22 12:06:30',
            ),
            125 => 
            array (
                'id' => 126,
                'name' => 'roles.edit',
                'guard_name' => 'web',
                'created_at' => '2021-09-22 12:06:30',
                'updated_at' => '2021-09-22 12:06:30',
            ),
            126 => 
            array (
                'id' => 127,
                'name' => 'roles.delete',
                'guard_name' => 'web',
                'created_at' => '2021-09-22 12:06:30',
                'updated_at' => '2021-09-22 12:06:30',
            ),
            127 => 
            array (
                'id' => 128,
                'name' => 'dashboard.states',
                'guard_name' => 'web',
                'created_at' => '2021-10-20 14:10:22',
                'updated_at' => '2021-10-20 14:10:22',
            ),
            128 => 
            array (
                'id' => 129,
                'name' => 'app-settings.setting',
                'guard_name' => 'web',
                'created_at' => '2021-10-21 12:03:43',
                'updated_at' => '2021-10-21 12:03:43',
            ),
            129 => 
            array (
                'id' => 130,
                'name' => 'app-settings.slider',
                'guard_name' => 'web',
                'created_at' => '2021-10-21 12:03:54',
                'updated_at' => '2021-10-21 12:03:54',
            ),
            130 => 
            array (
                'id' => 131,
                'name' => 'affiliate.settings',
                'guard_name' => 'web',
                'created_at' => '2021-11-23 18:51:50',
                'updated_at' => '2021-11-23 18:51:50',
            ),
            131 => 
            array (
                'id' => 132,
                'name' => 'affiliate.history',
                'guard_name' => 'web',
                'created_at' => '2021-11-23 18:51:50',
                'updated_at' => '2021-11-23 18:51:50',
            ),
            132 => 
            array (
                'id' => 133,
                'name' => 'wallet.settings',
                'guard_name' => 'web',
                'created_at' => '2021-11-23 18:51:51',
                'updated_at' => '2021-11-23 18:51:51',
            ),
            133 => 
            array (
                'id' => 134,
                'name' => 'wallet.history',
                'guard_name' => 'web',
                'created_at' => '2021-11-23 18:51:51',
                'updated_at' => '2021-11-23 18:51:51',
            ),
            134 => 
            array (
                'id' => 135,
                'name' => 'media-manager.manage',
                'guard_name' => 'web',
                'created_at' => '2022-03-09 22:50:50',
                'updated_at' => '2022-03-09 22:50:50',
            ),
            135 => 
            array (
                'id' => 136,
                'name' => 'app-settings.appUiShorting',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'name' => 'menu.Sectionshorting',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'name' => 'fake.views',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'name' => 'banneradd.view',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'name' => 'banneradd.create',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'name' => 'banneradd.edit',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'name' => 'banneradd.delete',
                'guard_name' => 'web',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}