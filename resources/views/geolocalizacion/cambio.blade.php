<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cambio de Dolar a Pesos Mexicanos</title>
</head>

<body>
    <div
        style="font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 80vh;">
        <div
            style="border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; max-width: 350px; width: 100%;">
            <div style="font-size: 3rem; margin-bottom: 10px;">💵</div>
            <h2 style="margin: 0 0 10px 0; color: #2c3e50;">Tipo de Cambio Oficial</h2>
            <p style="color: #7f8c8d; margin: 0 0 15px 0;">Diario Oficial de la Federación</p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">

            <p style="margin: 5px 0; font-size: 1rem; color: #555;">1 USD equivale a:</p>
            <h1 style="color: #27ae60; margin: 10px 0; font-size: 2.2rem;">
                ${{ $datosDolar['value'] ?? 'N/A' }} <span
                    style="font-size: 1.2rem; color: #7f8c8d;">MXN</span>
            </h1>

            <p style="margin-top: 15px; font-size: 0.85rem; color: #95a5a6;">
                📅 Fecha: {{ $datosDolar['date'] ?? 'Hoy' }}
            </p>
        </div>
    </div>



</body>

</html>
