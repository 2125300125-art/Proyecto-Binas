<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubicación IP</title>
</head>

<body>
    <ul>
        <li>Ip {{ $respuesta['query'] }}</li>
        <li>Pais: {{ $respuesta['country'] }}</li>
        <li>Región: {{ $respuesta['regionName'] }}</li>
        <li>Ciudad: {{ $respuesta['city'] }}</li>
        <li>Código Postal: {{ $respuesta['zip'] }}</li>
        <li>Latitud: {{ $respuesta['lat'] }}</li>
        <li>Longitud: {{ $respuesta['lon'] }}</li>  

    </ul>
</body>

</html>
