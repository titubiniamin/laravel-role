<?php
namespace App\Exports;

use App\Models\Highwall;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HighwallsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return a collection of all billboards
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Highwall::all(); // Get all billboards
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
     * @param  \App\Models\Highwall  $highwall
     * @return array
     */
    public function map($highwall): array
    {
        $typeLabel = '';
        if ($highwall->type === 'high_raise') {
            $typeLabel = 'High Raise';
        } elseif ($highwall->type === 'cold_store') {
            $typeLabel = 'Cold Store';
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
            $typeLabel, // Mapped type value
            $brandLabel,
            $highwall->location,
            $highwall->start_date,
            $highwall->end_date,
            asset('storage/' . $highwall->image),  // Image URL
        ];
    }
}
