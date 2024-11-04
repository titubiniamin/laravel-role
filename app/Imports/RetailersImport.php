<?php
namespace App\Imports;

use App\Models\Retailer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RetailersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if a retailer with the given 'id' exists
        $retailer = Retailer::find($row['id']);

        if ($retailer) {
            // If retailer exists, update only specific fields
            $retailer->update([
                'average_sales'     => $row['average_sales'],
                'market_size'       => $row['market_size'],
                'market_share'      => $row['market_share'],
                'competition_brand' => $row['competition_brand'],
            ]);
            return $retailer;
        } else {
            // If retailer doesn't exist, create a new record with all fields
            return Retailer::create([
                'id'                => $row['id'],
                'name'              => $row['name'],
                'owner_name'        => $row['owner_name'],
                'zone'              => $row['zone'],
                'retailer_code'     => $row['retailer_code'],
                'email'             => $row['email'],
                'website'           => $row['website'],
                'mobile'            => $row['mobile'],
                'address'           => $row['address'],
                'location'          => $row['location'],
                'district'          => $row['district'],
                'longitude'         => $row['longitude'],
                'latitude'          => $row['latitude'],
                'average_sales'     => $row['average_sales'],
                'market_size'       => $row['market_size'],
                'market_share'      => $row['market_share'],
                'competition_brand' => $row['competition_brand'],
            ]);
        }
    }
}
