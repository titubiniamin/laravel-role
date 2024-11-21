<?php
namespace App\Exports;

use App\Models\Shopsign;
use Illuminate\Support\Facades\DB;
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
//        return Shopsign::all(); // Get all billboards
        return DB::table('shopsigns')
            ->selectRaw('
            shopsigns.*,
            central_points.latitude as central_lat,
            central_points.longitude as central_lng,
            (
            6371 * ACOS(
                        COS(RADIANS(central_points.latitude)) * COS(RADIANS(shopsigns.latitude)) *
                        COS(RADIANS(shopsigns.longitude) - RADIANS(central_points.longitude)) +
                        SIN(RADIANS(central_points.latitude)) * SIN(RADIANS(shopsigns.latitude))
                    )
            ) as distance
            ')
            ->join('central_points', 'central_points.district','=','shopsigns.district')
            ->get();
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
            'District',
            'Start Date',
            'End Date',
            'Image URL',
            'Distance from Central Point(km)'
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
            $highwall->district,
            $highwall->start_date,
            $highwall->end_date,
            asset('storage/' . $highwall->image),  // Image URL
            round($highwall->distance,2),
        ];
    }
}
