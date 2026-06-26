@php
    $cc = " F cfa";
    $totalPay = 0;

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
<h4 style="text-align: center">Facture de : {{$descs ?? "PAIEMENT D'AVANCE " }} </h4>
<h4 style="text-align: center">Produit : {{$prodName}} <br> Qté : {{$qte}} </h4>
<p style="text-align: center">Fait le :
    @if($operation == "AVANCE")
        {{date('d-m-Y H:i:s', strtotime($date_hr))}}
    @else
        {{$date_hr}}
    @endif
</p>
<table style="height: 50px; width: 603px;" border="2">
    <tbody>
        <tr style="height: 25px;">
            <td style="width: 100.633px; height: 25px; padding-left: 15px;">Nom :</td>
            <td style="width: 300px; height: 25px; padding-left: 5px;">{{$nom}} </td>
            <td style="width: 253px; height: 25px; padding-left: 5px;  padding-right: 7px;">Facture Nº : {{$clientId}}
            </td>
        </tr>
        <tr style="height: 25px;">
            <td style="width: 100.633px; height: 25px; padding-left: 15px;">Contact :</td>
            <td style="width: 300px; height: 25px; padding-left: 5px;">
                {{$contact}}
            </td>
            <td style="width: 253px; height: 25px; padding-left: 5px; padding-right: 7px;">
                Montant total : @if ($tva == "")
                    {{number_format($total_ht)}}{{$cc}}
                @else
                    {{number_format($total_ttc)}}{{$cc}}
                @endif
            </td>
        </tr>
    </tbody>
</table>
<br>

<table style="height: 20px; width: 603px;" border="3">
    <tbody>
        <tr style="height: 2.8px;">
            <td style="width: 135.983px; height: 2.8px; padding-left: 100px; padding-right: 100px;">Date et heure </td>
            <td style="width: 120.417px; height: 2.8px; padding-left: 30px;">Montant Payé</td>
            <td style="width: 157px; height: 2.8px; padding-left: 15px; padding-right: 30px;">Effectué par</td>
        </tr>

        @if($operation == "AVANCE")
            @foreach ($datas as $data)
                <tr style="height: 2.5px;">
                    <td style="width: 135.983px; height: 2.5px; padding-left: 2px;">
                        {{$data->formatDate($data->created_at)}} à {{$data->formatHour($data->created_at)}}
                    </td>

                    <td style="width: 120.417px; height: 2.5px; padding-left: 2px;">{{number_format($data->montantPay)}}{{$cc}}
                    </td>
                    <td style="width: 157px; height: 2.5px; padding-left: 2px;">{{$data->done_by}} </td>
                </tr>
                @php
                    $totalPay += $data->montantPay;
                @endphp
            @endforeach
        @else
            @php
                $totalPay = $montantPay;
            @endphp
            <tr style="height: 2.5px;">
                <td style="width: 135.983px; height: 2.5px; padding-left: 5px;">
                    {{$date_hr}}
                </td>

                <td style="width: 120.417px; height: 2.5px; padding-left: 2px;">{{number_format($montantPay)}}{{$cc}}</td>
                <td style="width: 157px; height: 2.5px; padding-left: 2px;">{{$username}} </td>

            </tr>
        @endif
    </tbody>
</table>
<table style="width: 603px; height: 50px;" border="3">
    <tbody>

        <tr>
            <td style="width: 142.417px; margin-left: 100em; padding-left: 190px;  padding-right: 3px;">Total HT :</td>
            <td style="width: 230px; padding-right: 125px; padding-left: 2px;">{{number_format($total_ht)}}{{$cc}}</td>
        </tr>
        @if($tva != "")
            <tr>
                <td style="width: 142.417px; margin-left: 100em; padding-left: 190px; padding-right: 3px;">Total TVA :</td>
                <td style="width: 230px; padding-right: 125px; padding-left: 2px;">{{number_format($total_tva)}}{{$cc}}</td>
            </tr>
            <tr>
                <td style="width: 142.417px; margin-left: 100em; padding-left: 190px; padding-right: 3px;">Total TTC :</td>
                <td style="width: 230px; padding-right: 125px; padding-left: 2px;">{{number_format($total_ttc)}}{{$cc}}</td>
            </tr>
        @endif
        <tr>
            <td style="width: 142.417px; margin-left: 100em; padding-left: 190px; padding-right: 3px;">Montant Payer :
            </td>
            <td style="width: 230px; padding-right: 125px; padding-left: 2px;">{{number_format($totalPay)}}{{$cc}}</td>
        </tr>
        <tr>
            <td style="width: 142.417px; margin-left: 100em; padding-left: 190px; padding-right: 3px;">Restant :</td>
            @if ($tva != '')
                <td style="width: 230px; padding-right: 125px; padding-left: 2px;">
                    {{number_format($total_ttc - $totalPay)}}{{$cc}}
                </td>
            @else
                <td style="width: 230px; padding-right: 125px; padding-left: 2px;">
                    {{number_format($total_ht - $totalPay)}}{{$cc}}
                </td>
            @endif
        </tr>

    </tbody>
</table>
<h5 class="text-center">Arrête le present paiement d'avance à la somme de {{$amountInWords}}</h5>
<table style="height: 60px;" width="663">
    <tbody>
        <tr style="height: 79.8px;">
            <td style="width: 331.1px; height: 30.8px;">
                <p>POUR ACQUIT</p>
                <p>&nbsp;</p>
                <p>________________________</p>
            </td>
            <td style="width: 331.1px; height: 30.8px;">
                <p>LE FOURNISSEUR</p>
                <p>&nbsp;</p>
                <p>______________________________</p>
                <p>Effectué par : {{auth()->user()->name}} </p>
            </td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>

<div class="footer" style="width: 100%;  text-align: center;  position: fixed;  bottom: 0px;">
    <p> {{$footer}} </p>
</div>