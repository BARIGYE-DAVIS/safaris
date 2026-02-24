<section class="mt-10 sm:mt-12">
    <div class="rounded-2xl border border-gray-200 bg-white/70 backdrop-blur px-5 py-6 sm:px-8 sm:py-7 shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase">Certified by</p>
                <h3 class="mt-1 text-lg sm:text-xl font-bold text-gray-900">
                    Trusted partners & certifications
                </h3>
            </div>

            <div class="text-xs text-gray-500">
                <span class="inline-flex items-center gap-2 rounded-full bg-gray-50 px-3 py-1 border border-gray-200">
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    Verified partners
                </span>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-4 sm:gap-6">
            {{-- Local partner logos --}}
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="rounded-xl border border-gray-100 bg-white px-4 py-3 shadow-sm">
                    <img
                        class="h-10 sm:h-12 w-auto opacity-80 hover:opacity-100 transition-opacity"
                        src="{{ asset('images/tato-logo.png') }}"
                        alt="TATO Certified"
                        loading="lazy"
                    >
                </div>

                <div class="rounded-xl border border-gray-100 bg-white px-4 py-3 shadow-sm">
                    <img
                        class="h-10 sm:h-12 w-auto opacity-80 hover:opacity-100 transition-opacity"
                        src="{{ asset('images/tanapa-logo.png') }}"
                        alt="TANAPA Partner"
                        loading="lazy"
                    >
                </div>
            </div>

            <div class="h-px sm:h-12 w-full sm:w-px bg-gray-200 my-2 sm:my-0"></div>

            {{-- GetYourGuide badges --}}
            <div class="flex flex-wrap items-center gap-4 sm:gap-5">
                <a href="https://www.getyourguide.com/-s491424"
                   target="_blank" rel="noopener"
                   class="group block rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm hover:shadow-md transition">
                    <img
                        src="https://gyg.me/DFO5LFuz"
                        class="h-12 sm:h-14 w-auto border border-[#c6c8d0] rounded-md"
                        alt="GetYourGuide | Calm Africa Safaris"
                        loading="lazy"
                    >
                    <p class="mt-2 text-[11px] text-gray-500 group-hover:text-gray-700 transition">
                        View our GetYourGuide profile
                    </p>
                </a>

                <a href="https://www.getyourguide.com/-s491424"
                   target="_blank" rel="noopener"
                   class="group block rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm hover:shadow-md transition">
                    <img
                        src="https://gyg.me/kUtga42u"
                        class="h-12 sm:h-14 w-auto border border-[#c6c8d0] rounded-md"
                        alt="GetYourGuide | Calm Africa Safaris"
                        loading="lazy"
                    >
                    <p class="mt-2 text-[11px] text-gray-500 group-hover:text-gray-700 transition">
                        Explore our tours
                    </p>
                </a>
            </div>
        </div>
    </div>
</section>