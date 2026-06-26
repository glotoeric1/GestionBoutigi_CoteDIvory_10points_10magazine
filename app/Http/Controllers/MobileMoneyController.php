<?php

namespace App\Http\Controllers;

use App\Models\MobileMoney;
use Illuminate\Http\Request;

class MobileMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new MobileMoney();
        $datas = $obj->getAll();
        $total = MobileMoney::where('id_boutique', $boutiqueId)->count();
        return view("mobileMoney.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("mobileMoney.create");
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
            "contact" => ['required'],
            "types" => ['required'],
            "service" => ['required'],
            "montant" => ['required'],
        ]);
        $obj = new MobileMoney();
        $datas['id_setting'] = auth()->user()->id_setting;
        $datas['id_boutique'] = $boutiqueId;
        $data = $obj->StoreMobileMoney($datas);
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
        $data = MobileMoney::find($id);
        return view("mobileMoney.edit", compact("data"));
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
            "contact" => ['required'],
            "types" => ['required'],
            "service" => ['required'],
            "montant" => ['required'],
        ]);

        $datas = MobileMoney::find($id);
        $datas->contact = $request->input("contact");
        $datas->types = $request->input("types");
        $datas->service = $request->input("service");
        $datas->montant = $request->input("montant");
        $datas->update();
        if ($datas) {
            return redirect()->route("mobilemoney.index")->with("succes", "Mise à jour effectué avec succès.");
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
        $obj = new MobileMoney();
        $data = $obj->deleteMobileMoney($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}