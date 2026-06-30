@php
    // ---------- Load settings (your existing logic) ----------
    $entreprise = "Skill Codiing";
    $logo = "backend/images/ssk.jpg";
    $contacts = "73 23 16 45";
    $address = "Garantibougou, Bamako - Mali";
    $footer = "Produit de Skill Codiing <br> Tel: (+223) 83 85 90 08 / 73 23 16 45";
    $types = "Digitaliser votre entreprise";

    $setting = \App\Models\settings::find(auth()->user()->id_setting);
    if ($setting) {
        $entreprise = $setting->app_name ?? $entreprise;
        $contacts = $setting->contact ?? $contacts;
        $address = $setting->address ?? $address;
        $footer = $setting->footer ?? $footer;
        $types = $setting->types ?? $types;
        $logo = $setting->logo ?? $logo;
    }

    $boutique = \App\Models\Boutique::find(auth()->user()->id_boutigue);
    if ($boutique && $boutique->logo) {
        $logo = $boutique->logo;
    }

    // Convert logo to a usable asset URL (if it's not already)
    $logoUrl = filter_var($logo, FILTER_VALIDATE_URL) ? $logo : asset($logo);

    // ---------- Safe defaults for your receipt ----------
    $nom = $nom ?? 'Client';
    $clientId = $clientId ?? ('POS-' . time());      // fallback ticket number
    $date_hr = $date_hr ?? now();
    $total_ht = $total_ht ?? 0;
    $cc = $cc ?? 'FCFA';
    $amountReceived = $amountReceived ?? 0;
    $unpaid = $unpaid ?? ($total_ht - $amountReceived);
    $email = $email ?? '';                         // optional

    // Fixed headers (matches your image)
    $colDesignation = 'Désignation';
    $colPrice = 'Prix';
    $colQty = 'Qté';
    $colTotal = 'Montant';
    $dateFormat = 'd/m/Y H:i:s';                          // e.g. 26/06/2026 11:13:59
@endphp

<div
    style="font-family: 'Courier New', monospace; font-size: 12px; width: 100%; max-width: 300px; margin: 0 auto; padding: 8px; background: #fff; color: #000;">

    {{-- ============ HEADER ============ --}}
    <div style="text-align: center; margin-bottom: 8px;">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo"
                style="max-width: 100%; height: auto; max-height: 60px; display: block; margin: 0 auto 5px;">
        @endif
        <div style="font-weight: bold; font-size: 15px;">{{ strtoupper($entreprise) }}</div>
        @if($address)
            <div style="font-size: 12px;">{{ strtoupper($address) }}</div>
        @endif
        <div style="font-size: 11px;">Tél : {{ $contacts }}</div>
        @if($email)
            <div style="font-size: 11px;">{{ $email }}</div>
        @endif
        <div style="font-weight: bold; font-size: 15px; margin-top: 5px;">BON DE VENTE</div>
        <div style="margin-top: 2px;">N° Ticket: {{ $clientId }}</div>
    </div>

    {{-- Divider --}}
    <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

    {{-- ============ CLIENT ============ --}}
    <div style="margin-bottom: 4px;">
        <div><strong>CLIENT</strong></div>
        <div>Nom: {{ $nom }}</div>
    </div>

    {{-- Divider --}}
    <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

    {{-- ============ ARTICLES ============ --}}
    <div style="margin-bottom: 4px;">
        {{-- header line --}}
        <div
            style="display: flex; justify-content: space-between; font-weight: bold; font-size: 11px; border-bottom: 1px dotted #000; padding-bottom: 2px; margin-bottom: 4px;">
            <span>{{ $colDesignation }}</span>
            <span>{{ $colPrice }} / {{ $colQty }} / {{ $colTotal }}</span>
        </div>

        {{-- loop through cart --}}
        @php $somme = 0; @endphp
        @foreach (session("cart", []) as $cart)
            {{-- Product name + unit price --}}
            <div style="display: flex; justify-content: space-between;">
                <span>{{ $cart['produit'] }}</span>
                <span>{{ number_format($cart['prix']) }} {{ $cc }}</span>
            </div>
            {{-- Qty x Price = Total (indented to the right) --}}
            <div style="display: flex; justify-content: flex-end; font-size: 10px; margin-bottom: 4px;">
                <span>{{ $cart['qte'] }} x {{ number_format($cart['prix']) }} =
                    {{ number_format($cart['qte'] * $cart['prix']) }} {{ $cc }}</span>
            </div>
            @php $somme += $cart['qte'] * $cart['prix']; @endphp
        @endforeach
    </div>

    {{-- Divider --}}
    <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

    {{-- ============ SOUS‑TOTAL & TOTAL ============ --}}
    <div style="text-align: right; font-size: 13px;">
        <div>Sous-total: {{ number_format($total_ht) }} {{ $cc }}</div>
        <div
            style="font-weight: bold; font-size: 16px; border-top: 1px dashed #000; padding-top: 4px; margin-top: 4px;">
            TOTAL: {{ number_format($total_ht) }} {{ $cc }}
        </div>
    </div>

    {{-- ============ MONTANT REÇU & IMPAYE ============ --}}
    <div style="text-align: right; font-size: 12px; margin-top: 4px;">
        <div>Montant reçu: {{ number_format($amountReceived) }} {{ $cc }}</div>
        <div>IMPAYE: {{ number_format($unpaid) }} {{ $cc }}</div>
    </div>

    {{-- Divider --}}
    <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>

    {{-- ============ DATE & REMERCIEMENTS ============ --}}
    <div style="text-align: center; font-size: 11px; margin-top: 4px;">
        {{ date($dateFormat, strtotime($date_hr)) }}
    </div>
    <div style="text-align: center; font-size: 13px; margin-top: 8px;">
        Merci de votre achat!<br>À bientôt!
    </div>

    {{-- ============ FOOTER (from settings) ============ --}}
    @if($footer)
        <div style="text-align: center; font-size: 10px; margin-top: 6px; border-top: 1px dashed #000; padding-top: 6px;">
            {!! $footer !!}
        </div>
    @endif

    {{-- final decorative line --}}
    <div style="text-align: center; margin-top: 10px; letter-spacing: 2px;">- - - - - - - - - - - - - - - - - - - -
    </div>
</div>