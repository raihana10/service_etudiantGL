<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de demande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }
        .numero-demande {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Votre demande a été créée</h2>
        <p>Votre numéro de demande est :</p>
        <div class="numero-demande">{{ $numDemande }}</div>
        <p>Conservez ce numéro pour suivre votre demande.</p>
    </div>
</body>
</html>
