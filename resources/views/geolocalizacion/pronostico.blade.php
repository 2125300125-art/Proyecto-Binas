<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pronóstico del Clima</title>
</head>
<body>
    <h1>Pronóstico del Clima en {{ $ciudad }}</h1>

    <ul>
    <li>Temperatura actual: {{ $datosClima['current']['temperature_2m'] }} °C</li>
    <li>Humedad: {{ $datosClima['current']['relative_humidity_2m'] }} %</li>
    <li>Lluvia actual: {{ $datosClima['current']['precipitation'] }} mm</li>
    <li>Probabilidad de lluvia hoy: {{ $datosClima['daily']['precipitation_probability_max'][0] }} %</li>
</ul>
</body>
</html>