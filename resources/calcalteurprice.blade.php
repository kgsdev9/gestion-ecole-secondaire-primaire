<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul Prix et Quantité</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
        }
        label {
            font-weight: bold;
        }
        input {
            padding: 8px;
            box-sizing: border-box;
        }
        .total {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container" x-data="{
        rows: [
            { price: 0, quantity: 0 }
        ],
        addRow()
        {
            this.rows.push({ price: 0, quantity: 0 });
        },
        removeRow(index)
        {
            this.rows.splice(index, 1);
        },
        get grandTotal()
        {
            return this.rows.reduce((sum, row) => sum + (row.price * row.quantity), 0).toFixed(2);
        }
    }">
        <h1>Calculateur de Prix</h1>

        <template x-for="(row, index) in rows" :key="index">
            <div class="form-group">
                <input type="number" x-model.number="row.price" placeholder="Prix unitaire (€)" />
                <input type="number" x-model.number="row.quantity" placeholder="Quantité" />
                <span>Total : <span x-text="(row.price * row.quantity).toFixed(2)"></span> €</span>
                <button @click="removeRow(index)" style="background-color: red;">Supprimer</button>
            </div>
        </template>

        <button @click="addRow">Ajouter une ligne</button>

        <div class="total">
            Total général : <span x-text="grandTotal"></span> €
        </div>
    </div>
</body>
</html>
