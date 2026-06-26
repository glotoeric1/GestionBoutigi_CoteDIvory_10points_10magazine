<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoutiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = new Boutique();
        $datas = $obj->getAll();

        return view("boutique.index", compact("datas"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$userSittingId = auth()->user()->id_setting;
        $settings = settings::latest()->get();
        $boutiques = Boutique::latest()->get();
        if (Auth::user()->id != 1) {
            if (count($boutiques) == 10) {
                return back()->with('info', "Le nombre maxi de creation de boutique est atteint.");
            }
        }
        return view("boutique.create", compact("settings"));
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
            "gerant_boutique" => ["required"],
            "nom_boutique" => ["required"],
            "adresse" => ["required"],
            "contact" => ["required"],
            "contact_gerant" => ["required"],
            "id_setting" => ["required"],
            "logo" => ["required", "mimes:jpeg,png,jpg,", "max:2048"],
        ]);
        $obj2 = new settings();
        $datas = $request->all();
        $datas["logo"] = $obj2->UploadImage($request->logo);
        $obj = new Boutique();
        $data = $obj->StoreBoutique($datas);
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
        $datas = Boutique::find($id);
        $settings = settings::latest()->get();
        return view("boutique.edit", compact("datas", "settings"));
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

        $obj = new settings();

        $datas = Boutique::find($id);
        $datas->gerant_boutique = $request->input("gerant_boutique");
        $datas->nom_boutique = $request->input("nom_boutique");
        $datas->adresse = $request->input("adresse");
        $datas->contact = $request->input("contact");
        $datas->contact_gerant = $request->input("contact_gerant");
        $datas->type = $request->input("id_setting");
        $datas->logo = $obj->UploadImage($request->logo);
        $datas->update();
        if ($datas) {
            return redirect()->route("boutique.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new Boutique();
        $data = $obj->deleteBoutique($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}