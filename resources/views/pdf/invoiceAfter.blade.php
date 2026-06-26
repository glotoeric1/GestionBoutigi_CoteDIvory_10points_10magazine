@php
    $somme = 0;
    $cc = ' Fcfa';

    // Default values (fallback)
    $entreprise = 'Skill Codiing';
    $logo = public_path('backend/images/ssk.jpg'); // important for PDF
    $contacts = '73 23 16 45';
    $address = 'Garantibougou, Bamako - Mali';
    $footer = 'Produit de Skill Codiing <br> Tel: (+223) 83 85 90 08 / 73 23 16 45';
    $types = 'Digitaliser votre entreprise';
    $titles = '';

    // Fetch setting (single row, no loop)
    $setting = \App\Models\settings::find(auth()->user()->id_setting);

    if ($setting) {
        $entreprise = $setting->app_name ?? $entreprise;
        $titles = $setting->title ?? $titles;
        $contacts = $setting->contact ?? $contacts;
        $address = $setting->address ?? $address;
        $footer = $setting->footer ?? $footer;
        $types = $setting->types ?? $types;
        $logo = $setting->logo ?? $logo;
    }

    $boutique = \App\Models\Boutique::find(auth()->user()->id_boutigue);

    if ($boutique) {
        $logo = $boutique->logo ? public_path($boutique->logo) : $logo;
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facture_creance_n°_{{ $clientId }}</title>
</head>

<body>
    <p style="text-align: center">
        <img src="{{ $logo }}" alt="Application boutigue" width="100%" height="18%">
    </p>
    <h4 style="text-align: center">Facture {{ $operation }}</h4>
    <p style="text-align: center">Fait le : {{ date('d-m-Y H:i:s', strtotime($date_hr)) }}</p>
    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td colspan="4" style="padding-left: 5px; text-align:center">
                    Facture N° : {{ $clientId }}
                </td>
            </tr>
            <tr style="height: 25px;">
                <td style="padding-left: 15px;">Nom :</td>
                <td style="padding-left: 5px;">{{ $nom }} </td>
                <td style="padding-left: 15px;">Contact :</td>
                <td style="padding-left: 5px;">
                    {{ $contact }}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%;" border="2">
        <thead>
            <tr>
                <th style="padding-left: 5px; text-align:left">
                    Désignations
                </th>
                <th>Qté</th>
                <th>Prix</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $cart)
                <tr>
                    <td style="padding-left: 5px;">
                        {{ $cart['nom_produit'] }}
                    </td>
                    <td style="text-align:center">{{ $cart['quantite'] }} </td>
                    <td style="text-align:center">
                        {{ number_format($cart['prix']) }}{{ $cc }}
                    </td>
                    <td style="text-align:center">
                        {{ number_format($cart['prix'] * $cart['quantite']) }}{{ $cc }}
                    </td>
                </tr>
                @php
                    $somme += $cart['montant'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total HT :</th>
                <td style="text-align:center">{{ number_format($total_ht) }}{{ $cc }}</td>
            </tr>

            @if ($tva != '')
                <tr>
                    <th colspan="3">Total TVA @if ($tva == '0.05')
                            5%
                        @else
                            18%
                        @endif :</th>
                    <td style="text-align:center">{{ number_format($total_tva) }}{{ $cc }}</td>
                </tr>
                <tr>
                    <th colspan="3">Total TTC :</th>
                    <td style="text-align:center">{{ number_format($total_ttc) }}{{ $cc }}</td>
                </tr>
            @endif
            @if ($operation == 'DETTE' || $operation == 'créance')
                <tr>
                    <th colspan="3">Montant Payer :</th>
                    <td style="text-align:center">{{ number_format($montantDonner) }}{{ $cc }}</td>
                </tr>
                <tr>
                    <th colspan="3">Restant : </th>
                    @if ($tva != '')
                        <td style="text-align:center">
                            {{ number_format($total_ttc - $montantDonner) }}{{ $cc }}
                        </td>
                    @else
                        <td style="text-align:center">
                            {{ number_format($total_ht - $montantDonner) }}{{ $cc }}
                        </td>
                    @endif
                </tr>
            @endif
        </tfoot>
    </table>
    <h5 class="text-center">Arrête le present achat à la somme de {{ ucfirst($amountInWords) }}</h5>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    <p>POUR ACQUIT</p>
                </td>
                <td style="text-align: right">
                    <p>LE RECEPTIONNISTE</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right">
                    <p>Effectué par : {{ $username }} </p>
                </td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
</body>

</html>
