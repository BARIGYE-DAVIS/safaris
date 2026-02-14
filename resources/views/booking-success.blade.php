@extends('layouts.app')
@section('content')
   

    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Success Header -->
                <div class="bg-gradient-to-r from-green-500 to-blue-600 px-8 py-12 text-center">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check text-4xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-4">Booking Request Sent!</h1>
                    <p class="text-xl text-green-100">Thank you for choosing Safari Uganda</p>
                </div>

                <!-- Success Content -->
                <div class="px-8 py-12">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">What Happens Next?</h2>
                        <p class="text-lg text-gray-600">Our expert travel consultants will review your request and get back to you soon!</p>
                    </div>

                    <!-- Timeline -->
                    <div class="space-y-6 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Request Received</h3>
                                <p class="text-gray-600">Your booking request has been successfully submitted and assigned a reference number.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Review & Processing</h3>
                                <p class="text-gray-600">Our team is reviewing your requirements and preparing a personalized itinerary.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">We'll Contact You</h3>
                                <p class="text-gray-600">Expect to hear from us within <strong>24 hours</strong> via WhatsApp or email.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Need Immediate Assistance?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="https://wa.me/256700000000" target="_blank" 
                               class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-colors duration-300">
                                <i class="fab fa-whatsapp mr-2 text-xl"></i>
                                <span class="font-semibold">WhatsApp Us</span>
                            </a>
                            <a href="tel:+256700000000" 
                               class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors duration-300">
                                <i class="fas fa-phone mr-2"></i>
                                <span class="font-semibold">Call Now</span>
                            </a>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center space-y-4">
                        <a href="{{ route('tours.index') }}" 
                           class="inline-block bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                            <i class="fas fa-binoculars mr-2"></i>
                            Browse More Tours
                        </a>
                        <div>
                            <a href="{{ route('index') }}" class="text-gray-600 hover:text-green-600 font-medium">
                                Return to Homepage
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 text-center">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-2">Check Your Email</h4>
                    <p class="text-gray-600">A confirmation email has been sent to your inbox with all the details of your request.</p>
                </div>
            </div>
        </div>
    </div>
@endsection