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
    <title>{{ $desc }}_{{ $num_vente }}</title>
</head>

<body>
    <p style="text-align: center">
        <img src="{{ $logo }}" alt="Application boutigue" width="100%" height="18%">
    <p style="text-align: center; height: 2.4; margin-top: -25px">
    <h4 style="text-align: center; height: 2.4; display:none"> {{ $entreprise }} - {{ $types }} </h4>
    </p>
    <p style="text-align: center; height: 2.4; display:none"> {{ $contacts }} - {{ $address }}</p>
    </p>
    <h4 style="text-align: center">PROFORMANT Nº : {{ $num_vente }}</h4>
    <p style="text-align: center">Fait le : {{ date('d-m-Y H:i:s', strtotime($date_hr)) }}</p>
    <table style="width: 100%; height: 50px;" border="2">
        <tbody>
            <tr style="height: 25px;">
                <td style="padding-left: 15px;">Prénom & Nom :</td>
                <td style="padding-left: 5px;">{{ $nom }} </td>
            </tr>
            <tr style="height: 25px;">
                <td style="padding-left: 15px;">Contact :</td>
                <td style="padding-left: 5px;">
                    {{ $contact }}
                </td>
            </tr>
        </tbody>
    </table>
    <br>

    <table style="width: 100%; height: 50px;" border="2">
        <thead>
            <tr>
                <th style="width: 135.983px;">Désignations</th>
                <th style="width: 106.417px;">Prix</th>
                <th style="width: 61.0667px;">Qté</th>
                <th style="width: 112.733px;">Montant </th>
            </tr>
        </thead>
        <tbody>
            @if ($operation != 'update')
                @foreach (session('cart') as $cart)
                    <tr>
                        <td style="width: 135.983px; height: 2.5px; padding-left: 5px;">
                            {{ $cart['produit'] }}
                        </td>
                        <td style="width: 106.417px; height: 2.5px; padding-left: 5px;">
                            {{ number_format($cart['prix']) }}{{ $cc }}
                        </td>
                        <td style="width: 61.0667px; height: 2.5px; padding-left: 5px;">
                            {{ $cart['qte'] }}
                        </td>
                        <td style="width: 112.733px; height: 2.5px; padding-left: 5px;">
                            {{ number_format($cart['prix'] * $cart['qte']) }}{{ $cc }}
                        </td>
                    </tr>
                    @php
                        $somme += $cart['total'];
                    @endphp
                @endforeach
            @else
                @foreach ($carts as $cart)
                    <tr>
                        <td style="padding-left: 5px;">
                            {{ $cart->nom_produit }}
                        </td>
                        <td style="text-align:center">
                            {{ number_format($cart->prix) }}{{ $cc }}
                        </td>
                        <td style="text-align:center">
                            {{ $cart->quantite }}
                        </td>
                        <td style="text-align:center">
                            {{ number_format($cart->quantite * $cart->prix) }}{{ $cc }}
                        </td>
                    </tr>
                    @php
                        $somme += $cart->montant;
                    @endphp
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:center">Montant total :</td>
                <td style="text-align:center">{{ number_format($somme) }}{{ $cc }}</td>
            </tr>
            @if ($tva != '')
                <tr>
                    <td style="text-align:center">Total TVA @if ($tva == '0.05')
                            5%
                        @else
                            18%
                        @endif :</td>
                    <td style="text-align:center">{{ number_format($total_tva) }}{{ $cc }}</td>
                </tr>
                <tr>
                    <td style="text-align:center">Total TTC :</td>
                    <td style="text-align:center">{{ number_format($total_ttc) }}{{ $cc }}</td>
                </tr>
            @endif
        </tfoot>
    </table>

    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="text-align:left;">
                    <p style="text-decoration: underline;">POUR ACQUIT</p>
                </td>
                <td style="text-align:right;">
                    <p style="text-decoration: underline;">RECEPTIONNISTE</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <p style="text-align:right;">Effectué par : {{ auth()->user()->name }} </p>
                </td>
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
    session()->forget('cart');
    session('cart', []);
@endphp
