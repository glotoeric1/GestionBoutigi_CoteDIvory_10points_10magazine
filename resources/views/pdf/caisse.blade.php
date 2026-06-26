<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Caisse</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <small>Imprimé le {{ \Carbon\Carbon::parse($date_hr)->format('d/m/Y H:i') }}</small>
    </div>

    <table>
        <tr>
            <th colspan="2" class="text-center">RÉSUMÉ</th>
        </tr>
        <tr>
            <td>Total dû</td>
            <td class="text-right">{{ number_format($totalDu, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Réduction</td>
            <td class="text-right">{{ number_format($totalReduction, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Net à payer</td>
            <td class="text-right">{{ number_format($netAPayer, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Total payé</td>
            <td class="text-right">{{ number_format($totalPaye, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Reste à payer</td>
            <td class="text-right">{{ number_format($totalRestant, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Bénéfice Détail</td>
            <td class="text-right">{{ number_format($beneficeVenteDetail, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Bénéfice Gros</td>
            <td class="text-right">{{ number_format($beneficeVenteGros, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Bénéfice Total</td>
            <td class="text-right">{{ number_format($beneficeVenteDetail + $beneficeVenteGros, 0, ",", " ") }}
                {{ $currency }}</td>
        </tr>
        <tr>
            <td>Valeur du Stock</td>
            <td class="text-right">{{ number_format($stockValue, 0, ",", " ") }} {{ $currency }}</td>
        </tr>
        <tr>
            <td>Quantité en Stock</td>
            <td class="text-right">{{ $stockQuantity }}</td>
        </tr>
    </table>
</body>

</html>