<?php

namespace App\Http\Controllers;

use App\Models\Employes;
use App\Models\Salaires;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalairesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $obj = new Salaires();
        $datas = $obj->getAll();
        $totalS = Salaires::whereMonth('created_at', Carbon::now()->month)
            ->where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->sum("montantRecu");
        $totalR = Salaires::whereMonth('created_at', Carbon::now()->month)
            ->where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->sum("montantRestant");
        $totalB = Salaires::whereMonth('created_at', Carbon::now()->month)
            ->where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->sum("bonus");
        return view("salaire.index", compact("datas", "totalS", "totalR", "totalB"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $datas = Employes::where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->get();
        return view("salaire.create", compact("datas"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $amountInWords = 0;


        $datas = $request->validate([
            "emp_id" => ['required'],
            "montantRecu" => ['required', "numeric"],
            "salaire" => ['required', "numeric"],
            "montantRestant" => ['required', "numeric"],
            "years" => ['required', "numeric"],
            "mois" => ['required'],
        ]);

        $obj = new Salaires();
        $datas["done_by"] = auth()->user()->name;
        $datas["id_setting"] = auth()->user()->id_setting;
        $datas["bonus"] = $request->bonus;
        $datas["pay_number"] = $obj->generatePayId();
        $datas["id_boutique"] = $boutiqueId;
        $amountInWords = $objAmount->convertAmountToWords($request->montantRecu);
        $data = $obj->StoreSalaires($datas);
        if ($data) {
            if ($request->btn == "PRINT") {
                $datass = [
                    'nom' => $obj->ShowName($request->emp_id),
                    'years' => $request->years,
                    'mois' => $request->mois,
                    'montantRecu' => $request->montantRecu,
                    "salaire" => $request->salaire,
                    "montantRestant" => $request->montantRestant,
                    'date_hr' => Carbon::now(),
                    'pay_number' => $datas["pay_number"],
                    'bonus' => $request->bonus,
                    'done_by' => $datas["done_by"],
                    'amountInWords' => $amountInWords,
                ];

                $pdf = PDF::loadView('pdf.salaire', $datass);
                return $pdf->download("Salaire_" . date('d-m-Y') . ".pdf");
            }
            return back()->with("succes", "Enregistrement effectué avec succès");
        }
        return back()->with("error", "Catégorie n'a pas été ajoutée!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $datas = Salaires::find($id);
        $emps = Employes::where('id_setting', auth()->user()->id_setting)
            ->where("id_boutique", $boutiqueId)
            ->get();
        return view("salaire.edit", compact("datas", "emps"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->types == "PAY") {
            $request->validate([
                "montant" => ['required', 'numeric']
            ]);

            $data = Salaires::find($id);
            $amount_pay = $data->montantRecu + $request->montant;
            $data->montantRecu = $amount_pay;
            $data->montantRestant = $data->salaire - $amount_pay;
            $dt = $data->update();

            if ($dt) {
                return redirect()->route("salaires.index")->with("succes", "Paiement effectué avec succès.");
            }
            return back()->with("error", "Mise à jour non effectué.");

        }

        $datas = $request->validate([
            "emp_id" => ['required'],
            "montantRecu" => ['required', "numeric"],
            "salaire" => ['required', "numeric"],
            "montantRestant" => ['required', "numeric"],
            "years" => ['required', "numeric"],
            "mois" => ['required'],
        ]);

        $obj = new Salaires();
        $datas["done_by"] = 1;
        $datas["bonus"] = $request->bonus;
        $data = $obj->updateSalaires($id, $datas);
        if ($data) {
            return redirect()->route("salaires.index")->with("succes", "Mise à jour effectué avec succès.");
        }
        return back()->with("error", "Mise à jour non effectué.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = new Salaires();
        $data = $obj->deleteSalaires($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function getsalaire($id)
    {
        $data = Employes::where('id', $id)->get();
        return response()->json($data);

    }

    public function PrintSalaire(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $obj = new Salaires();
        $amountInWords = 0;

        $datas = Salaires::where('pay_number', $request->pay_number)->first();

        if ($request->valider == "print") {
            $amountInWords = $objAmount->convertAmountToWords($datas['montantRecu']);
            $datass = [
                'nom' => $obj->ShowName($datas['emp_id']),
                'years' => $datas['years'],
                'mois' => $datas['mois'],
                'montantRecu' => $datas['montantRecu'],
                "salaire" => $datas['salaire'],
                "montantRestant" => $datas['montantRestant'],
                'date_hr' => Carbon::now(),
                'pay_number' => $datas["pay_number"],
                'bonus' => $datas['bonus'],
                'done_by' => $datas["done_by"],
                'amountInWords' => $amountInWords,
            ];
            $pdf = PDF::loadView('pdf.salaire', $datass);
            return $pdf->download("Salaire_" . date('d-m-Y') . ".pdf");
        }
    }

}