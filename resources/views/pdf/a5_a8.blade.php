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

    $boutique = auth()->user() != null &&
        auth()->user()->roles == 'Super Admin'
        ? \App\Models\Boutique::find(session('selected_boutique_id'))
        : \App\Models\Boutique::find(auth()->user() ? auth()->user()->id_boutigue : $data->id_boutique);

    if ($boutique) {
        $logo = $boutique->logo ? public_path($boutique->logo) : $logo;
    }

    // ---------- Paper size (set to 'A5', 'A6', 'A7', or 'A8') ----------
    $paperSize = $paperSize ?? 'A6';   // default: A6 (similar to thermal receipt width)
    $maxWidths = [
        'A5' => '420px',
        'A6' => '300px',
        'A7' => '210px',
        'A8' => '150px',
    ];
    $containerWidth = $maxWidths[$paperSize] ?? '300px';

    // ---------- Helper to format amount in words (if not already defined) ----------
    if (!function_exists('numberToWords')) {
        function numberToWords($number)
        {
            // You can keep your existing 'ucfirst($amountInWords)' logic.
            // For simplicity, we just return the number with currency.
            return number_format($number) . ' ' . ($cc ?? 'FCFA');
        }
    }
    $amountInWords = $amountInWords ?? numberToWords($total_ht);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture & Bon de livraison - {{ $num_vente ?? 'N°' }}</title>
    <style>
        /* Print and screen styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .receipt-page {
            background: #fff;
            padding: 5px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width:
                {{ $containerWidth }}
            ;
            font-size: 12px;
            color: #000;
            page-break-after: always;
            /* for printing */
        }

        .receipt-page:last-child {
            page-break-after: auto;
        }

        /* Keep all lines inside the container */
        .receipt-page * {
            max-width: 100%;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .flex-row {
            display: flex;
            justify-content: space-between;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .fs-small {
            font-size: 10px;
        }

        .fs-large {
            font-size: 15px;
        }

        .indent {
            padding-left: 12px;
        }

        /* For signature lines */
        .signature-line {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .signature-line span {
            border-top: 1px solid #000;
            padding-top: 4px;
            width: 45%;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- ============================================================
    PAGE 1 : BORDEREAU DE LIVRAISON (if cart exists)
    ============================================================ --}}
    @if (session('cart'))
        <div class="receipt-page">
            {{-- Header --}}
            <div class="text-center mb-1">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo"
                        style="max-width:100%; height:auto; max-height:60px; display:block; margin:0 auto 5px;">
                @endif
                <div class="fw-bold fs-large">{{ strtoupper($entreprise) }}</div>
                @if($address)
                    <div>{{ strtoupper($address) }}</div>
                @endif
                <div>Tél : {{ $contacts }}</div>
                <div class="fw-bold fs-large" style="margin-top:4px;">BORDEREAU DE LIVRAISON</div>
                <div>N°: {{ $num_liv ?? '—' }}</div>
                <div>Fait le : {{ $date_hr ?? now() }}</div>
            </div>

            <div class="divider"></div>

            {{-- Client & Point de vente --}}
            <div>
                <div class="flex-row"><span>Pointe de vente :</span><span>{{ $boutique->nom_boutique ?? '—' }}</span></div>
                <div class="flex-row"><span>Client :</span><span>{{ $nom ?? '—' }}</span></div>
                <div class="flex-row"><span>Contact :</span><span>{{ $contact ?? '—' }}</span></div>
            </div>

            <div class="divider"></div>

            {{-- Articles (with quantity, provenance) --}}
            <div>
                <div class="flex-row fw-bold fs-small"
                    style="border-bottom:1px dotted #000; padding-bottom:2px; margin-bottom:4px;">
                    <span>Désignation</span>
                    <span>Provenance / Qté</span>
                </div>
                @foreach (session('cart') as $cart)
                    <div class="flex-row">
                        <span>{{ $cart['produit'] }}</span>
                        <span>{{ $cart['nom_stock'] ?? '-' }} / {{ $cart['qte'] }}</span>
                    </div>
                    {{-- Optional: line for validation --}}
                    <div class="flex-row fs-small" style="padding-left:10px;">
                        <span>Valider : ________</span>
                        <span></span>
                    </div>
                    @php $somme += $cart['qte'] * $cart['prix']; @endphp
                @endforeach
            </div>

            <div class="divider"></div>

            {{-- Signature area --}}
            <div class="signature-line">
                <span>POUR CLIENT</span>
                <span>RECEPTIONNISTE</span>
            </div>
            @if(isset($username))
                <div class="text-right fs-small">{{ 'Agent: ' . $username }}</div>
            @endif

            {{-- Footer --}}
            <div class="text-center fs-small mt-1" style="border-top:1px dashed #000; padding-top:6px;">
                {!! $footer !!}
            </div>
            <div class="text-center" style="letter-spacing:2px; margin-top:6px;">- - - - - - - - - - - - - - - - - - - -
            </div>
        </div>
    @endif

    {{-- ============================================================
    PAGE 2 : FACTURE (always displayed)
    ============================================================ --}}
    <div class="receipt-page">
        {{-- Header --}}
        <div class="text-center mb-1">
            @if($logo)
                <img src="{{ $logo }}" alt="Logo"
                    style="max-width:100%; height:auto; max-height:60px; display:block; margin:0 auto 5px;">
            @endif
            <div class="fw-bold fs-large">{{ strtoupper($entreprise) }}</div>
            @if($address)
                <div>{{ strtoupper($address) }}</div>
            @endif
            <div>Tél : {{ $contacts }}</div>
            <div class="fw-bold fs-large" style="margin-top:4px;">FACTURE</div>
            <div>N°: {{ $num_vente ?? '—' }}</div>
            <div>Fait le : {{ $date_hr ?? now() }}</div>
        </div>

        <div class="divider"></div>

        {{-- Client & Point de vente --}}
        <div>
            <div class="flex-row"><span>Pointe de vente :</span><span>{{ $boutique->nom_boutique ?? '—' }}</span></div>
            <div class="flex-row"><span>Client :</span><span>{{ $nom ?? '—' }}</span></div>
            <div class="flex-row"><span>Contact :</span><span>{{ $contact ?? '—' }}</span></div>
        </div>

        <div class="divider"></div>

        {{-- Articles --}}
        <div>
            <div class="flex-row fw-bold fs-small"
                style="border-bottom:1px dotted #000; padding-bottom:2px; margin-bottom:4px;">
                <span>Désignation</span>
                <span>Prix / Qté / Montant</span>
            </div>
            @php $somme = 0; @endphp
            @if($operation != 'update')
                @foreach (session('cart') as $cart)
                    <div class="flex-row">
                        <span>{{ $cart['produit'] }}</span>
                        <span>{{ number_format($cart['prix']) }}{{ $cc }} / {{ $cart['qte'] }} /
                            {{ number_format($cart['qte'] * $cart['prix']) }}{{ $cc }}</span>
                    </div>
                    @php $somme += $cart['qte'] * $cart['prix']; @endphp
                @endforeach
            @else
                @foreach ($carts as $cart)
                    <div class="flex-row">
                        <span>{{ $cart->ShowProdNameVente($cart->id_prod) }}</span>
                        <span>{{ number_format($cart->prix) }}{{ $cc }} / {{ $cart->quantite }} /
                            {{ number_format($cart->quantite * $cart->prix) }}{{ $cc }}</span>
                    </div>
                    @php $somme += $cart->montant; @endphp
                @endforeach
            @endif
        </div>

        <div class="divider"></div>

        {{-- Totals (with reduction and TVA) --}}
        <div class="text-right">
            @if(isset($reduction) && $reduction != 0)
                <div>Réduction : {{ number_format($reduction) }}{{ $cc }}</div>
            @endif
            <div class="fw-bold">Total HT : {{ number_format($total_ht) }}{{ $cc }}</div>
            @if(isset($tva) && $tva != 0)
                <div>Total TVA ({{ $tva == '0.05' ? '5%' : '18%' }}) : {{ number_format($total_tva) }}{{ $cc }}</div>
                <div class="fw-bold fs-large" style="border-top:1px dashed #000; padding-top:4px; margin-top:4px;">
                    Total TTC : {{ number_format($total_ttc) }}{{ $cc }}
                </div>
            @else
                <div class="fw-bold fs-large" style="border-top:1px dashed #000; padding-top:4px; margin-top:4px;">
                    TOTAL : {{ number_format($total_ht) }}{{ $cc }}
                </div>
            @endif
            @if(isset($amountInWords))
                <div class="fs-small" style="margin-top:4px;">Arrêté à : {{ ucfirst($amountInWords) }}</div>
            @endif
        </div>

        <div class="divider"></div>

        {{-- Signature area --}}
        <div class="signature-line">
            <span>POUR CLIENT</span>
            <span>RECEPTIONNISTE</span>
        </div>
        @if(isset($username))
            <div class="text-right fs-small">{{ 'Agent: ' . $username }}</div>
        @endif

        {{-- Footer --}}
        <div class="text-center fs-small mt-1" style="border-top:1px dashed #000; padding-top:6px;">
            {!! $footer !!}
        </div>
        <div class="text-center" style="letter-spacing:2px; margin-top:6px;">- - - - - - - - - - - - - - - - - - - -
        </div>
    </div>

    {{-- Clear session cart if needed (your old code did it at the end) --}}
    @php
        session()->forget('cart');
        session('cart', []);
    @endphp

</body>

</html>