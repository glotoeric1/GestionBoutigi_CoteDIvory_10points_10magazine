@php
   $datass=[]; 
@endphp
<h4> le bar code </h4>
<table style="height: 122px; width: 817px;">
<tbody>
    @if (count($datass)>0)
        @foreach ($datass as $data)
            <tr style="height: 117.8px;">
                <td style="width: 183.4px; height: 117.8px;">&nbsp; 
                    {{$data->barcode}} &nbsp;
                    <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->generate('Make me into an QrCode!')) }} ">
                </td>
            </tr>
        @endforeach
    @endif

</tbody>
</table>
<p>&nbsp;</p>