<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\BodyStyle;
use App\Models\Type;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\Trim;
use App\Models\VehicleStatus;

use Database\Factories\BrandFactory;
use Database\Factories\CarModelFactory;
use Database\Factories\BodyStyleFactory;
use Database\Factories\TypeFactory;
use Database\Factories\TransmissionTypeFactory;
use Database\Factories\DriveTypeFactory;
use Database\Factories\EngineTypeFactory;
use Database\Factories\TrimFactory;
use Database\Factories\VehicleStatusFactory;

class LookupSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBrandsModels();

        $this->seedBodyStyles();

        $this->seedTypes();

        foreach (TransmissionTypeFactory::$transmissionTypes as $item) {
            TransmissionType::firstOrCreate(['name' => $item]);
        }

        foreach (DriveTypeFactory::$models as $item) {
            DriveType::firstOrCreate([
                'name' => [
                    'en' => $item,
                    'ar' => $item
                ]
            ]);
        }

        foreach (EngineTypeFactory::$engineTypes as $item) {
            EngineType::firstOrCreate([
                'name' => [
                    'en' => $item,
                    'ar' => $item
                ]
            ]);
        }

        foreach (TrimFactory::$trims as $item) {
            Trim::firstOrCreate(['name' => $item]);
        }

        foreach (VehicleStatusFactory::$statuses as $item) {
            VehicleStatus::firstOrCreate(['name' => $item]);
        }
    }

    public function seedBrandsModels()
    {
        $carModelBrandMap = [
            'Corolla' => ['Toyota','تويتا', 'brands/Toyota.png'],
            'Civic' => ['Honda','هوندا', 'brands/Honda.png'],
            'Mustang' => ['Ford','فورد', 'brands/Ford.png'],
            'Camaro' => ['Chevrolet','تشيفورلي', 'brands/Chevrolet.png'],
            'Tucson' => ['Hyundai','هوندا', 'brands/Hyundai.png'],
            'Model 3' => ['Tesla','تيسلا', 'brands/Tesla.png'],
            'CX-5' => ['Mazda','مازدا', 'brands/Mazda.png'],
            'Altima' => ['Nissan','نيسان', 'brands/Nissan.png'],
            'X5' => ['BMW','بم', 'brands/BMW.png'],
            'A4' => ['Audi','أودي', 'brands/Audi.png'],
            'Wrangler' => ['Jeep','جيب', 'brands/Jeep.png'],
            'Golf' => ['Volkswagen','فولكswagen', 'brands/Volkswagen.png'],
            '500' => ['Fiat','فيت', 'brands/Fiat.png'],
            'Clio' => ['Renault','رينالوت', 'brands/Renault.png'],
            'Mitsubishi Outlander' => ['Mitsubishi','ميتسبوبي', 'brands/Mitsubishi.png'],
            'Skoda' => ['Skoda','سكودا', 'brands/Skoda.png'],
            'Seat' => ['Seat','سيت', 'brands/Seat.png'],
            'Peugeot' => ['Peugeot','بيوتوغ', 'brands/Peugeot.png'],
            'MG' => ['MG','ميج', 'brands/MG.png'],
            'Kia' => ['Kia','كيا', 'brands/Kia.png'],
            'Citroen' => ['Citroen','سيترون', 'brands/Citroen.png']
        ];

        $brandIds = [];

        // Create brands if they don't exist, or update image if necessary
        foreach ($carModelBrandMap as $modelName => [$brandName_en,$brandName_ar, $brandImage]) {
            $brand = Brand::firstOrCreate(
                ['name' =>[
                    'en' => $brandName_en,
                    'ar' => $brandName_ar
                ]],
                ['image' => $brandImage] // Create with image if not exists
            );

            // If the brand exists but the image is different, update it
            if ($brand->image !== $brandImage) {
                $brand->update(['image' => $brandImage]);
            }

            // Store brand ID
            $brandIds[$brandName_en] = $brand->id;
        }

        // Create car models with the corresponding brand_id
        foreach ($carModelBrandMap as $modelName => [$brandName_en, $brandName_ar, $brandImage]) {
            CarModel::firstOrCreate([
                'name' => [
                    'en' => $modelName,
                    'ar' => $modelName
                ],
                'brand_id' => $brandIds[$brandName_en],
            ]);
        }
    }

    private function seedBodyStyles()
    {
        $bodyStyles = [
            ['ar' => 'سيدان', 'en' => 'Sedan'],
            ['ar' => 'دفع رباعي', 'en' => 'SUV'],
            ['ar' => 'شاحنة', 'en' => 'Truck'],
            ['ar' => 'فان', 'en' => 'Van'],
            ['ar' => 'كوبيه', 'en' => 'Coupe'],
            ['ar' => 'هاتشباك', 'en' => 'Hatchback'],
            ['ar' => 'كابريوليه', 'en' => 'Convertible'],
            ['ar' => 'ستيشن واجن', 'en' => 'Wagon'],
            ['ar' => 'مينيفان', 'en' => 'Minivan'],
            ['ar' => 'بيك أب', 'en' => 'Pickup']
        ];

        foreach ($bodyStyles as $style) {
            \App\Models\BodyStyle::firstOrCreate(
                ['name->en' => $style['en']],
                ['name' => $style]
            );
        }
    }

    private function seedTypes()
    {
        $types = [
            ['en' => 'Sedan', 'ar' => 'سيدان'],
            ['en' => 'SUV', 'ar' => 'دفع رباعي'],
            ['en' => 'Truck', 'ar' => 'شاحنة'],
            ['en' => 'Coupe', 'ar' => 'كوبيه'],
            ['en' => 'Convertible', 'ar' => 'كابريوليه'],
            ['en' => 'Hatchback', 'ar' => 'هاتشباك'],
            ['en' => 'Minivan', 'ar' => 'ميني فان'],
            ['en' => 'Van', 'ar' => 'فان'],
            ['en' => 'Crossover', 'ar' => 'كروس أوفر'],
            ['en' => 'Wagon', 'ar' => 'وايقن'],
            ['en' => 'Pickup', 'ar' => 'بيك أب'],
            ['en' => 'Electric', 'ar' => 'كهربائي'],
            ['en' => 'Hybrid', 'ar' => 'هجين']
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(
                ['name->en' => $type['en']],
                ['name' => $type]
            );
        }
    }
}
