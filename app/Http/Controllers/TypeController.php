<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Models\Type;
use Carbon\Carbon;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $obj = new Type();
        $datas = $obj->getAll();
        $total = Type::where('id_setting', auth()->user()->id_setting)->count();
        return view("type.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        return view("type.create", compact("cats"));
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
            "nom_type" => ["required"],
            "categorie" => ["required"]
        ]);
        $obj = new Type();
        $datas = $request->all();
        $datas['id_setting'] = auth()->user()->id_setting;
        $data = $obj->StoreType($datas);
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
        $datas = Type::find($id);
        $cats = Categorie::where('id_setting', auth()->user()->id_setting)->get();
        return view("type.edit", compact("datas", "cats"));
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
        $datas = Type::find($id);
        $datas->nom_type = $request->input("nom_type");
        $datas->categorie = $request->input("categorie");
        $datas->update();
        if ($datas) {
            return redirect()->route("type.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new Type();
        $data = $obj->deleteType($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}