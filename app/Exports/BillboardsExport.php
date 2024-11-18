<?php
namespace App\Exports;

use App\Models\Billboard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillboardsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return a collection of all billboards
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Billboard::all(); // Get all billboards
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
     * @param  \App\Models\Billboard  $billboard
     * @return array
     */
    public function map($billboard): array
    {
        $typeLabel = '';
        if ($billboard->type === 'single_side') {
            $typeLabel = 'Single Side';
        } elseif ($billboard->type === 'unipool') {
            $typeLabel = 'Unipool';
        } elseif ($billboard->type === 'neon') {
            $typeLabel = 'Neon';
        }
        if($billboard->brand === 'fresh_super_cement'){
            $brandLabel = 'Fresh Super Cement';
        }elseif($billboard->brand === 'dhalai_special_cement'){
            $brandLabel = 'Dhalai Special Cement';
        }elseif($billboard->brand === 'meghnacem_delux_cement'){
            $brandLabel = 'Meghnace Delux Cement';
        }

        return [
            $billboard->id,
            $billboard->name,
            $billboard->size,
            $typeLabel, // Mapped type value
            $brandLabel,
            $billboard->location,
            $billboard->start_date,
            $billboard->end_date,
            asset('storage/' . $billboard->image),  // Image URL
        ];
    }
}
