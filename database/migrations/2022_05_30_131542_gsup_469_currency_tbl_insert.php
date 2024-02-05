<?php

use App\Models\CurrencyMaster;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gsup469CurrencyTblInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CurrencyMaster::truncate(); 
        $currency_arr = array(
            ['currency_name'=>'Omani Rial','currency_code'=>'OMR','decimal_places'=>3], 
            ['currency_name'=>'US Dollar','currency_code'=>'USD','decimal_places'=>2], 
            ['currency_name'=>'UAE Dirham','currency_code'=>'AED','decimal_places'=>2], 
            ['currency_name'=>'British Pound','currency_code'=>'GBP','decimal_places'=>2], 
            ['currency_name'=>'Euro','currency_code'=>'EUR','decimal_places'=>2], 
            ['currency_name'=>'Saudi Rial','currency_code'=>'SAR','decimal_places'=>2], 
            ['currency_name'=>'Bahraini Dinar','currency_code'=>'BHD','decimal_places'=>2], 
            ['currency_name'=>'Canadian Dollar','currency_code'=>'CAD','decimal_places'=>2], 
            ['currency_name'=>'Qatari Rial','currency_code'=>'QAR','decimal_places'=>2], 
            ['currency_name'=>'Kuwaiti Dinar','currency_code'=>'KWD','decimal_places'=>2], 
            ['currency_name'=>'Singapore Dollar','currency_code'=>'SGD','decimal_places'=>2], 
            ['currency_name'=>'Sri Lankan Rupee','currency_code'=>'LKR','decimal_places'=>2], 
            ['currency_name'=>'Indian Rupee','currency_code'=>'INR','decimal_places'=>2], 
            ['currency_name'=>'Yemeni Rial','currency_code'=>'YER','decimal_places'=>2], 
            ['currency_name'=>'Algerian Dinar','currency_code'=>'DZD','decimal_places'=>2], 
            ['currency_name'=>'Japanese Yen','currency_code'=>'YEN','decimal_places'=>2], 
            ['currency_name'=>'Philippine Piso','currency_code'=>'PHP','decimal_places'=>2],  
            ['currency_name'=>'Australian Dollar','currency_code'=>'AUD','decimal_places'=>2],  
            ['currency_name'=>'Swiss Franc','currency_code'=>'CHF','decimal_places'=>2],  
            ['currency_name'=>'Swedish Korana','currency_code'=>'SKR','decimal_places'=>2] 
        );
        foreach($currency_arr as $val){ 
            $data['currency_name'] = $val['currency_name'];
            $data['currency_code'] = $val['currency_code'];
            $data['decimal_places'] = $val['decimal_places'];
            CurrencyMaster::create($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
