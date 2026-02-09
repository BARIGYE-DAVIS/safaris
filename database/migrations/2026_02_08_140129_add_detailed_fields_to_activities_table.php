<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            // Featured Image (for header background)
            $table->string('featured_image')->nullable()->after('image');
            
            // Detailed Content Fields
            $table->text('overview')->nullable()->after('description');
            $table->longText('what_to_expect')->nullable()->after('overview');
            $table->longText('highlights')->nullable()->after('what_to_expect');
            
            // JSON fields for structured data
            $table->json('inclusions')->nullable()->after('highlights'); // What's included
            $table->json('exclusions')->nullable()->after('inclusions'); // What's NOT included
            $table->json('equipment_provided')->nullable()->after('exclusions'); // Equipment provided
            $table->json('skill_levels')->nullable()->after('equipment_provided'); // Beginner, Intermediate, Advanced
            $table->json('best_times')->nullable()->after('skill_levels'); // Seasonal information
            $table->json('what_to_bring')->nullable()->after('best_times'); // Packing list
            $table->json('pricing_packages')->nullable()->after('what_to_bring'); // Different packages
            $table->json('faqs')->nullable()->after('pricing_packages'); // FAQs
            
            // Text fields for important info
            $table->text('regulations')->nullable()->after('faqs'); // Rules and permits
            $table->text('safety_info')->nullable()->after('regulations'); // Safety guidelines
            $table->text('health_requirements')->nullable()->after('safety_info'); // Health info
            $table->text('cultural_experience')->nullable()->after('health_requirements'); // Cultural aspects
            $table->text('conservation_info')->nullable()->after('cultural_experience'); // Conservation efforts
            
            // Booking Information
            $table->json('booking_info')->nullable()->after('conservation_info'); // Group sizes, cancellation policy
            $table->text('special_notes')->nullable()->after('booking_info'); // Additional notes
            
            // Duration and difficulty
            $table->string('duration')->nullable()->after('special_notes'); // e.g., "Full Day", "2-8 hours"
            $table->enum('difficulty_level', ['easy', 'moderate', 'challenging', 'extreme'])->nullable()->after('duration');
            $table->integer('min_age')->nullable()->after('difficulty_level');
            $table->integer('max_group_size')->nullable()->after('min_age');
            
            // Pricing
            $table->decimal('price_from', 10, 2)->nullable()->after('max_group_size');
            $table->decimal('price_to', 10, 2)->nullable()->after('price_from');
            $table->string('currency', 3)->default('USD')->after('price_to');
            
            // SEO fields
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image',
                'overview',
                'what_to_expect',
                'highlights',
                'inclusions',
                'exclusions',
                'equipment_provided',
                'skill_levels',
                'best_times',
                'what_to_bring',
                'pricing_packages',
                'faqs',
                'regulations',
                'safety_info',
                'health_requirements',
                'cultural_experience',
                'conservation_info',
                'booking_info',
                'special_notes',
                'duration',
                'difficulty_level',
                'min_age',
                'max_group_size',
                'price_from',
                'price_to',
                'currency',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }
};