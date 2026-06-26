<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = new Barcode();
        $datas = $obj->getAllLatest();
        $total = count($datas);
        return view("barcode.index", compact("datas", "total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("barcode.create");
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
            "code_max" => ["required"]
        ]);
        $datass = [];
        $datas['id_setting'] = auth()->user()->id_setting;
        $obj = new Barcode();
        for ($i = 0; $i < $request->code_max; $i++) {
            $datas['barcode'] = $obj->generateBarcode();

            $datass = [
                'barcodes' => $datas['barcode'],
            ];
            $data = $obj->StoreBarcode($datas);
        }

        if ($data) {
            //dd($datas);
            if ($request->types == "PRINT") {
                //dd($datass);
                $pdf = PDF::loadView('pdf.barcode', $datass);
                //session()->flash('succes', 'Achat a ete affectue');
                return $pdf->download("codeBarre_" . $datas['barcode'] . ".pdf");
            }
            return back()->with("succes", "Enregistrement effectué avec succès");
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
        $datas = Barcode::find($id);
        return view("barcode.edit", compact("datas"));
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
        $datas = Barcode::find($id);
        $datas->nom_Barcode = $request->input("nom_Barcode");
        $datas->update();
        if ($datas) {
            return redirect()->route("barcode.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new Barcode();
        $data = $obj->deleteBarcode($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }
}