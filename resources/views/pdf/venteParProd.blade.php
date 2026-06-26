@php
    use Carbon\Carbon;

    /*
    |--------------------------------------------------------------------------
    | SAFE DEFAULTS
    |--------------------------------------------------------------------------
    */
    $cc = " F CFA";
    $i = 1;
    $totalLignes = 0;   // sum of all line items (grand total)

    // Core fallback values
    $entreprise = $entreprise ?? "Skill Codiing";
    $logo = $logo ?? public_path("backend/images/ssk.jpg");
    $contacts = $contacts ?? "73 23 16 45";
    $address = $address ?? "Bamako - Mali";
    $footer = $footer ?? "Skill Codiing - ERP System";

    $productName = $productName ?? "Tous les produits";
    $username = $username ?? "Inconnu";
    $operation = $operation ?? "VENTE";
    $title = $title ?? "";
    $date_hr = $date_hr ?? now();

    $totalM = $totalM ?? 0;  // Total TTC from vente table (not used in new totals)
    $totalR = $totalR ?? 0;  // Total reduction
    $totalV = $totalV ?? 0;  // Total quantity

    /*
    |--------------------------------------------------------------------------
    | SETTINGS OVERRIDE
    |--------------------------------------------------------------------------
    */
    $setting = \App\Models\settings::find(auth()->user()->id_setting ?? null);
    if ($setting) {
        $entreprise = $setting->app_name ?? $entreprise;
        $contacts = $setting->contact ?? $contacts;
        $address = $setting->address ?? $address;
        $footer = $setting->footer ?? $footer;
        $logo = $setting->logo ? public_path($setting->logo) : $logo;
    }

    /*
    |--------------------------------------------------------------------------
    | BOUTIQUE OVERRIDE
    |--------------------------------------------------------------------------
    */
    $boutique = \App\Models\Boutique::find(auth()->user()->id_boutigue ?? null);
    if ($boutique && $boutique->logo) {
        $logo = public_path($boutique->logo);
    }

    /*
    |--------------------------------------------------------------------------
    | FILTERED PRODUCT NAME (if any)
    |--------------------------------------------------------------------------
    */
    if (request()->filled('id_prod')) {
        $produit = \App\Models\Stock::find(request('id_prod'));
        $productName = $produit->libelle ?? $produit->libelle ?? 'Produit #' . request('id_prod');
    }
@endphp

<!-- ================= HEADER ================= -->
<div style="text-align:center; margin-bottom:10px;">
    <img src="{{ $logo }}" style="width:100%; height:110px; object-fit:contain;">
    <h2 style="margin:5px 0;">{{ $entreprise }}</h2>
    <small>{{ $contacts }} | {{ $address }}</small>
</div>

<hr>

<!-- ================= TITLE ================= -->
<h3 style="text-align:center;">Rapport : {{ strtoupper($operation) }}</h3>
<p style="text-align:center;">{{ $title }}</p>
<p style="text-align:center;">Date : {{ Carbon::parse($date_hr)->format('d/m/Y H:i:s') }}</p>

<!-- ================= SUMMARY ================= -->
<table width="100%" border="1" cellpadding="6" style="border-collapse:collapse;">
    <tr>
        <td><b>Produit</b></td>
        <td>{{ $productName }}</td>
        <td><b>Utilisateur</b></td>
        <td>{{ $username }}</td>
    </tr>
    <tr>
        <td><b>Total Quantité</b></td>
        <td>{{ $totalV }}</td>
        <td></td>
        <td></td>
    </tr>
</table>

<br>

<!-- ================= ITEMS TABLE ================= -->
<table width="100%" border="1" cellpadding="6" style="border-collapse:collapse; font-size:12px;">
    <thead style="background:#f2f2f2;">
        <tr>
            <th>N°</th>
            <th>Produit</th>
            <th>Prix</th>
            <th>Qté</th>
            <th>Montant</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($carts as $vente)
            @foreach($vente->items as $item)
                @php
                    // Fetch product name safely
                    $prod = \App\Models\Stock::find($item->id_prod);
                    $prodName = $prod->libelle ?? $prod->libelle ?? 'Produit #' . $item->id_prod;

                    // Line amount
                    $montantLigne = $item->montant ?? 0;
                    $totalLignes += $montantLigne;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $prodName }}</td>
                    <td>{{ number_format($item->prix, 0, ",", " " ?? 0) }}{{ $cc }}</td>
                    <td>{{ $item->quantite ?? 0 }}</td>
                    <td>{{ number_format($montantLigne, 0, ",", " ") }}{{ $cc }}</td>
                    <td>{{ Carbon::parse($vente->created_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="6" style="text-align:center;">Aucune donnée disponible</td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>

<!-- ================= TOTALS (based on line sums) ================= -->
<table width="100%" border="1" cellpadding="6" style="border-collapse:collapse;">
    <tr>
        <td><b>Total Général (lignes)</b></td>
        <td>{{ number_format($totalLignes, 0, ",", " ") }}{{ $cc }}</td>
    </tr>
    <tr>
        <td><b>Réduction</b></td>
        <td>{{ number_format($totalR, 0, ",", " ") }}{{ $cc }}</td>
    </tr>
    <tr>
        <td><b>Total Net</b></td>
        <td>{{ number_format($totalLignes - $totalR, 0, ",", " ") }}{{ $cc }}</td>
    </tr>
</table>

<!-- ================= FOOTER ================= -->
<p style="text-align:center; margin-top:20px; font-size:11px;">
    {!! $footer !!}
</p>