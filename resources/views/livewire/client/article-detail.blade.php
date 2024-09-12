<div style="padding: 1.5rem /* 24px */; max-width: 1366px; margin: auto; background-color: #ffffff">
    <style>
        .image-container {
            content: '';
            width: 100%;
            height: 100%;
            max-width: 1392px;
            max-height: 720px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            max-width: 1392px;
            max-height: 720px;
            object-fit: cover;
        }

        .image-gradient {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
        }

        .articulo2 {
            margin: auto;
            position: relative;
            background-color: #f9f9f9;
        }

        .titulo_category {
            width: 100%;
            text-align: center;
            position: absolute;
            display: grid;
            padding-left: 35px;
            padding-right: 35px;
            bottom: 30px;
        }

        .categoria2 {
            z-index: 1;
            margin: 0 0 5px;
            padding: 4px 8px;
            border-radius: 2px;
            color: #ffffff;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            font-size: 18px;
            line-height: 1.2 !important;
            font-weight: 900;
            text-transform: uppercase !important;
        }

        .titulo2 {
            padding-right: 10px;
            z-index: 1;
            margin: 10px 15px 10px 15px;
            line-height: 1;
            font-size: 60px;
            color: white;
            font-weight: 900;
            font-family: serif;
        }

        .post-info {
            padding-left: 45px;
            padding-right: 45px;
        }

        .info-header {
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            color: black;
            font-size: 1rem;
            /* text-base */
            font-weight: bold;
            padding-top: 2.5rem;
            /* pt-10 */
            padding-bottom: 1.5rem;
            /* pb-6 */
        }

        .divider-vertical {
            height: 20px;
            width: 2px;
            background-color: #d1d5db;
            /* bg-gray-300 */
        }

        .divider {
            height: 0.5px;
            width: 100%;
            background-color: #d1d5db;
            /* bg-gray-300 */
        }

        .content-container {
            display: flex;
            max-width: 1240px;
            margin: auto;
            justify-content: center;
            width: 100%;
            padding-top: 2.5rem;
            /* pt-10 */
            gap: 30px;
        }

        .content-main {
            width: 70%;
            display: grid;
            gap: 2rem;
            /* gap-8 */
        }

        .content-sidebar {
            width: 30%;
        }

        .share-box {
            display: grid;
            justify-content: center;
            align-items: center;
            gap: 1.25rem;
            /* gap-5 */
            box-shadow: 0 4px 6px rgba(201, 201, 201, 0.5);
            /* shadow-md shadow-[#c9c9c9] */
            padding: 2.5rem;
            /* p-10 */
            padding-left: 3.5rem;
            /* pl-14 */
            padding-right: 3.5rem;
            /* pr-14 */
        }

        .share-title {
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
        }

        .share-icon {
            background-color: #ef4444;
            /* bg-red-500 */
            padding: 0.75rem;
            /* p-3 */
        }

        .share-icon img {
            width: 1rem;
            /* w-4 */
            height: 1rem;
            /* h-4 */
        }
    </style>

    <div class="articulo2">
        <div class="titulo_category">
            <div class="categoria2">{{ $detail->categoria }}</div>
            <div class="titulo2">{{ $detail->titulo }}</div>
        </div>
        <div class="image-container">
            <img src="{{ $detail->imagen }}" alt="Imagen" />
            <div class="image-gradient"></div>
        </div>
    </div>

    <div class="post-info">
        <div class="info-header">
            <div>Por: Diario Los Andes</div>
            <div class="divider-vertical"></div>
            <div>Fecha: {{ $detail->fecha }}</div>
        </div>

        <div class="divider"></div>

        <div class="content-container">
            <div class="content-main">
                <p>{{ $detail->p1 }}</p>
                <p>{{ $detail->p2 }}</p>
                <p>{{ $detail->p3 }}</p>
                <p>{{ $detail->p4 }}</p>
                <p>{{ $detail->p5 }}</p>
                <p>{{ $detail->p6 }}</p>
                <p>{{ $detail->p7 }}</p>
                <p>{{ $detail->p8 }}</p>
                <p>{{ $detail->p9 }}</p>
                <p>{{ $detail->p10 }}</p>
                <p>{{ $detail->p11 }}</p>
                <p>{{ $detail->p12 }}</p>
                <p>{{ $detail->p13 }}</p>
                <p>{{ $detail->p14 }}</p>
                <p>{{ $detail->p15 }}</p>
            </div>
            <div class="content-sidebar">
                <div class="share-box">
                    <h1 class="share-title">Compartir post:</h1>
                    <div class="flex justify-center gap-[6px]">
                        <div class="share-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/81/81341.png" alt="">
                        </div>
                        <div class="share-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/81/81341.png" alt="">
                        </div>
                        <div class="share-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/81/81341.png" alt="">
                        </div>
                        <div class="share-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/81/81341.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
