@php
    $cc = " F cfa";
    $i = 1;
    $totalPrix = 0;

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
<p style="text-align: center">
    <img src="{{$logo}}" alt="Application boutigue" width="100%" height="18%">
<p style="text-align: center; height: 2.4; margin-top: -25px">
<h4 style="text-align: center; height: 2.4; display:none"> {{$entreprise}} - {{$types}} </h4>
</p>
<p style="text-align: center; height: 2.4; display:none"> {{$contacts}} - {{$address}}</p>
</p>
<h4 style="text-align: center">Facture de : {{$operation}}</h4>
<p style="text-align: center">{{$title}}</p>
<p style="text-align: center">Fait le : {{date('d-m-Y H:i:s', strtotime($date_hr))}}</p>
<table style="height: 50px; width: 1030px;" border="2">
    <tbody>
        <tr style="height: 25px;">
            <td style="width: 210.633px; height: 25px; padding-left: 15px;">Nom du vendeur (se) :</td>
            <td style="width: 460.367px; height: 25px; padding-left: 5px;">{{$username}} </td>
        </tr>
    </tbody>
</table>
<br>

<table style="height: 20px; width: 1030px;" border="3">
    <tbody>
        <tr style="height: 2.8px;">
            <td style="width: 20px; height: 2.8px; padding-left: 13px; font-size:12px;">Nº:</td>
            <td style="width: 100px; height: 2.8px; padding-left: 82px; padding-right: 100px; font-size:12px;">
                Désignations </td>
            <td style="width: 70px; height: 2.8px; padding-left: 30px; font-size:12px;">Prix</td>
            <td style="width: 20px; height: 2.8px; padding-left: 13px; font-size:12px;">Qté</td>
            <td style="width: 90px; height: 2.8px; padding-left: 22px; font-size:12px;">Montant </td>
            <td style="width: 90px; height: 2.8px; padding-left: 22px; font-size:12px;">Date </td>
        </tr>
        @foreach ($carts as $cart)
            <tr style="height: 2.5px;">
                <td style="width: 20px; height: 2.5px; padding-left: 2px; font-size:12px;">{{$i++}} </td>
                <td style="width: 100px; height: 2.5px; padding-left: 5px; font-size:12px;">
                    @if ($operation == "VENTE")
                        {{$cart->ShowProdNameVente($cart->id_prod)}}
                    @else
                        {{$cart->ShowProdNameVente($cart->id_prod)}}
                    @endif
                </td>
                <td style="width: 70px; height: 2.5px; padding-left: 2px; font-size:12px;">
                    {{number_format($cart->prix)}}{{$cc}}
                </td>
                <td style="width: 20px; height: 2.5px; padding-left: 2px; font-size:12px;">{{$cart->quantite}} </td>

                <td style="width: 100px; height: 2.5px; padding-left: 2px; font-size:12px;">
                    {{number_format($cart->quantite * $cart->prix)}}{{$cc}}
                </td>
                <td style="width: 100px; height: 2.5px; padding-left: 2px; font-size:12px;">
                    {{$cart->FormatDate($cart->created_at)}}
                </td>

            </tr>
            @php
                $totalPrix += ((int) $cart->prix - (int) $cart->ShowPriceAchat($cart->id_prod)) * (int) $cart->quantite;
            @endphp
        @endforeach
    </tbody>
</table>
<table style="width: 1030px; height: 50px;" border="3">
    <tbody>

        @if($operation == "VENTE")
            <tr>
                <td style="width: 180px; margin-left: 100em; padding-left: 282px; font-size:12px;">Réduction :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format($totalR)}}{{$cc}}</td>
            </tr>
            <tr>
                <td style="width: 180px; margin-left: 100em; padding-left: 282px; font-size:12px;">Total :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format($totalM)}}{{$cc}}</td>
            </tr>
            <tr>
                <td style="width: 180px; margin-left: 100em; padding-left: 282px; font-size:12px;">Total Après Réduction :
                </td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format($totalM - $totalR)}}{{$cc}}
                </td>
            </tr>
            <tr>
                <td style="width: 100px; margin-left: 190em; padding-left: 205px; font-size:12px;">Total bénéfice :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">
                    {{number_format(($totalPrix - $totalR))}}{{$cc}}
                </td>
            </tr>
        @else
            <tr>
                <td style="width: 180px; margin-left: 100em; padding-left: 282px; font-size:12px;">Total :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format($totalM)}}{{$cc}}</td>
            </tr>
            <tr>
                <td style="width: 180px; margin-left: 100em; padding-left: 282px; font-size:12px;">Montant Payé :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format($totalM - $totalR)}}{{$cc}}
                </td>
            </tr>
            <tr>
                <td style="width: 180px; margin-left: 100em; padding-left: 282px; font-size:12px;">Montant Reste à Payer :
                </td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format(abs($totalR))}}{{$cc}}</td>
            </tr>
            <tr>
                <td style="width: 100px; margin-left: 190em; padding-left: 205px; font-size:12px;">Total bénéfice :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format(($totalPrix))}}{{$cc}}</td>
            </tr>
        @endif

    </tbody>
</table>
<p>&nbsp;</p>


</div>