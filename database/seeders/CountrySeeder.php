<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Algeria', 'code' => 'DZ', 'phone_code' => '+213', 'currency_code' => 'DZD'],
            ['name' => 'Angola', 'code' => 'AO', 'phone_code' => '+244', 'currency_code' => 'AOA'],
            ['name' => 'Benin', 'code' => 'BJ', 'phone_code' => '+229', 'currency_code' => 'XOF'],
            ['name' => 'Botswana', 'code' => 'BW', 'phone_code' => '+267', 'currency_code' => 'BWP'],
            ['name' => 'Burkina Faso', 'code' => 'BF', 'phone_code' => '+226', 'currency_code' => 'XOF'],
            ['name' => 'Burundi', 'code' => 'BI', 'phone_code' => '+257', 'currency_code' => 'BIF'],
            ['name' => 'Cabo Verde', 'code' => 'CV', 'phone_code' => '+238', 'currency_code' => 'CVE'],
            ['name' => 'Cameroon', 'code' => 'CM', 'phone_code' => '+237', 'currency_code' => 'XAF'],
            ['name' => 'Central African Republic', 'code' => 'CF', 'phone_code' => '+236', 'currency_code' => 'XAF'],
            ['name' => 'Chad', 'code' => 'TD', 'phone_code' => '+235', 'currency_code' => 'XAF'],
            ['name' => 'Comoros', 'code' => 'KM', 'phone_code' => '+269', 'currency_code' => 'KMF'],
            ['name' => 'Congo', 'code' => 'CG', 'phone_code' => '+242', 'currency_code' => 'XAF'],
            ['name' => 'Côte d\'Ivoire', 'code' => 'CI', 'phone_code' => '+225', 'currency_code' => 'XOF'],
            ['name' => 'Democratic Republic of the Congo', 'code' => 'CD', 'phone_code' => '+243', 'currency_code' => 'CDF'],
            ['name' => 'Djibouti', 'code' => 'DJ', 'phone_code' => '+253', 'currency_code' => 'DJF'],
            ['name' => 'Egypt', 'code' => 'EG', 'phone_code' => '+20', 'currency_code' => 'EGP'],
            ['name' => 'Equatorial Guinea', 'code' => 'GQ', 'phone_code' => '+240', 'currency_code' => 'XAF'],
            ['name' => 'Eritrea', 'code' => 'ER', 'phone_code' => '+291', 'currency_code' => 'ERN'],
            ['name' => 'Eswatini', 'code' => 'SZ', 'phone_code' => '+268', 'currency_code' => 'SZL'],
            ['name' => 'Ethiopia', 'code' => 'ET', 'phone_code' => '+251', 'currency_code' => 'ETB'],
            ['name' => 'Gabon', 'code' => 'GA', 'phone_code' => '+241', 'currency_code' => 'XAF'],
            ['name' => 'Gambia', 'code' => 'GM', 'phone_code' => '+220', 'currency_code' => 'GMD'],
            ['name' => 'Ghana', 'code' => 'GH', 'phone_code' => '+233', 'currency_code' => 'GHS'],
            ['name' => 'Guinea', 'code' => 'GN', 'phone_code' => '+224', 'currency_code' => 'GNF'],
            ['name' => 'Guinea-Bissau', 'code' => 'GW', 'phone_code' => '+245', 'currency_code' => 'XOF'],
            ['name' => 'Kenya', 'code' => 'KE', 'phone_code' => '+254', 'currency_code' => 'KES'],
            ['name' => 'Lesotho', 'code' => 'LS', 'phone_code' => '+266', 'currency_code' => 'LSL'],
            ['name' => 'Liberia', 'code' => 'LR', 'phone_code' => '+231', 'currency_code' => 'LRD'],
            ['name' => 'Libya', 'code' => 'LY', 'phone_code' => '+218', 'currency_code' => 'LYD'],
            ['name' => 'Madagascar', 'code' => 'MG', 'phone_code' => '+261', 'currency_code' => 'MGA'],
            ['name' => 'Malawi', 'code' => 'MW', 'phone_code' => '+265', 'currency_code' => 'MWK'],
            ['name' => 'Mali', 'code' => 'ML', 'phone_code' => '+223', 'currency_code' => 'XOF'],
            ['name' => 'Mauritania', 'code' => 'MR', 'phone_code' => '+222', 'currency_code' => 'MRU'],
            ['name' => 'Mauritius', 'code' => 'MU', 'phone_code' => '+230', 'currency_code' => 'MUR'],
            ['name' => 'Morocco', 'code' => 'MA', 'phone_code' => '+212', 'currency_code' => 'MAD'],
            ['name' => 'Mozambique', 'code' => 'MZ', 'phone_code' => '+258', 'currency_code' => 'MZN'],
            ['name' => 'Namibia', 'code' => 'NA', 'phone_code' => '+264', 'currency_code' => 'NAD'],
            ['name' => 'Niger', 'code' => 'NE', 'phone_code' => '+227', 'currency_code' => 'XOF'],
            ['name' => 'Nigeria', 'code' => 'NG', 'phone_code' => '+234', 'currency_code' => 'NGN'],
            ['name' => 'Rwanda', 'code' => 'RW', 'phone_code' => '+250', 'currency_code' => 'RWF'],
            ['name' => 'São Tomé and Príncipe', 'code' => 'ST', 'phone_code' => '+239', 'currency_code' => 'STN'],
            ['name' => 'Senegal', 'code' => 'SN', 'phone_code' => '+221', 'currency_code' => 'XOF'],
            ['name' => 'Seychelles', 'code' => 'SC', 'phone_code' => '+248', 'currency_code' => 'SCR'],
            ['name' => 'Sierra Leone', 'code' => 'SL', 'phone_code' => '+232', 'currency_code' => 'SLE'],
            ['name' => 'Somalia', 'code' => 'SO', 'phone_code' => '+252', 'currency_code' => 'SOS'],
            ['name' => 'South Africa', 'code' => 'ZA', 'phone_code' => '+27', 'currency_code' => 'ZAR'],
            ['name' => 'South Sudan', 'code' => 'SS', 'phone_code' => '+211', 'currency_code' => 'SSP'],
            ['name' => 'Sudan', 'code' => 'SD', 'phone_code' => '+249', 'currency_code' => 'SDG'],
            ['name' => 'Tanzania', 'code' => 'TZ', 'phone_code' => '+255', 'currency_code' => 'TZS'],
            ['name' => 'Togo', 'code' => 'TG', 'phone_code' => '+228', 'currency_code' => 'XOF'],
            ['name' => 'Tunisia', 'code' => 'TN', 'phone_code' => '+216', 'currency_code' => 'TND'],
            ['name' => 'Uganda', 'code' => 'UG', 'phone_code' => '+256', 'currency_code' => 'UGX'],
            ['name' => 'Zambia', 'code' => 'ZM', 'phone_code' => '+260', 'currency_code' => 'ZMW'],
            ['name' => 'Zimbabwe', 'code' => 'ZW', 'phone_code' => '+263', 'currency_code' => 'ZWL'],
            // Major global hubs for diaspora / partnerships
            ['name' => 'United Kingdom', 'code' => 'GB', 'phone_code' => '+44', 'currency_code' => 'GBP', 'is_active' => true],
            ['name' => 'United States', 'code' => 'US', 'phone_code' => '+1', 'currency_code' => 'USD', 'is_active' => true],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                array_merge(['is_active' => true], $country)
            );
        }
    }
}
