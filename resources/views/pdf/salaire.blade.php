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
<h4 style="text-align: center">Salaire de {{$mois}} - {{$years}}</h4>
<p style="text-align: center">Fait le :
    {{$date_hr}}
</p>
<table style="height: 50px; width: 603px;" border="2">
    <tbody>
        <tr style="height: 25px;">
            <td style="width: 100.633px; height: 25px; padding-left: 15px;">Nom :</td>
            <td style="width: 200px; height: 25px; padding-left: 5px;">{{$nom}} </td>
            <td style="width: 153px; height: 25px; padding-left: 5px;  padding-right: 7px;">Nº : {{$pay_number}} </td>
        </tr>
        <tr style="height: 25px;">
            <td style="width: 253px; height: 25px; padding-left: 5px; padding-right: 7px;">
                Salaire : {{number_format($salaire)}}{{$cc}}
            </td>
        </tr>
    </tbody>
</table>
<br>

<table style="height: 20px; width: 603px;" border="3">
    <tbody>
        <tr style="height: 2.8px;">
            <td style="width: 160.417px; height: 2.8px; padding-left: 30px;">Salaire</td>
            <td style="width: 120.417px; height: 2.8px; padding-left: 30px;">Montant Reçu</td>
            <td style="width: 90.417px; height: 2.8px; padding-left: 30px;">Restant</td>
            <td style="width: 138.417px; height: 2.8px; padding-left: 30px;">Bonus</td>
        </tr>
        <tr style="height: 2.5px;">
            <td style="width: 160.417px; height: 2.5px; padding-left: 2px;">{{number_format($salaire)}}{{$cc}}</td>
            <td style="width: 120.417px; height: 2.5px; padding-left: 2px;">{{number_format($montantRecu)}}{{$cc}}</td>
            <td style="width: 90.417px; height: 2.5px; padding-left: 2px;">{{number_format($montantRestant)}}{{$cc}}
            </td>
            <td style="width: 138.417px; height: 2.5px; padding-left: 2px;">{{number_format($bonus)}}{{$cc}}</td>
        </tr>
    </tbody>
</table>
<h5 class="text-center">Arrête le present salaire à la somme de {{$amountInWords}}</h5>
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