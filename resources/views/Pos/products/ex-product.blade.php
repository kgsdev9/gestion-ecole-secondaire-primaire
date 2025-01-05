<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Caisse</title>
    <style>
        /* Style pour rendre le ticket adapté aux petites imprimantes */
        body {
            font-family: 'Arial', sans-serif;
            width: 300px; /* Largeur typique des imprimantes POS */
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .ticket-header {
            font-weight: bold;
        }
        .ticket-body {
            padding: 5px 0;
        }
        .ticket-footer {
            font-size: 10px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .total {
            font-weight: bold;
        }
        .line {
            border-bottom: 1px solid #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="ticket-header center">
        <h3>Magasin Exemple</h3>
        <p>123 Rue de l'Exemple</p>
        <p>Téléphone : 01 23 45 67 89</p>
        <div class="line"></div>
    </div>

    <div class="ticket-body">
        <div>
            <span>Produit 1</span>
            <span class="right">50 FCFA</span>
        </div>
        <div>
            <span>Produit 2</span>
            <span class="right">100 FCFA</span>
        </div>
        <div>
            <span>Produit 3</span>
            <span class="right">150 FCFA</span>
        </div>
        <div class="line"></div>

        <div>
            <span>Total</span>
            <span class="right total">300 FCFA</span>
        </div>
    </div>

    <div class="ticket-footer center">
        <p>Merci de votre achat !</p>
        <p>Visitez-nous sur www.exemple.com</p>
    </div>
</body>
</html>
