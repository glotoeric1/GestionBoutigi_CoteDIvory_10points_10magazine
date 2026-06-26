<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CompteBancaire;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $depenseM = 0;
        $depenseDeport = 0;
        $depenseRetrait = 0;
        $depenseRemise = 0;
        $depenseM = 0;
        $banks = CompteBancaire::where('id_boutique', $boutiqueId)->get();
        $obj = new Bank();
        $datas = $obj->getAllLatest();
        $depenseT = Bank::whereMonth('created_at', Carbon::now()->month)
            ->where('id_boutique', $boutiqueId)
            ->get();
        if (count($depenseT) > 0) {
            foreach ($depenseT as $item) {
                //$depenseM += $item->montant;
                $depenseDeport += $item->montant_depot;
                $depenseRetrait += $item->montant_retrait;
                $depenseRemise += $item->montant_remise;
            }
            $depenseM = ($depenseRemise + $depenseDeport) - $depenseRetrait;
        }

        return view("banks.index", compact("datas", "depenseRemise", "depenseT", "banks", "depenseDeport", "depenseM", "depenseRetrait"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $banks = CompteBancaire::where('id_boutique', $boutiqueId)->get();
        return view("banks.create", compact("banks"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $sommeDepot = 0;
        $sommeRetrait = 0;
        $soldes = 0;
        $sommeRemise = 0;
        $datas = $request->validate([
            "numero_de_compte" => ['required'],
            "operation" => ['required'],
            "montant" => ['required', 'numeric'],
            "done_by" => ['required'],
            "dates" => ['required'],
        ]);
        $datas['id_user'] = auth()->user()->name;
        $datas['id_setting'] = auth()->user()->id_setting;
        $datas['id_boutique'] = $boutiqueId;
        $datas['descs'] = $request->descs;
        $datas['montant_retrait'] = 0;
        $datas['montant_remise'] = 0;
        $datas['montant_depot'] = 0;

        if ($request->operation == "Retrait") {
            $dataDb = Bank::where('numero_de_compte', $request->numero_de_compte)->where('id_boutique', $boutiqueId)->get();
            if (count($dataDb) > 0) {
                foreach ($dataDb as $item) {
                    $sommeDepot += $item->montant_depot;
                    $sommeRetrait += $item->montant_retrait;
                    $sommeRemise += $item->montant_remise;
                }

                $soldes = ($sommeRemise + $sommeDepot) - $sommeRetrait;
            }

            if ($request->montant > $soldes) {
                return back()->with("error", "Vous avez " . $soldes . " F dans votre compte");
            }

            $datas['montant_retrait'] = $request->montant;
        } elseif ($request->operation == "Remise") {
            $datas['montant_remise'] = $request->montant;
        } else {
            $datas['montant_depot'] = $request->montant;
        }

        $obj = new Bank();
        $data = $obj->StoreBank($datas);
        if ($data) {
            return back()->with("succes", "Enregistrement effectué avec succès");
        }
        return back()->with("error", "Enregistrement n'a pas été ajoutée!");
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
        $data = Bank::find($id);
        $banks = CompteBancaire::where('id_boutique', $boutiqueId)->get();
        return view("banks.edit", compact("data", 'banks'));
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
        //dd($request->all());
        $datas = $request->validate([
            "numero_de_compte" => ['required'],
            "operation" => ['required'],
            "montant" => ['required', 'numeric'],
            "done_by" => ['required'],
            "dates" => ['required'],
        ]);
        $datas['id_user'] = auth()->user()->name;
        $datas['descs'] = $request->descs;
        $obj = new Bank();
        $data = $obj->updateBank($id, $datas);
        if ($data) {
            return redirect()->route("banks.index")->with("succes", "Mise à jour effectué avec succès.");
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
        $obj = new Bank();
        $data = $obj->deleteBank($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function Recharche(Request $request)
    {
        //dd($request->all())
        $depenseM = 0;
        $depenseDeport = 0;
        $depenseRetrait = 0;
        $depenseRemise = 0;

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');

        $banks = CompteBancaire::where('id_boutique', $boutiqueId)->get();
        if (!empty($request->dateDebut) && !empty($request->dateFin) && !empty($request->numero_de_compte)) {
            $datas = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('numero_de_compte', $request->numero_de_compte)
                ->where('id_boutique', $boutiqueId)
                ->get();
            $depenseT = $datas;
            if (count($datas) > 0) {
                foreach ($datas as $item) {
                    $depenseM += $item->montant;
                    if ($item->operation == "Dépôt") {
                        $depenseDeport += $item->montant;
                    } elseif ($item->operation == "Retrait") {
                        $depenseRetrait += $item->montant;
                    } elseif ($item->operation == "Remise") {
                        $depenseRemise += $item->montant;
                    }
                }
            }
            /*
            $depenseM = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('numero_de_compte', $request->numero_de_compte)
                ->sum("montant");

            $depenseDeport = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('numero_de_compte', $request->numero_de_compte)
                ->where('operation', 'Dépôt')
                ->sum("montant");
            $depenseRetrait = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('numero_de_compte', $request->numero_de_compte)
                ->where('operation', 'Retrait')
                ->sum("montant");
            $depenseRemise = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('numero_de_compte', $request->numero_de_compte)
                ->where('operation', 'Remise')
                ->sum("montant");
            */

            return view("banks.index", compact("datas", "depenseT", "banks", "depenseDeport", "depenseM", "depenseRetrait", "depenseRemise"));

        } elseif (!empty($request->dateDebut) && !empty($request->dateFin)) {

            $datas = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('id_boutique', $boutiqueId)
                ->get();
            $depenseT = $datas;
            if (count($datas) > 0) {
                foreach ($datas as $item) {
                    $depenseM += $item->montant;
                    if ($item->operation == "Dépôt") {
                        $depenseDeport += $item->montant;
                    } elseif ($item->operation == "Retrait") {
                        $depenseRetrait += $item->montant;
                    } elseif ($item->operation == "Remise") {
                        $depenseRemise += $item->montant;
                    }
                }
            }
            /*
            $depenseM = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->sum("montant");

            $depenseDeport = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('operation', 'Dépôt')
                ->sum("montant");
            $depenseRetrait = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('operation', 'Retrait')
                ->sum("montant");
            $depenseRemise = Bank::whereDate('created_at', '>=', $request->dateDebut)
                ->whereDate('created_at', '<=', $request->dateFin)
                ->where('operation', 'Remise')
                ->sum("montant");
            */

            return view("banks.index", compact("datas", "depenseT", "banks", "depenseDeport", "depenseM", "depenseRetrait", "depenseRemise"));

        } elseif (!empty($request->numero_de_compte)) {
            $datas = Bank::where('numero_de_compte', $request->numero_de_compte)
                ->where('id_boutique', $boutiqueId)
                ->get();
            $depenseT = $datas;
            if (count($datas) > 0) {
                foreach ($datas as $item) {
                    $depenseM += $item->montant;
                    if ($item->operation == "Dépôt") {
                        $depenseDeport += $item->montant;
                    } elseif ($item->operation == "Retrait") {
                        $depenseRetrait += $item->montant;
                    } elseif ($item->operation == "Remise") {
                        $depenseRemise += $item->montant;
                    }
                }
            }
            /*
            $depenseM = Bank::where('numero_de_compte', $request->numero_de_compte)
                ->sum("montant");

            $depenseDeport = Bank::where('numero_de_compte', $request->numero_de_compte)
                ->where('operation', 'Dépôt')
                ->sum("montant");
            $depenseRetrait = Bank::where('numero_de_compte', $request->numero_de_compte)
                ->where('operation', 'Retrait')
                ->sum("montant");
            $depenseRemise = Bank::where('numero_de_compte', $request->numero_de_compte)
                ->where('operation', 'Remise')
                ->sum("montant");

                */
            return view("banks.index", compact("datas", "depenseT", "banks", "depenseDeport", "depenseM", "depenseRetrait", "depenseRemise"));
        }
        return redirect()->route("banks.index");
    }
}