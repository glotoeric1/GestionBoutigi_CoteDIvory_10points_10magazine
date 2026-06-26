@php
    $somme = 0;
    $cc = " Fcfa";

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
<p style="text-align: center">Fait le : {{date('d-m-Y H:i:s', strtotime($date_hr))}}</p>
<table style="height: 50px; width: 603px;" border="2">
    <tbody>
        <tr style="height: 25px;">
            <td style="width: 135.633px; height: 25px; padding-left: 15px;">Titre :</td>
            <td style="width: 264.367px; height: 25px; padding-left: 5px;">{{$titre}} </td>
            <td style="width: 185px; height: 25px; padding-left: 5px;">Facture Nº : {{$numero }} </td>
        </tr>
    </tbody>
</table>
<br>

<table style="height: 20px; width: 603px;" border="3">
    <tbody>
        <tr style="height: 2.8px;">
            <td style="width: 420.983px; height: 2.8px; padding-left: 100px; padding-right: 100px;">Désignations </td>
        </tr>
        <tr style="height: 2.5px;">
            <td style="width: 420.983px; height: 2.5px; padding-left: 5px;">{{$descs}}</td>
        </tr>
    </tbody>
</table>
<table style="width: 603px; height: 50px;" border="3">
    <tbody>

        <tr>
            <td style="width: 200.567px; margin-left: 100em; padding-left: 282px;">Total</td>
            <td style="width: 130.633px;">{{number_format($total_ht)}}{{$cc}}</td>
        </tr>

    </tbody>
</table>
<h5 class="text-center">Arrête le present depense à la somme de {{$amountInWords}}</h5>
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