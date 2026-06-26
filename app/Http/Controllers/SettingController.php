<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $obj = new settings();
        $datas = $obj->getAll();
        return view("setting.index", compact("datas"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("setting.create");
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
            "app_name" => ["required"],
            "logo" => ["required", "mimes:jpeg,png,jpg,", "max:2048"],
            "logo2" => ["required", "mimes:jpeg,png,jpg,", "max:2048"],
            // "types" => ["required"],
            // "title" => ["required"],
            "address" => ["required"],
            // "contact" => ["required", "string"],
            "footer" => ["required"],
            "name_user" => ["required"],
            "contact_user" => ["required", "numeric"],
            "bar_option" => ["codebar"]
        ]);
        $datas = $request->all();
        $obj = new settings();
        $datas["bar_option"] = $request->codebar;
        $datas["sms"] = $request->sms ? $request->sms : 'NON';
        $datas["senderName"] = $request->senderName;
        $datas["msgAchat"] = $request->msgAchat;

        $datas["email"] = $request->email;
        $datas["password"] = $request->password;

        $datas["msgAnnuler"] = $request->msgAnnuler;
        $datas["login"] = $request->login;
        $datas["sidebar"] = $request->sidebar;
        $datas["date_fin"] = $request->date_fin;
        $datas["navbar"] = $request->navbar;
        $datas["warning_message"] = $request->warning_message;
        $datas["logo"] = $obj->UploadImage($request->logo);
        $datas["logo2"] = $obj->UploadImage($request->logo2);
        $datas['code'] = uniqid();
        $datas['qte_alert'] = $request->qte_alert ? $request->qte_alert : '10';
        $data = $obj->Storesettings($datas);
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
        $data = settings::find($id);
        return view("setting.edit", compact("data"));
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
        if ($request->option == "ACTIVER") {
            $dt = $request->all();
            if ($request->app_statut == "OUI") {
                $dt['app_statut'] = 'NON';
                $msg = "Mise à jour effectuée avec succès. <br> Copier le code d'activation !";
            } else {
                $dt['app_statut'] = 'OUI';
                $dt['code'] = uniqid();
                $msg = "Mise à jour effectuée avec succès.";
            }
            $data = $obj->updatesettings($id, $dt);
            if ($data) {
                return back()->with("info", $msg);
            }
        }

        $datas = $request->validate([
            "app_name" => ["required"],
            "logo" => ["mimes:jpeg,png,jpg,", "max:2048"],
            // "types" => ["required"],
            // "title" => ["required"],
            "address" => ["required"],
            // "contact" => ["required", "string"],
            "footer" => ["required"],
            "name_user" => ["required"],
            "contact_user" => ["required", "numeric"],
        ]);
        $datas = $request->all();

        $obj = new settings();
        $datas["bar_option"] = $request->codebar;
        $datas["sms"] = $request->sms ? $request->sms : 'NON';
        $datas["senderName"] = $request->senderName;
        $datas["msgAchat"] = $request->msgAchat;
        $datas["msgAnnuler"] = $request->msgAnnuler;
        $datas["login"] = $request->login;
        $datas["sidebar"] = $request->sidebar;
        $datas["date_fin"] = $request->date_fin;
        $datas["navbar"] = $request->navbar;
        $datas["email"] = $request->email;
        $datas["password"] = $request->password;
        $datas["warning_message"] = $request->warning_message;
        $datas['qte_alert'] = $request->qte_alert ? $request->qte_alert : '10';

        if ($request->hasFile("logo")) {
            $obj->DeleteImage($id);
            $datas["logo"] = $obj->UploadImage($request->logo);
        }

        if ($request->hasFile("logo2")) {
            $obj->DeleteImage($id);
            $datas["logo2"] = $obj->UploadImage($request->logo2);
        }

        $datas = $obj->updatesettings($id, $datas);
        if ($datas) {
            return redirect()->route("settings.index")->with("succes", "Mise à jour effectuée avec succès.");
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
        $obj = new settings();
        $obj->DeleteImage($id);
        $data = $obj->deletesettings($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function ActiverApp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string']
        ]);
        $data = settings::where('code', $request->code)->first();

        if (!empty($data) > 0) {
            $dt['app_statut'] = $data->app_statut = "OUI";
            $dt['code'] = uniqid();
            (new settings)->updatesettings($data->id, $dt);
            return redirect()->route("home")->with("succes", "Activation effectuée avec succès.");
        }
        return back()->with("error", "Activation non effectuée avec succès.");
    }

}