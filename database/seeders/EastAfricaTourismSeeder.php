<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Support\Str;

class EastAfricaTourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Activity Categories first
        $categories = [
            [
                'name' => 'Wildlife Safari',
                'slug' => 'wildlife-safari',
                'description' => 'Experience thrilling wildlife encounters and game drives',
                'icon' => 'fas fa-paw',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Adventure Sports',
                'slug' => 'adventure-sports',
                'description' => 'Adrenaline-pumping outdoor adventures',
                'icon' => 'fas fa-hiking',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Cultural Experience',
                'slug' => 'cultural-experience',
                'description' => 'Immerse in local traditions and communities',
                'icon' => 'fas fa-users',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Water Activities',
                'slug' => 'water-activities',
                'description' => 'Beach, diving, and water-based adventures',
                'icon' => 'fas fa-water',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Nature & Scenery',
                'slug' => 'nature-scenery',
                'description' => 'Explore breathtaking landscapes and natural wonders',
                'icon' => 'fas fa-mountain',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ActivityCategory::create($category);
        }

        $wildlifeSafari = ActivityCategory::where('slug', 'wildlife-safari')->first();
        $adventureSports = ActivityCategory::where('slug', 'adventure-sports')->first();
        $culturalExperience = ActivityCategory::where('slug', 'cultural-experience')->first();
        $waterActivities = ActivityCategory::where('slug', 'water-activities')->first();
        $natureScenery = ActivityCategory::where('slug', 'nature-scenery')->first();

        // ========================
        // KENYA
        // ========================
        $kenya = Country::create([
            'name' => 'Kenya',
            'code' => 'KE',
            'flag_icon' => '🇰🇪',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Kenya Destinations
        $kenyaDestinations = [
            [
                'name' => 'Maasai Mara National Reserve',
                'slug' => 'maasai-mara',
                'description' => 'World-renowned for the Great Migration and abundant wildlife including lions, elephants, and cheetahs.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Amboseli National Park',
                'slug' => 'amboseli',
                'description' => 'Famous for its large elephant herds and stunning views of Mount Kilimanjaro.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Lake Nakuru National Park',
                'slug' => 'lake-nakuru',
                'description' => 'Known for thousands of flamingos and rhino sanctuary.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Diani Beach',
                'slug' => 'diani-beach',
                'description' => 'Pristine white sand beaches and crystal-clear waters on the Indian Ocean coast.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tsavo National Parks',
                'slug' => 'tsavo',
                'description' => 'One of the largest national parks in the world, famous for red elephants.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Mount Kenya',
                'slug' => 'mount-kenya',
                'description' => 'Africa\'s second-highest mountain, perfect for hiking and climbing.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Samburu National Reserve',
                'slug' => 'samburu',
                'description' => 'Home to unique wildlife species and the Samburu people.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        $kenyaDestinationModels = [];
        foreach ($kenyaDestinations as $dest) {
            $kenyaDestinationModels[] = $kenya->destinations()->create($dest);
        }

        // Kenya Activities
        $kenyaActivities = [
            [
                'name' => 'Big Five Safari',
                'slug' => 'big-five-safari-kenya',
                'description' => 'Spot lions, elephants, buffalos, leopards, and rhinos in their natural habitat.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $kenyaDestinationModels[0]->id, // Maasai Mara
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Great Migration Viewing',
                'slug' => 'great-migration-kenya',
                'description' => 'Witness millions of wildebeest crossing the Mara River.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $kenyaDestinationModels[0]->id,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Hot Air Balloon Safari',
                'slug' => 'hot-air-balloon-kenya',
                'description' => 'Float over the Maasai Mara at sunrise for breathtaking views.',
                'category_id' => $adventureSports->id,
                'destination_id' => $kenyaDestinationModels[0]->id,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Maasai Village Visit',
                'slug' => 'maasai-village-kenya',
                'description' => 'Experience traditional Maasai culture, dances, and customs.',
                'category_id' => $culturalExperience->id,
                'destination_id' => $kenyaDestinationModels[0]->id,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Snorkeling & Diving',
                'slug' => 'snorkeling-diving-kenya',
                'description' => 'Explore vibrant coral reefs and marine life.',
                'category_id' => $waterActivities->id,
                'destination_id' => $kenyaDestinationModels[3]->id, // Diani Beach
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Mount Kenya Trekking',
                'slug' => 'mount-kenya-trekking',
                'description' => 'Summit Africa\'s second-highest peak.',
                'category_id' => $adventureSports->id,
                'destination_id' => $kenyaDestinationModels[5]->id, // Mount Kenya
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Bird Watching',
                'slug' => 'bird-watching-kenya',
                'description' => 'Spot flamingos and over 400 bird species.',
                'category_id' => $natureScenery->id,
                'destination_id' => $kenyaDestinationModels[2]->id, // Lake Nakuru
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($kenyaActivities as $activity) {
            Activity::create($activity);
        }

        // ========================
        // TANZANIA
        // ========================
        $tanzania = Country::create([
            'name' => 'Tanzania',
            'code' => 'TZ',
            'flag_icon' => '🇹🇿',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Tanzania Destinations
        $tanzaniaDestinations = [
            [
                'name' => 'Serengeti National Park',
                'slug' => 'serengeti',
                'description' => 'Iconic safari destination famous for the Great Migration and vast plains.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ngorongoro Crater',
                'slug' => 'ngorongoro-crater',
                'description' => 'World\'s largest inactive volcanic caldera with dense wildlife population.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mount Kilimanjaro',
                'slug' => 'mount-kilimanjaro',
                'description' => 'Africa\'s highest mountain and the world\'s tallest free-standing peak.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Zanzibar Island',
                'slug' => 'zanzibar',
                'description' => 'Exotic spice island with pristine beaches and rich Swahili culture.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tarangire National Park',
                'slug' => 'tarangire',
                'description' => 'Famous for large elephant herds and ancient baobab trees.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Lake Manyara National Park',
                'slug' => 'lake-manyara',
                'description' => 'Known for tree-climbing lions and diverse ecosystems.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Selous Game Reserve',
                'slug' => 'selous',
                'description' => 'One of Africa\'s largest wildlife reserves with boat safaris.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        $tanzaniaDestinationModels = [];
        foreach ($tanzaniaDestinations as $dest) {
            $tanzaniaDestinationModels[] = $tanzania->destinations()->create($dest);
        }

        // Tanzania Activities
        $tanzaniaActivities = [
            [
                'name' => 'Serengeti Safari',
                'slug' => 'serengeti-safari',
                'description' => 'Experience the world\'s most famous wildlife spectacle.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $tanzaniaDestinationModels[0]->id, // Serengeti
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kilimanjaro Climbing',
                'slug' => 'kilimanjaro-climbing',
                'description' => 'Summit the Roof of Africa via multiple routes.',
                'category_id' => $adventureSports->id,
                'destination_id' => $tanzaniaDestinationModels[2]->id, // Kilimanjaro
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Ngorongoro Crater Drive',
                'slug' => 'ngorongoro-crater-drive',
                'description' => 'Descend into the crater for incredible wildlife viewing.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $tanzaniaDestinationModels[1]->id, // Ngorongoro
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Spice Tour',
                'slug' => 'spice-tour-zanzibar',
                'description' => 'Discover Zanzibar\'s famous spice plantations.',
                'category_id' => $culturalExperience->id,
                'destination_id' => $tanzaniaDestinationModels[3]->id, // Zanzibar
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Dhow Cruise',
                'slug' => 'dhow-cruise-zanzibar',
                'description' => 'Sail on traditional Arab sailing vessels at sunset.',
                'category_id' => $waterActivities->id,
                'destination_id' => $tanzaniaDestinationModels[3]->id,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Stone Town Walking Tour',
                'slug' => 'stone-town-tour',
                'description' => 'Explore UNESCO World Heritage Site with rich history.',
                'category_id' => $culturalExperience->id,
                'destination_id' => $tanzaniaDestinationModels[3]->id,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Boat Safari',
                'slug' => 'boat-safari-selous',
                'description' => 'Unique water-based game viewing experience.',
                'category_id' => $waterActivities->id,
                'destination_id' => $tanzaniaDestinationModels[6]->id, // Selous
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($tanzaniaActivities as $activity) {
            Activity::create($activity);
        }

        // ========================
        // UGANDA
        // ========================
        $uganda = Country::create([
            'name' => 'Uganda',
            'code' => 'UG',
            'flag_icon' => '🇺🇬',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Uganda Destinations
        $ugandaDestinations = [
            [
                'name' => 'Bwindi Impenetrable National Park',
                'slug' => 'bwindi',
                'description' => 'Home to half of the world\'s mountain gorilla population.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Queen Elizabeth National Park',
                'slug' => 'queen-elizabeth',
                'description' => 'Uganda\'s most popular park with tree-climbing lions.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Murchison Falls National Park',
                'slug' => 'murchison-falls',
                'description' => 'Spectacular waterfall where the Nile explodes through a narrow gorge.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Jinja - Source of the Nile',
                'slug' => 'jinja',
                'description' => 'Adventure capital of East Africa with white-water rafting.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Kibale National Park',
                'slug' => 'kibale',
                'description' => 'Primate capital with highest density of chimpanzees.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Lake Bunyonyi',
                'slug' => 'lake-bunyonyi',
                'description' => 'One of Africa\'s deepest lakes with 29 islands.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Rwenzori Mountains',
                'slug' => 'rwenzori-mountains',
                'description' => 'The legendary Mountains of the Moon.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        $ugandaDestinationModels = [];
        foreach ($ugandaDestinations as $dest) {
            $ugandaDestinationModels[] = $uganda->destinations()->create($dest);
        }

        // Uganda Activities
        $ugandaActivities = [
            [
                'name' => 'Gorilla Trekking',
                'slug' => 'gorilla-trekking-uganda',
                'description' => 'Once-in-a-lifetime encounter with mountain gorillas.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $ugandaDestinationModels[0]->id, // Bwindi
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Chimpanzee Tracking',
                'slug' => 'chimpanzee-tracking-uganda',
                'description' => 'Track our closest relatives in their natural habitat.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $ugandaDestinationModels[4]->id, // Kibale
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'White Water Rafting',
                'slug' => 'white-water-rafting-uganda',
                'description' => 'Grade 5 rapids on the mighty Nile River.',
                'category_id' => $adventureSports->id,
                'destination_id' => $ugandaDestinationModels[3]->id, // Jinja
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Murchison Falls Boat Cruise',
                'slug' => 'murchison-falls-boat-cruise',
                'description' => 'Cruise to the base of the powerful falls.',
                'category_id' => $waterActivities->id,
                'destination_id' => $ugandaDestinationModels[2]->id, // Murchison Falls
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tree Climbing Lions Safari',
                'slug' => 'tree-climbing-lions-uganda',
                'description' => 'Spot unique lions that climb fig trees.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $ugandaDestinationModels[1]->id, // Queen Elizabeth
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Bungee Jumping',
                'slug' => 'bungee-jumping-uganda',
                'description' => 'Leap from 44 meters above the Nile.',
                'category_id' => $adventureSports->id,
                'destination_id' => $ugandaDestinationModels[3]->id, // Jinja
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Rwenzori Trekking',
                'slug' => 'rwenzori-trekking',
                'description' => 'Multi-day trek in the mystical Mountains of the Moon.',
                'category_id' => $adventureSports->id,
                'destination_id' => $ugandaDestinationModels[6]->id, // Rwenzori
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($ugandaActivities as $activity) {
            Activity::create($activity);
        }

        // ========================
        // RWANDA
        // ========================
        $rwanda = Country::create([
            'name' => 'Rwanda',
            'code' => 'RW',
            'flag_icon' => '🇷🇼',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Rwanda Destinations
        $rwandaDestinations = [
            [
                'name' => 'Volcanoes National Park',
                'slug' => 'volcanoes-national-park',
                'description' => 'Home to endangered mountain gorillas and golden monkeys.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Nyungwe Forest National Park',
                'slug' => 'nyungwe-forest',
                'description' => 'Ancient rainforest with chimpanzees and canopy walkway.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kigali City',
                'slug' => 'kigali',
                'description' => 'Clean, modern capital city with genocide memorial.',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Akagera National Park',
                'slug' => 'akagera',
                'description' => 'Rwanda\'s only savanna park with Big Five.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Lake Kivu',
                'slug' => 'lake-kivu',
                'description' => 'Beautiful lake with resort towns and beaches.',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        $rwandaDestinationModels = [];
        foreach ($rwandaDestinations as $dest) {
            $rwandaDestinationModels[] = $rwanda->destinations()->create($dest);
        }

        // Rwanda Activities
        $rwandaActivities = [
            [
                'name' => 'Mountain Gorilla Trekking',
                'slug' => 'gorilla-trekking-rwanda',
                'description' => 'Premium gorilla tracking experience in the Virungas.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $rwandaDestinationModels[0]->id, // Volcanoes
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Golden Monkey Tracking',
                'slug' => 'golden-monkey-tracking',
                'description' => 'Track rare and endangered golden monkeys.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $rwandaDestinationModels[0]->id,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Canopy Walkway',
                'slug' => 'canopy-walkway-rwanda',
                'description' => 'Walk 50 meters above the rainforest floor.',
                'category_id' => $adventureSports->id,
                'destination_id' => $rwandaDestinationModels[1]->id, // Nyungwe
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Genocide Memorial Visit',
                'slug' => 'genocide-memorial-kigali',
                'description' => 'Learn about Rwanda\'s history and resilience.',
                'category_id' => $culturalExperience->id,
                'destination_id' => $rwandaDestinationModels[2]->id, // Kigali
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Big Five Safari',
                'slug' => 'big-five-safari-rwanda',
                'description' => 'Spot lions, elephants, rhinos, buffalo, and leopards.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $rwandaDestinationModels[3]->id, // Akagera
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Lake Kivu Boat Tour',
                'slug' => 'lake-kivu-boat-tour',
                'description' => 'Relax on one of Africa\'s Great Lakes.',
                'category_id' => $waterActivities->id,
                'destination_id' => $rwandaDestinationModels[4]->id, // Lake Kivu
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Chimpanzee Trekking',
                'slug' => 'chimpanzee-trekking-rwanda',
                'description' => 'Track habituated chimpanzee families.',
                'category_id' => $wildlifeSafari->id,
                'destination_id' => $rwandaDestinationModels[1]->id, // Nyungwe
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($rwandaActivities as $activity) {
            Activity::create($activity);
        }

        $this->command->info('✅ East Africa Tourism data seeded successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - 4 Countries: Kenya, Tanzania, Uganda, Rwanda');
        $this->command->info('   - 5 Activity Categories');
        $this->command->info('   - 26 Destinations');
        $this->command->info('   - 28 Activities');
    }
}