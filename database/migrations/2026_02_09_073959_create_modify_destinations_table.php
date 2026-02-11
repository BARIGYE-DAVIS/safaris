<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            // Basic expanded info
            $table->string('region')->nullable()->after('country_id');
            $table->string('type')->nullable()->after('region'); // National Park, Reserve, Forest, etc.
            
            // Main content sections
            $table->longText('detailed_overview')->nullable()->after('description');
            $table->longText('what_to_see_do')->nullable()->after('detailed_overview');
            $table->longText('wildlife_highlights')->nullable()->after('what_to_see_do');
            $table->longText('geography_landscape')->nullable()->after('wildlife_highlights');
            $table->longText('best_time_visit')->nullable()->after('geography_landscape');
            $table->longText('how_to_get_there')->nullable()->after('best_time_visit');
            $table->longText('accommodation_options')->nullable()->after('how_to_get_there');
            $table->longText('practical_information')->nullable()->after('accommodation_options');
            $table->longText('cultural_significance')->nullable()->after('practical_information');
            $table->longText('photography_tips')->nullable()->after('cultural_significance');
            $table->longText('nearby_attractions')->nullable()->after('photography_tips');
            $table->longText('interesting_facts')->nullable()->after('nearby_attractions');
            
            // Images - Support for multiple inline images per section
            $table->json('overview_images')->nullable()->after('interesting_facts');
            $table->json('activities_images')->nullable()->after('overview_images');
            $table->json('wildlife_images')->nullable()->after('activities_images');
            $table->json('landscape_images')->nullable()->after('wildlife_images');
            $table->json('accommodation_images')->nullable()->after('landscape_images');
            $table->json('gallery_images')->nullable()->after('accommodation_images');
            
            // Main featured image (hero/header)
            $table->string('featured_image')->nullable()->after('gallery_images');
            
            // Geography details
            $table->decimal('latitude', 10, 8)->nullable()->after('featured_image');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('area_size')->nullable()->after('longitude'); // in square kilometers
            $table->string('area_unit')->default('km²')->after('area_size');
            $table->integer('altitude_min')->nullable()->after('area_unit'); // meters above sea level
            $table->integer('altitude_max')->nullable()->after('altitude_min');
            
            // Pricing info
            $table->decimal('entry_fee_foreign', 10, 2)->nullable()->after('altitude_max');
            $table->decimal('entry_fee_resident', 10, 2)->nullable()->after('entry_fee_foreign');
            $table->decimal('entry_fee_local', 10, 2)->nullable()->after('entry_fee_resident');
            $table->string('currency', 10)->default('USD')->after('entry_fee_local');
            
            // Additional meta
            $table->string('established_year', 4)->nullable()->after('currency');
            $table->integer('annual_visitors')->nullable()->after('established_year');
            
            // Contact information
            $table->string('phone')->nullable()->after('annual_visitors');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            
            // Opening hours/seasons
            $table->string('opening_hours')->nullable()->after('website');
            $table->string('best_season')->nullable()->after('opening_hours');
            
            // Weather info
            $table->string('climate')->nullable()->after('best_season');
            $table->integer('avg_temp_high')->nullable()->after('climate'); // Celsius
            $table->integer('avg_temp_low')->nullable()->after('avg_temp_high');
            $table->integer('rainfall_annual')->nullable()->after('avg_temp_low'); // mm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'region',
                'type',
                'detailed_overview',
                'what_to_see_do',
                'wildlife_highlights',
                'geography_landscape',
                'best_time_visit',
                'how_to_get_there',
                'accommodation_options',
                'practical_information',
                'cultural_significance',
                'photography_tips',
                'nearby_attractions',
                'interesting_facts',
                'overview_images',
                'activities_images',
                'wildlife_images',
                'landscape_images',
                'accommodation_images',
                'gallery_images',
                'featured_image',
                'latitude',
                'longitude',
                'area_size',
                'area_unit',
                'altitude_min',
                'altitude_max',
                'entry_fee_foreign',
                'entry_fee_resident',
                'entry_fee_local',
                'currency',
                'established_year',
                'annual_visitors',
                'phone',
                'email',
                'website',
                'opening_hours',
                'best_season',
                'climate',
                'avg_temp_high',
                'avg_temp_low',
                'rainfall_annual',
            ]);
        });
    }
};