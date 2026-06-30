@php
    // ---------- Load settings (exactly as in your old code) ----------
    $somme = 0;
    $cc = ' Fcfa';

    // Default values
    $entreprise = 'Skill Codiing';
    $logo = public_path('backend/images/ssk.jpg');
    $contacts = '73 23 16 45';
    $address = 'Garantibougou, Bamako - Mali';
    $footer = 'Produit de Skill Codiing <br> Tel: (+223) 83 85 90 08 / 73 23 16 45';
    $types = 'Digitaliser votre entreprise';
    $titles = '';

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

    $boutique = auth()->user() != null && auth()->user()->roles == 'Super Admin'
        ? \App\Models\Boutique::find(session('selected_boutique_id'))
        : \App\Models\Boutique::find(auth()->user() ? auth()->user()->id_boutigue : $data->id_boutique);

    if ($boutique) {
        $logo = $boutique->logo ? public_path($boutique->logo) : $logo;
    }

    // Convert logo to a usable asset URL (for PDF/HTML)
    $logoUrl = filter_var($logo, FILTER_VALIDATE_URL) ? $logo : asset($logo);

    // ---------- Prepare common data ----------
    $date_hr = $date_hr ?? now();
    $nom = $nom ?? '—';
    $contact = $contact ?? '—';
    $username = $username ?? '';
    $amountInWords = $amountInWords ?? '';

    // For invoice totals
    $total_ht = $total_ht ?? 0;
    $reduction = $reduction ?? 0;
    $tva = $tva ?? 0;
    $total_tva = $total_tva ?? 0;
    $total_ttc = $total_ttc ?? 0;

    // Determine which cart data to use
    $cartItems = [];
    if ($operation != 'update') {
        $cartItems = session('cart', []);
    } else {
        // $carts is the collection from DB (used in old code)
        $cartItems = $carts ?? [];
    }

    // Helper to format currency
    function fmt($number)
    {
        return number_format($number) . ' Fcfa';
    }

    // Helper to print a dashed line
    function dashedLine()
    {
        return '<div style="border-top: 1px dashed #000; margin: 6px 0;"></div>';
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture_vente_&_Bon_livraison_n°{{ $num_vente }}</title>
    <style>
        /* Print-friendly reset */
        body {
            margin: 0;
            padding: 10px;
            background: #eee;
        }

        .receipt-page {
            background: #fff;
            width: 300px;
            margin: 0 auto 20px auto;
            padding: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #000;
            page-break-after: always;
        }

        @media print {
            body {
                background: #fff;
            }

            .receipt-page {
                margin: 0 auto;
                page-break-after: always;
            }
        }

        /* Flex helpers */
        .flex-row {
            display: flex;
            justify-content: space-between;
        }

        .flex-end {
            display: flex;
            justify-content: flex-end;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .small {
            font-size: 10px;
        }

        .large {
            font-size: 14px;
        }

        .xlarge {
            font-size: 16px;
        }
    </style>
</head>

<body>

    {{-- ============================================================= --}}
    {{-- PAGE 1 : BORDEREAU DE LIVRAISON (only if cart exists) --}}
    {{-- ============================================================= --}}
    @if (session('cart') && count(session('cart')) > 0)
        <div class="receipt-page">
            {{-- HEADER --}}
            <div class="text-center mb-2">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo"
                        style="max-width:100%; height:auto; max-height:60px; display:block; margin:0 auto 5px;">
                @endif
                <div class="fw-bold large">{{ strtoupper($entreprise) }}</div>
                @if($address)
                    <div class="small">{{ strtoupper($address) }}</div>
                @endif
                <div class="small">Tél : {{ $contacts }}</div>
                <div class="fw-bold xlarge mt-1">BORDEREAU DE LIVRAISON</div>
                <div>N°: {{ $num_liv }}</div>
                <div class="small">Fait le : {{ $date_hr }}</div>
            </div>

            {!! dashedLine() !!}

            {{-- CLIENT & POINT DE VENTE --}}
            <div>
                <div class="flex-row"><span>Pointe de vente :</span><span>{{ $boutique->nom_boutique ?? '' }}</span></div>
                <div class="flex-row"><span>Client :</span><span>{{ $nom }}</span></div>
                <div class="flex-row"><span>Contact :</span><span>{{ $contact }}</span></div>
            </div>

            {!! dashedLine() !!}

            {{-- ARTICLES --}}
            <div>
                <div class="flex-row fw-bold small"
                    style="border-bottom:1px dotted #000; padding-bottom:2px; margin-bottom:4px;">
                    <span>Désignation</span>
                    <span>Provenance / Qté / Valider</span>
                </div>
                @foreach ($cartItems as $item)
                    <div class="flex-row">
                        <span>{{ $item['produit'] ?? $item->ShowProdNameVente($item->id_prod) }}</span>
                        <span>{{ $item['nom_stock'] ?? $item->nom_stock ?? '-' }}</span>
                    </div>
                    <div class="flex-end small mb-1">
                        <span>Qté: {{ $item['qte'] ?? $item->quantite ?? 0 }} &nbsp;&nbsp; Valider: ______</span>
                    </div>
                @endforeach
            </div>

            {!! dashedLine() !!}

            {{-- SIGNATURES --}}
            <div class="flex-row mt-2">
                <span>POUR CLIENT</span>
                <span>RECEPTIONNISTE</span>
            </div>
            <div class="flex-row mt-1">
                <span></span>
                <span>{{ $username ? 'Agent: ' . $username : '' }}</span>
            </div>

            {{-- FOOTER --}}
            @if($footer)
                <div class="text-center small mt-2" style="border-top:1px dashed #000; padding-top:6px;">
                    {!! $footer !!}
                </div>
            @endif
            <div class="text-center mt-1" style="letter-spacing:2px;">- - - - - - - - - - - - - - - - - - - -</div>
        </div>
    @endif

    {{-- ============================================================= --}}
    {{-- PAGE 2 : FACTURE / BON DE VENTE --}}
    {{-- ============================================================= --}}
    <div class="receipt-page">
        {{-- HEADER --}}
        <div class="text-center mb-2">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo"
                    style="max-width:100%; height:auto; max-height:60px; display:block; margin:0 auto 5px;">
            @endif
            <div class="fw-bold large">{{ strtoupper($entreprise) }}</div>
            @if($address)
                <div class="small">{{ strtoupper($address) }}</div>
            @endif
            <div class="small">Tél : {{ $contacts }}</div>
            <div class="fw-bold xlarge mt-1">BON DE VENTE</div>
            <div>N° Ticket: {{ $num_vente }}</div>
            <div class="small">Fait le : {{ $date_hr }}</div>
        </div>

        {!! dashedLine() !!}

        {{-- CLIENT & POINT DE VENTE --}}
        <div>
            <div class="flex-row"><span>Pointe de vente :</span><span>{{ $boutique->nom_boutique ?? '' }}</span></div>
            <div class="flex-row"><span>Client :</span><span>{{ $nom }}</span></div>
            <div class="flex-row"><span>Contact :</span><span>{{ $contact }}</span></div>
        </div>

        {!! dashedLine() !!}

        {{-- ARTICLES --}}
        <div>
            <div class="flex-row fw-bold small"
                style="border-bottom:1px dotted #000; padding-bottom:2px; margin-bottom:4px;">
                <span>Désignation</span>
                <span>Prix / Qté / Montant</span>
            </div>
            @php $somme = 0; @endphp
            @foreach ($cartItems as $item)
                @php
                    $produit = $item['produit'] ?? $item->ShowProdNameVente($item->id_prod);
                    $prix = $item['prix'] ?? $item->prix ?? 0;
                    $qte = $item['qte'] ?? $item->quantite ?? 0;
                    $montant = $qte * $prix;
                    $somme += $montant;
                @endphp
                <div class="flex-row">
                    <span>{{ $produit }}</span>
                    <span>{{ fmt($prix) }} / {{ $qte }} / {{ fmt($montant) }}</span>
                </div>
            @endforeach
        </div>

        {!! dashedLine() !!}

        {{-- TOTALS --}}
        <div class="text-right">
            <div>Sous-total : {{ fmt($total_ht) }}</div>
            @if($reduction != 0)
                <div>Réduction : -{{ fmt($reduction) }}</div>
            @endif
            <div class="fw-bold large" style="border-top:1px dashed #000; padding-top:4px; margin-top:4px;">
                TOTAL HT : {{ fmt($total_ht - $reduction) }}
            </div>
            @if($tva != 0)
                <div>TVA ({{ $tva == '0.05' ? '5%' : '18%' }}) : {{ fmt($total_tva) }}</div>
                <div class="fw-bold xlarge">TOTAL TTC : {{ fmt($total_ttc) }}</div>
            @endif
            <div class="small mt-1">Arrêté à : {{ ucfirst($amountInWords) }}</div>
        </div>

        {!! dashedLine() !!}

        {{-- SIGNATURES --}}
        <div class="flex-row mt-2">
            <span>POUR CLIENT</span>
            <span>RECEPTIONNISTE</span>
        </div>
        <div class="flex-row mt-1">
            <span></span>
            <span>{{ $username ? 'Agent: ' . $username : '' }}</span>
        </div>

        {{-- FOOTER --}}
        @if($footer)
            <div class="text-center small mt-2" style="border-top:1px dashed #000; padding-top:6px;">
                {!! $footer !!}
            </div>
        @endif
        <div class="text-center mt-1" style="letter-spacing:2px;">- - - - - - - - - - - - - - - - - - - -</div>
    </div>

    {{-- ============================================================= --}}
    {{-- CLEAR SESSION (same as old) --}}
    {{-- ============================================================= --}}
    @php
        session()->forget('cart');
        session('cart', []);
    @endphp

</body>

</html>