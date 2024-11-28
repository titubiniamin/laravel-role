<?php
namespace App\Exports;

use App\Models\Retailer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RetailersExport implements FromCollection, WithHeadings, WithStyles
{
    // Define the collection to be exported
    public function collection()
    {
        return Retailer::select(
            'id',
            'name',
            'owner_name',
            'zone',
            'retailer_code',
            'email',
            'website',
            'mobile',
            'address',
            'location',
            'district',
            'longitude',
            'latitude',
            'average_sales',
            'market_size',
            'market_share',
            'competition_brand'
        )->get();
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Owner Name',
            'Zone',
            'Retailer Code',
            'Email',
            'Website',
            'Mobile',
            'Address',
            'Location',
            'District',
            'Longitude',
            'Latitude',
            'Average Sales',
            'Market Size',
            'Market Share',
            'Competition Brand',
        ];
    }

    // Define the styles for the worksheet
    public function styles(Worksheet $sheet)
    {
        // Set widths for columns
        $sheet->getColumnDimension('A')->setWidth(7);   // ID
        $sheet->getColumnDimension('B')->setWidth(15);  // Name
        $sheet->getColumnDimension('C')->setWidth(15);  // Owner Name
        $sheet->getColumnDimension('D')->setWidth(15);  // Zone
        $sheet->getColumnDimension('E')->setWidth(10);  // Retailer Code
        $sheet->getColumnDimension('F')->setWidth(20);  // Email
        $sheet->getColumnDimension('G')->setWidth(20);  // Website
        $sheet->getColumnDimension('H')->setWidth(15);  // Mobile
        $sheet->getColumnDimension('I')->setWidth(30);  // Address
        $sheet->getColumnDimension('J')->setWidth(25);  // Location
        $sheet->getColumnDimension('K')->setWidth(25);  // District
        $sheet->getColumnDimension('L')->setWidth(10);  // Longitude
        $sheet->getColumnDimension('M')->setWidth(10);  // Latitude
        $sheet->getColumnDimension('N')->setWidth(10);  // Average Sales
        $sheet->getColumnDimension('O')->setWidth(10);  // Market Size
        $sheet->getColumnDimension('P')->setWidth(10);  // Market Share
        $sheet->getColumnDimension('Q')->setWidth(10);  // Competition Brand

        // Enable text wrapping for all columns
        foreach (range('A', 'Q') as $column) {
            $sheet->getStyle($column)->getAlignment()->setWrapText(true);
        }
    }
}
