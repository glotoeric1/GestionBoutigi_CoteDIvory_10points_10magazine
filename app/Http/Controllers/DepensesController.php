<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Depenses;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $obj = new Depenses();
        $datas = $obj->getAll();
        $depenseT = Depenses::whereMonth('created_at', Carbon::now()->month)
            ->where("id_boutique", $boutiqueId)
            ->get();
        $depenseM = Depenses::whereMonth('created_at', Carbon::now()->month)
            ->where("id_boutique", $boutiqueId)
            ->sum("montant");

        return view("depense.index", compact("datas", "depenseT", "depenseM"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $depot = 0;
        $remise = 0;
        $retrait = 0;
        $total = 0;
        $numero_de_compte = '';
        $caisses = DB::table('compte_bancaires')
            ->leftJoin('banks', 'compte_bancaires.numero', '=', 'banks.numero_de_compte')
            ->where('compte_bancaires.id_setting', auth()->user()->id_setting)
            ->where("compte_bancaires.id_boutique", $boutiqueId)
            ->get();
        if (count($caisses) > 0) {
            foreach ($caisses as $item) {
                if ($item->type == "Caisse") {
                    $depot += $item->montant_depot;
                    $remise += $item->montant_remise;
                    $retrait += $item->montant_retrait;
                    $numero_de_compte = $item->numero_de_compte;
                }
            }

            $total = ($depot + $remise) - $retrait;
        }

        return view("depense.create", compact("total", "numero_de_compte"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = new Depenses();
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $desc = "Rapport de Depense";
        $operation = "DEPENSE";

        $datas = $request->validate([
            "titre" => ["required"],
            "montant" => ["required"],
            "dates" => ["required"],
            "done_by" => ["required"]
        ]);
        $datas['id_setting'] = auth()->user()->id_setting;

        if ($request->montant > $request->caisse) {
            return back()->with("error", "Vous avez " . $request->caisse . " F cfa dans la caisse.");
        }
        $datas['id_user'] = auth()->user()->name;
        $datas['id_boutique'] = $boutiqueId;
        $datas['numero'] = (new Depenses())->generateDepenseId();

        $operationBank['numero_de_compte'] = $request->numero_de_compte;
        $operationBank['numero'] = $datas['numero'];
        $operationBank['operation'] = 'Retrait';
        $operationBank['montant'] = $request->montant;
        $operationBank['montant_retrait'] = $request->montant;
        $operationBank['montant_depot'] = 0;
        $operationBank['montant_remise'] = 0;
        $operationBank['done_by'] = $request->done_by;
        $operationBank['dates'] = $request->dates;
        $operationBank['descs'] = $request->descs;
        $operationBank['id_user'] = auth()->user()->name;
        $operationBank['id_setting'] = auth()->user()->id_setting;
        $operationBank['id_boutique'] = $boutiqueId;

        $obj = new Depenses();
        $data = $obj->StoreDepenses($datas);
        (new Bank())->StoreBank($operationBank);
        if ($data) {

            if ($request->valider == "print") {
                $amountInWords = $objAmount->convertAmountToWords($request->montant);
                $datass = [
                    'numero' => $datas['numero'],
                    'titre' => $request->titre,
                    'descs' => $request->descs,
                    'total_ht' => $request->montant,
                    'date_hr' => $request->dates,
                    "username" => $operationBank['id_user'],
                    "operation" => $operation,
                    "desc" => $desc,
                    "amountInWords" => $amountInWords,
                ];
                $pdf = Pdf::loadView('pdf.depense', $datass);
                session()->flash('succes', 'Votre depense à été effectué');
                return $pdf->download("depense_" . $request->numero_de_compte . ".pdf");
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
        $data = Depenses::find($id);
        $depot = 0;
        $remise = 0;
        $retrait = 0;
        $total = 0;
        $numero_de_compte = '';
        $caisses = DB::table('compte_bancaires')
            ->leftJoin('banks', 'compte_bancaires.numero', '=', 'banks.numero_de_compte')
            ->where('id_setting', auth()->user()->id_setting)
            ->get();
        if (count($caisses) > 0) {
            foreach ($caisses as $item) {
                if ($item->type == "Caisse") {
                    $depot += $item->montant_depot;
                    $remise += $item->montant_remise;
                    $retrait += $item->montant_retrait;
                    $numero_de_compte = $item->numero_de_compte;
                }
            }

            $total = ($depot + $remise) - $retrait;
        }

        return view("depense.edit", compact("data", "total", "numero_de_compte"));
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
        $datas = $request->validate([
            "titre" => ["required"],
            "descs" => ["required"],
            "montant" => ["required"],
            "dates" => ["required"],
            "done_by" => ["required"]
        ]);
        if ($request->montant < $request->total) {
            return back()->with("error", "Vous avez " . $request->total . " F cfa dans la caisse.");
        }
        $datas['id_user'] = auth()->user()->name;

        $operationBank['numero_de_compte'] = $request->numero_de_compte;
        $operationBank['operation'] = 'Retrait';
        $operationBank['montant'] = $request->montant;
        $operationBank['montant_retrait'] = $request->montant;
        $operationBank['montant_depot'] = 0;
        $operationBank['montant_remise'] = 0;
        $operationBank['done_by'] = $request->done_by;
        $operationBank['dates'] = $request->dates;
        $operationBank['descs'] = $request->descs;
        $operationBank['id_user'] = auth()->user()->name;

        $obj = new Depenses();
        $data = $obj->updateDepenses($id, $datas);
        if ($data) {
            Bank::where('numero', $request->numero)->update($operationBank);
            return redirect()->route("depenses.index")->with("succes", "Mise à jour effectué avec succès.");
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
        $obj = new Depenses();
        Bank::where('numero', Depenses::find($id)->numero)->delete();
        $data = $obj->deleteDepenses($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function Recharche(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        if (!empty($request->dateDebut) && !empty($request->dateFin)) {
            $datas = Depenses::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_boutique', $boutiqueId)
                ->get();
            $depenseT = $datas;


            $depenseM = Depenses::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_boutique', $boutiqueId)
                ->sum("montant");

            return view("depense.index", compact("datas", "depenseT", "depenseM"));

        }
        return redirect()->route("depenses.index");
    }

    public function PrintDepense(Request $request)
    {
        $objAmount = new CurrencyConverterController();
        $amountInWords = 0;
        $desc = "Rapport de Depense";
        $operation = "DEPENSE";

        $datas = Depenses::where('numero', $request->numero)->first();

        if ($request->valider == "print") {
            $amountInWords = $objAmount->convertAmountToWords($request->montant);
            $datass = [
                'numero' => $datas['numero'],
                'titre' => $datas['titre'],
                'descs' => $datas['descs'],
                'total_ht' => $datas['montant'],
                'date_hr' => $datas['dates'],
                "username" => $datas['id_user'],
                "operation" => $operation,
                "desc" => $desc,
                "amountInWords" => $amountInWords,
            ];
            $pdf = Pdf::loadView('pdf.depense', $datass);
            session()->flash('succes', 'Votre depense à été effectué');
            return $pdf->download("depense_" . $request->numero_de_compte . ".pdf");
        }
    }
}