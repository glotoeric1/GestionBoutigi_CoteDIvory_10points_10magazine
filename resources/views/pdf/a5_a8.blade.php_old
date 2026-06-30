@php
    $somme = 0;
    $cc = " FCFA";

    // Default values (fallback)
    $entreprise = "Skill Codiing";
    $logo = public_path("backend/images/ssk.jpg"); // important for PDF
    $contacts = "73 23 16 45";
    $address = "Garantibougou, Bamako - Mali";
    $footer = "Produit de Skill Codiing <br> Tel: (+223) 83 85 90 08 / 73 23 16 45";
    $types = "Digitaliser votre entreprise";
    $titles = "";

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


@if($address == 'A5')
    <p style="text-align: center">
        <img src="{{$logo}}" alt="Application boutique" width="100%" height="18%">
    </p>
    <h4 style="text-align: center">Facture de : {{$operation2}}</h4>
    <p style="text-align: center">Fait le : {{date('d-m-Y H:i:s', strtotime($date_hr))}}</p>

    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 50%;">Nom :</td>
                <td style="width: 50%;">{{$nom}}</td>
            </tr>
            <tr>
                <td style="width: 50%;">Facture Nº :</td>
                <td style="width: 50%;">{{$clientId}}</td>
            </tr>
            <tr>
                <td style="width: 50%;">Contact :</td>
                <td style="width: 50%;">{{$contact}}</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="3">
        <tbody>
            <tr>
                <td>Désignations</td>
                <td>Prix</td>
                <td>Qté</td>
                <td>Montant</td>
            </tr>
            @foreach (session("cart") as $cart)
                <tr>
                    <td>{{$cart['produit']}}</td>
                    <td>{{number_format($cart['prix'])}}{{$cc}}</td>
                    <td>{{$cart['qte']}}</td>
                    <td>{{number_format($cart['qte'] * $cart['prix'])}}{{$cc}}</td>
                </tr>
                @php
                    $somme += $cart['total'];
                @endphp
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%;" border="3">
        <tbody>
            <tr>
                <td>Total HT :</td>
                <td>{{number_format($total_ht)}}{{$cc}}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer" style="text-align: center; font-size: 10px;">
        <p> {{$footer}} </p>
    </div>

@elseif ($address == 'A6')
    <p style="text-align: center">
        <img src="{{$logo}}" alt="Application boutique" width="100%" height="18%">
    </p>
    <p style="text-align: center">Fait le : {{date('d-m-Y H:i:s', strtotime($date_hr))}}</p>

    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 50%;">Nom :</td>
                <td style="width: 50%;">{{$nom}}</td>
            </tr>
            <tr>
                <td style="width: 50%;">Facture Nº :</td>
                <td style="width: 50%;">{{$clientId}}</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="3">
        <tbody>
            <tr>
                <td>Produit</td>
                <td>Prix</td>
                <td>Qté</td>
                <td>Montant</td>
            </tr>
            @foreach (session("cart") as $cart)
                <tr>
                    <td>{{$cart['produit']}}</td>
                    <td>{{number_format($cart['prix'])}}{{$cc}}</td>
                    <td>{{$cart['qte']}}</td>
                    <td>{{number_format($cart['qte'] * $cart['prix'])}}{{$cc}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%;" border="3">
        <tbody>
            <tr>
                <td>Total :</td>
                <td>{{number_format($total_ht)}}{{$cc}}</td>
            </tr>
        </tbody>
    </table>
    <div class="footer" style="text-align: center; font-size: 10px;">
        <p> {{$footer}} </p>
    </div>

@elseif ($address == 'A7')
    <p style="text-align: center">
        <img src="{{$logo}}" alt="Application boutique" width="100%" height="18%">
    </p>
    <p style="text-align: center">Date : {{date('d-m-Y H:i:s', strtotime($date_hr))}}</p>

    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 50%;">Nom :</td>
                <td style="width: 50%;">{{$nom}}</td>
            </tr>
            <tr>
                <td style="width: 50%;">Facture Nº :</td>
                <td style="width: 50%;">{{$clientId}}</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="3">
        <tbody>
            <tr>
                <td>Produit</td>
                <td>PU</td>
                <td>Qté</td>
                <td>Montant</td>
            </tr>
            @foreach (session("cart") as $cart)
                <tr>
                    <td>{{$cart['produit']}}</td>
                    <td>{{number_format($cart['prix'])}}{{$cc}}</td>
                    <td>{{$cart['qte']}}</td>
                    <td>{{number_format($cart['qte'] * $cart['prix'])}}{{$cc}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="text-align: center;">Total: {{number_format($total_ht)}}{{$cc}}</p>
    <div class="footer" style="text-align: center; font-size: 10px;">
        <p> {{$footer}} </p>
    </div>

@elseif ($address == 'A8')
    <p style="text-align: center">
        <img src="{{$logo}}" alt="App boutique" width="100%" height="18%">
    </p>

    <p style="text-align: center">Date: {{date('d-m-Y', strtotime($date_hr))}}</p>
    <table style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 50%;">Nom :</td>
                <td style="width: 50%;">{{$nom}}</td>
            </tr>
            <tr>
                <td style="width: 50%;">Facture Nº :</td>
                <td style="width: 50%;">{{$clientId}}</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="3">
        <tbody>
            <tr>
                <td>Produit</td>
                <td>PU</td>
                <td>Qté</td>
                <td>Total</td>
            </tr>
            @foreach (session("cart") as $cart)
                <tr>
                    <td>{{$cart['produit']}}</td>
                    <td>{{number_format($cart['prix'])}}{{$cc}}</td>
                    <td>{{$cart['qte']}}</td>
                    <td>{{number_format($cart['qte'] * $cart['prix'])}}{{$cc}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: center;">Total: {{number_format($total_ht)}}{{$cc}}</p>
    <div class="footer" style="text-align: center; font-size: 10px;">
        <p> {{$footer}} </p>
    </div>

@endif