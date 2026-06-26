@extends("layout.main")
@section("main")

  <div class="row">
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Paiement de creance</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <!-- /.modal -->

          <h4>Paiement - {{$datas->first()->nom}} </h4>
            <h5 class="text-center">Dernier Paiement le <span class="btn btn-outline-success"> {{$datas->first()->updated_at->format("d/m/Y")}}</span></h5>
            <table class="table table-bordered border-primary">
              <thead>
                <tr>
                  <th>Prix</th>
                  <th>Qté</th>
                  <th>Total</th>
                  <th>Total déja Payé</th>
                  <th>Reste à Payer</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{number_format($datas->first()->montant)}} {{config('app.cc')}}</td>
                  <td>{{$datas->first()->qte}}</td>
                  <td>{{number_format($datas->first()->qte * $datas->first()->montant)}} {{config('app.cc')}}</td>
                  <td>{{number_format($datas->first()->CalculateSum($datas->first()->clientId))}} {{config('app.cc')}}</td>
                  @if ($datas->first()->total == $datas->first()->CalculateSum($datas->first()->clientId) )
                    <td class="btn btn-outline-success">0 {{config('app.cc')}}</td>
                  @else
                    @if ($datas->first()->tva !="")
                      <td class="btn btn-outline-danger">{{number_format($datas->first()->total_ttc - $datas->first()->CalculateSum($datas->first()->clientId))}} {{config('app.cc')}}</td>
                    @else
                      <td class="btn btn-outline-danger">{{number_format($datas->first()->total - $datas->first()->CalculateSum($datas->first()->clientId))}} {{config('app.cc')}}</td>
                    @endif
                    
                  @endif
                  
                </tr>
              </tbody>
            </table>
            @if ($datas->first()->montantPay < $datas->first()->qte * $datas->first()->montant)
            <form action="{{ route("paiementavances.update", [$datas->first()->id]) }}" method="post">
              @csrf
              @method("PUT")
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="montantPay">Montant</label>
                    <input type="number" name="montantPay" id="montantPays" value="" class="form-control @error('montantPay') is-invalid @enderror"  placeholder="">
                    @error("montantPay")
                        <span class="text-danger"> {{$message}}</span>
                    @enderror
                </div>
                </div>
                <input type="hidden" name="totalApayer" value="{{$datas->first()->qte * $datas->first()->montant}}">
                <input type="hidden" name="montantDejaPayer" value="{{$datas->first()->CalculateSum($datas->first()->clientId)}}">
                <input type="hidden" name="id" value="{{$datas->first()->id}}">
                <input type="hidden" name="clientId" value="{{$datas->first()->clientId}}">
                <input type="hidden" name="types" value="PAY">
                <input type="hidden" name="option" value="AVANCE">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <a href="{{ route('paiementavances.index') }}" class="btn btn-default rounded-pill">Fermer</a>
              <button type="submit" class="btn btn-outline-primary rounded-pill">Confirmer</button>
            </div>
          </form>
          @endif
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  </div>
@endsection