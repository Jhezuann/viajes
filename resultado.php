<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados del Viaje</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Viaje</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cargar configuración
            $config = include('.env.php');
            $apiKeyClima = $config['weather_api_key'];
            $apiKeyCambio = $config['exchange_api_key'];

            $ciudad = urlencode($_POST['ciudad']);
            $moneda = strtoupper($_POST['moneda']);
            $destino = urlencode($_POST['destino']);
            $presupuestoEnMoneda = $_POST['presupuesto'];

            // Llamada a la API de clima actual
            $urlClima = "http://api.weatherapi.com/v1/current.json?key={$apiKeyClima}&q={$destino}&aqi=no";
            $respuestaClima = file_get_contents($urlClima);
            $datosClima = json_decode($respuestaClima, true);

            // Llamada a la API de clima para los últimos 2 días
            $urlClimaFuturo = "http://api.weatherapi.com/v1/history.json?key={$apiKeyClima}&q={$destino}&dt=" . date("Y-m-d", strtotime("-1 day"));
            $respuestaClimaFuturo1 = file_get_contents($urlClimaFuturo);
            $datosClimaFuturo1 = json_decode($respuestaClimaFuturo1, true);

            $urlClimaFuturo2 = "http://api.weatherapi.com/v1/history.json?key={$apiKeyClima}&q={$destino}&dt=" . date("Y-m-d", strtotime("-2 day"));
            $respuestaClimaFuturo2 = file_get_contents($urlClimaFuturo2);
            $datosClimaFuturo2 = json_decode($respuestaClimaFuturo2, true);

            // Llamada a la API de clima para los próximos 2 días
            $urlClimaFuturo3 = "http://api.weatherapi.com/v1/forecast.json?key={$apiKeyClima}&q={$destino}&days=3&aqi=no";
            $respuestaClimaFuturo3 = file_get_contents($urlClimaFuturo3);
            $datosClimaFuturo3 = json_decode($respuestaClimaFuturo3, true);

            if (isset($datosClima['location']['country'])) {
                $pais = $datosClima['location']['country'];
                $temperaturaActual = $datosClima['current']['temp_c'];
                $descripcionActual = $datosClima['current']['condition']['text'];

                $temperaturaFutura1 = $datosClimaFuturo1['forecast']['forecastday'][0]['day']['avgtemp_c'];
                $descripcionFutura1 = $datosClimaFuturo1['forecast']['forecastday'][0]['day']['condition']['text'];
                
                $temperaturaFutura2 = $datosClimaFuturo2['forecast']['forecastday'][0]['day']['avgtemp_c'];
                $descripcionFutura2 = $datosClimaFuturo2['forecast']['forecastday'][0]['day']['condition']['text'];
                
                $temperaturaFutura3 = $datosClimaFuturo3['forecast']['forecastday'][1]['day']['avgtemp_c'];
                $descripcionFutura3 = $datosClimaFuturo3['forecast']['forecastday'][1]['day']['condition']['text'];

                $temperaturaFutura4 = $datosClimaFuturo3['forecast']['forecastday'][2]['day']['avgtemp_c'];
                $descripcionFutura4 = $datosClimaFuturo3['forecast']['forecastday'][2]['day']['condition']['text'];

                // Traducir condiciones climáticas al español
                $climaTraducido = [
                    'Patchy rain possible' => 'Posible lluvia irregular',
                    'Clear' => 'Despejado',
                    'Sunny' => 'Soleado',
                    'Partly cloudy' => 'Parcialmente nublado',
                    'Patchy rain nearby' => 'Lluvias cercanas',
                    'Cloudy' => 'Nublado',
                    'Rain' => 'Lluvia',
                    'Showers' => 'Chubascos',
                    'Thunderstorm' => 'Tormenta eléctrica',
                    'Snow' => 'Nieve',
                ];

                // Mostrar la información en español
                echo "<div class='card'>
                        <h2>Clima de Hace Dos Días (Anteayer)</h2>
                        <p>{$temperaturaFutura2} °C - " . (isset($climaTraducido[$descripcionFutura2]) ? $climaTraducido[$descripcionFutura2] : $descripcionFutura2) . "</p>
                    </div>";

                echo "<div class='card'>
                        <h2>Clima del Último Día (Ayer)</h2>
                        <p>{$temperaturaFutura1} °C - " . (isset($climaTraducido[$descripcionFutura1]) ? $climaTraducido[$descripcionFutura1] : $descripcionFutura1) . "</p>
                    </div>";

                echo "<div class='card'>
                    <h2>Clima Actual (Hoy)</h2>
                    <p><strong>{$destino} ({$pais})</strong></p>
                    <p>{$temperaturaActual} °C - " . (isset($climaTraducido[$descripcionActual]) ? $climaTraducido[$descripcionActual] : $descripcionActual) . "</p>
                </div>";

                echo "<div class='card'>
                        <h2>Clima de Mañana</h2>
                        <p>{$temperaturaFutura3} °C - " . (isset($climaTraducido[$descripcionFutura3]) ? $climaTraducido[$descripcionFutura3] : $descripcionFutura3) . "</p>
                    </div>";

                echo "<div class='card'>
                        <h2>Clima en Dos Días (Pasado mañana)</h2>
                        <p>{$temperaturaFutura4} °C - " . (isset($climaTraducido[$descripcionFutura4]) ? $climaTraducido[$descripcionFutura4] : $descripcionFutura4) . "</p>
                    </div>";
                
                // Llamada a la API de tipo de cambio
                $urlCambio = "https://v6.exchangerate-api.com/v6/{$apiKeyCambio}/latest/{$moneda}";
                $respuestaCambio = file_get_contents($urlCambio);
                $datosCambio = json_decode($respuestaCambio, true);

                if (isset($datosCambio['conversion_rates'])) {
                    $monedasPorPais = [
                        'Spain' => 'EUR',          // España - Euro
                        'United States' => 'USD',  // Estados Unidos - Dólar estadounidense
                        'Colombia' => 'COP',       // Colombia - Peso colombiano
                        'Mexico' => 'MXN',         // México - Peso mexicano
                        'Argentina' => 'ARS',      // Argentina - Peso argentino
                        'Brazil' => 'BRL',         // Brasil - Real brasileño
                        'United Kingdom' => 'GBP', // Reino Unido - Libra esterlina
                        'Canada' => 'CAD',         // Canadá - Dólar canadiense
                        'Japan' => 'JPY',          // Japón - Yen japonés
                        'Australia' => 'AUD',      // Australia - Dólar australiano
                        'Switzerland' => 'CHF',    // Suiza - Franco suizo
                        'Venezuela' => 'VES',      // Venezuela - Bolívar venezolano
                        'France' => 'EUR',         // Francia - Euro
                        'Germany' => 'EUR',        // Alemania - Euro
                        'Italy' => 'EUR',          // Italia - Euro
                        'China' => 'CNY',          // China - Yuan chino
                        'India' => 'INR',          // India - Rupia india
                        'Russia' => 'RUB',         // Rusia - Rublo ruso
                        'South Korea' => 'KRW',    // Corea del Sur - Won surcoreano
                        'South Africa' => 'ZAR',   // Sudáfrica - Rand sudafricano
                        'Saudi Arabia' => 'SAR',   // Arabia Saudita - Riyal saudí
                        'United Arab Emirates' => 'AED', // Emiratos Árabes Unidos - Dirham emiratí
                        'Chile' => 'CLP',          // Chile - Peso chileno
                        'Peru' => 'PEN',           // Perú - Nuevo sol peruano
                        'Uruguay' => 'UYU',        // Uruguay - Peso uruguayo
                        'Cuba' => 'CUP',           // Cuba - Peso cubano
                        'Egypt' => 'EGP',          // Egipto - Libra egipcia
                        'Turkey' => 'TRY',         // Turquía - Lira turca
                        'Israel' => 'ILS',         // Israel - Nuevo shekel israelí
                        'New Zealand' => 'NZD',    // Nueva Zelanda - Dólar neozelandés
                        'Thailand' => 'THB',       // Tailandia - Baht tailandés
                        'Malaysia' => 'MYR',       // Malasia - Ringgit malayo
                        'Singapore' => 'SGD',      // Singapur - Dólar de Singapur
                        'Indonesia' => 'IDR',      // Indonesia - Rupia indonesia
                        'Nigeria' => 'NGN',        // Nigeria - Naira nigeriano
                        'Philippines' => 'PHP',    // Filipinas - Peso filipino
                        'Hong Kong' => 'HKD',      // Hong Kong - Dólar de Hong Kong
                        'South Korea' => 'KRW',    // Corea del Sur - Won surcoreano
                    ];
                    
                    // Obtener tasa de cambio
                    $tasaCambio = $datosCambio['conversion_rates'][$monedasPorPais[$pais] ?? 'EUR'];
                    $presupuestoFinal = $presupuestoEnMoneda * $tasaCambio;

                    echo "<div class='card'>
                            <h2>Presupuesto en la Moneda de Destino</h2>
                            <p><strong>{$presupuestoFinal}</strong> {$monedasPorPais[$pais]}</p>
                        </div>";

                }
            } else {
                echo "<p>Lo siento, no se pudo obtener información del clima.</p>";
            }
        }
        ?>

        <button class="btn" onclick="window.location.href='index.php'">Regresar</button>
    </div>
</body>
</html>
