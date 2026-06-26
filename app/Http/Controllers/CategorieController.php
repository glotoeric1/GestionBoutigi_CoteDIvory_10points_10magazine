<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $obj = new Categorie();
        $datas = $obj->getAll();
        $total = Categorie::where('id_setting', auth()->user()->id_setting)->count();
        return view("categorie.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("categorie.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "nom_categorie" => ["required"]
        ]);
        $datas = $request->all();
        $datas['id_setting'] = auth()->user()->id_setting;
        $obj = new Categorie();
        $data = $obj->StoreCategorie($datas);
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
        $datas = Categorie::find($id);
        return view("categorie.edit", compact("datas"));
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
        $datas = Categorie::find($id);
        $datas->nom_categorie = $request->input("nom_categorie");
        $datas->update();
        if ($datas) {
            return redirect()->route("categorie.index")->with("succes", "Mise à jour effectué avec succès.");
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
        $obj = new Categorie();
        $data = $obj->deleteCategorie($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}