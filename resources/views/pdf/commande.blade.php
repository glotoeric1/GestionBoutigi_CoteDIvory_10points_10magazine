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
        $logo = $setting->logo2 ?? $logo;
    }

    // $boutique = \App\Models\Boutique::find(auth()->user()->id_boutigue);

    // if ($boutique) {
    //     $logo = $boutique->logo ? public_path($boutique->logo) : $logo;
    // }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facture_commande_n°_{{ $numero_commande }}</title>
</head>

<body>
    <p style="text-align: center">
        <img src="{{ $logo }}" alt="Application boutigue" width="100%" height="18%">
    <p style="text-align: center; height: 2.4; margin-top: -25px">
    <h4 style="text-align: center; height: 2.4; display:none"> {{ $entreprise }} - {{ $types }} </h4>
    </p>
    <p style="text-align: center; height: 2.4; display:none"> {{ $contacts }} - {{ $address }}</p>
    </p>
    <h4 style="text-align: center">FACTURE N°: {{ $numero_commande }}</h4>
    <p style="text-align: center">Fait le : {{ date('d-m-Y H:i:s', strtotime($date_hr)) }}</p>

    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td>Fournisseur:</td>
                <td>{{ $fournisseur }} </td>
            </tr>
        </tbody>
    </table>
    <br>

    <table style="width: 100%;" border="2">
        <thead>
            <tr>
                <th>
                    Désignations
                </th>
                <th>Prix</th>
                <th>Qté</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @if ($operation == 'COMMANDE')
                @foreach (session('commande') as $cart)
                    <tr>
                        <td style="width: 135.983px; height: 2.5px; padding-left: 5px;">
                            {{ $cart['produit'] }}
                        </td>

                        <td style="text-align:center">
                            {{ number_format($cart['prix']) }}{{ $cc }}</td>
                        <td style="text-align:center">{{ $cart['qte'] }} </td>
                        <td style="text-align:center">
                            {{ number_format($cart['qte'] * $cart['prix']) }}{{ $cc }}
                        </td>
                    </tr>
                    @php
                        $somme += $cart['total'];
                    @endphp
                @endforeach
            @else
                @foreach ($carts as $cart)
                    <tr>
                        <td>
                            {{ $cart->getProductNom($cart->id_prod) }}
                        </td>
                        <td style="text-align:center">
                            {{ number_format($cart->prix) }}{{ $cc }}
                        </td>
                        <td style="text-align:center">
                            @if ($cart->qte_valider != '')
                                {{ $cart->qte_valider }}
                            @else
                                {{ $cart->qte_commander }}
                            @endif
                        </td>

                        <td style="text-align:center">
                            @if ($cart->qte_valider != '')
                                {{ number_format($cart->qte_valider * $cart->prix, 0, '',' ') }}
                            @else
                                {{ number_format($cart->qte_commander * $cart->prix, 0, '',' ') }}
                            @endif
                        </td>
                    </tr>
                    @php
                        if ($cart->qte_valider != '') {
                            $somme += $cart->qte_valider * $cart->prix;
                        } else {
                            $somme += $cart->qte_commander * $cart->prix;
                        }
                    @endphp
                @endforeach
                {{-- @php
                    $total_ht = $somme;
                @endphp --}}
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total HT :</th>
                <td style="text-align:center">{{ number_format($total_ht, 0, '', ' ') }}{{ $cc }}</td>
            </tr>
            @if ($fraisTransit != '')
                <tr>
                    <th colspan="3">Frais Transite :</th>
                    <td style="text-align:center">{{ number_format($fraisTransit, 0, '', ' ') }}{{ $cc }}</td>
                </tr>
            @endif
            @if ($fraisLogistique != '')
                <tr>
                    <th colspan="3">Frais Logistque :</th>
                    <td style="text-align:center">{{ number_format($fraisLogistique, 0, '', ' ') }}{{ $cc }}</td>
                </tr>
            @endif
            <tr>
                <th colspan="3">Total TTC :</th>
                <td style="text-align:center">
                    {{ number_format($total_ht + ($fraisLogistique + $fraisTransit), 0, '', ' ') }}{{ $cc }}
                </td>
            </tr>
        </tfoot>
    </table>
    <h5 class="text-center">Arrête le present commande à la somme de {{ ucfirst($amountInWords) }}</h5>
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td>
                    <p style="text-transform: uppercase; text-decoration: underline">
                        Agent {{ $entreprise }}
                    </p>
                </td>
                <td style="text-align:right">
                    <p style="text-decoration: underline">LE FOURNISSEUR</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Effectué par : {{ auth()->user()->name }} </p>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>

    <div class="footer" style="width: 100%;  text-align: center;  position: fixed;  bottom: 0px;">
        <p> {{ $footer }} </p>
    </div>
</body>

</html>

@php
    session()->forget('commande');
    session('commande', []);
@endphp
