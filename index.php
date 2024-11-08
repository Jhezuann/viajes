<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Viajes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestor de Viajes</h1>
        <p>Ingresa tu ciudad, destino y presupuesto para obtener información útil sobre tu viaje.</p>
        
        <form method="POST" action="resultado.php">
            <label for="ciudad">Ciudad de Origen</label>
            <input type="text" id="ciudad" name="ciudad" placeholder="Ej. Caracas" required>

            <label for="destino">Destino</label>
            <input type="text" id="destino" name="destino" placeholder="Ej. Madrid, New York" required>

            <label for="moneda">Moneda Actual</label>
            <select id="moneda" name="moneda" required>
                <option value="USD">USD - Dólar Estadounidense</option>
                <option value="EUR">EUR - Euro</option>
                <option value="VES">VES - Bolívar</option>
                <option value="COP">COP - Peso Colombiano</option>
                <option value="MXN">MXN - Peso Mexicano</option>
                <option value="ARS">ARS - Peso Argentino</option>
                <option value="BRL">BRL - Real Brasileño</option>
                <option value="GBP">GBP - Libra Esterlina</option>
                <option value="CAD">CAD - Dólar Canadiense</option>
                <option value="JPY">JPY - Yen Japonés</option>
                <option value="AUD">AUD - Dólar Australiano</option>
                <option value="CHF">CHF - Franco Suizo</option>
                <option value="CNY">CNY - Yuan Chino</option>
                <option value="INR">INR - Rupia India</option>
            </select>
            
            <label for="presupuesto">Presupuesto (Tu Moneda)</label>
            <input type="number" id="presupuesto" name="presupuesto" placeholder="Ej. 5000000" required>
            
            <button type="submit">Calcular</button>
        </form>
    </div>
</body>
</html>
