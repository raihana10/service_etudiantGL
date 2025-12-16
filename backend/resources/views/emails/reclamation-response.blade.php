<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre réclamation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #0A0D25;
            background-color: #E3EDF2;
            margin: 0;
            padding: 20px;
        }

        .container-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #4E7D96;
            color: white;
            padding: 24px;
            text-align: center;
        }

        .content {
            padding: 30px;
        }

        .reclamation-info {
            background: #E3EDF2;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #4E7D96;
        }

        .response {
            background: #FFE8DC;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            border-left: 4px solid #FF844B;
        }

        .response h3 {
            color: #FF844B;
            margin-top: 0;
        }

        .response p {
            color: #0A0D25;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #4E7D96;
            font-size: 14px;
            padding-bottom: 20px;
        }

        .label {
            font-weight: 500;
            color: #4E7D96;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        h3 {
            color: #0A0D25;
            margin-top: 0;
        }

        p {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container-wrapper">
        <div class="header">
            <h1>Services Étudiants - Université Abdelmalek Essaâdi</h1>
            <p>Réponse à votre réclamation</p>
        </div>

        <div class="content">
            <p>Cher/Chère {{ $etudiant->prenom }} {{ $etudiant->nom }},</p>

            <p>Nous avons bien reçu votre réclamation et nous vous remercions de votre patience. Voici notre réponse :
            </p>

            <div class="reclamation-info">
                <h3>Informations sur votre réclamation</h3>
                <p><span class="label">Numéro de réclamation:</span> #{{ $reclamation->idReclamation }}</p>
                <p><span class="label">Sujet :</span> {{ $reclamation->sujet }}</p>
                <p><span class="label">Date de soumission :</span>
                    {{ $reclamation->datesoumission->format('d/m/Y H:i') }}</p>
                <p><span class="label">Description :</span></p>
                <p>{{ $reclamation->description }}</p>
            </div>

            <div class="response">
                <h3>Notre réponse</h3>
                <p>{{ $reclamation->reponse }}</p>
            </div>

            <p>Nous espérons que cette réponse répond à votre attente. N'hésitez pas à nous contacter si vous avez
                besoin de plus d'informations.</p>

            <p>Cordialement,<br>
                L'équipe des Services Étudiants<br>
                Université Abdelmalek Essaâdi</p>
        </div>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas répondre à cet email.</p>
        <p>Pour toute question supplémentaire, veuillez contacter le service des étudiants.</p>
    </div>
</body>

</html>