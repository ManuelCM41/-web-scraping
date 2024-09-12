<div class="absolute top-0 w-full p-1">
    <div class="grid grid-cols-6 gap-1">
        <div class="py-0.5 bg-yellow-700 rounded-full"></div>
        <div class="py-0.5 bg-orange-700 rounded-full"></div>
        <div class="py-0.5 bg-red-700 rounded-full"></div>
        <div class="py-0.5 bg-blue-700 rounded-full"></div>
        <div class="py-0.5 bg-gray-700 rounded-full"></div>
        <div class="py-0.5 bg-black rounded-full"></div>
    </div>
    <div class="relative flex justify-center mt-1 text-xs">
        <a href="/"
            class="absolute left-0 flex items-center gap-2 w-max px-4 py-2 font-bold text-center text-gray-900 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-gray-900/10 active:bg-gray-900/20">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="hidden md:block">Regresar</span>
        </a>
        <div class="px-6 py-2 bg-white/80 w-max rounded-b-full shadow-lg text-center uppercase">
            {{ $title }}
        </div>
    </div>
</div>
