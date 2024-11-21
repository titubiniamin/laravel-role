<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillboardsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return a collection of billboards with distances from central points.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('billboards')
            ->selectRaw('
                billboards.*,
                central_points.latitude AS central_lat,
                central_points.longitude AS central_lng,
                (
                    6371 * ACOS(
                        COS(RADIANS(central_points.latitude)) * COS(RADIANS(billboards.latitude)) *
                        COS(RADIANS(billboards.longitude) - RADIANS(central_points.longitude)) +
                        SIN(RADIANS(central_points.latitude)) * SIN(RADIANS(billboards.latitude))
                    )
                ) AS distance
            ')
            ->join('central_points', 'billboards.district', '=', 'central_points.district')
            ->get();
    }

    /**
     * Define headings for the Excel file.
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
            'Distance from Central Point (km)', // New column for distance
        ];
    }

    /**
     * Map each row of the data to the Excel file format.
     *
     * @param  \stdClass  $billboard
     * @return array
     */
    public function map($billboard): array
    {
        $typeLabel = match ($billboard->type) {
            'single_side' => 'Single Side',
            'unipool' => 'Unipool',
            'neon' => 'Neon',
            default => 'Unknown',
        };

        $brandLabel = match ($billboard->brand) {
            'fresh_super_cement' => 'Fresh Super Cement',
            'dhalai_special_cement' => 'Dhalai Special Cement',
            'meghnacem_delux_cement' => 'Meghnacem Deluxe Cement',
            default => 'Unknown',
        };

        return [
            $billboard->id,
            $billboard->name,
            $billboard->size,
            $typeLabel,
            $brandLabel,
            $billboard->location,
            $billboard->district,
            $billboard->start_date,
            $billboard->end_date,
            asset('storage/' . $billboard->image),
            round($billboard->distance, 2), // Rounded distance in kilometers
        ];
    }
}
