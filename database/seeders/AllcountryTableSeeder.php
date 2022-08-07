<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllcountryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('allcountry')->delete();
        
        \DB::table('allcountry')->insert(array (
            0 => 
            array (
                'id' => 1,
                'iso' => 'AF',
                'name' => 'AFGHANISTAN',
                'nicename' => 'Afghanistan',
                'iso3' => 'AFG',
                'numcode' => 4,
                'phonecode' => 93,
            ),
            1 => 
            array (
                'id' => 2,
                'iso' => 'AL',
                'name' => 'ALBANIA',
                'nicename' => 'Albania',
                'iso3' => 'ALB',
                'numcode' => 8,
                'phonecode' => 355,
            ),
            2 => 
            array (
                'id' => 3,
                'iso' => 'DZ',
                'name' => 'ALGERIA',
                'nicename' => 'Algeria',
                'iso3' => 'DZA',
                'numcode' => 12,
                'phonecode' => 213,
            ),
            3 => 
            array (
                'id' => 4,
                'iso' => 'AS',
                'name' => 'AMERICAN SAMOA',
                'nicename' => 'American Samoa',
                'iso3' => 'ASM',
                'numcode' => 16,
                'phonecode' => 1684,
            ),
            4 => 
            array (
                'id' => 5,
                'iso' => 'AD',
                'name' => 'ANDORRA',
                'nicename' => 'Andorra',
                'iso3' => 'AND',
                'numcode' => 20,
                'phonecode' => 376,
            ),
            5 => 
            array (
                'id' => 6,
                'iso' => 'AO',
                'name' => 'ANGOLA',
                'nicename' => 'Angola',
                'iso3' => 'AGO',
                'numcode' => 24,
                'phonecode' => 244,
            ),
            6 => 
            array (
                'id' => 7,
                'iso' => 'AI',
                'name' => 'ANGUILLA',
                'nicename' => 'Anguilla',
                'iso3' => 'AIA',
                'numcode' => 660,
                'phonecode' => 1264,
            ),
            7 => 
            array (
                'id' => 8,
                'iso' => 'AQ',
                'name' => 'ANTARCTICA',
                'nicename' => 'Antarctica',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 0,
            ),
            8 => 
            array (
                'id' => 9,
                'iso' => 'AG',
                'name' => 'ANTIGUA AND BARBUDA',
                'nicename' => 'Antigua and Barbuda',
                'iso3' => 'ATG',
                'numcode' => 28,
                'phonecode' => 1268,
            ),
            9 => 
            array (
                'id' => 10,
                'iso' => 'AR',
                'name' => 'ARGENTINA',
                'nicename' => 'Argentina',
                'iso3' => 'ARG',
                'numcode' => 32,
                'phonecode' => 54,
            ),
            10 => 
            array (
                'id' => 11,
                'iso' => 'AM',
                'name' => 'ARMENIA',
                'nicename' => 'Armenia',
                'iso3' => 'ARM',
                'numcode' => 51,
                'phonecode' => 374,
            ),
            11 => 
            array (
                'id' => 12,
                'iso' => 'AW',
                'name' => 'ARUBA',
                'nicename' => 'Aruba',
                'iso3' => 'ABW',
                'numcode' => 533,
                'phonecode' => 297,
            ),
            12 => 
            array (
                'id' => 13,
                'iso' => 'AU',
                'name' => 'AUSTRALIA',
                'nicename' => 'Australia',
                'iso3' => 'AUS',
                'numcode' => 36,
                'phonecode' => 61,
            ),
            13 => 
            array (
                'id' => 14,
                'iso' => 'AT',
                'name' => 'AUSTRIA',
                'nicename' => 'Austria',
                'iso3' => 'AUT',
                'numcode' => 40,
                'phonecode' => 43,
            ),
            14 => 
            array (
                'id' => 15,
                'iso' => 'AZ',
                'name' => 'AZERBAIJAN',
                'nicename' => 'Azerbaijan',
                'iso3' => 'AZE',
                'numcode' => 31,
                'phonecode' => 994,
            ),
            15 => 
            array (
                'id' => 16,
                'iso' => 'BS',
                'name' => 'BAHAMAS',
                'nicename' => 'Bahamas',
                'iso3' => 'BHS',
                'numcode' => 44,
                'phonecode' => 1242,
            ),
            16 => 
            array (
                'id' => 17,
                'iso' => 'BH',
                'name' => 'BAHRAIN',
                'nicename' => 'Bahrain',
                'iso3' => 'BHR',
                'numcode' => 48,
                'phonecode' => 973,
            ),
            17 => 
            array (
                'id' => 18,
                'iso' => 'BD',
                'name' => 'BANGLADESH',
                'nicename' => 'Bangladesh',
                'iso3' => 'BGD',
                'numcode' => 50,
                'phonecode' => 880,
            ),
            18 => 
            array (
                'id' => 19,
                'iso' => 'BB',
                'name' => 'BARBADOS',
                'nicename' => 'Barbados',
                'iso3' => 'BRB',
                'numcode' => 52,
                'phonecode' => 1246,
            ),
            19 => 
            array (
                'id' => 20,
                'iso' => 'BY',
                'name' => 'BELARUS',
                'nicename' => 'Belarus',
                'iso3' => 'BLR',
                'numcode' => 112,
                'phonecode' => 375,
            ),
            20 => 
            array (
                'id' => 21,
                'iso' => 'BE',
                'name' => 'BELGIUM',
                'nicename' => 'Belgium',
                'iso3' => 'BEL',
                'numcode' => 56,
                'phonecode' => 32,
            ),
            21 => 
            array (
                'id' => 22,
                'iso' => 'BZ',
                'name' => 'BELIZE',
                'nicename' => 'Belize',
                'iso3' => 'BLZ',
                'numcode' => 84,
                'phonecode' => 501,
            ),
            22 => 
            array (
                'id' => 23,
                'iso' => 'BJ',
                'name' => 'BENIN',
                'nicename' => 'Benin',
                'iso3' => 'BEN',
                'numcode' => 204,
                'phonecode' => 229,
            ),
            23 => 
            array (
                'id' => 24,
                'iso' => 'BM',
                'name' => 'BERMUDA',
                'nicename' => 'Bermuda',
                'iso3' => 'BMU',
                'numcode' => 60,
                'phonecode' => 1441,
            ),
            24 => 
            array (
                'id' => 25,
                'iso' => 'BT',
                'name' => 'BHUTAN',
                'nicename' => 'Bhutan',
                'iso3' => 'BTN',
                'numcode' => 64,
                'phonecode' => 975,
            ),
            25 => 
            array (
                'id' => 26,
                'iso' => 'BO',
                'name' => 'BOLIVIA',
                'nicename' => 'Bolivia',
                'iso3' => 'BOL',
                'numcode' => 68,
                'phonecode' => 591,
            ),
            26 => 
            array (
                'id' => 27,
                'iso' => 'BA',
                'name' => 'BOSNIA AND HERZEGOVINA',
                'nicename' => 'Bosnia and Herzegovina',
                'iso3' => 'BIH',
                'numcode' => 70,
                'phonecode' => 387,
            ),
            27 => 
            array (
                'id' => 28,
                'iso' => 'BW',
                'name' => 'BOTSWANA',
                'nicename' => 'Botswana',
                'iso3' => 'BWA',
                'numcode' => 72,
                'phonecode' => 267,
            ),
            28 => 
            array (
                'id' => 29,
                'iso' => 'BV',
                'name' => 'BOUVET ISLAND',
                'nicename' => 'Bouvet Island',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 0,
            ),
            29 => 
            array (
                'id' => 30,
                'iso' => 'BR',
                'name' => 'BRAZIL',
                'nicename' => 'Brazil',
                'iso3' => 'BRA',
                'numcode' => 76,
                'phonecode' => 55,
            ),
            30 => 
            array (
                'id' => 31,
                'iso' => 'IO',
                'name' => 'BRITISH INDIAN OCEAN TERRITORY',
                'nicename' => 'British Indian Ocean Territory',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 246,
            ),
            31 => 
            array (
                'id' => 32,
                'iso' => 'BN',
                'name' => 'BRUNEI DARUSSALAM',
                'nicename' => 'Brunei Darussalam',
                'iso3' => 'BRN',
                'numcode' => 96,
                'phonecode' => 673,
            ),
            32 => 
            array (
                'id' => 33,
                'iso' => 'BG',
                'name' => 'BULGARIA',
                'nicename' => 'Bulgaria',
                'iso3' => 'BGR',
                'numcode' => 100,
                'phonecode' => 359,
            ),
            33 => 
            array (
                'id' => 34,
                'iso' => 'BF',
                'name' => 'BURKINA FASO',
                'nicename' => 'Burkina Faso',
                'iso3' => 'BFA',
                'numcode' => 854,
                'phonecode' => 226,
            ),
            34 => 
            array (
                'id' => 35,
                'iso' => 'BI',
                'name' => 'BURUNDI',
                'nicename' => 'Burundi',
                'iso3' => 'BDI',
                'numcode' => 108,
                'phonecode' => 257,
            ),
            35 => 
            array (
                'id' => 36,
                'iso' => 'KH',
                'name' => 'CAMBODIA',
                'nicename' => 'Cambodia',
                'iso3' => 'KHM',
                'numcode' => 116,
                'phonecode' => 855,
            ),
            36 => 
            array (
                'id' => 37,
                'iso' => 'CM',
                'name' => 'CAMEROON',
                'nicename' => 'Cameroon',
                'iso3' => 'CMR',
                'numcode' => 120,
                'phonecode' => 237,
            ),
            37 => 
            array (
                'id' => 38,
                'iso' => 'CA',
                'name' => 'CANADA',
                'nicename' => 'Canada',
                'iso3' => 'CAN',
                'numcode' => 124,
                'phonecode' => 1,
            ),
            38 => 
            array (
                'id' => 39,
                'iso' => 'CV',
                'name' => 'CAPE VERDE',
                'nicename' => 'Cape Verde',
                'iso3' => 'CPV',
                'numcode' => 132,
                'phonecode' => 238,
            ),
            39 => 
            array (
                'id' => 40,
                'iso' => 'KY',
                'name' => 'CAYMAN ISLANDS',
                'nicename' => 'Cayman Islands',
                'iso3' => 'CYM',
                'numcode' => 136,
                'phonecode' => 1345,
            ),
            40 => 
            array (
                'id' => 41,
                'iso' => 'CF',
                'name' => 'CENTRAL AFRICAN REPUBLIC',
                'nicename' => 'Central African Republic',
                'iso3' => 'CAF',
                'numcode' => 140,
                'phonecode' => 236,
            ),
            41 => 
            array (
                'id' => 42,
                'iso' => 'TD',
                'name' => 'CHAD',
                'nicename' => 'Chad',
                'iso3' => 'TCD',
                'numcode' => 148,
                'phonecode' => 235,
            ),
            42 => 
            array (
                'id' => 43,
                'iso' => 'CL',
                'name' => 'CHILE',
                'nicename' => 'Chile',
                'iso3' => 'CHL',
                'numcode' => 152,
                'phonecode' => 56,
            ),
            43 => 
            array (
                'id' => 44,
                'iso' => 'CN',
                'name' => 'CHINA',
                'nicename' => 'China',
                'iso3' => 'CHN',
                'numcode' => 156,
                'phonecode' => 86,
            ),
            44 => 
            array (
                'id' => 45,
                'iso' => 'CX',
                'name' => 'CHRISTMAS ISLAND',
                'nicename' => 'Christmas Island',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 61,
            ),
            45 => 
            array (
                'id' => 46,
                'iso' => 'CC',
            'name' => 'COCOS (KEELING) ISLANDS',
            'nicename' => 'Cocos (Keeling) Islands',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 672,
            ),
            46 => 
            array (
                'id' => 47,
                'iso' => 'CO',
                'name' => 'COLOMBIA',
                'nicename' => 'Colombia',
                'iso3' => 'COL',
                'numcode' => 170,
                'phonecode' => 57,
            ),
            47 => 
            array (
                'id' => 48,
                'iso' => 'KM',
                'name' => 'COMOROS',
                'nicename' => 'Comoros',
                'iso3' => 'COM',
                'numcode' => 174,
                'phonecode' => 269,
            ),
            48 => 
            array (
                'id' => 49,
                'iso' => 'CG',
                'name' => 'CONGO',
                'nicename' => 'Congo',
                'iso3' => 'COG',
                'numcode' => 178,
                'phonecode' => 242,
            ),
            49 => 
            array (
                'id' => 50,
                'iso' => 'CD',
                'name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
                'nicename' => 'Congo, the Democratic Republic of the',
                'iso3' => 'COD',
                'numcode' => 180,
                'phonecode' => 242,
            ),
            50 => 
            array (
                'id' => 51,
                'iso' => 'CK',
                'name' => 'COOK ISLANDS',
                'nicename' => 'Cook Islands',
                'iso3' => 'COK',
                'numcode' => 184,
                'phonecode' => 682,
            ),
            51 => 
            array (
                'id' => 52,
                'iso' => 'CR',
                'name' => 'COSTA RICA',
                'nicename' => 'Costa Rica',
                'iso3' => 'CRI',
                'numcode' => 188,
                'phonecode' => 506,
            ),
            52 => 
            array (
                'id' => 53,
                'iso' => 'CI',
                'name' => 'COTE D\'IVOIRE',
                'nicename' => 'Cote D\'Ivoire',
                'iso3' => 'CIV',
                'numcode' => 384,
                'phonecode' => 225,
            ),
            53 => 
            array (
                'id' => 54,
                'iso' => 'HR',
                'name' => 'CROATIA',
                'nicename' => 'Croatia',
                'iso3' => 'HRV',
                'numcode' => 191,
                'phonecode' => 385,
            ),
            54 => 
            array (
                'id' => 55,
                'iso' => 'CU',
                'name' => 'CUBA',
                'nicename' => 'Cuba',
                'iso3' => 'CUB',
                'numcode' => 192,
                'phonecode' => 53,
            ),
            55 => 
            array (
                'id' => 56,
                'iso' => 'CY',
                'name' => 'CYPRUS',
                'nicename' => 'Cyprus',
                'iso3' => 'CYP',
                'numcode' => 196,
                'phonecode' => 357,
            ),
            56 => 
            array (
                'id' => 57,
                'iso' => 'CZ',
                'name' => 'CZECH REPUBLIC',
                'nicename' => 'Czech Republic',
                'iso3' => 'CZE',
                'numcode' => 203,
                'phonecode' => 420,
            ),
            57 => 
            array (
                'id' => 58,
                'iso' => 'DK',
                'name' => 'DENMARK',
                'nicename' => 'Denmark',
                'iso3' => 'DNK',
                'numcode' => 208,
                'phonecode' => 45,
            ),
            58 => 
            array (
                'id' => 59,
                'iso' => 'DJ',
                'name' => 'DJIBOUTI',
                'nicename' => 'Djibouti',
                'iso3' => 'DJI',
                'numcode' => 262,
                'phonecode' => 253,
            ),
            59 => 
            array (
                'id' => 60,
                'iso' => 'DM',
                'name' => 'DOMINICA',
                'nicename' => 'Dominica',
                'iso3' => 'DMA',
                'numcode' => 212,
                'phonecode' => 1767,
            ),
            60 => 
            array (
                'id' => 61,
                'iso' => 'DO',
                'name' => 'DOMINICAN REPUBLIC',
                'nicename' => 'Dominican Republic',
                'iso3' => 'DOM',
                'numcode' => 214,
                'phonecode' => 1809,
            ),
            61 => 
            array (
                'id' => 62,
                'iso' => 'TP',
                'name' => 'EAST TIMOR',
                'nicename' => 'East Timor',
                'iso3' => 'TMP',
                'numcode' => NULL,
                'phonecode' => 626,
            ),
            62 => 
            array (
                'id' => 63,
                'iso' => 'EC',
                'name' => 'ECUADOR',
                'nicename' => 'Ecuador',
                'iso3' => 'ECU',
                'numcode' => 218,
                'phonecode' => 593,
            ),
            63 => 
            array (
                'id' => 64,
                'iso' => 'EG',
                'name' => 'EGYPT',
                'nicename' => 'Egypt',
                'iso3' => 'EGY',
                'numcode' => 818,
                'phonecode' => 20,
            ),
            64 => 
            array (
                'id' => 65,
                'iso' => 'SV',
                'name' => 'EL SALVADOR',
                'nicename' => 'El Salvador',
                'iso3' => 'SLV',
                'numcode' => 222,
                'phonecode' => 503,
            ),
            65 => 
            array (
                'id' => 66,
                'iso' => 'GQ',
                'name' => 'EQUATORIAL GUINEA',
                'nicename' => 'Equatorial Guinea',
                'iso3' => 'GNQ',
                'numcode' => 226,
                'phonecode' => 240,
            ),
            66 => 
            array (
                'id' => 67,
                'iso' => 'ER',
                'name' => 'ERITREA',
                'nicename' => 'Eritrea',
                'iso3' => 'ERI',
                'numcode' => 232,
                'phonecode' => 291,
            ),
            67 => 
            array (
                'id' => 68,
                'iso' => 'EE',
                'name' => 'ESTONIA',
                'nicename' => 'Estonia',
                'iso3' => 'EST',
                'numcode' => 233,
                'phonecode' => 372,
            ),
            68 => 
            array (
                'id' => 69,
                'iso' => 'ET',
                'name' => 'ETHIOPIA',
                'nicename' => 'Ethiopia',
                'iso3' => 'ETH',
                'numcode' => 231,
                'phonecode' => 251,
            ),
            69 => 
            array (
                'id' => 70,
                'iso' => 'XA',
                'name' => 'EXTERNAL TERRITORIES OF AUSTRALIA',
                'nicename' => 'External Territories of Australia',
                'iso3' => 'CXR',
                'numcode' => 162,
                'phonecode' => 61,
            ),
            70 => 
            array (
                'id' => 71,
                'iso' => 'FK',
            'name' => 'FALKLAND ISLANDS (MALVINAS)',
            'nicename' => 'Falkland Islands (Malvinas)',
                'iso3' => 'FLK',
                'numcode' => 238,
                'phonecode' => 500,
            ),
            71 => 
            array (
                'id' => 72,
                'iso' => 'FO',
                'name' => 'FAROE ISLANDS',
                'nicename' => 'Faroe Islands',
                'iso3' => 'FRO',
                'numcode' => 234,
                'phonecode' => 298,
            ),
            72 => 
            array (
                'id' => 73,
                'iso' => 'FJ',
                'name' => 'FIJI',
                'nicename' => 'Fiji',
                'iso3' => 'FJI',
                'numcode' => 242,
                'phonecode' => 679,
            ),
            73 => 
            array (
                'id' => 74,
                'iso' => 'FI',
                'name' => 'FINLAND',
                'nicename' => 'Finland',
                'iso3' => 'FIN',
                'numcode' => 246,
                'phonecode' => 358,
            ),
            74 => 
            array (
                'id' => 75,
                'iso' => 'FR',
                'name' => 'FRANCE',
                'nicename' => 'France',
                'iso3' => 'FRA',
                'numcode' => 250,
                'phonecode' => 33,
            ),
            75 => 
            array (
                'id' => 76,
                'iso' => 'GF',
                'name' => 'FRENCH GUIANA',
                'nicename' => 'French Guiana',
                'iso3' => 'GUF',
                'numcode' => 254,
                'phonecode' => 594,
            ),
            76 => 
            array (
                'id' => 77,
                'iso' => 'PF',
                'name' => 'FRENCH POLYNESIA',
                'nicename' => 'French Polynesia',
                'iso3' => 'PYF',
                'numcode' => 258,
                'phonecode' => 689,
            ),
            77 => 
            array (
                'id' => 78,
                'iso' => 'TF',
                'name' => 'FRENCH SOUTHERN TERRITORIES',
                'nicename' => 'French Southern Territories',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 0,
            ),
            78 => 
            array (
                'id' => 79,
                'iso' => 'GA',
                'name' => 'GABON',
                'nicename' => 'Gabon',
                'iso3' => 'GAB',
                'numcode' => 266,
                'phonecode' => 241,
            ),
            79 => 
            array (
                'id' => 80,
                'iso' => 'GM',
                'name' => 'GAMBIA',
                'nicename' => 'Gambia',
                'iso3' => 'GMB',
                'numcode' => 270,
                'phonecode' => 220,
            ),
            80 => 
            array (
                'id' => 81,
                'iso' => 'GE',
                'name' => 'GEORGIA',
                'nicename' => 'Georgia',
                'iso3' => 'GEO',
                'numcode' => 268,
                'phonecode' => 995,
            ),
            81 => 
            array (
                'id' => 82,
                'iso' => 'DE',
                'name' => 'GERMANY',
                'nicename' => 'Germany',
                'iso3' => 'DEU',
                'numcode' => 276,
                'phonecode' => 49,
            ),
            82 => 
            array (
                'id' => 83,
                'iso' => 'GH',
                'name' => 'GHANA',
                'nicename' => 'Ghana',
                'iso3' => 'GHA',
                'numcode' => 288,
                'phonecode' => 233,
            ),
            83 => 
            array (
                'id' => 84,
                'iso' => 'GI',
                'name' => 'GIBRALTAR',
                'nicename' => 'Gibraltar',
                'iso3' => 'GIB',
                'numcode' => 292,
                'phonecode' => 350,
            ),
            84 => 
            array (
                'id' => 85,
                'iso' => 'GR',
                'name' => 'GREECE',
                'nicename' => 'Greece',
                'iso3' => 'GRC',
                'numcode' => 300,
                'phonecode' => 30,
            ),
            85 => 
            array (
                'id' => 86,
                'iso' => 'GL',
                'name' => 'GREENLAND',
                'nicename' => 'Greenland',
                'iso3' => 'GRL',
                'numcode' => 304,
                'phonecode' => 299,
            ),
            86 => 
            array (
                'id' => 87,
                'iso' => 'GD',
                'name' => 'GRENADA',
                'nicename' => 'Grenada',
                'iso3' => 'GRD',
                'numcode' => 308,
                'phonecode' => 1473,
            ),
            87 => 
            array (
                'id' => 88,
                'iso' => 'GP',
                'name' => 'GUADELOUPE',
                'nicename' => 'Guadeloupe',
                'iso3' => 'GLP',
                'numcode' => 312,
                'phonecode' => 590,
            ),
            88 => 
            array (
                'id' => 89,
                'iso' => 'GU',
                'name' => 'GUAM',
                'nicename' => 'Guam',
                'iso3' => 'GUM',
                'numcode' => 316,
                'phonecode' => 1671,
            ),
            89 => 
            array (
                'id' => 90,
                'iso' => 'GT',
                'name' => 'GUATEMALA',
                'nicename' => 'Guatemala',
                'iso3' => 'GTM',
                'numcode' => 320,
                'phonecode' => 502,
            ),
            90 => 
            array (
                'id' => 91,
                'iso' => 'GG',
                'name' => 'GUERNSEY AND ALDERNEY',
                'nicename' => 'Guernsey and Alderney',
                'iso3' => 'GGY',
                'numcode' => 1481,
                'phonecode' => 44,
            ),
            91 => 
            array (
                'id' => 92,
                'iso' => 'GN',
                'name' => 'GUINEA',
                'nicename' => 'Guinea',
                'iso3' => 'GIN',
                'numcode' => 324,
                'phonecode' => 224,
            ),
            92 => 
            array (
                'id' => 93,
                'iso' => 'GW',
                'name' => 'GUINEA-BISSAU',
                'nicename' => 'Guinea-Bissau',
                'iso3' => 'GNB',
                'numcode' => 624,
                'phonecode' => 245,
            ),
            93 => 
            array (
                'id' => 94,
                'iso' => 'GY',
                'name' => 'GUYANA',
                'nicename' => 'Guyana',
                'iso3' => 'GUY',
                'numcode' => 328,
                'phonecode' => 592,
            ),
            94 => 
            array (
                'id' => 95,
                'iso' => 'HT',
                'name' => 'HAITI',
                'nicename' => 'Haiti',
                'iso3' => 'HTI',
                'numcode' => 332,
                'phonecode' => 509,
            ),
            95 => 
            array (
                'id' => 96,
                'iso' => 'HM',
                'name' => 'HEARD ISLAND AND MCDONALD ISLANDS',
                'nicename' => 'Heard Island and Mcdonald Islands',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 0,
            ),
            96 => 
            array (
                'id' => 97,
                'iso' => 'HN',
                'name' => 'HONDURAS',
                'nicename' => 'Honduras',
                'iso3' => 'HND',
                'numcode' => 340,
                'phonecode' => 504,
            ),
            97 => 
            array (
                'id' => 98,
                'iso' => 'HK',
                'name' => 'HONG KONG',
                'nicename' => 'Hong Kong',
                'iso3' => 'HKG',
                'numcode' => 344,
                'phonecode' => 852,
            ),
            98 => 
            array (
                'id' => 99,
                'iso' => 'HU',
                'name' => 'HUNGARY',
                'nicename' => 'Hungary',
                'iso3' => 'HUN',
                'numcode' => 348,
                'phonecode' => 36,
            ),
            99 => 
            array (
                'id' => 100,
                'iso' => 'IS',
                'name' => 'ICELAND',
                'nicename' => 'Iceland',
                'iso3' => 'ISL',
                'numcode' => 352,
                'phonecode' => 354,
            ),
            100 => 
            array (
                'id' => 101,
                'iso' => 'IN',
                'name' => 'INDIA',
                'nicename' => 'India',
                'iso3' => 'IND',
                'numcode' => 356,
                'phonecode' => 91,
            ),
            101 => 
            array (
                'id' => 102,
                'iso' => 'ID',
                'name' => 'INDONESIA',
                'nicename' => 'Indonesia',
                'iso3' => 'IDN',
                'numcode' => 360,
                'phonecode' => 62,
            ),
            102 => 
            array (
                'id' => 103,
                'iso' => 'IR',
                'name' => 'IRAN, ISLAMIC REPUBLIC OF',
                'nicename' => 'Iran, Islamic Republic of',
                'iso3' => 'IRN',
                'numcode' => 364,
                'phonecode' => 98,
            ),
            103 => 
            array (
                'id' => 104,
                'iso' => 'IQ',
                'name' => 'IRAQ',
                'nicename' => 'Iraq',
                'iso3' => 'IRQ',
                'numcode' => 368,
                'phonecode' => 964,
            ),
            104 => 
            array (
                'id' => 105,
                'iso' => 'IE',
                'name' => 'IRELAND',
                'nicename' => 'Ireland',
                'iso3' => 'IRL',
                'numcode' => 372,
                'phonecode' => 353,
            ),
            105 => 
            array (
                'id' => 106,
                'iso' => 'IL',
                'name' => 'ISRAEL',
                'nicename' => 'Israel',
                'iso3' => 'ISR',
                'numcode' => 376,
                'phonecode' => 972,
            ),
            106 => 
            array (
                'id' => 107,
                'iso' => 'IT',
                'name' => 'ITALY',
                'nicename' => 'Italy',
                'iso3' => 'ITA',
                'numcode' => 380,
                'phonecode' => 39,
            ),
            107 => 
            array (
                'id' => 108,
                'iso' => 'JM',
                'name' => 'JAMAICA',
                'nicename' => 'Jamaica',
                'iso3' => 'JAM',
                'numcode' => 388,
                'phonecode' => 1876,
            ),
            108 => 
            array (
                'id' => 109,
                'iso' => 'JP',
                'name' => 'JAPAN',
                'nicename' => 'Japan',
                'iso3' => 'JPN',
                'numcode' => 392,
                'phonecode' => 81,
            ),
            109 => 
            array (
                'id' => 110,
                'iso' => 'JE',
                'name' => 'JERSEY',
                'nicename' => 'Jersey',
                'iso3' => 'JEY',
                'numcode' => 1534,
                'phonecode' => 44,
            ),
            110 => 
            array (
                'id' => 111,
                'iso' => 'JO',
                'name' => 'JORDAN',
                'nicename' => 'Jordan',
                'iso3' => 'JOR',
                'numcode' => 400,
                'phonecode' => 962,
            ),
            111 => 
            array (
                'id' => 112,
                'iso' => 'KZ',
                'name' => 'KAZAKHSTAN',
                'nicename' => 'Kazakhstan',
                'iso3' => 'KAZ',
                'numcode' => 398,
                'phonecode' => 7,
            ),
            112 => 
            array (
                'id' => 113,
                'iso' => 'KE',
                'name' => 'KENYA',
                'nicename' => 'Kenya',
                'iso3' => 'KEN',
                'numcode' => 404,
                'phonecode' => 254,
            ),
            113 => 
            array (
                'id' => 114,
                'iso' => 'KI',
                'name' => 'KIRIBATI',
                'nicename' => 'Kiribati',
                'iso3' => 'KIR',
                'numcode' => 296,
                'phonecode' => 686,
            ),
            114 => 
            array (
                'id' => 115,
                'iso' => 'KP',
                'name' => 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF',
                'nicename' => 'Korea, Democratic People\'s Republic of',
                'iso3' => 'PRK',
                'numcode' => 408,
                'phonecode' => 850,
            ),
            115 => 
            array (
                'id' => 116,
                'iso' => 'KR',
                'name' => 'KOREA, REPUBLIC OF',
                'nicename' => 'Korea, Republic of',
                'iso3' => 'KOR',
                'numcode' => 410,
                'phonecode' => 82,
            ),
            116 => 
            array (
                'id' => 117,
                'iso' => 'KW',
                'name' => 'KUWAIT',
                'nicename' => 'Kuwait',
                'iso3' => 'KWT',
                'numcode' => 414,
                'phonecode' => 965,
            ),
            117 => 
            array (
                'id' => 118,
                'iso' => 'KG',
                'name' => 'KYRGYZSTAN',
                'nicename' => 'Kyrgyzstan',
                'iso3' => 'KGZ',
                'numcode' => 417,
                'phonecode' => 996,
            ),
            118 => 
            array (
                'id' => 119,
                'iso' => 'LA',
                'name' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
                'nicename' => 'Lao People\'s Democratic Republic',
                'iso3' => 'LAO',
                'numcode' => 418,
                'phonecode' => 856,
            ),
            119 => 
            array (
                'id' => 120,
                'iso' => 'LV',
                'name' => 'LATVIA',
                'nicename' => 'Latvia',
                'iso3' => 'LVA',
                'numcode' => 428,
                'phonecode' => 371,
            ),
            120 => 
            array (
                'id' => 121,
                'iso' => 'LB',
                'name' => 'LEBANON',
                'nicename' => 'Lebanon',
                'iso3' => 'LBN',
                'numcode' => 422,
                'phonecode' => 961,
            ),
            121 => 
            array (
                'id' => 122,
                'iso' => 'LS',
                'name' => 'LESOTHO',
                'nicename' => 'Lesotho',
                'iso3' => 'LSO',
                'numcode' => 426,
                'phonecode' => 266,
            ),
            122 => 
            array (
                'id' => 123,
                'iso' => 'LR',
                'name' => 'LIBERIA',
                'nicename' => 'Liberia',
                'iso3' => 'LBR',
                'numcode' => 430,
                'phonecode' => 231,
            ),
            123 => 
            array (
                'id' => 124,
                'iso' => 'LY',
                'name' => 'LIBYAN ARAB JAMAHIRIYA',
                'nicename' => 'Libyan Arab Jamahiriya',
                'iso3' => 'LBY',
                'numcode' => 434,
                'phonecode' => 218,
            ),
            124 => 
            array (
                'id' => 125,
                'iso' => 'LI',
                'name' => 'LIECHTENSTEIN',
                'nicename' => 'Liechtenstein',
                'iso3' => 'LIE',
                'numcode' => 438,
                'phonecode' => 423,
            ),
            125 => 
            array (
                'id' => 126,
                'iso' => 'LT',
                'name' => 'LITHUANIA',
                'nicename' => 'Lithuania',
                'iso3' => 'LTU',
                'numcode' => 440,
                'phonecode' => 370,
            ),
            126 => 
            array (
                'id' => 127,
                'iso' => 'LU',
                'name' => 'LUXEMBOURG',
                'nicename' => 'Luxembourg',
                'iso3' => 'LUX',
                'numcode' => 442,
                'phonecode' => 352,
            ),
            127 => 
            array (
                'id' => 128,
                'iso' => 'MO',
                'name' => 'MACAO',
                'nicename' => 'Macao',
                'iso3' => 'MAC',
                'numcode' => 446,
                'phonecode' => 853,
            ),
            128 => 
            array (
                'id' => 129,
                'iso' => 'MK',
                'name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
                'nicename' => 'Macedonia, the Former Yugoslav Republic of',
                'iso3' => 'MKD',
                'numcode' => 807,
                'phonecode' => 389,
            ),
            129 => 
            array (
                'id' => 130,
                'iso' => 'MG',
                'name' => 'MADAGASCAR',
                'nicename' => 'Madagascar',
                'iso3' => 'MDG',
                'numcode' => 450,
                'phonecode' => 261,
            ),
            130 => 
            array (
                'id' => 131,
                'iso' => 'MW',
                'name' => 'MALAWI',
                'nicename' => 'Malawi',
                'iso3' => 'MWI',
                'numcode' => 454,
                'phonecode' => 265,
            ),
            131 => 
            array (
                'id' => 132,
                'iso' => 'MY',
                'name' => 'MALAYSIA',
                'nicename' => 'Malaysia',
                'iso3' => 'MYS',
                'numcode' => 458,
                'phonecode' => 60,
            ),
            132 => 
            array (
                'id' => 133,
                'iso' => 'MV',
                'name' => 'MALDIVES',
                'nicename' => 'Maldives',
                'iso3' => 'MDV',
                'numcode' => 462,
                'phonecode' => 960,
            ),
            133 => 
            array (
                'id' => 134,
                'iso' => 'ML',
                'name' => 'MALI',
                'nicename' => 'Mali',
                'iso3' => 'MLI',
                'numcode' => 466,
                'phonecode' => 223,
            ),
            134 => 
            array (
                'id' => 135,
                'iso' => 'MT',
                'name' => 'MALTA',
                'nicename' => 'Malta',
                'iso3' => 'MLT',
                'numcode' => 470,
                'phonecode' => 356,
            ),
            135 => 
            array (
                'id' => 136,
                'iso' => 'IM',
            'name' => 'ISLE OF MAN (Isle of)',
                'nicename' => 'Isle of Man',
                'iso3' => 'IMN',
                'numcode' => NULL,
                'phonecode' => 1624,
            ),
            136 => 
            array (
                'id' => 137,
                'iso' => 'MH',
                'name' => 'MARSHALL ISLANDS',
                'nicename' => 'Marshall Islands',
                'iso3' => 'MHL',
                'numcode' => 584,
                'phonecode' => 692,
            ),
            137 => 
            array (
                'id' => 138,
                'iso' => 'MQ',
                'name' => 'MARTINIQUE',
                'nicename' => 'Martinique',
                'iso3' => 'MTQ',
                'numcode' => 474,
                'phonecode' => 596,
            ),
            138 => 
            array (
                'id' => 139,
                'iso' => 'MR',
                'name' => 'MAURITANIA',
                'nicename' => 'Mauritania',
                'iso3' => 'MRT',
                'numcode' => 478,
                'phonecode' => 222,
            ),
            139 => 
            array (
                'id' => 140,
                'iso' => 'MU',
                'name' => 'MAURITIUS',
                'nicename' => 'Mauritius',
                'iso3' => 'MUS',
                'numcode' => 480,
                'phonecode' => 230,
            ),
            140 => 
            array (
                'id' => 141,
                'iso' => 'YT',
                'name' => 'MAYOTTE',
                'nicename' => 'Mayotte',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 269,
            ),
            141 => 
            array (
                'id' => 142,
                'iso' => 'MX',
                'name' => 'MEXICO',
                'nicename' => 'Mexico',
                'iso3' => 'MEX',
                'numcode' => 484,
                'phonecode' => 52,
            ),
            142 => 
            array (
                'id' => 143,
                'iso' => 'FM',
                'name' => 'MICRONESIA, FEDERATED STATES OF',
                'nicename' => 'Micronesia, Federated States of',
                'iso3' => 'FSM',
                'numcode' => 583,
                'phonecode' => 691,
            ),
            143 => 
            array (
                'id' => 144,
                'iso' => 'MD',
                'name' => 'MOLDOVA, REPUBLIC OF',
                'nicename' => 'Moldova, Republic of',
                'iso3' => 'MDA',
                'numcode' => 498,
                'phonecode' => 373,
            ),
            144 => 
            array (
                'id' => 145,
                'iso' => 'MC',
                'name' => 'MONACO',
                'nicename' => 'Monaco',
                'iso3' => 'MCO',
                'numcode' => 492,
                'phonecode' => 377,
            ),
            145 => 
            array (
                'id' => 146,
                'iso' => 'MN',
                'name' => 'MONGOLIA',
                'nicename' => 'Mongolia',
                'iso3' => 'MNG',
                'numcode' => 496,
                'phonecode' => 976,
            ),
            146 => 
            array (
                'id' => 147,
                'iso' => 'MS',
                'name' => 'MONTSERRAT',
                'nicename' => 'Montserrat',
                'iso3' => 'MSR',
                'numcode' => 500,
                'phonecode' => 1664,
            ),
            147 => 
            array (
                'id' => 148,
                'iso' => 'MA',
                'name' => 'MOROCCO',
                'nicename' => 'Morocco',
                'iso3' => 'MAR',
                'numcode' => 504,
                'phonecode' => 212,
            ),
            148 => 
            array (
                'id' => 149,
                'iso' => 'MZ',
                'name' => 'MOZAMBIQUE',
                'nicename' => 'Mozambique',
                'iso3' => 'MOZ',
                'numcode' => 508,
                'phonecode' => 258,
            ),
            149 => 
            array (
                'id' => 150,
                'iso' => 'MM',
                'name' => 'MYANMAR',
                'nicename' => 'Myanmar',
                'iso3' => 'MMR',
                'numcode' => 104,
                'phonecode' => 95,
            ),
            150 => 
            array (
                'id' => 151,
                'iso' => 'NA',
                'name' => 'NAMIBIA',
                'nicename' => 'Namibia',
                'iso3' => 'NAM',
                'numcode' => 516,
                'phonecode' => 264,
            ),
            151 => 
            array (
                'id' => 152,
                'iso' => 'NR',
                'name' => 'NAURU',
                'nicename' => 'Nauru',
                'iso3' => 'NRU',
                'numcode' => 520,
                'phonecode' => 674,
            ),
            152 => 
            array (
                'id' => 153,
                'iso' => 'NP',
                'name' => 'NEPAL',
                'nicename' => 'Nepal',
                'iso3' => 'NPL',
                'numcode' => 524,
                'phonecode' => 977,
            ),
            153 => 
            array (
                'id' => 154,
                'iso' => 'AN',
                'name' => 'NETHERLANDS ANTILLES',
                'nicename' => 'Netherlands Antilles',
                'iso3' => 'ANT',
                'numcode' => 530,
                'phonecode' => 599,
            ),
            154 => 
            array (
                'id' => 155,
                'iso' => 'NL',
                'name' => 'NETHERLANDS',
                'nicename' => 'Netherlands',
                'iso3' => 'NLD',
                'numcode' => 528,
                'phonecode' => 31,
            ),
            155 => 
            array (
                'id' => 156,
                'iso' => 'NC',
                'name' => 'NEW CALEDONIA',
                'nicename' => 'New Caledonia',
                'iso3' => 'NCL',
                'numcode' => 540,
                'phonecode' => 687,
            ),
            156 => 
            array (
                'id' => 157,
                'iso' => 'NZ',
                'name' => 'NEW ZEALAND',
                'nicename' => 'New Zealand',
                'iso3' => 'NZL',
                'numcode' => 554,
                'phonecode' => 64,
            ),
            157 => 
            array (
                'id' => 158,
                'iso' => 'NI',
                'name' => 'NICARAGUA',
                'nicename' => 'Nicaragua',
                'iso3' => 'NIC',
                'numcode' => 558,
                'phonecode' => 505,
            ),
            158 => 
            array (
                'id' => 159,
                'iso' => 'NE',
                'name' => 'NIGER',
                'nicename' => 'Niger',
                'iso3' => 'NER',
                'numcode' => 562,
                'phonecode' => 227,
            ),
            159 => 
            array (
                'id' => 160,
                'iso' => 'NG',
                'name' => 'NIGERIA',
                'nicename' => 'Nigeria',
                'iso3' => 'NGA',
                'numcode' => 566,
                'phonecode' => 234,
            ),
            160 => 
            array (
                'id' => 161,
                'iso' => 'NU',
                'name' => 'NIUE',
                'nicename' => 'Niue',
                'iso3' => 'NIU',
                'numcode' => 570,
                'phonecode' => 683,
            ),
            161 => 
            array (
                'id' => 162,
                'iso' => 'NF',
                'name' => 'NORFOLK ISLAND',
                'nicename' => 'Norfolk Island',
                'iso3' => 'NFK',
                'numcode' => 574,
                'phonecode' => 672,
            ),
            162 => 
            array (
                'id' => 163,
                'iso' => 'MP',
                'name' => 'NORTHERN MARIANA ISLANDS',
                'nicename' => 'Northern Mariana Islands',
                'iso3' => 'MNP',
                'numcode' => 580,
                'phonecode' => 1670,
            ),
            163 => 
            array (
                'id' => 164,
                'iso' => 'NO',
                'name' => 'NORWAY',
                'nicename' => 'Norway',
                'iso3' => 'NOR',
                'numcode' => 578,
                'phonecode' => 47,
            ),
            164 => 
            array (
                'id' => 165,
                'iso' => 'OM',
                'name' => 'OMAN',
                'nicename' => 'Oman',
                'iso3' => 'OMN',
                'numcode' => 512,
                'phonecode' => 968,
            ),
            165 => 
            array (
                'id' => 166,
                'iso' => 'PK',
                'name' => 'PAKISTAN',
                'nicename' => 'Pakistan',
                'iso3' => 'PAK',
                'numcode' => 586,
                'phonecode' => 92,
            ),
            166 => 
            array (
                'id' => 167,
                'iso' => 'PW',
                'name' => 'PALAU',
                'nicename' => 'Palau',
                'iso3' => 'PLW',
                'numcode' => 585,
                'phonecode' => 680,
            ),
            167 => 
            array (
                'id' => 168,
                'iso' => 'PS',
                'name' => 'PALESTINIAN TERRITORY, OCCUPIED',
                'nicename' => 'Palestinian Territory, Occupied',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 970,
            ),
            168 => 
            array (
                'id' => 169,
                'iso' => 'PA',
                'name' => 'PANAMA',
                'nicename' => 'Panama',
                'iso3' => 'PAN',
                'numcode' => 591,
                'phonecode' => 507,
            ),
            169 => 
            array (
                'id' => 170,
                'iso' => 'PG',
                'name' => 'PAPUA NEW GUINEA',
                'nicename' => 'Papua New Guinea',
                'iso3' => 'PNG',
                'numcode' => 598,
                'phonecode' => 675,
            ),
            170 => 
            array (
                'id' => 171,
                'iso' => 'PY',
                'name' => 'PARAGUAY',
                'nicename' => 'Paraguay',
                'iso3' => 'PRY',
                'numcode' => 600,
                'phonecode' => 595,
            ),
            171 => 
            array (
                'id' => 172,
                'iso' => 'PE',
                'name' => 'PERU',
                'nicename' => 'Peru',
                'iso3' => 'PER',
                'numcode' => 604,
                'phonecode' => 51,
            ),
            172 => 
            array (
                'id' => 173,
                'iso' => 'PH',
                'name' => 'PHILIPPINES',
                'nicename' => 'Philippines',
                'iso3' => 'PHL',
                'numcode' => 608,
                'phonecode' => 63,
            ),
            173 => 
            array (
                'id' => 174,
                'iso' => 'PN',
                'name' => 'PITCAIRN',
                'nicename' => 'Pitcairn',
                'iso3' => 'PCN',
                'numcode' => 612,
                'phonecode' => 0,
            ),
            174 => 
            array (
                'id' => 175,
                'iso' => 'PL',
                'name' => 'POLAND',
                'nicename' => 'Poland',
                'iso3' => 'POL',
                'numcode' => 616,
                'phonecode' => 48,
            ),
            175 => 
            array (
                'id' => 176,
                'iso' => 'PT',
                'name' => 'PORTUGAL',
                'nicename' => 'Portugal',
                'iso3' => 'PRT',
                'numcode' => 620,
                'phonecode' => 351,
            ),
            176 => 
            array (
                'id' => 177,
                'iso' => 'PR',
                'name' => 'PUERTO RICO',
                'nicename' => 'Puerto Rico',
                'iso3' => 'PRI',
                'numcode' => 630,
                'phonecode' => 1787,
            ),
            177 => 
            array (
                'id' => 178,
                'iso' => 'QA',
                'name' => 'QATAR',
                'nicename' => 'Qatar',
                'iso3' => 'QAT',
                'numcode' => 634,
                'phonecode' => 974,
            ),
            178 => 
            array (
                'id' => 179,
                'iso' => 'RE',
                'name' => 'REUNION',
                'nicename' => 'Reunion',
                'iso3' => 'REU',
                'numcode' => 638,
                'phonecode' => 262,
            ),
            179 => 
            array (
                'id' => 180,
                'iso' => 'RO',
                'name' => 'ROMANIA',
                'nicename' => 'Romania',
                'iso3' => 'ROU',
                'numcode' => 642,
                'phonecode' => 40,
            ),
            180 => 
            array (
                'id' => 181,
                'iso' => 'RU',
                'name' => 'RUSSIAN FEDERATION',
                'nicename' => 'Russian Federation',
                'iso3' => 'RUS',
                'numcode' => 643,
                'phonecode' => 7,
            ),
            181 => 
            array (
                'id' => 182,
                'iso' => 'RW',
                'name' => 'RWANDA',
                'nicename' => 'Rwanda',
                'iso3' => 'RWA',
                'numcode' => 646,
                'phonecode' => 250,
            ),
            182 => 
            array (
                'id' => 183,
                'iso' => 'SH',
                'name' => 'SAINT HELENA',
                'nicename' => 'Saint Helena',
                'iso3' => 'SHN',
                'numcode' => 654,
                'phonecode' => 290,
            ),
            183 => 
            array (
                'id' => 184,
                'iso' => 'KN',
                'name' => 'SAINT KITTS AND NEVIS',
                'nicename' => 'Saint Kitts and Nevis',
                'iso3' => 'KNA',
                'numcode' => 659,
                'phonecode' => 1869,
            ),
            184 => 
            array (
                'id' => 185,
                'iso' => 'LC',
                'name' => 'SAINT LUCIA',
                'nicename' => 'Saint Lucia',
                'iso3' => 'LCA',
                'numcode' => 662,
                'phonecode' => 1758,
            ),
            185 => 
            array (
                'id' => 186,
                'iso' => 'PM',
                'name' => 'SAINT PIERRE AND MIQUELON',
                'nicename' => 'Saint Pierre and Miquelon',
                'iso3' => 'SPM',
                'numcode' => 666,
                'phonecode' => 508,
            ),
            186 => 
            array (
                'id' => 187,
                'iso' => 'VC',
                'name' => 'SAINT VINCENT AND THE GRENADINES',
                'nicename' => 'Saint Vincent and the Grenadines',
                'iso3' => 'VCT',
                'numcode' => 670,
                'phonecode' => 1784,
            ),
            187 => 
            array (
                'id' => 188,
                'iso' => 'WS',
                'name' => 'SAMOA',
                'nicename' => 'Samoa',
                'iso3' => 'WSM',
                'numcode' => 882,
                'phonecode' => 684,
            ),
            188 => 
            array (
                'id' => 189,
                'iso' => 'SM',
                'name' => 'SAN MARINO',
                'nicename' => 'San Marino',
                'iso3' => 'SMR',
                'numcode' => 674,
                'phonecode' => 378,
            ),
            189 => 
            array (
                'id' => 190,
                'iso' => 'ST',
                'name' => 'SAO TOME AND PRINCIPE',
                'nicename' => 'Sao Tome and Principe',
                'iso3' => 'STP',
                'numcode' => 678,
                'phonecode' => 239,
            ),
            190 => 
            array (
                'id' => 191,
                'iso' => 'SA',
                'name' => 'SAUDI ARABIA',
                'nicename' => 'Saudi Arabia',
                'iso3' => 'SAU',
                'numcode' => 682,
                'phonecode' => 966,
            ),
            191 => 
            array (
                'id' => 192,
                'iso' => 'SN',
                'name' => 'SENEGAL',
                'nicename' => 'Senegal',
                'iso3' => 'SEN',
                'numcode' => 686,
                'phonecode' => 221,
            ),
            192 => 
            array (
                'id' => 193,
                'iso' => 'CS',
                'name' => 'SERBIA AND MONTENEGRO',
                'nicename' => 'Serbia and Montenegro',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 381,
            ),
            193 => 
            array (
                'id' => 194,
                'iso' => 'SC',
                'name' => 'SEYCHELLES',
                'nicename' => 'Seychelles',
                'iso3' => 'SYC',
                'numcode' => 690,
                'phonecode' => 248,
            ),
            194 => 
            array (
                'id' => 195,
                'iso' => 'SL',
                'name' => 'SIERRA LEONE',
                'nicename' => 'Sierra Leone',
                'iso3' => 'SLE',
                'numcode' => 694,
                'phonecode' => 232,
            ),
            195 => 
            array (
                'id' => 196,
                'iso' => 'SG',
                'name' => 'SINGAPORE',
                'nicename' => 'Singapore',
                'iso3' => 'SGP',
                'numcode' => 702,
                'phonecode' => 65,
            ),
            196 => 
            array (
                'id' => 197,
                'iso' => 'SK',
                'name' => 'SLOVAKIA',
                'nicename' => 'Slovakia',
                'iso3' => 'SVK',
                'numcode' => 703,
                'phonecode' => 421,
            ),
            197 => 
            array (
                'id' => 198,
                'iso' => 'SI',
                'name' => 'SLOVENIA',
                'nicename' => 'Slovenia',
                'iso3' => 'SVN',
                'numcode' => 705,
                'phonecode' => 386,
            ),
            198 => 
            array (
                'id' => 199,
                'iso' => 'XG',
                'name' => 'SMALLER TERRITORIES OF THE UK',
                'nicename' => 'Smaller Territories of the UK',
                'iso3' => 'XXG',
                'numcode' => NULL,
                'phonecode' => 44,
            ),
            199 => 
            array (
                'id' => 200,
                'iso' => 'SB',
                'name' => 'SOLOMON ISLANDS',
                'nicename' => 'Solomon Islands',
                'iso3' => 'SLB',
                'numcode' => 90,
                'phonecode' => 677,
            ),
            200 => 
            array (
                'id' => 201,
                'iso' => 'SO',
                'name' => 'SOMALIA',
                'nicename' => 'Somalia',
                'iso3' => 'SOM',
                'numcode' => 706,
                'phonecode' => 252,
            ),
            201 => 
            array (
                'id' => 202,
                'iso' => 'ZA',
                'name' => 'SOUTH AFRICA',
                'nicename' => 'South Africa',
                'iso3' => 'ZAF',
                'numcode' => 710,
                'phonecode' => 27,
            ),
            202 => 
            array (
                'id' => 203,
                'iso' => 'GS',
                'name' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
                'nicename' => 'South Georgia and the South Sandwich Islands',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 0,
            ),
            203 => 
            array (
                'id' => 204,
                'iso' => 'SS',
                'name' => 'SOUTH SUDAN',
                'nicename' => 'South Sudan',
                'iso3' => 'SSD',
                'numcode' => 728,
                'phonecode' => 211,
            ),
            204 => 
            array (
                'id' => 205,
                'iso' => 'ES',
                'name' => 'SPAIN',
                'nicename' => 'Spain',
                'iso3' => 'ESP',
                'numcode' => 724,
                'phonecode' => 34,
            ),
            205 => 
            array (
                'id' => 206,
                'iso' => 'LK',
                'name' => 'SRI LANKA',
                'nicename' => 'Sri Lanka',
                'iso3' => 'LKA',
                'numcode' => 144,
                'phonecode' => 94,
            ),
            206 => 
            array (
                'id' => 207,
                'iso' => 'SD',
                'name' => 'SUDAN',
                'nicename' => 'Sudan',
                'iso3' => 'SDN',
                'numcode' => 736,
                'phonecode' => 249,
            ),
            207 => 
            array (
                'id' => 208,
                'iso' => 'SR',
                'name' => 'SURINAME',
                'nicename' => 'Suriname',
                'iso3' => 'SUR',
                'numcode' => 740,
                'phonecode' => 597,
            ),
            208 => 
            array (
                'id' => 209,
                'iso' => 'SJ',
                'name' => 'SVALBARD AND JAN MAYEN',
                'nicename' => 'Svalbard and Jan Mayen',
                'iso3' => 'SJM',
                'numcode' => 744,
                'phonecode' => 47,
            ),
            209 => 
            array (
                'id' => 210,
                'iso' => 'SZ',
                'name' => 'SWAZILAND',
                'nicename' => 'Swaziland',
                'iso3' => 'SWZ',
                'numcode' => 748,
                'phonecode' => 268,
            ),
            210 => 
            array (
                'id' => 211,
                'iso' => 'SE',
                'name' => 'SWEDEN',
                'nicename' => 'Sweden',
                'iso3' => 'SWE',
                'numcode' => 752,
                'phonecode' => 46,
            ),
            211 => 
            array (
                'id' => 212,
                'iso' => 'CH',
                'name' => 'SWITZERLAND',
                'nicename' => 'Switzerland',
                'iso3' => 'CHE',
                'numcode' => 756,
                'phonecode' => 41,
            ),
            212 => 
            array (
                'id' => 213,
                'iso' => 'SY',
                'name' => 'SYRIAN ARAB REPUBLIC',
                'nicename' => 'Syrian Arab Republic',
                'iso3' => 'SYR',
                'numcode' => 760,
                'phonecode' => 963,
            ),
            213 => 
            array (
                'id' => 214,
                'iso' => 'TW',
                'name' => 'TAIWAN, PROVINCE OF CHINA',
                'nicename' => 'Taiwan, Province of China',
                'iso3' => 'TWN',
                'numcode' => 158,
                'phonecode' => 886,
            ),
            214 => 
            array (
                'id' => 215,
                'iso' => 'TJ',
                'name' => 'TAJIKISTAN',
                'nicename' => 'Tajikistan',
                'iso3' => 'TJK',
                'numcode' => 762,
                'phonecode' => 992,
            ),
            215 => 
            array (
                'id' => 216,
                'iso' => 'TZ',
                'name' => 'TANZANIA, UNITED REPUBLIC OF',
                'nicename' => 'Tanzania, United Republic of',
                'iso3' => 'TZA',
                'numcode' => 834,
                'phonecode' => 255,
            ),
            216 => 
            array (
                'id' => 217,
                'iso' => 'TH',
                'name' => 'THAILAND',
                'nicename' => 'Thailand',
                'iso3' => 'THA',
                'numcode' => 764,
                'phonecode' => 66,
            ),
            217 => 
            array (
                'id' => 218,
                'iso' => 'TG',
                'name' => 'TOGO',
                'nicename' => 'Togo',
                'iso3' => 'TGO',
                'numcode' => 768,
                'phonecode' => 228,
            ),
            218 => 
            array (
                'id' => 219,
                'iso' => 'TK',
                'name' => 'TOKELAU',
                'nicename' => 'Tokelau',
                'iso3' => 'TKL',
                'numcode' => 772,
                'phonecode' => 690,
            ),
            219 => 
            array (
                'id' => 220,
                'iso' => 'TO',
                'name' => 'TONGA',
                'nicename' => 'Tonga',
                'iso3' => 'TON',
                'numcode' => 776,
                'phonecode' => 676,
            ),
            220 => 
            array (
                'id' => 221,
                'iso' => 'TT',
                'name' => 'TRINIDAD AND TOBAGO',
                'nicename' => 'Trinidad and Tobago',
                'iso3' => 'TTO',
                'numcode' => 780,
                'phonecode' => 1868,
            ),
            221 => 
            array (
                'id' => 222,
                'iso' => 'TN',
                'name' => 'TUNISIA',
                'nicename' => 'Tunisia',
                'iso3' => 'TUN',
                'numcode' => 788,
                'phonecode' => 216,
            ),
            222 => 
            array (
                'id' => 223,
                'iso' => 'TR',
                'name' => 'TURKEY',
                'nicename' => 'Turkey',
                'iso3' => 'TUR',
                'numcode' => 792,
                'phonecode' => 90,
            ),
            223 => 
            array (
                'id' => 224,
                'iso' => 'TM',
                'name' => 'TURKMENISTAN',
                'nicename' => 'Turkmenistan',
                'iso3' => 'TKM',
                'numcode' => 795,
                'phonecode' => 7370,
            ),
            224 => 
            array (
                'id' => 225,
                'iso' => 'TC',
                'name' => 'TURKS AND CAICOS ISLANDS',
                'nicename' => 'Turks and Caicos Islands',
                'iso3' => 'TCA',
                'numcode' => 796,
                'phonecode' => 1649,
            ),
            225 => 
            array (
                'id' => 226,
                'iso' => 'TV',
                'name' => 'TUVALU',
                'nicename' => 'Tuvalu',
                'iso3' => 'TUV',
                'numcode' => 798,
                'phonecode' => 688,
            ),
            226 => 
            array (
                'id' => 227,
                'iso' => 'UG',
                'name' => 'UGANDA',
                'nicename' => 'Uganda',
                'iso3' => 'UGA',
                'numcode' => 800,
                'phonecode' => 256,
            ),
            227 => 
            array (
                'id' => 228,
                'iso' => 'UA',
                'name' => 'UKRAINE',
                'nicename' => 'Ukraine',
                'iso3' => 'UKR',
                'numcode' => 804,
                'phonecode' => 380,
            ),
            228 => 
            array (
                'id' => 229,
                'iso' => 'AE',
                'name' => 'UNITED ARAB EMIRATES',
                'nicename' => 'United Arab Emirates',
                'iso3' => 'ARE',
                'numcode' => 784,
                'phonecode' => 971,
            ),
            229 => 
            array (
                'id' => 230,
                'iso' => 'GB',
                'name' => 'UNITED KINGDOM',
                'nicename' => 'United Kingdom',
                'iso3' => 'GBR',
                'numcode' => 826,
                'phonecode' => 44,
            ),
            230 => 
            array (
                'id' => 231,
                'iso' => 'US',
                'name' => 'UNITED STATES',
                'nicename' => 'United States',
                'iso3' => 'USA',
                'numcode' => 840,
                'phonecode' => 1,
            ),
            231 => 
            array (
                'id' => 232,
                'iso' => 'UM',
                'name' => 'UNITED STATES MINOR OUTLYING ISLANDS',
                'nicename' => 'United States Minor Outlying Islands',
                'iso3' => NULL,
                'numcode' => NULL,
                'phonecode' => 1,
            ),
            232 => 
            array (
                'id' => 233,
                'iso' => 'UY',
                'name' => 'URUGUAY',
                'nicename' => 'Uruguay',
                'iso3' => 'URY',
                'numcode' => 858,
                'phonecode' => 598,
            ),
            233 => 
            array (
                'id' => 234,
                'iso' => 'UZ',
                'name' => 'UZBEKISTAN',
                'nicename' => 'Uzbekistan',
                'iso3' => 'UZB',
                'numcode' => 860,
                'phonecode' => 998,
            ),
            234 => 
            array (
                'id' => 235,
                'iso' => 'VU',
                'name' => 'VANUATU',
                'nicename' => 'Vanuatu',
                'iso3' => 'VUT',
                'numcode' => 548,
                'phonecode' => 678,
            ),
            235 => 
            array (
                'id' => 236,
                'iso' => 'VA',
            'name' => 'HOLY SEE (VATICAN CITY STATE)',
            'nicename' => 'Holy See (Vatican City State)',
                'iso3' => 'VAT',
                'numcode' => 336,
                'phonecode' => 39,
            ),
            236 => 
            array (
                'id' => 237,
                'iso' => 'VE',
                'name' => 'VENEZUELA',
                'nicename' => 'Venezuela',
                'iso3' => 'VEN',
                'numcode' => 862,
                'phonecode' => 58,
            ),
            237 => 
            array (
                'id' => 238,
                'iso' => 'VN',
                'name' => 'VIET NAM',
                'nicename' => 'Viet Nam',
                'iso3' => 'VNM',
                'numcode' => 704,
                'phonecode' => 84,
            ),
            238 => 
            array (
                'id' => 239,
                'iso' => 'VG',
                'name' => 'VIRGIN ISLANDS, BRITISH',
                'nicename' => 'Virgin Islands, British',
                'iso3' => 'VGB',
                'numcode' => 92,
                'phonecode' => 1284,
            ),
            239 => 
            array (
                'id' => 240,
                'iso' => 'VI',
                'name' => 'VIRGIN ISLANDS, U.S.',
                'nicename' => 'Virgin Islands, U.s.',
                'iso3' => 'VIR',
                'numcode' => 850,
                'phonecode' => 1340,
            ),
            240 => 
            array (
                'id' => 241,
                'iso' => 'WF',
                'name' => 'WALLIS AND FUTUNA',
                'nicename' => 'Wallis and Futuna',
                'iso3' => 'WLF',
                'numcode' => 876,
                'phonecode' => 681,
            ),
            241 => 
            array (
                'id' => 242,
                'iso' => 'EH',
                'name' => 'WESTERN SAHARA',
                'nicename' => 'Western Sahara',
                'iso3' => 'ESH',
                'numcode' => 732,
                'phonecode' => 212,
            ),
            242 => 
            array (
                'id' => 243,
                'iso' => 'YE',
                'name' => 'YEMEN',
                'nicename' => 'Yemen',
                'iso3' => 'YEM',
                'numcode' => 887,
                'phonecode' => 967,
            ),
            243 => 
            array (
                'id' => 244,
                'iso' => 'YU',
                'name' => 'YUGOSLAVIA',
                'nicename' => 'Yugoslavia',
                'iso3' => 'YUG',
                'numcode' => 891,
                'phonecode' => 38,
            ),
            244 => 
            array (
                'id' => 245,
                'iso' => 'ZM',
                'name' => 'ZAMBIA',
                'nicename' => 'Zambia',
                'iso3' => 'ZMB',
                'numcode' => 894,
                'phonecode' => 260,
            ),
            245 => 
            array (
                'id' => 246,
                'iso' => 'ZW',
                'name' => 'ZIMBABWE',
                'nicename' => 'Zimbabwe',
                'iso3' => 'ZWE',
                'numcode' => 716,
                'phonecode' => 263,
            ),
            246 => 
            array (
                'id' => 247,
                'iso' => 'TL',
                'name' => 'TIMOR-LESTE',
                'nicename' => 'Timor-Leste',
                'iso3' => 'TLC',
                'numcode' => NULL,
                'phonecode' => 670,
            ),
        ));
        
        
    }
}