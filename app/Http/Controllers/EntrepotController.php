<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use Illuminate\Http\Request;
use App\Models\Entrepot;
use App\Models\settings;

class EntrepotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->id == 1) {
            $datas = Entrepot::latest()->get();
            return view("entrepot.index", compact("datas"));
        }
        $datas = Entrepot::where("id_setting", auth()->user()->id_setting)->get();
        return view("entrepot.index", compact("datas"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userSittingId = auth()->user()->id_setting;
        $magazines = Entrepot::where('id_setting', $userSittingId)->get();
        if (auth()->user()->id != 1) {
            if (count($magazines) == 10) {
                return back()->with('info', "Le nombre maxi de creation de boutique est atteint.");
            }
        }

        //$settings = settings::where('id', $userSittingId)->latest()->get();
        //$boutiques = Boutique::where('id_setting', $userSittingId)->get();

        $settings = settings::latest()->get();
        $boutiques = Boutique::get();
        return view("entrepot.create", compact("settings", "boutiques"));
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
            "nom_entrepot" => "required",
            "id_boutique" => "required",
            "id_setting" => "required"
        ]);

        $check = Entrepot::create($datas);

        if ($check) {
            return back()->with('succes', "Le magasin {$datas['nom_entrepot']} est enregistré avec succès");
        }
        return back()->with("error", "Une erreur s'est produite lors de la création du magasin");
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
        //Get the data of the entrepot to edit
        $data = Entrepot::findOrFail($id);
        $settings = settings::latest()->get();
        $boutiques = Boutique::get();
        return view("entrepot.edit", compact("data", "settings", "boutiques"));
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
        //validate the request
        $datas = $request->validate([
            "nom_entrepot" => "required",
            "id_boutique" => "required",
            "id_setting" => "required"
        ]);
        //Get the data of the entrepot to update
        $entrepot = Entrepot::findOrFail($id);

        //Check if the save was successful
        if ($entrepot) {
            $updateMagasin = $entrepot->update($datas);
            if ($updateMagasin) {
                return redirect()->route('entrepot.index')->with('succes', "Le magasin est modifier avec succès");
            } else {
                return back()->with('error', 'Modification non effectué avec succès. Svp réessayer prochainement');
            }
        }
        return back()->with('info', "L'information est introuvable");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}