@php
    $cc = " F cfa";
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
        @if($productName != '')
            <tr style="height: 25px;">
                <td style="width: 135.633px; height: 25px; padding-left: 15px;">Produit :</td>
                <td style="width: 460.367px; height: 25px; padding-left: 5px;">{{$productName}} </td>
            </tr>
        @endif
        <tr style="height: 25px;">
            <td style="width: 210.633px; height: 25px; padding-left: 15px;">Total Qté :</td>
            <td style="width: 460.367px; height: 25px; padding-left: 5px;">
                {{number_format($totalV)}}
            </td>
        </tr>
    </tbody>
</table>
<br>

<table style="height: 20px; width: 1030px;" border="3">
    <tbody>
        <tr style="height: 2.8px;">
            <td style="width: 100px; height: 2.8px; padding-left: 15px; font-size:12px;">Prix</td>
            <td style="width: 25px; height: 2.8px; padding-left: 30px; font-size:12px;">Qté</td>
            <td style="width: 110px; height: 2.8px; padding-left: 22px; font-size:12px;">Montant </td>
            <td style="width: 80px; height: 2.8px; padding-left: 22px; font-size:12px;">Date </td>
            <td style="width: 250px; height: 2.8px; padding-left: 22px; font-size:12px;">Effectué par</td>
        </tr>
        @foreach ($carts as $cart)
            <tr style="height: 2.5px;">
                <td style="width: 100px; height: 2.5px; padding-left: 3px; font-size:12px;">
                    {{number_format($cart->prix)}}{{$cc}}
                </td>
                <td style="width: 25px; height: 2.5px; padding-left: 3px; font-size:12px;">{{$cart->quantite}} </td>
                <td style="width: 110px; height: 2.5px; padding-left: 3px; font-size:12px;">
                    {{number_format($cart->quantite * $cart->prix)}}{{$cc}}
                </td>
                <td style="width: 80px; height: 2.5px; padding-left: 3px; font-size:12px;">
                    {{$cart->FormatDate($cart->created_at)}}
                </td>
                <td style="width: 250px; height: 2.5px; padding-left: 3px; font-size:12px;">
                    @if ($operation == "VENTE")
                        {{$cart->ShowUserNameVente($cart->username)}}
                    @else
                        {{$cart->ShowUserNameDette($cart->username)}}
                    @endif
                    @php
                        $totalPrix += ((int) $cart->prix - (int) $cart->ShowPriceAchat($cart->id_prod)) * (int) $cart->quantite;
                    @endphp

                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<table style="width: 1030px; height: 50px;" border="3">
    <tbody>

        @if($operation == "VENTE")
            <tr>
                <td style="width: 60px; margin-left: 100em; padding-left: 90px; font-size:12px;">Réduction :</td>
                <td style="width: 324px; font-size:12px; padding-left:2px;">{{number_format($totalR)}}{{$cc}}</td>
            </tr>
            <tr>
                <td style="width: 84px; margin-left: 100em; padding-left: 90px; font-size:12px;">Total :</td>
                <td style="width: 324px; font-size:12px; padding-left:2px; padding-right:16em;">
                    {{number_format($totalM)}}{{$cc}}
                </td>
            </tr>
            <tr>
                <td style="width: 84px; margin-left: 100em; padding-left: 40px; font-size:12px;">Total Après Réduction :
                </td>
                <td style="width: 324px; font-size:12px; padding-left:2px; padding-right:16em;">
                    {{number_format($totalM - $totalR)}}{{$cc}}
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
                <td style="width: 84px; margin-left: 100em; padding-left: 90px; font-size:12px;">Total :</td>
                <td style="width: 324px; font-size:12px; padding-left:2px; padding-right:16em;">
                    {{number_format($totalM)}}{{$cc}}
                </td>
            </tr>
            <tr>
                <td style="width: 84px; margin-left: 100em; padding-left: 90px; font-size:12px;">Montant Payé :</td>
                <td style="width: 324px; font-size:12px; padding-left:2px; padding-right:16em;">
                    {{number_format($totalM - $totalR)}}{{$cc}}
                </td>
            </tr>
            <tr>
                <td style="width: 82px; margin-left: 70em; padding-left: 40px; font-size:12px;">Montant Reste à Payer :</td>
                <td style="width: 324px; font-size:12px; padding-left:2px; padding-right:16em;">
                    {{number_format(abs($totalR))}}{{$cc}}
                </td>
            </tr>
            <tr>
                <td style="width: 100px; margin-left: 190em; padding-left: 205px; font-size:12px;">Total bénéfice :</td>
                <td style="width: 227px; font-size:12px; padding-left: 2px;">{{number_format(($totalPrix))}}{{$cc}}</td>
            </tr>
        @endif

    </tbody>
</table>
<p>&nbsp;</p>