<?php

use App\Models\FormData;
use App\Models\FormFieldData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateAsasCatSub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();
         $categoryArr = [
            ['catID'=>1, 'catDescription'=>'Automotive Spareparts'],
            ['catID'=>2, 'catDescription'=>'Building Materials & PPE'],
            ['catID'=>3, 'catDescription'=>'Computer Services'],
            ['catID'=>4, 'catDescription'=>'Electrical Equip'],
            ['catID'=>5, 'catDescription'=>'Filters'],
            ['catID'=>6, 'catDescription'=> 'Furniture'],
            ['catID'=>7, 'catDescription'=>'Industrial Equip'],
            ['catID'=>8, 'catDescription'=>'Industrial Gases'],
            ['catID'=>9, 'catDescription'=>'Labels & Stickers'],
            ['catID'=>10, 'catDescription'=>'Laboratory Equip'],
            ['catID'=>11, 'catDescription'=>'Mechanical, Electrical & Plumping Services'],
            ['catID'=>12, 'catDescription'=>'Oil field Services'],
            ['catID'=>13, 'catDescription'=>'Packing Equip'],
            ['catID'=>14, 'catDescription'=>'Pipes Spareparts'],
            ['catID'=>15, 'catDescription'=>'PPE & Safety'],
            ['catID'=>16, 'catDescription'=>'Safety Services'],
            ['catID'=>17, 'catDescription'=>'Stationary'],
            ['catID'=>18, 'catDescription'=>'Tools & equipments'],
            ['catID'=>19, 'catDescription'=>'Welding Equip'],
            ['catID'=>20, 'catDescription'=>'Marketing Services'],
            ['catID'=>21, 'catDescription'=>'Finance'],
            ['catID'=>23, 'catDescription'=>'Utility'],
            ['catID'=>24, 'catDescription'=>'General'],
            ['catID'=>25, 'catDescription'=>'Construction'],
            ['catID'=>26, 'catDescription'=>'Insurance'],
            ['catID'=>27, 'catDescription'=>'Transportation'],
        ];
        
        
        
        //category
        foreach ($categoryArr as $category) {
            FormData::insert([
                [
                    'value'         => $category['catID'],
                    'text'          =>  $category['catDescription'],
                    'created_at'    => $now
                ],
            ]);
        } 

        $catFieldData = FormData::select('id')->whereBetween('id', [255, 280])->get();
        foreach($catFieldData as $val1){ 
            FormFieldData::insert([ 
                [
                    'form_field_id' => 1,
                    'form_data_id'  => $val1['id'],
                    'created_at'    => $now
                ],
            ]);
        }  
        $categoryArr = [
            ['subCatID'=>1, 'subcatDescription'=>'Automotive Spareparts'],
            ['subCatID'=>2, 'subcatDescription'=>'Building Materials & PPE'],
            ['subCatID'=>3, 'subcatDescription'=>'Computer Services'],
            ['subCatID'=>4, 'subcatDescription'=>'Electrical Equip'],
            ['subCatID'=>5, 'subcatDescription'=>'Filters'],
            ['subCatID'=>6, 'subcatDescription'=> 'Furniture'],
            ['subCatID'=>7, 'subcatDescription'=>'Industrial Equip'],
            ['subCatID'=>8, 'subcatDescription'=>'Industrial Gases'],
            ['subCatID'=>9, 'subcatDescription'=>'Labels & Stickers'],
            ['subCatID'=>10, 'subcatDescription'=>'Laboratory Equip'],
            ['subCatID'=>11, 'subcatDescription'=>'Mechanical, Electrical & Plumping Services'],
            ['subCatID'=>12, 'subcatDescription'=>'Oil field Services'],
            ['subCatID'=>13, 'subcatDescription'=>'Packing Equip'],
            ['subCatID'=>14, 'subcatDescription'=>'Pipes Spareparts'],
            ['subCatID'=>15, 'subcatDescription'=>'PPE & Safety'],
            ['subCatID'=>16, 'subcatDescription'=>'Safety Services'],
            ['subCatID'=>17, 'subcatDescription'=>'Stationary'],
            ['subCatID'=>18, 'subcatDescription'=>'Tools & equipments'],
            ['subCatID'=>19, 'subcatDescription'=>'Welding Equip'] 
        ];

          //sub category
          foreach ($categoryArr as $category) {
            FormData::insert([
                [
                    'value'         => $category['subCatID'],
                    'text'          =>  $category['subcatDescription'],
                    'created_at'    => $now
                ],
            ]);
        }

        $catFieldData = FormData::select('id')->whereBetween('id', [281, 299])->get();
        foreach($catFieldData as $val1){ 
            FormFieldData::insert([ 
                [
                    'form_field_id' => 2,
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
        Schema::dropIfExists('asas_cat_sub');
    }
}
