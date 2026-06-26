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
    $setting = auth()->user() ? \App\Models\settings::find(auth()->user()->id_setting) : \App\Models\settings::first();

    if ($setting) {
        $entreprise = $setting->app_name ?? $entreprise;
        $titles = $setting->title ?? $titles;
        $contacts = $setting->contact ?? $contacts;
        $address = $setting->address ?? $address;
        $footer = $setting->footer ?? $footer;
        $types = $setting->types ?? $types;
        $logo = $setting->logo2 ?? $logo;
    }

    $boutique = auth()->user() != null &&
        auth()->user()->roles == 'Super Admin'
            ? \App\Models\Boutique::find(session('selected_boutique_id'))
            : \App\Models\Boutique::find( auth()->user() ? auth()->user()->id_boutigue : $data->id_boutique);

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
    <title>Facture_vente_&_Bon_livraison_n°{{ $num_vente }}</title>
</head>

<body>
    @if (session('cart'))
        <div style="page-break-after: always;">
            <p style="text-align: center">
                <img src="{{ $logo }}" alt="Application boutigue" width="100%" height="18%">
            </p>
            <h4 style="text-align: center">BORDEREAU DE LIVRAISION N°: {{ $num_liv }}</h4>
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
                            {{ $nom ?? '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 5px;">
                            Contact :
                        </td>
                        <td style="padding-left: 5px;">
                            {{ $contact ?? '—' }}
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
                        <th style="padding-left: 5px; text-align:left">
                            Provenance de magasin
                        </th>
                        <th style="text-align:center;">Qté</th>
                        <th style="text-align:center;">Valider</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('cart') as $cart)
                        <tr>
                            <td style="padding-left: 5px; text-align:left;">
                                {{ $cart['produit'] }}
                            </td>
                            <td>
                                {{ $cart['nom_stock'] ?? '-' }}
                            </td>
                            <td style="text-align:center;">
                                {{ $cart['qte'] }}
                            </td>

                            <td style="text-align:center;">
                                
                            </td>

                        </tr>
                        @php
                            $somme += $cart['qte'] * $cart['prix'];
                        @endphp
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
                            <p> {{ $username != null ? 'Agent: ' . $username : '' }} </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>&nbsp;</p>
            <div class="footer" style="width: 100%;  text-align: center;  position: fixed;  bottom: 0px;">
                <p> {{ $footer }} </p>
            </div>
        </div>
    @endif
    <div>
        <p style="text-align: center">
            <img src="{{ $logo }}" alt="Application boutigue" width="100%" height="18%">
        </p>
        <h4 style="text-align: center">FACTURE N°: {{ $num_vente }}</h4>
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
                        {{ $nom ?? '—' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 5px;">
                        Contact :
                    </td>
                    <td style="padding-left: 5px;">
                        {{ $contact ?? '—' }}
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%;" border="2">
            <thead>
                <tr style="height: 2.8px;">
                    <th style="padding-left: 5px; text-align:left;">
                        Désignations
                    </th>
                    <th style="padding-left: 5px; text-align:center;">Prix</th>
                    <th style="padding-left: 5px; text-align:center;">Qté</th>
                    <th style="padding-left: 5px; text-align:center;">Montant</th>
                </tr>
            </thead>
            <tbody>

                @if ($operation != 'update')
                    @foreach (session('cart') as $cart)
                        <tr>
                            <td style="padding-left: 5px; text-align:left;">
                                {{ $cart['produit'] }}
                            </td>

                            <td style="padding-left: 5px; text-align:center;">
                                {{ number_format($cart['prix']) }}{{ $cc }}
                            </td>
                            <td style="padding-left: 5px; text-align:center;">
                                {{ $cart['qte'] }}
                            </td>
                            <td style="padding-left: 5px; text-align:center;">
                                {{ number_format($cart['qte'] * $cart['prix']) }}{{ $cc }}
                            </td>
                        </tr>
                        @php
                            $somme += $cart['qte'] * $cart['prix'];
                        @endphp
                    @endforeach
                @else
                    @foreach ($carts as $cart)
                        <tr>
                            <td style="padding-left: 5px; text-align:left;">
                                {{ $cart->ShowProdNameVente($cart->id_prod) }}
                            </td>
                            <td style="padding-left: 5px; text-align:center;">
                                {{ number_format($cart->prix) }}{{ $cc }}
                            </td>
                            <td style="padding-left: 5px; text-align:center;">
                                {{ $cart->quantite }}
                            </td>
                            <td style="padding-left: 5px; text-align:center;">
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
                @if ($reduction != '0')
                    <tr>
                        <th colspan="3" style="padding-left: 5px; text-align:center;">Reduction :</th>
                        <td style="padding-left: 5px; text-align:center;">
                            {{ number_format($reduction) }}{{ $cc }}</td>
                    </tr>
                @endif

                <tr>
                    <th colspan="3" style="padding-left: 5px; text-align:center;">Total HT :</th>
                    <td style="padding-left: 5px; text-align:center;">
                        {{ number_format($total_ht) }}{{ $cc }}</td>
                </tr>

                @if ($tva != 0)
                    <tr>
                        <th colspan="3" style="padding-left: 5px; text-align:center;">
                            Total TVA
                            @if ($tva == '0.05')
                                5%
                            @else
                                18%
                            @endif
                        </th>
                        <td style="padding-left: 5px; text-align:center;">
                            {{ number_format($total_tva) }}{{ $cc }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" style="padding-left: 5px; text-align:center;">Total TTC :</th>
                        <td style="padding-left: 5px;">{{ number_format($total_ttc) }}{{ $cc }}</td>
                    </tr>
                @endif
            </tfoot>
        </table>
        <h5 class="text-center">Arrête le present achat à la somme de {{ ucfirst($amountInWords) }}</h5>
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
                        <p>{{ $username != null ? 'Agent: ' . $username : '' }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>

        <div class="footer" style="width: 100%;  text-align: center;  position: fixed;  bottom: 0px;">
            <p> {{ $footer }} </p>
        </div>
    </div>
    @php
        session()->forget('cart');
        session('cart', []);
    @endphp
</body>

</html>
