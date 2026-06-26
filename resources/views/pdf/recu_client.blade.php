@php
    $somme = 0;
    $cc = ' FCFA';

    // Default values (fallback)
    $entreprise = 'Skill Codiing';
    $logo = public_path('backend/images/ssk.jpg'); // important for PDF
    $contacts = '73 23 16 45';
    $address = 'Garantibougou, Bamako - Mali';
    $footer = 'Produit de Skill Codiing <br> Tel: (+223) 83 85 90 08 / 73 23 16 45';
    $types = 'Digitaliser votre entreprise';
    $titles = '';

    // Fetch setting (single row, no loop)
    $setting = \App\Models\settings::find($detail->id_setting);

    if ($setting) {
        $entreprise = $setting->app_name ?? $entreprise;
        $titles = $setting->title ?? $titles;
        $contacts = $setting->contact ?? $contacts;
        $address = $setting->address ?? $address;
        $footer = $setting->footer ?? $footer;
        $types = $setting->types ?? $types;
        $logo = $setting->logo2 ?? $logo;
    }

@endphp

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    .header {
        text-align: center;
        width: 100%;
    }

    .title {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th {
        background-color: #f2f2f2;

    }

    td {
        padding: 5px;
    }

    .no-border {
        border: none !important;
    }

    .footer {
        position: fixed;
        bottom: 0;
        text-align: center;
        width: 100%;
        font-size: 11px;
    }

    .w-100 {
        width: 100%;
    }
</style>

<!-- HEADER -->
<div class="header">
    <img src="{{ $logo }}" style="object-fit: cover; width: 100%; height: 100px;">
    {{-- <div><strong>{{ $entreprise }}</strong></div>
    <div>{{ $types }}</div>
    <div>{{ $contacts }}</div> --}}
</div>

<hr>

<div class="title" style="text-align:center;">
    Reçu N° : {{ $detail->num_mouvement }}
</div>

<!-- CLIENT INFO -->
<table class="no-border" style="width: 100%">
    <tr class="no-border">
        <td class="no-border" style="text-align:right;">
            <strong>Date :</strong> {{ $detail->created_at->format('d/m/Y à H:i:s') }}
        </td>
    </tr>
    <tr class="no-border">
        <td class="no-border">
            <strong>Prénom & Nom: </strong> {{ $detail->getClt($detail->client_id)->nom }}
        </td>
    </tr>

    <tr class="no-border">
      <td class="no-border">
          <strong>Adresse: </strong> {{ $detail->getClt($detail->client_id)->adresse }}
      </td>
  </tr>

  <tr class="no-border">
    <td class="no-border">
        <strong>Montant débuter: </strong> {{ number_format($detail->montant, 2, ',',  ' ') }}FCFA
    </td>
</tr>
</table>

<!-- SIGNATURES -->
<table class="no-border w-100">
    <tr class="no-border">
        <td class="no-border" style="text-align: left">
            <p style="text-decoration: underline;">Client</p>
        </td>
        <td class="no-border" style="text-align: right">
            <p style="text-decoration: underline;">
                <b>Gestionnaire</b>
            </p>

        </td>
    </tr>
</table>

<!-- FOOTER -->
<div class="footer">
    {{ $footer }}
</div>
