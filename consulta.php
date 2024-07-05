<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;700&family=Roboto+Mono&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto Slab', serif;
        }
        #api_key {
            font-family: 'Roboto Mono', monospace;
        }
    </style>
    <title>Consulta de Engines da OpenAI</title>
    <script>
        function validateForm() {
            var apiKey = document.getElementById("api_key").value;
            if (apiKey.startsWith("sk-")) {
                return true;
            } else {
                alert("Chave de API digitada incorretamente. Deve começar com 'sk-'.");
                return false;
            }
        }
    </script>
</head>
<body class="bg-gray-100 p-8">
<div class="container mx-auto">
    <div class="bg-white p-8 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Consulta de Engines da OpenAI</h2>
        <form method="post" onsubmit="return validateForm()" class="mb-4">
            <label for="api_key" class="block text-lg font-medium text-gray-700 mb-2">Digite sua chave de API:</label>
            <input type="text" id="api_key" name="api_key" class="block w-full p-2 border border-gray-300 rounded mb-4" required>
            <button type="submit" name="submit" class="bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition duration-200">Enviar</button>
        </form>
    
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["api_key"])) {
    $api_key = $_POST["api_key"];
    $url = 'https://api.openai.com/v1/engines';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
        $error = curl_error($ch);
        error_log('Erro ao fazer a solicitação: ' . $error);
        echo 'Erro ao fazer a solicitação. Veja o log para mais detalhes.';
    } else {
        if ($httpCode == 401) {
            $modified_key = substr($api_key, 3);
            echo '<blockquote><span class="pln">
  </span><em><span class="pln"><h5>Key Inválida!</span></em>
</blockquote></h5><br><h6>' . htmlspecialchars($modified_key) . '</h6>';
        } else if ($httpCode >= 400) {
            // Captura e exibe a mensagem de erro da API
            $errorResponse = json_decode($response, true);
            $errorMessage = isset($errorResponse['error']['message']) ? 
$errorResponse['error']['message'] : 'Erro desconhecido.';
            echo 'Erro na resposta da API: ' . htmlspecialchars($errorMessage);
        } else {
            $data = json_decode($response, true);
            if ($data && isset($data['data'])) {
                echo '<blockquote><span class="pln">
  </span><em><span class="pln"><h5>Engines disponíveis:</span></em>
</blockquote></h5>';
                foreach ($data['data'] as $engine) {
                    echo '- ' . htmlspecialchars($engine['id']) . ': ' . 
htmlspecialchars($engine['object']) . '<br>';
                }
            } else {
                echo 'Erro na resposta da API.';
            }
        }
    }

    curl_close($ch);
}
?>

</body>
</html>

