<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ContractManagmentExport implements FromArray, WithEvents, ShouldAutoSize
{
    protected $data;
    protected $detailArray;

    public function __construct(array $data, $url = '', $detailArray = [])
    {
        $this->data = $data;
        $this->detailArray = array_merge(['headerRow' => 1], $detailArray);
    }

    public function array(): array
    {
        return $this->data;
    }

    public function map($data): array
    {
        return $data;
    }
    public function registerEvents(): array
    {

        $detailArray = $this->detailArray;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($detailArray) {
                $sheet = $event->getDelegate();

                $columnCount = $sheet->getDelegate()->getHighestColumn();
                $headerRow = isset($detailArray['headerRow']) ? $detailArray['headerRow'] : 1;
                $boldRange = 'A1:' . $columnCount . $headerRow;

                $event->sheet->getStyle($boldRange)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $i = 7;
                if (!isset($this->detailArray['title']) && empty($this->detailArray['title'])) {
                    $i = $i - 1;
                }

                if (!isset($this->detailArray['company_name']) && empty($this->detailArray['company_name'])) {
                    $i = $i - 1;
                }

                if (!isset($this->detailArray['type']) && empty($this->detailArray['type'])) {
                    $i = $i - 4;
                }

                $sheet->autoSize(true);
            }
        ];
    }
}
