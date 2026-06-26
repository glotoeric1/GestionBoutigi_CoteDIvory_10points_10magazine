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
          <span style="font-size: 16px;"> Fait le : {{$datas->first()->created_at->format("d/m/Y")}} à
            {{$datas->first()->created_at->format("h:i")}} </span>
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered border-primary">
                <thead>
                  <tr>
                    <th>Total</th>
                    <th>Montant déja payer</th>
                    <th>Restant (a payer)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    @if($datas->first()->tva == "")
                      <td>{{number_format($datas->first()->total_ht)}} {{config('app.cc')}}</td>
                    @else
                      <td>{{number_format($datas->first()->total_ttc)}} {{config('app.cc')}}</td>
                    @endif
                    <td>{{number_format($datas->first()->montantDonner)}} {{config('app.cc')}}</td>
                    @if($datas->first()->tva == "")
                      @if ($datas->first()->total_ht <= $datas->first()->montantDonner)
                        <td class="btn btn-outline-success">0 {{config('app.cc')}}</td>
                      @else
                        <td class="btn btn-outline-danger">{{number_format($datas->first()->total_ht - $datas->first()->montantDonner)}} {{config('app.cc')}}
                        </td>
                      @endif
                    @else
                      @if ($datas->first()->total_ttc <= $datas->first()->montantDonner)
                        <td class="btn btn-outline-success">0 {{config('app.cc')}}</td>
                      @else
                        <td class="btn btn-outline-danger">{{number_format($datas->first()->total_ttc - $datas->first()->montantDonner)}}{{config('app.cc')}}
                        </td>
                      @endif
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <form action="{{ route("dette.update", [$datas->first()->id]) }}" method="post">
            @csrf
            @method("PUT")
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="montant">Montant</label>
                  <input type="hidden" name="clientId" value="{{$datas->first()->clientId}}">
                  <input type="number" name="montant" id="montants" value=""
                    class="form-control @error('montant') is-invalid @enderror" placeholder="">
                  @error("montant")
                    <span class="text-danger"> {{$message}}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="montant">Comments</label>
                  <textarea name="comments" id="" class="form-control montantPayer" cols="1" rows="2"></textarea>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default rounded-pill" data-dismiss="modal">Fermer</button>
          <button type="submit" class="btn btn-success rounded-pill">Confirmer</button>
        </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  </div>
@endsection