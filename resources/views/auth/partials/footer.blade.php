<div class="absolute bottom-0 w-full p-1">
    <div class="text-xs md:text-sm text-center mb-2">
        © 2023 -
        <script>
            document.write(new Date().getFullYear())
        </script>, made by <a
            href="{{ !empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '' }}"
            target="_blank" class="hover:text-blue-700">Dynamus Developer Team</a>
    </div>
    <div class="grid grid-cols-6 gap-1">
        <div class="py-0.5 bg-black rounded-full"></div>
        <div class="py-0.5 bg-gray-700 rounded-full"></div>
        <div class="py-0.5 bg-blue-700 rounded-full"></div>
        <div class="py-0.5 bg-red-700 rounded-full"></div>
        <div class="py-0.5 bg-orange-700 rounded-full"></div>
        <div class="py-0.5 bg-yellow-700 rounded-full"></div>
    </div>
</div>
