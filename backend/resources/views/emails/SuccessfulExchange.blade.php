<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #4b0082;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .footer {
            background-color: #f8f8f8;
            color: #999999;
            text-align: center;
            padding: 10px;
            border-radius: 0 0 8px 8px;
        }

        .btn {
            background-color: #4b0082;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }

        .btn:hover {
            background-color: #371a55;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Parabéns, {{ $client->name }}!</h1>
        </div>
        <div class="content">
            <p>Você recebeu sua recompensa: <strong>{{$reward->name}}</strong>! Essa troca custou <strong>{{$reward->pointsCost}}</strong> @if($reward->pointsCost != 1) pontos, @else ponto, @endif restando um total de <strong>{{intdiv($client->balance, 5)}}</strong> @if(intdiv($client->balance, 5) != 1) pontos @else ponto @endif em sua conta.</p>
            <p>Continue comprando para ganhar ainda mais pontos e aproveitar nossas recompensas exclusivas 😃</p>
            <a href="#" class="btn">Ver Recompensas</a>
        </div>
        <div class="footer">
            <p>Este é um email automático, por favor, não responda.</p>
        </div>
    </div>
</body>
</html>
