<?php

namespace App\Http\Controllers;

use App\Models\CompteBancaire;
use Illuminate\Http\Request;

class CompteBancaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new CompteBancaire();
        $datas = $obj->getAll();
        $total = CompteBancaire::where('id_boutique', $boutiqueId)->count();
        return view("comptes.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("comptes.create");
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
        $datas = $request->validate([
            "numero" => ["required"],
            "bank" => ["required"],
            "type" => ["required"],
            "titulaire" => ["required"],
        ]);
        $datas = $request->all();
        $obj = new CompteBancaire();
        $datas["id_setting"] = auth()->user()->id_setting;
        $datas["id_boutique"] = $boutiqueId;
        $data = $obj->StoreCompteBancaire($datas);
        if ($data) {
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
        $datas = CompteBancaire::find($id);
        return view("comptes.edit", compact("datas"));
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
        $request->validate([
            "numero" => ["required"],
            "bank" => ["required"],
            "type" => ["required"],
            "titulaire" => ["required"],
        ]);
        $obj = new CompteBancaire();
        $datas = $obj->updateCompteBancaire($id, $request->all());
        if ($datas) {
            return redirect()->route("comptes.index")->with("succes", "Mise à jour effectué avec succès.");
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
        $obj = new CompteBancaire();
        $data = $obj->deleteCompteBancaire($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}