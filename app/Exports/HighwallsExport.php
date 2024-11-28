<?php
namespace App\Exports;

use App\Models\Highwall;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HighwallsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return a collection of all highwalls
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
//        return Highwall::all(); // Get all highwalls
        return DB::table('highwalls')
            ->selectRaw('
            highwalls.*,
            central_points.latitude as central_lat,
            central_points.longitude as central_lng,
            (
                    6371 * ACOS(
                        COS(RADIANS(central_points.latitude)) * COS(RADIANS(highwalls.latitude)) *
                        COS(RADIANS(highwalls.longitude) - RADIANS(central_points.longitude)) +
                        SIN(RADIANS(central_points.latitude)) * SIN(RADIANS(highwalls.latitude))
                    )
                ) AS distance
            ')
            ->join('central_points', 'highwalls.district', '=', 'central_points.district')
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
            'Type',
            'Brand',
            'Location',
            'District',
            'Start Date',
            'End Date',
            'Image URL',
            'Distance from Central Point(km)',
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
            $highwall->district,
            $highwall->start_date,
            $highwall->end_date,
            asset('storage/' . $highwall->image),  // Image URL
            round($highwall->distance,2)
        ];
    }
}
