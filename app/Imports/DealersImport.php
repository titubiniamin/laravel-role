<?php

namespace App\Imports;

use App\Models\Dealer;
use App\Models\Retailer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DealersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Use 'id' or 'dealer_code' to check if the dealer already exists
        $dealer = Dealer::find($row['id']);

        if ($dealer) {
            // If retailer exists, update only specific fields
            $dealer->update([
                'average_sales' => $row['average_sales'],
                'market_size' => $row['market_size'],
                'market_share' => $row['market_share'],
                'competition_brand' => $row['competition_brand'],
            ]);
            return $dealer;
        } else {
            // If retailer doesn't exist, create a new record with all fields
            return Dealer::create([
                'id' => $row['id'],
                'name' => $row['name'],
                'owner_name' => $row['owner_name'],
                'zone' => $row['zone'],
                'dealer_code' => $row['dealer_code'],
                'email' => $row['email'],
                'website' => $row['website'],
                'mobile' => $row['mobile'],
                'address' => $row['address'],
                'location' => $row['location'],
                'district' => $row['district'],
                'longitude' => $row['longitude'],
                'latitude' => $row['latitude'],
                'average_sales' => $row['average_sales'],
                'market_size' => $row['market_size'],
                'market_share' => $row['market_share'],
                'competition_brand' => $row['competition_brand'],
            ]);
        }
    }
}
