<?php
namespace App\Imports;

use App\Models\Retailer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RetailersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Use 'id' or 'dealer_code' to check if the dealer already exists
        return Retailer::updateOrCreate(
            [
                'id' => $row['id'], // Assuming dealer_code is unique
            ],
            [
                'name'                => $row['name'],
                'owner_name'          => $row['owner_name'],
                'zone'                => $row['zone'],
                'retailer_code'         => $row['retailer_code'],
                'email'               => $row['email'],
                'website'             => $row['website'],
                'mobile'              => $row['mobile'],
                'address'             => $row['address'],
                'location'            => $row['location'],
                'longitude'           => $row['longitude'],
                'latitude'            => $row['latitude'],
                'average_sales'       => $row['average_sales'],
                'market_size'         => $row['market_size'],
                'market_share'        => $row['market_share'],
                'competition_brand'    => $row['competition_brand'],
            ]
        );
    }
}
