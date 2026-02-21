<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\AccommodationImage;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UgandaAccommodationSeeder extends Seeder
{
    public function run(): void
    {
        /*
         |--------------------------------------------------------------------------
         | 1) Ensure Country: Uganda
         |--------------------------------------------------------------------------
         */
        $uganda = Country::firstOrCreate(
            ['name' => 'Uganda'],
            [
                'slug'      => 'uganda',
                'is_active' => true,
            ]
        );

        /*
         |--------------------------------------------------------------------------
         | 2) Ensure core Uganda destinations
         |--------------------------------------------------------------------------
         */
        $destinationsMap = [
            'Entebbe'                           => 'City / Arrival',
            'Murchison Falls National Park'     => 'Savannah Park',
            'Queen Elizabeth National Park'     => 'Savannah Park',
            'Bwindi Impenetrable National Park' => 'Forest / Gorilla Region',
        ];

        $destinations = [];
        foreach ($destinationsMap as $name => $type) {
            $destinations[$name] = Destination::firstOrCreate(
                ['name' => $name],
                [
                    'slug'       => Str::slug($name),
                    'type'       => $type,
                    'country_id' => $uganda->id,
                    'is_active'  => true,
                ]
            );
        }

        /*
         |--------------------------------------------------------------------------
         | 3) Define accommodations by category: budget / mid-range / high-end
         |--------------------------------------------------------------------------
         | Note: "category" is conceptual here; we encode it in the name/description
         | and especially in the price ranges.
         |--------------------------------------------------------------------------
         */

        // ---------- BUDGET ----------
        $budget = [
            [
                'name'        => 'Entebbe Budget Guesthouse',
                'type'        => 'Guesthouse',
                'location'    => 'Entebbe – residential area, short drive from airport',
                'destination' => 'Entebbe',
                'category'    => 'budget',
                'price_from'  => 40,
                'price_to'    => 70,
                'short_desc'  => 'Simple, clean guesthouse in Entebbe, ideal for budget-conscious travelers needing a safe and comfortable place before or after their safari.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/entebbe-budget-guesthouse.jpg',
                'gallery'     => [
                    'accommodations/gallery/entebbe-budget-1.jpg',
                    'accommodations/gallery/entebbe-budget-2.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    'Wi-Fi in public areas',
                    'Restaurant',
                    'Secure parking',
                    'Airport transfers',
                ],
                'sort_order'  => 10,
            ],
            [
                'name'        => 'Murchison Budget Camp',
                'type'        => 'Tented Camp',
                'location'    => 'Near Murchison Falls National Park gate',
                'destination' => 'Murchison Falls National Park',
                'category'    => 'budget',
                'price_from'  => 60,
                'price_to'    => 90,
                'short_desc'  => 'Affordable tented camp near Murchison Falls National Park, offering basic but comfortable tented rooms with shared or simple en-suite facilities.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/murchison-budget-camp.jpg',
                'gallery'     => [
                    'accommodations/gallery/murchison-budget-1.jpg',
                    'accommodations/gallery/murchison-budget-2.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    'Restaurant',
                    'Bar',
                    'Campfire area',
                    'Guided nature walks',
                ],
                'sort_order'  => 20,
            ],
            [
                'name'        => 'Queen Budget Lodge',
                'type'        => 'Lodge',
                'location'    => 'Outside Queen Elizabeth National Park',
                'destination' => 'Queen Elizabeth National Park',
                'category'    => 'budget',
                'price_from'  => 55,
                'price_to'    => 85,
                'short_desc'  => 'Budget-friendly lodge on the edge of Queen Elizabeth National Park with simple rooms and friendly staff, perfect for travelers focused on game drives rather than luxury.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/queen-budget-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/queen-budget-1.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    'Restaurant',
                    'Bar',
                    'Secure parking',
                ],
                'sort_order'  => 30,
            ],
            [
                'name'        => 'Bwindi Budget Lodge',
                'type'        => 'Lodge',
                'location'    => 'Near Bwindi Impenetrable National Park trailheads',
                'destination' => 'Bwindi Impenetrable National Park',
                'category'    => 'budget',
                'price_from'  => 70,
                'price_to'    => 100,
                'short_desc'  => 'Basic but cozy lodge close to Bwindi’s gorilla trekking start points, offering clean rooms and hot meals for budget travelers.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/bwindi-budget-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/bwindi-budget-1.jpg',
                    'accommodations/gallery/bwindi-budget-2.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    'Restaurant',
                    'Bar',
                    'Laundry service',
                ],
                'sort_order'  => 40,
            ],
        ];

        // ---------- MID-RANGE ----------
        $midrange = [
            [
                'name'        => 'Lake Victoria View Guesthouse',
                'type'        => 'Guesthouse',
                'location'    => 'Entebbe – quiet residential area with lake views',
                'destination' => 'Entebbe',
                'category'    => 'mid-range',
                'price_from'  => 70,
                'price_to'    => 110,
                'short_desc'  => 'Cozy mid-range guesthouse in Entebbe with views over Lake Victoria, en-suite rooms, and a relaxed garden atmosphere, ideal for arrivals and departures.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/lake-victoria-view.jpg',
                'gallery'     => [
                    'accommodations/gallery/lake-victoria-1.jpg',
                    'accommodations/gallery/lake-victoria-2.jpg',
                    'accommodations/gallery/lake-victoria-3.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    'Wi-Fi in public areas',
                    'Wi-Fi in rooms',
                    'Restaurant',
                    'Bar',
                    'Airport transfers',
                    'Secure parking',
                ],
                'sort_order'  => 50,
            ],
            [
                'name'        => 'Para Safari Lodge',
                'type'        => 'Lodge',
                'location'    => 'Inside Murchison Falls National Park, overlooking the Nile',
                'destination' => 'Murchison Falls National Park',
                'category'    => 'mid-range',
                'price_from'  => 180,
                'price_to'    => 260,
                'short_desc'  => 'Classic mid-range safari lodge inside Murchison Falls National Park with Nile views, spacious en-suite rooms, and a swimming pool, ideal for exploring Uganda’s largest park.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/para-safari-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/para-1.jpg',
                    'accommodations/gallery/para-2.jpg',
                    'accommodations/gallery/para-3.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    '24-hour electricity',
                    'Mosquito nets',
                    'Restaurant',
                    'Bar',
                    'Lounge',
                    'Swimming pool',
                    'Wi-Fi in public areas',
                    'Guided nature walks',
                ],
                'sort_order'  => 60,
            ],
            [
                'name'        => 'Kasese Plains Lodge',
                'type'        => 'Lodge',
                'location'    => 'Near Queen Elizabeth National Park',
                'destination' => 'Queen Elizabeth National Park',
                'category'    => 'mid-range',
                'price_from'  => 150,
                'price_to'    => 220,
                'short_desc'  => 'Mid-range lodge on the edge of Queen Elizabeth National Park with views over the savannah and Rwenzori foothills, offering comfortable rooms and easy access to game drives and boat safaris.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/kasese-plains-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/kasese-1.jpg',
                    'accommodations/gallery/kasese-2.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Hot showers',
                    'Restaurant',
                    'Bar',
                    'Wi-Fi in public areas',
                    'Swimming pool',
                    'Family rooms',
                    'Secure parking',
                ],
                'sort_order'  => 70,
            ],
            [
                'name'        => 'Bwindi Forest Camp',
                'type'        => 'Tented Camp',
                'location'    => 'Near Bwindi Impenetrable National Park, gorilla tracking area',
                'destination' => 'Bwindi Impenetrable National Park',
                'category'    => 'mid-range',
                'price_from'  => 190,
                'price_to'    => 260,
                'short_desc'  => 'Comfortable mid-range tented camp set in the forested hills close to Bwindi’s gorilla trekking trailheads, offering en-suite tents and an authentic rainforest atmosphere.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/bwindi-forest-camp.jpg',
                'gallery'     => [
                    'accommodations/gallery/bwindi-1.jpg',
                    'accommodations/gallery/bwindi-2.jpg',
                    'accommodations/gallery/bwindi-3.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Solar power',
                    'Hot showers',
                    'Mosquito nets',
                    'Restaurant',
                    'Bar',
                    'Campfire area',
                    'Guided nature walks',
                    'Laundry service',
                ],
                'sort_order'  => 80,
            ],
        ];

        // ---------- HIGH-END ----------
        $highend = [
            [
                'name'        => 'Entebbe Lakefront Boutique Hotel',
                'type'        => 'Hotel',
                'location'    => 'Entebbe – directly on Lake Victoria shoreline',
                'destination' => 'Entebbe',
                'category'    => 'high-end',
                'price_from'  => 220,
                'price_to'    => 350,
                'short_desc'  => 'Upscale boutique hotel on the shores of Lake Victoria with stylish rooms, fine dining, and spa services, perfect for a luxurious start or end to your safari.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/entebbe-lakefront-boutique.jpg',
                'gallery'     => [
                    'accommodations/gallery/entebbe-lakefront-1.jpg',
                    'accommodations/gallery/entebbe-lakefront-2.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Air conditioning',
                    'Wi-Fi in rooms',
                    'Restaurant',
                    'Bar',
                    'Spa / massage services',
                    'Swimming pool',
                    'Airport transfers',
                ],
                'sort_order'  => 90,
            ],
            [
                'name'        => 'Murchison Luxury Safari Lodge',
                'type'        => 'Lodge',
                'location'    => 'Inside Murchison Falls National Park, exclusive riverfront',
                'destination' => 'Murchison Falls National Park',
                'category'    => 'high-end',
                'price_from'  => 350,
                'price_to'    => 550,
                'short_desc'  => 'High-end safari lodge with spacious suites, private decks, and exceptional service on a quiet stretch of the Nile in Murchison Falls National Park.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/murchison-luxury-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/murchison-luxury-1.jpg',
                    'accommodations/gallery/murchison-luxury-2.jpg',
                    'accommodations/gallery/murchison-luxury-3.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Air conditioning',
                    'Wi-Fi in rooms',
                    'Restaurant',
                    'Bar',
                    'Lounge',
                    'Swimming pool',
                    'Spa / massage services',
                    'Laundry service',
                    'Guided nature walks',
                ],
                'sort_order'  => 100,
            ],
            [
                'name'        => 'Queen Luxury Hills Lodge',
                'type'        => 'Lodge',
                'location'    => 'Hills above Queen Elizabeth National Park',
                'destination' => 'Queen Elizabeth National Park',
                'category'    => 'high-end',
                'price_from'  => 320,
                'price_to'    => 480,
                'short_desc'  => 'Luxurious lodge set on a hillside with sweeping views over Queen Elizabeth National Park, offering elegant suites, fine dining, and a tranquil pool area.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/queen-luxury-hills-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/queen-luxury-1.jpg',
                    'accommodations/gallery/queen-luxury-2.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Air conditioning',
                    'Wi-Fi in rooms',
                    'Restaurant',
                    'Bar',
                    'Lounge',
                    'Swimming pool',
                    'Spa / massage services',
                    'Honeymoon suite',
                ],
                'sort_order'  => 110,
            ],
            [
                'name'        => 'Bwindi Luxury Forest Lodge',
                'type'        => 'Lodge',
                'location'    => 'Exclusive forest setting near Bwindi gorilla tracking trails',
                'destination' => 'Bwindi Impenetrable National Park',
                'category'    => 'high-end',
                'price_from'  => 400,
                'price_to'    => 650,
                'short_desc'  => 'High-end forest lodge offering spacious cottages, fireplaces, gourmet cuisine, and personalized service just a short drive from Bwindi’s gorilla tracking start points.',
                'full_desc'   => null,
                'featured'    => 'accommodations/featured/bwindi-luxury-forest-lodge.jpg',
                'gallery'     => [
                    'accommodations/gallery/bwindi-luxury-1.jpg',
                    'accommodations/gallery/bwindi-luxury-2.jpg',
                    'accommodations/gallery/bwindi-luxury-3.jpg',
                ],
                'amenities'   => [
                    'En-suite bathrooms',
                    'Fireplaces in rooms',
                    'Wi-Fi in public areas',
                    'Restaurant',
                    'Bar',
                    'Spa / massage services',
                    'Laundry service',
                    'Guided nature walks',
                    'Honeymoon suite',
                ],
                'sort_order'  => 120,
            ],
        ];

        /*
         |--------------------------------------------------------------------------
         | 4) Create or update accommodations & images
         |--------------------------------------------------------------------------
         */
        $all = array_merge($budget, $midrange, $highend);

        foreach ($all as $item) {
            $destination = $destinations[$item['destination']] ?? null;

            $accommodation = Accommodation::firstOrCreate(
                ['name' => $item['name']],
                [
                    'slug'             => Str::slug($item['name']) . '-' . Str::random(5),
                    'type'             => $item['type'],
                    'location'         => $item['location'],
                    'country_id'       => $uganda->id,
                    'destination_id'   => $destination?->id,
                    'currency'         => 'USD',
                    'price_from'       => $item['price_from'],
                    'price_to'         => $item['price_to'],
                    'short_description'=> $item['short_desc'],
                    'full_description' => $item['full_desc'],
                    'featured_image'   => $item['featured'],
                    'amenities'        => $item['amenities'],
                    'is_active'        => true,
                    'is_featured'      => $item['category'] === 'high-end', // e.g. feature high-end ones
                    'sort_order'       => $item['sort_order'],
                ]
            );

            // Gallery images
            if (!empty($item['gallery'])) {
                foreach ($item['gallery'] as $index => $path) {
                    AccommodationImage::firstOrCreate(
                        [
                            'accommodation_id' => $accommodation->id,
                            'path'             => $path,
                        ],
                        [
                            'caption'    => null,
                            'alt_text'   => $accommodation->name . ' image',
                            'is_featured'=> false,
                            'sort_order' => $index,
                        ]
                    );
                }
            }
        }
    }
}