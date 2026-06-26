<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    use HasFactory;
    protected $fillable = [
        "app_name",
        "logo",
        "logo2",
        "types",
        "title",
        "address",
        "contact",
        "footer",
        "name_user",
        "contact_user",
        "sms",
        "senderName",
        "msgAchat",
        "msgAnnuler",
        "login",
        "sidebar",
        "warning_message",
        "app_statut",
        "navbar",
        "code",
        "qte_alert",
        "date_fin",
        "email",
        "password",
        "bar_option",
    ];

    public function Storesettings($data)
    {
        return settings::create($data);
    }

    public function getAll()
    {
        return settings::all();
    }
    public function getAllLatest()
    {
        return settings::lastes()->limit(10)->get();
    }
    public function deletesettings($id)
    {
        return settings::find($id)->delete();
    }

    public function updatesettings($id, $data)
    {
        return settings::find($id)->update($data);
    }

    public function UploadImage($imgName)
    {
        //inserting image one 
        $img_public_path = "backend/images";
        $imgFullName = date("ymd") . '_' . mt_rand(999, 9999999) . '.' . $imgName->getClientOriginalExtension();
        $imgName->move(public_path('/' . $img_public_path), $imgFullName);
        $imgurl = $img_public_path . '/' . $imgFullName;

        return $imgurl;
    }

    public function DeleteImage($id)
    {
        $settings = settings::find($id);
        if (file_exists($settings->logo)) {
            unlink($settings->logo);
        }
    }
}