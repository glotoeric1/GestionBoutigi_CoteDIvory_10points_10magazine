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
    $setting = \App\Models\settings::first();

    if ($setting) {
        $entreprise = $setting->app_name ?? $entreprise;
        $titles = $setting->title ?? $titles;
        $contacts = $setting->contact ?? $contacts;
        $address = $setting->address ?? $address;
        $footer = $setting->footer ?? $footer;
        $types = $setting->types ?? $types;
        $logo = $setting->logo2 ?? $logo;
    }

    $boutique = auth()->user()->roles == "Super Admin" ? \App\Models\Boutique::find(session('selected_boutique_id')) : \App\Models\Boutique::find(auth()->user()->id_boutigue);

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
    <title>Bon_livraison_n°_{{ $num_vente }}</title>
</head>

<body>
    <p style="text-align: center">
        <img src="{{ $logo }}" alt="Application boutigue" width="100%" height="18%">
    </p>
    <h4 style="text-align: center">BORDEREAU DE LIVRAISON N°: {{ $num_vente }}</h4>
    <p style="text-align: center">Fait le : {{ $date_hr }}</p>

    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td colspan="2" style="text-align: center; text-transform: uppercase;">
                    Pointe de vente : {{ $boutique->nom_boutique }}
                </td>
            </tr>
            <tr>
                <td style="padding-left: 5px;">
                    Client :
                </td>
                <td style="padding-left: 5px;">
                    {{ $nom ?? '-' }}
                </td>
            </tr>
            <tr>
                <td style="padding-left: 5px;">
                    Contact :
                </td>
                <td style="padding-left: 5px;">
                    {{ $contact ?? '-' }}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%;" border="2">
        <thead>
            <tr>
                <th style="padding-left: 5px; text-align:left;">
                    Désignations
                </th>
                <th style="text-align:left">
                    Provenance de magasin
                </th>
                <th style="text-align:center;">Qté</th>
                <th style="text-align:center;">Valider</th>
            </tr>
        </thead>
        <tbody>
            
                @foreach ($carts as $cart)
                    <tr>
                        <td style="padding-left: 5px;">
                            {{ $cart->ShowProdNameVente($cart->id_prod) }}
                        </td>
                        <td style="padding-left: 5px;">
                            {{ $cart->ShowEntrepotName($cart->stock_id) }}
                        </td>
                        <td style="padding-left: 5px; text-align:center">
                            {{ $cart->quantite }}
                        </td>
                        <td style="text-align: center"></td>
                    </tr>                    
                @endforeach
        </tbody>
    </table>
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td>
                    <p>POUR CLIENT</p>
                </td>
                <td style="text-align:right;">
                    <p>RECEPTIONNISTE</p>
                </td>
            </tr>

            <tr>
                <td></td>
                <td style="text-align:right;">
                    <p> {{ $username != null ? 'Agent: '. $username : '' }} </p>
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
