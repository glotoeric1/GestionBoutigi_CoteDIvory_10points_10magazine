<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boutiqueId = auth()->user()->id_boutigue ?? session('selected_boutique_id');
        $obj = new User();
        if (auth()->user()->roles == "Super Admin") {
            $datas = $obj->where('id', '!=', 1)->get();
            $total = $obj->where('id', '!=', 1)->count();
            return view('users.index', compact('datas', 'total'));
        }
        $datas = $obj->where('id_boutigue', $boutiqueId)->get();
        $total = $obj->where('id_boutigue', $boutiqueId)->count();
        return view('users.index', compact('datas', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $settings = settings::latest()->get();
        $boutiques = Boutique::latest()->get();
        return view("users.create", compact("boutiques", "settings"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'contact' => ['required'],
            'roles' => ['required'],
            'id_setting' => ['required'],

            'id_boutique' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('roles') !== 'Super Admin';
                })
            ],
        ]);

        $data = $request->all();

        $passwords = mt_rand(11, 39) . mt_rand(40, 69) . mt_rand(70, 99);

        $saved = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact' => $data['contact'],
            'id_setting' => $request->id_setting,
            'id_boutigue' => $request->id_boutique ?? null,
            'roles' => $data['roles'],
            'secret' => $passwords,
            'password' => Hash::make($passwords),
        ]);

        if ($saved) {
            return back()->with('succes', "l'Utilisateur a été effectuée avec sucès");
        }
        return back()->with('error', "l'Utilisateur ne pas été effectuée");
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
        $datas = User::find($id);
        $boutiques = Boutique::latest()->get();
        return view('users.edit', compact('datas', 'boutiques'));
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


        if ($request->types == "ADMIN") {
            $data['statut'] = $request->statut;
            $user = (new User)->updateUser($data, $id);

            if (!$user) {
                return back()->with('error', 'Mise à jour ne pas été effectuée');
            }
            return back()->with('succes', 'Mise à jour a été effectuée');
        }

        if ($request->types == "USERS") {
            $data = $request->validate([
                'password' => ['required', 'confirmed'],
                'password_old' => ['required'],
            ]);

            //$data['secret']=$request->password;
            //$data['password']=Hash::make($request->password);
            //$user=(new User)->updateUser($data, $id);
            //dd($request->all());

            if (Hash::check($request->password_old, auth()->user()->password)) {
                if (!Hash::check($request->password, auth()->user()->password)) {
                    $user = User::where('id', auth()->user()->id)->first();
                    $user->secret = $request->password;
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return redirect()->back()->with("succes", "La mise a jour a été effectuée");
                } else {
                    return redirect()->back()->with("error", "Le nouveau mot de passe ne peut pas être l'ancien mot de passe !");
                }
            }
            return redirect()->back()->with("error", "l'ancien mot de passe ne correspond pas");


            /*
            if(!$user){
            return redirect()->back()->with('error', 'Mise à jour ne pas été effectuée');
            }
            return redirect()->back()->with('success', 'Mise à jour a été effectuée');
            */

        }

        $data = $request->validate([
            'name' => ['required'],
            'contact' => ['required'],
            'email' => ['required'],
            'roles' => ['required'],
            'id_boutique' => ['required'],
        ]);

        $data['id_boutigue'] = $request->id_boutique;

        $user = (new User)->updateUser($data, $id);

        if (!$user) {
            return redirect()->route('users.edit')->with('error', 'Mise à jour ne pas été effectuée');
        }
        return redirect()->route('users.index')->with('succes', 'Mise à jour a été effectuée');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = new User();
        $data = $obj->deleteUser($id);
        if ($data) {
            return back()->with("succes", "Supression reussie");
        }
        return back()->with("error", "Supression non reussie");
    }

    public function forGetPasswordRequest(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email' => ['required', 'max:200']
        ]);

        $user = User::where('email', $request->email)->orWhere("contact", $request->email)->first();
        if (!$user) {
            return back()->with('error', "L'adresse email ou le contact n'existe pas.");
        }
        DB::table('passwords_reset')->where('email', $request->email)->delete();
        $token = Str::random(60);

        DB::table('passwords_reset')->insert([
            'email' => $user->email,
            'token' => $token,
        ]);

        $subject = 'Demande de réinitialisation du mot de passe';
        $msg = 'Nous vous avons envoyé ce courriel en réponse à votre demande de réinitialisation de votre mot de passe sur notre plateforme';
        $msg2 = 'Pour réinitialiser votre mot de passe, veuillez suivre le lien ci-dessous : ';
        $btn = 'Réinitialiser mot de passe';
        $routes = route("confirmation", ['email' => $user->email, 'token' => $token]);


        $mail = Mail::to($user->email)->send(new WelcomeMail($user->name, $msg, $msg2, $routes, $btn, $subject));

        return redirect()->back()->with('succes', "Un mail a a été envoyé à " . $user->email);

    }

    public function confirmation(Request $request)
    {
        $query = DB::table('passwords_reset')->where('email', $request->email)->where('token', $request->token)->first();
        if ($query) {
            return view('auth.passwords.reset', ['email' => $request->email, 'token' => $request->token]);
        }
        return redirect('/')->with("succes", "Le mot de passe a été modifié");
    }

    public function updatePassword(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'password' => ['required', 'confirmed']
        ]);

        $query = DB::table('passwords_reset')->where('email', $request->email)->where('token', $request->token)->first();
        if ($query) {
            $user = User::where('email', $request->email)->first();
            $user->secret = $request->password;
            $user->password = Hash::make($request->password);
            $user->save();
            DB::table('passwords_reset')->where('email', $request->email)->delete();
            return redirect('/')->with("succes", "Le mot de passe a été modifié");
        }
        return redirect('/')->with("error", "Jeton expiré, veuillez réessayer.");
    }


}