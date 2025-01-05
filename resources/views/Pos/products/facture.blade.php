<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression Ticket de Caisse</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

<button id="printTicketButton">Imprimer Ticket</button>

<script>
document.getElementById('printTicketButton').addEventListener('click', () => {
    // Créer un nouveau document PDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        unit: 'mm',       // Unité de mesure en millimètres
        format: [80, 120] // Format 80mm de large et 120mm de haut pour un ticket de caisse standard
    });

    // Ajouter du contenu au PDF
    doc.setFont("helvetica", "normal");

    // En-tête de la facture
    doc.setFontSize(10);
    doc.text("Magasin Exemple", 10, 10); // Position du texte
    doc.text("123 Rue de l'Exemple", 10, 15);
    doc.text("Téléphone : 01 23 45 67 89", 10, 20);
    doc.line(10, 25, 70, 25);  // Ligne de séparation

    // Produits
    doc.text("Produit 1", 10, 30);
    doc.text("50 FCFA", 60, 30, null, null, 'right');  // Aligner à droite
    doc.text("Produit 2", 10, 40);
    doc.text("100 FCFA", 60, 40, null, null, 'right');
    doc.text("Produit 3", 10, 50);
    doc.text("150 FCFA", 60, 50, null, null, 'right');

    // Ligne de séparation
    doc.line(10, 55, 70, 55);

    // Total
    doc.setFontSize(12);
    doc.text("Total", 10, 60);
    doc.text("300 FCFA", 60, 60, null, null, 'right'); // Total aligné à droite

    // Footer
    doc.setFontSize(8);
    doc.text("Merci de votre achat !", 10, 110);
    doc.text("Visitez-nous sur www.exemple.com", 10, 115);

    // Sauvegarder ou imprimer le document
    doc.autoPrint();  // Fonction pour lancer l'impression
    doc.output('dataurlnewwindow');  // Afficher le PDF dans une nouvelle fenêtre pour visualisation
});
</script>

</body>
</html>
