<?php

namespace App\Helpers;

use App\Helpers\General;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CreateExcel
{

    public static function process($type, $fileName,$array = NULL,$export,$disk)
    {

        $companyCode = isset($array['company_code']) ? $array['company_code'] : 'common';
    
        $full_name = $companyCode . '_' . $fileName . '_' . strtotime(date("Y-m-d H:i:s")) . '.' . $type;
        $path = $companyCode. '/cm/reports/excel-sheets/'. $full_name;
    
        // Use the store method instead of download
        \Excel::store($export, $path, $disk);
    
        // Assuming you have a function like \General::getFileUrlFromS3 to generate the public URL
        $basePath = General::getFileUrlFromS3($path);
    
        return ['basePath' => $basePath, 'path' => $path];
    }

    public static function fromDate($array, $sheet, $type)
    {
        $sheet->cell('A3', function ($cell) use ($array, $type) {
            if (isset($array['from_date'])) {
                $cell->setValue($type . ' - ' . $array['from_date']);
                $cell->setFont(array(

                    'family'     => 'Calibri',

                    'size'       => '12',

                    'bold'       =>  true

                ));
                $cell->setAlignment('left');
            }
        });
    }

    public static function toDate($array, $sheet)
    {
        $sheet->cell('A4', function ($cell) use ($array) {
            if (isset($array['to_date'])) {
                $cell->setValue('To Date - ' . $array['to_date']);
                $cell->setFont(array(

                    'family'     => 'Calibri',

                    'size'       => '12',

                    'bold'       =>  true

                ));
                $cell->setAlignment('left');
            }
        });
    }

    public static function currency($array, $sheet, $col)
    {
        $sheet->cell($col, function ($cell) use ($array) {
            if (isset($array['cur'])) {
                $cell->setValue('Currency - ' . $array['cur']);
                $cell->setFont(array(

                    'family'     => 'Calibri',

                    'size'       => '12',

                    'bold'       =>  true

                ));
                $cell->setAlignment('left');
            }
        });
    }
}
