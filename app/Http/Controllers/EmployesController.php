<?php

namespace App\Http\Controllers;

use App\Models\Employes;
use Illuminate\Http\Request;

class EmployesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new Employes();
        $datas = $obj->getAll();
        $totalS = Employes::where('id_boutique', $boutiqueId)->sum("salaire");
        return view("employe.index", compact("datas", "totalS"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("employe.create");
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
        $request->validate([
            "nom" => ["required"],
            "contact" => ["required"],
            "adresse" => ["required"],
            "post" => ["required"],
            "salaire" => ["required"],
            "dateStart" => ["required"],
            "emergency_name" => ["required"],
            "relationship" => ["required"],
            "contact_joint" => ["required"]
        ]);
        $datas = $request->all();
        $datas['id_setting'] = auth()->user()->id_setting;
        $datas['id_boutique'] = $boutiqueId;
        $obj = new Employes();
        $data = $obj->StoreEmployes($datas);
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
        $datas = Employes::find($id);
        return view("employe.edit", compact("datas"));
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
            "nom" => ["required"],
            "contact" => ["required"],
            "adresse" => ["required"],
            "post" => ["required"],
            "salaire" => ["required"],
            "dateStart" => ["required"],
            "emergency_name" => ["required"],
            "relationship" => ["required"],
            "contact_joint" => ["required"]
        ]);
        $obj = new Employes();
        $data = $obj->updateEmployes($id, $request->all());
        if ($data) {
            return redirect()->route("employes.index")->with("succes", "Mise à jour effectué avec succès.");
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
        $obj = new Employes();
        $data = $obj->deleteEmployes($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}