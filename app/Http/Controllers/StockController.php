<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\settings;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Stock::where('id_setting', Auth::user()->id_setting)->latest()->get();
        return view('stock.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $settings = settings::get();
        return view('stock.create', compact('settings'));
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
            "libelle" => 'required',
            "id_setting" => 'required',
        ]);

        $saveMagasin = Stock::create($datas);
        if ($saveMagasin) {
            return back()->with('succes', "Le magasin {$datas['libelle']} est enregistré avec succès");
        } else {
            return back()->with('error', 'Enregistrement non effectué avec succès. Svp réessayer prochainement');
        }
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
        $settings = settings::get();
        $boutiques = Boutique::get();
        $data = Stock::find($id);
        if ($data) {
            return view('stock.edit', compact('settings', 'data', 'boutiques'));
        } else {
            return back()->with('info', "L'information est introuvable");
        }
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
            "libelle" => 'required',
            "id_setting" => 'required',
        ]);

        $stock = Stock::find($id);
        if ($stock) {
            $updateMagasin = $stock->update($datas);
            if ($updateMagasin) {
                return redirect()->route('stock.index')->with('succes', "Le magasin est modifier avec succès");
            } else {
                return back()->with('error', 'Modification non effectué avec succès. Svp réessayer prochainement');
            }
        } else {
            return back()->with('info', "L'information est introuvable");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Stock::find($id);
        if ($stock) {
            $stock->delete();
            return back()->with('succes', "Le magasin est supprimé avec succès");
        }
    }
}
