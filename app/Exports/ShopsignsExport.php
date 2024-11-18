<?php
namespace App\Exports;

use App\Models\Shopsign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ShopsignsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return a collection of all billboards
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Shopsign::all(); // Get all billboards
    }

    /**
     * Define headings for the Excel file
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Size',
            'Type',
            'Brand',
            'Location',
            'Start Date',
            'End Date',
            'Image URL',
        ];
    }

    /**
     * Map each row of the data to the Excel file format
     *
     * @param  \App\Models\Shopsign  $highwall
     * @return array
     */
    public function map($highwall): array
    {
        $typeLabel = '';
        if ($highwall->type === 'single_side') {
            $typeLabel = 'Single Side';
        } elseif ($highwall->type === 'unipool') {
            $typeLabel = 'Unipool';
        } elseif ($highwall->type === 'neon') {
            $typeLabel = 'Neon';
        }
        if($highwall->brand === 'fresh_super_cement'){
            $brandLabel = 'Fresh Super Cement';
        }elseif($highwall->brand === 'dhalai_special_cement'){
            $brandLabel = 'Dhalai Special Cement';
        }elseif($highwall->brand === 'meghnacem_delux_cement'){
            $brandLabel = 'Meghnace Delux Cement';
        }

        return [
            $highwall->id,
            $highwall->name,
            $highwall->size,
            $typeLabel, // Mapped type value
            $brandLabel,
            $highwall->location,
            $highwall->start_date,
            $highwall->end_date,
            asset('storage/' . $highwall->image),  // Image URL
        ];
    }
}
