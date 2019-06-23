<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificado</title>
    <style>
        @charset "utf-8";
        html {
            margin: 0;
        }
        body {
            margin: 0;
            background-image: url({{ $background }});
        }
        @font-face {
            font-family: 'Poppins';
            src: url({{ storage_path('fonts/Poppins/Poppins-Light.ttf') }}) format("truetype");
            font-weight: 300;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url({{ storage_path('fonts/Poppins/Poppins-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url({{ storage_path('fonts/Poppins/Poppins-Medium.ttf') }}) format("truetype");
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url({{ storage_path('fonts/Poppins/Poppins-SemiBold.ttf') }}) format("truetype");
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url({{ storage_path('fonts/Poppins/Poppins-Bold.ttf') }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url({{ storage_path('fonts/Poppins/Poppins-Bold.ttf') }}) format("truetype");
            font-weight: 700;
            font-style: bold;
        }
        * { font-family: 'Poppins'; color: #2c3e50; }
        .wrapper {
            padding: 120px 15px;
            padding-bottom: 0;
            position: relative;
            text-align: center;
        }
        .wrapper .header {
            font-weight: bold;
            margin-bottom: 35px;
        }
        .wrapper .text {
            margin-bottom: 35px;
            padding: 0 80px;
        }
        .wrapper .text span {
            font-weight: bold;
        }
        .wrapper .logo-preview {
            padding-top: 80px;
            text-align: center;
        }
        .wrapper .logo-preview img {
            max-width: 200px;
        }
        .wrapper .signature-preview {
            margin-top: 150px;
            border-top: 1px solid #2c3e50;
            margin-left: 300px;
            margin-right: 300px;
            position: relative;
        }
        .wrapper .signature-preview .image {
            max-width: 250px;
            max-height: 60px;
            position: absolute;
            top: -60px;
            right: 0;
            left: 35%;
            object-fit: contain;
        }
        .wrapper .signature-preview p {
            margin-top: 5px;
        }

        .uuid {
            position: absolute;
            bottom: -130px;
            left: 34%;
        }

        .uuid span {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <h1 class="header">CERTIFICADO DE COMPARECIMENTO</h1>
        <p class="text">
            Certificamos que <span>{{ $subscription->user->name }}</span> participou do evento <span>{{ $subscription->event->name }}</span>,
            realizado em <span>{{ $subscription->event->address->name }}</span>, no dia <span>{{ $subscription->event->starts_at->isoFormat('D [de] MMMM [de] Y') }}</span> com carga horária de
            <span>2 horas</span>, organizado por <span>{{ $subscription->event->owner->name }}</span>.
        </p>

        <div class="signature-preview">
            <img class="image" src="{{ $subscription->event->certificate->signature_image }}">
            <p>{{ $subscription->event->certificate->signature_name }}</p>
        </div>

        <div class="logo-preview">
            <img src="{{ $subscription->event->certificate->logo }}">
        </div>

        <p class="uuid">Código: <span>{{ $subscription->uuid }}</span></p>
    </div>

</body>
</html>