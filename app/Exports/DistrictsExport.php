<?php
namespace App\Exports;

use App\Models\Dealer;
use App\Models\District;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DistrictsExport implements FromCollection, WithHeadings, WithStyles
{
    // Define the collection to be exported
    public function collection()
    {
        return District::select(
            'id',
            'name',
            'average_sales',
            'market_size',
            'market_share',
            'competition_brand',
            'total_outlets',
            'own_outlets',
            'coverage',
            'location',
            'district',
            'longitude',
            'latitude',
        )->get();
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Average Sales',
            'Market Size',
            'Market Share',
            'Competition Brand',
            'Total Outlets',
            'Own Outlets',
            'Coverage',
            'Location',
            'District',
            'Longitude',
            'Latitude',

        ];
    }

    // Define the styles for the worksheet
    public function styles(Worksheet $sheet)
    {
        // Set widths for columns
        $sheet->getColumnDimension('A')->setWidth(7);   // ID
        $sheet->getColumnDimension('B')->setWidth(15);  // Name
        $sheet->getColumnDimension('C')->setWidth(10);  // Average Sales
        $sheet->getColumnDimension('D')->setWidth(10);  // Market Size
        $sheet->getColumnDimension('E')->setWidth(10);  // Market Share
        $sheet->getColumnDimension('F')->setWidth(10);  // Competition Brand
        $sheet->getColumnDimension('G')->setWidth(10);  // total outlets
        $sheet->getColumnDimension('H')->setWidth(10);  // own outlets
        $sheet->getColumnDimension('I')->setWidth(10);  // coverage
        $sheet->getColumnDimension('J')->setWidth(15);  // Location
        $sheet->getColumnDimension('K')->setWidth(10);  // Longitude
        $sheet->getColumnDimension('L')->setWidth(10);  // Latitude
        $sheet->getColumnDimension('M')->setWidth(10);  // Latitude


        // Enable text wrapping for all columns
        foreach (range('A', 'Q') as $column) {
            $sheet->getStyle($column)->getAlignment()->setWrapText(true);
        }
    }
}
