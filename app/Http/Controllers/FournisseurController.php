<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\SupplieDetail;
use App\Models\Supply;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = new Fournisseur();
        $datas = $obj->getAll();
        $total = Fournisseur::where('id_setting', auth()->user()->id_setting)->count();
        return view("fournisseur.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("fournisseur.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datas = $request->validate([
            "nom_fournisseur" => ["required"],
            "contact_fournisseur" => ["required"],
        ]);
        $obj = new Fournisseur();
        $datas['adresse_fournisseur'] = $request->adresse_fournisseur;
        $datas['id_setting'] = auth()->user()->id_setting;
        $data = $obj->StoreFournisseur($datas);
        if ($data) {
            return back()->with("succes", "Enregistrement effectué avec succès.");
        }
        return back()->with("error", "Enregistrement non effectué!");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Fournisseur::find($id);
        $supplys = Supply::where('id_fournisseur', $id)->get();
        return view('fournisseur.show', compact('data', 'supplys'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $datas = Fournisseur::find($id);
        return view("fournisseur.edit", compact("datas"));
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
        $datas = Fournisseur::find($id);
        $datas->nom_fournisseur = $request->input("nom_fournisseur");
        $datas->adresse_fournisseur = $request->input("adresse_fournisseur");
        $datas->contact_fournisseur = $request->input("contact_fournisseur");
        $datas->email_fournisseur = $request->input("email_fournisseur");
        $datas->update();
        if ($datas) {
            return redirect()->route("fournisseur.index")->with("succes", "Mise à jour effectuée avec succès.");
        }
        return back()->with("error", "Mise à jour non effectuée!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = new Fournisseur();
        $data = $obj->deleteFournisseur($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}