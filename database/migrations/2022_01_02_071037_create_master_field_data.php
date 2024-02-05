<?php

use App\Models\FormData;
use App\Models\FormFieldData;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateMasterFieldData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $currecyArr = [
            ['currency_code' => 'OMR', 'currency_id' => 1],
            ['currency_code' => 'USD', 'currency_id' => 2],
            ['currency_code' => 'AED', 'currency_id' => 3],
            ['currency_code' => 'GBP', 'currency_id' => 6],
            ['currency_code' => 'EUR', 'currency_id' => 7],
            ['currency_code' => 'SAR', 'currency_id' => 8],
            ['currency_code' => 'BHD', 'currency_id' => 9],
            ['currency_code' => 'CAD', 'currency_id' => 10],
            ['currency_code' => 'QAR', 'currency_id' => 11],
            ['currency_code' => 'KWD', 'currency_id' => 12],
            ['currency_code' => 'SGD', 'currency_id' => 13],
            ['currency_code' => 'LKR', 'currency_id' => 14],
            ['currency_code' => 'INR', 'currency_id' => 15],
            ['currency_code' => 'YER', 'currency_id' => 16],
            ['currency_code' => 'DZD', 'currency_id' => 17],
            ['currency_code' => 'YEN', 'currency_id' => 18],
            ['currency_code' => 'PHP', 'currency_id' => 19],
            ['currency_code' => 'AUD', 'currency_id' => 21],
            ['currency_code' => 'CHF', 'currency_id' => 22]
        ];
        
        // Currency
        foreach ($currecyArr as $currency) {
            FormData::insert([
                [
                    'value'         => $currency['currency_id'],
                    'text'          =>  $currency['currency_code'],
                    'created_at'    => $now
                ],
            ]);
        }

        $currencyFieldData = FormData::select('id')->whereBetween('id', [40, 58])->get();

        foreach($currencyFieldData as $val1){ 
            FormFieldData::insert([ 
                [
                    'form_field_id' => 28,
                    'form_data_id'  => $val1['id'],
                    'created_at'    => $now
                ],
            ]);
        }


        $country = [
            ['countryID' => 1,'countryDescription'=>'Sultanate of Oman'],
            ['countryID' => 2,'countryDescription'=> 'United Kingdom'],
            ['countryID' => 3,'countryDescription'=> 'United States of America (USA)'],
            ['countryID' => 4,'countryDescription'=>'India'],
            ['countryID' => 5,'countryDescription'=>'United Arab Emirates (UAE)'],
            ['countryID' => 6,'countryDescription'=>'France'],
            ['countryID' => 7,'countryDescription'=>'Germany'], 
            ['countryID' => 8,'countryDescription'=>'Saudi Arabia'],
            ['countryID' => 9,'countryDescription'=>'Qatar'],
            ['countryID' => 10,'countryDescription'=> 'Canada'],
            ['countryID' => 11,'countryDescription'=> 'Bahrain'],
            ['countryID' => 12,'countryDescription'=> 'Spain'],
            ['countryID' => 13,'countryDescription'=> 'Netherland'],
            ['countryID' => 14,'countryDescription'=> 'Turkey'], 
            ['countryID' => 15,'countryDescription'=> 'Australia'], 
            ['countryID' => 16,'countryDescription'=> 'Sweden'],
            ['countryID' => 17,'countryDescription'=> 'Yemen'], 
            ['countryID' => 18,'countryDescription'=> 'Sri Lanka'], 
            ['countryID' => 19,'countryDescription'=> 'Afghanistan'],
            ['countryID' => 20,'countryDescription'=> 'China'],
            ['countryID' => 21,'countryDescription'=> 'Bangladesh'],
            ['countryID' => 22,'countryDescription'=> 'Finland'],
            ['countryID' => 23,'countryDescription'=> 'Indonesia'],
            ['countryID' => 24,'countryDescription'=> 'Israel'], 
            ['countryID' => 25,'countryDescription'=> 'Japan'],
            ['countryID' =>26,'countryDescription'=> 'Kuwait'],
            ['countryID' =>27,'countryDescription'=> 'Lebanon'],
            ['countryID' =>28,'countryDescription'=> 'South Africa'],
            ['countryID' =>29,'countryDescription'=> 'Thailand'],
            ['countryID' =>30,'countryDescription'=> 'Singapore'],
            ['countryID' =>31,'countryDescription'=> 'Ireland'], 
            ['countryID' =>32,'countryDescription'=> 'Russia'],
            ['countryID' =>33,'countryDescription'=> 'Norway'],
            ['countryID' =>34,'countryDescription'=> 'Iran'],
            ['countryID' =>35,'countryDescription'=> 'Italy'], 
            ['countryID' =>36,'countryDescription'=> 'Egypt'],
            ['countryID' =>37,'countryDescription'=> 'Algeria'],
            ['countryID' =>38,'countryDescription'=> 'Sudan'], 
            ['countryID' =>39,'countryDescription'=> 'Malaysia'],
            ['countryID' =>40,'countryDescription'=> 'Philippines'],
            ['countryID' =>41, 'countryDescription'=> 'Pakistan'],
            ['countryID' =>42,'countryDescription'=>  'Syria'],
            ['countryID' =>43, 'countryDescription'=> 'Jordan'],
            ['countryID' =>44, 'countryDescription'=> 'Libiya'],
            ['countryID' =>45, 'countryDescription'=> 'Nepal'],
            ['countryID' =>46, 'countryDescription'=> 'Romania'],
            ['countryID' =>47, 'countryDescription'=> 'Croatia'],
            ['countryID' =>48, 'countryDescription'=> 'Morocco'],
            ['countryID' =>49, 'countryDescription'=> 'Nigeria'],
            ['countryID' =>50, 'countryDescription'=> 'Greece'],
            ['countryID' =>51, 'countryDescription'=> 'Kenya'],
            ['countryID' =>52, 'countryDescription'=> 'Switzerland'], 
            ['countryID' =>53, 'countryDescription'=> 'Albania '],
            ['countryID' =>54, 'countryDescription'=> 'American Samoa '],
            ['countryID' =>55, 'countryDescription'=> 'Andorra '],
            ['countryID' =>56, 'countryDescription'=> 'Angola '],
            ['countryID' =>57, 'countryDescription'=> 'Anguilla '],
            ['countryID' =>58, 'countryDescription'=> 'Antigua & Barbuda '],
            ['countryID' =>59,'countryDescription'=>  'Argentina '], 
            ['countryID' =>60, 'countryDescription'=> 'Armenia '], 
            ['countryID' =>61, 'countryDescription'=> 'Aruba '], 
            ['countryID' =>62, 'countryDescription'=> 'Austria '],
            ['countryID' =>63, 'countryDescription'=> 'Azerbaijan '],
            ['countryID' =>64, 'countryDescription'=> 'Bahamas, The '], 
            ['countryID' =>65, 'countryDescription'=> 'Barbados '],
            ['countryID' =>66, 'countryDescription'=> 'Belarus '],
            ['countryID' =>67,'countryDescription'=> 'Belgium '], 
            ['countryID' =>68,'countryDescription'=> 'Belize '],
            ['countryID' =>69,'countryDescription'=> 'Benin '],
            ['countryID' =>70,'countryDescription'=> 'Bermuda '],
            ['countryID' =>71,'countryDescription'=> 'Bhutan '],
            ['countryID' =>72,'countryDescription'=> 'Bolivia '],
            ['countryID' =>73,'countryDescription'=> 'Bosnia & Herzegovina '],
            ['countryID' =>74,'countryDescription'=> 'Botswana '],
            ['countryID' =>75,'countryDescription'=> 'Brazil '],
            ['countryID' =>76,'countryDescription'=> 'British Virgin Is. '], 
            ['countryID' =>77,'countryDescription'=> 'Brunei '],
            ['countryID' =>78,'countryDescription'=> 'Bulgaria '],
            ['countryID' =>79,'countryDescription'=> 'Burkina Faso '], 
            ['countryID' =>80,'countryDescription'=> 'Burma '],
            ['countryID' =>81, 'countryDescription'=> 'Burundi '],
            ['countryID' =>82,'countryDescription'=>  'Cambodia '],
            ['countryID' =>83,'countryDescription'=>  'Cameroon '],
            ['countryID' =>84,'countryDescription'=>  'Cape Verde '], 
            ['countryID' =>85,'countryDescription'=>  'Cayman Islands '],
            ['countryID' =>86,'countryDescription'=>  'Central African Rep. '], 
            ['countryID' =>87,'countryDescription'=>  'Chad '],
            ['countryID' =>88,'countryDescription'=>  'Chile '],
            ['countryID' =>89,'countryDescription'=>  'Colombia '], 
            ['countryID' =>90,'countryDescription'=>  'Comoros '],
            ['countryID' =>91,'countryDescription'=>  'Congo, Dem. Rep. '],
            ['countryID' =>92, 'countryDescription'=> 'Congo, Repub. of the '],
            ['countryID' =>93, 'countryDescription'=> 'Cook Islands '],
            ['countryID' =>94,'countryDescription'=>  'Costa Rica '], 
            ['countryID' =>95,'countryDescription'=>  'Cote d\'Ivoire '], 
            ['countryID' =>96, 'countryDescription'=>  'Cuba '],
            ['countryID' =>97, 'countryDescription'=> 'Cyprus '],
            ['countryID' =>98, 'countryDescription'=> 'Czech Republic '],  
            ['countryID' =>99, 'countryDescription'=> 'Denmark '],
            ['countryID' =>100,'countryDescription'=>  'Djibouti '], 
            ['countryID' =>101,'countryDescription'=>  'Dominica '], 
            ['countryID' =>102,'countryDescription'=>  'Dominican Republic '],
            ['countryID' =>103, 'countryDescription'=> 'East Timor '],
            ['countryID' =>104,'countryDescription'=>  'Ecuador '], 
            ['countryID' =>105, 'countryDescription'=> 'El Salvador '], 
            ['countryID' =>106, 'countryDescription'=> 'Equatorial Guinea '],
            ['countryID' =>107, 'countryDescription'=> 'Eritrea '],
            ['countryID' =>108, 'countryDescription'=> 'Estonia '], 
            ['countryID' =>109, 'countryDescription'=> 'Ethiopia '], 
            ['countryID' =>110, 'countryDescription'=> 'Faroe Islands '],  
            ['countryID' =>111, 'countryDescription'=> 'Fiji '], 
            ['countryID' =>112, 'countryDescription'=> 'French Guiana '],
            ['countryID' =>113, 'countryDescription'=> 'French Polynesia '],
            ['countryID' =>114, 'countryDescription'=> 'Gabon '],
            ['countryID' =>115, 'countryDescription'=> 'Gambia, The '],
            ['countryID' =>116, 'countryDescription'=> 'Gaza Strip '],
            ['countryID' =>117, 'countryDescription'=> 'Georgia '],
            ['countryID' =>118, 'countryDescription'=> 'Ghana '],
            ['countryID' =>	149,'countryDescription'=>  'Macau '],
            ['countryID' =>	150,'countryDescription'=>  'Macedonia '], 
            ['countryID' =>	151,'countryDescription'=>  'Madagascar '], 
            ['countryID' =>	152,'countryDescription'=>  'Malawi '], 
            ['countryID' =>	153,'countryDescription'=>  'Maldives '], 
            ['countryID' =>	154,'countryDescription'=>  'Mali '],
            ['countryID' =>	155,'countryDescription'=>  'Malta '], 
            ['countryID' =>	156, 'countryDescription'=> 'Marshall Islands '], 
            ['countryID' =>	157, 'countryDescription'=> 'Martinique '],
            ['countryID' =>	158, 'countryDescription'=> 'Mauritania '], 
            ['countryID' =>	159, 'countryDescription'=> 'Mauritius '],
            ['countryID' =>	160, 'countryDescription'=> 'Mayotte '], 
            ['countryID' =>	161,'countryDescription'=>  'Mexico '],
            ['countryID' =>	162,'countryDescription'=>  'Micronesia, Fed. St. '],
            ['countryID' =>	163, 'countryDescription'=> 'Moldova '],
            ['countryID' =>	164, 'countryDescription'=> 'Monaco '],
            ['countryID' =>	165, 'countryDescription'=> 'Mongolia '], 
            ['countryID' =>	166, 'countryDescription'=> 'Montserrat '], 
            ['countryID' =>	167, 'countryDescription'=> 'Mozambique '], 
            ['countryID' =>	168, 'countryDescription'=> 'Namibia '],
            ['countryID' =>	169, 'countryDescription'=> 'Nauru '], 
            ['countryID' =>	170, 'countryDescription'=> 'Netherlands Antilles '],
            ['countryID' =>	171, 'countryDescription'=> 'New Caledonia '],
            ['countryID' =>	172, 'countryDescription'=> 'New Zealand '], 
            ['countryID' =>	173, 'countryDescription'=> 'Nicaragua '],
            ['countryID' =>	174, 'countryDescription'=> 'Niger '], 
            ['countryID' =>	175, 'countryDescription'=> 'N. Mariana Islands '],
            ['countryID' =>	176, 'countryDescription'=> 'Palau '], 
            ['countryID' =>	177, 'countryDescription'=> 'Panama '],
            ['countryID' =>	178,'countryDescription'=>  'Papua New Guinea '],
            ['countryID' =>	179, 'countryDescription'=> 'Paraguay '],
            ['countryID' =>	180,'countryDescription'=>  'Peru '],
            ['countryID' =>	181, 'countryDescription'=> 'Poland '], 
            ['countryID' =>	182,'countryDescription'=>  'Portugal '], 
            ['countryID' =>	183, 'countryDescription'=> 'Puerto Rico '],
            ['countryID' =>	184, 'countryDescription'=> 'Reunion '], 
            ['countryID' =>	185, 'countryDescription'=> 'Rwanda '], 
            ['countryID' =>	186, 'countryDescription'=> 'Saint Helena '],
            ['countryID' =>	187, 'countryDescription'=> 'Saint Kitts & Nevis '],
            ['countryID' =>	188, 'countryDescription'=> 'Saint Lucia '], 
            ['countryID' =>	189,'countryDescription'=>  'St Pierre & Miquelon '],
            ['countryID' =>	190,'countryDescription'=>  'Saint Vincent and the Grenadines '],
            ['countryID' =>	191, 'countryDescription'=> 'Samoa '], 
            ['countryID' =>	192, 'countryDescription'=> 'San Marino '],
            ['countryID' =>	193, 'countryDescription'=> 'Sao Tome & Principe '],
            ['countryID' =>	194, 'countryDescription'=> 'Senegal '],
            ['countryID' =>	195, 'countryDescription'=> 'Serbia '],
            ['countryID' =>	196, 'countryDescription'=> 'Seychelles '], 
            ['countryID' =>	197, 'countryDescription'=> 'Sierra Leone '], 
            ['countryID' =>	198,'countryDescription'=>  'Slovakia '], 
            ['countryID' =>	199, 'countryDescription'=> 'Slovenia '],
            ['countryID' =>	200, 'countryDescription'=> 'Solomon Islands'],
            ['countryID' =>	201, 'countryDescription'=> 'Somalia'],
            ['countryID' =>	202, 'countryDescription'=> 'Suriname'],
            ['countryID' =>	203, 'countryDescription'=> 'Taiwan'],
            ['countryID' =>	204, 'countryDescription'=> 'Tajikistan'],
            ['countryID' =>	205, 'countryDescription'=> 'Tanzania'],
            ['countryID' =>	206, 'countryDescription'=> 'Togo'],
            ['countryID' =>	207, 'countryDescription'=> 'Tonga'],
            ['countryID' =>	208,'countryDescription'=>  'Trinidad & Tobago'],
            ['countryID' =>	209,'countryDescription'=>  'Tunisia'],
            ['countryID' =>	210,'countryDescription'=>  'Turkmenistan'],
            ['countryID' =>	211,'countryDescription'=>  'Turks & Caicos Is'],
            ['countryID' =>	212, 'countryDescription'=> 'Tuvalu'],
            ['countryID' =>	213,'countryDescription'=>  'Uganda'],
            ['countryID' =>	214,'countryDescription'=>  'Ukraine'],
            ['countryID' =>	215,'countryDescription'=>  'Uruguay'],
            ['countryID' =>	216,'countryDescription'=>  'Uzbekistan'],
            ['countryID' =>	217,'countryDescription'=>  'Vanuatu'],
            ['countryID' =>	218,'countryDescription'=>  'Venezuela'],
            ['countryID' =>	219,'countryDescription'=>  'Vietnam'],
            ['countryID' =>	220,'countryDescription'=>  'Virgin Islands'], 
            ['countryID' =>	221,'countryDescription'=>  'Wallis and Futuna'], 
            ['countryID' =>	222,'countryDescription'=>  'West Bank'],
            ['countryID' =>	223,'countryDescription'=>  'Western Sahara'], 
            ['countryID' =>	224,'countryDescription'=>  'Zambia'],
            ['countryID' =>	225, 'countryDescription'=> 'Zimbabwe'],
            ['countryID' =>	226, 'countryDescription'=> 'Palestine'], 
        ];

        foreach ($country as $country1) {
            FormData::insert([
                [
                    'value'         => $country1['countryID'],
                    'text'          =>  $country1['countryDescription'],
                    'created_at'    => $now
                ],
            ]);
        }

        $countryFieldData = FormData::select('id')->whereBetween('id', [59, 254])->get();

        foreach($countryFieldData as $val1){ 
            FormFieldData::insert([ 
                [
                    'form_field_id' => 46,
                    'form_data_id'  => $val1['id'],
                    'created_at'    => $now
                ],
            ]);
        }

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
