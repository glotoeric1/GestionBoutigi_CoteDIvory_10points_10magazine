<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteBancaireController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\EntreStockController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\CustomHomeController;
use App\Http\Controllers\MobileMoneyController;
use App\Http\Controllers\ProductBoutigueController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\CarteController;
use App\Http\Controllers\DepensesController;
use App\Http\Controllers\EmployesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaiementAvanceController;
use App\Http\Controllers\SalairesController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VenteIndirectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepotController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/daily/repport/{startDate}/{endAt}', [VenteController::class, 'GetDailyReport'])->name("daily");
Route::get('/monthly/repport', [VenteController::class, 'GetMonthlyReport'])->name("monthly");
Route::get('/print/facture/{id}/vente', [VenteController::class, 'printVenteInvoice'])->name("facture_vente.client");

Route::get('/', function () {
    return view("auth.login");
});
Route::get('/login', function () {
    return view("auth.login");
});

Auth::routes([
    'register' => false,
    'confirm' => false,
]);

Route::get('/confirmation/compte/email={email}/token={token}', [UsersController::class, 'confirmation'])->name('confirmation');
Route::put('/updatePassword/compte', [UsersController::class, 'updatePassword'])->name('updatepass.confirm');
Route::post('/passwords/forgets', [UsersController::class, 'forGetPasswordRequest'])->name('passwords.email');
Route::post("/activers", [SettingController::class, "ActiverApp"])->name("setting.activer");
Route::get('/client/{id}/solde', [ClientController::class, 'verifiedSolde'])->name('client.verified');



Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/autocomplete-search', [VenteController::class, 'autocompleteSearch']);

    Route::get('/statistics/ventes', [StatisticsController::class, "venteCreate"])->name("venteCreate");
    Route::get('/statistics/dettes', [StatisticsController::class, "detteCreate"])->name("detteCreate");
    Route::get('/statistics/stocks', [StatisticsController::class, "stockCreate"])->name("stockCreate");

    Route::get('getsalaire/{id}', [SalairesController::class, 'getsalaire']);
    Route::get('getproduct/{id}', [PaiementAvanceController::class, 'getproduct']);
    Route::get('getmontant/{id}', [ServiceController::class, 'getMontant']);

    Route::get('/dashboard', [CustomHomeController::class, "index"])->name("dashboard");
    Route::get('/minidashboard', [CustomHomeController::class, "minidashboard"])->name("minidashboard");
    Route::get('fatchstudentInfo/{id}', [VenteController::class, 'fatcheStudentInfo']);
    Route::get('getcat/{id}', [ProduitController::class, 'getcat']);
    Route::get('orders/{id}', [VenteController::class, 'order']);
    Route::get('autocomplete', [VenteController::class, 'autocomplete'])->name('autocomplete');
    Route::post('/addTocart', [CarteController::class, 'addTocart'])->name('add.cart');
    Route::get('/removeItem/{id}/{types}', [CarteController::class, 'removeCart'])->name('remove.cart');
    Route::get('/print/invoices', [VenteController::class, 'PrintInvoice'])->name("printInvoice");
    Route::get('/print/invoice/afters', [VenteIndirectController::class, 'PrintInvoiceVente'])->name("indirect.printInvoice");
    Route::get('/print/dettes', [DetteController::class, 'PrintInvoice'])->name("printDette");
    Route::get('/print/avances', [PaiementAvanceController::class, 'PrintInvoice'])->name("printAvance"); //Recharche
    Route::get('/print/service', [ServiceController::class, 'PrintInvoice'])->name("printService");
    Route::get('/print/Recharche', [DepensesController::class, 'Recharche'])->name("recharche"); //
    Route::get('/banks/Recharche', [BankController::class, 'Recharche'])->name("bank.recharche"); //
    Route::get('/paiement/Recharche', [PaiementAvanceController::class, 'Recharche'])->name("paiement.recherche"); //
    Route::get('/supply/Recharche', [SupplyController::class, 'RechercheSupply'])->name("supply.recharche"); //
    Route::patch('update-cart', [CarteController::class, 'updateCart'])->name('update.cart');

    Route::get('/ventes', [VenteController::class, 'searchSale'])->name('seach.Item');
    Route::get('/statistics/users/search', [VenteController::class, 'GetUserDailyReport'])->name("search.userRepport");
    Route::get('/statistics/ventes/search', [StatisticsController::class, 'StatisticsAll'])->name("seach.statistics");
    Route::post('/ventes/afterachat/{id?}/{clientId?}', [VenteController::class, 'deleteAfterBuy'])->name('vente.deleteAfterBy');
    Route::post('/dettes/afterachat/{id?}/{clientId?}', [DetteController::class, 'deleteAfterBuy'])->name('dette.deleteAfterBy');
    Route::post("/produits/adds", [ProduitController::class, "AddQte"])->name("produit.addQte");
    Route::post("/supply/{id}/valid_product", [SupplyController::class, "ValiderProd"])->name("commande.valider");
    Route::post("/stocks/ads", [SupplyController::class, "AddNewProd"])->name("stcoks.add");
    Route::post("/services/add", [ServiceController::class, "AddMiniService"])->name("service.add");
    Route::post("/codebar/add", [ProduitController::class, "AddBarCode"])->name("codebar.add");
    Route::get('/search/produit', [ProduitController::class, 'searchProduit'])->name('produit.search');

    //get route
    Route::get('caisseglobal/searches', [CaisseController::class, 'CaisseGlobal'])->name("caisse.Search");
    Route::get('caisseglobal/afficher', [CaisseController::class, 'CaisseGlobal'])->name("caisse.afficher");
    Route::get('getPrix/{id}', [SupplyController::class, 'getProduitPrix']);
    Route::get('/printCmd/after', [SupplyController::class, 'PrintCmdAfterSale'])->name('printCmd.afterOrder');
    Route::get('/supply/{id}/detail', [SupplyController::class, 'showDetail'])->name('commande.detail');

    Route::put('/supply/{id}/detail_update', [SupplyController::class, 'updateDetail'])->name('detail.update');
    Route::delete('/supply/{id}/detail_delete', [SupplyController::class, 'detailDelete'])->name('detail.delete');
    Route::delete('/supply/{id}/paiement_delete', [SupplyController::class, 'paiementDelete'])->name('paiement.delete');

    //Vente details 
    Route::delete('/ventes/{id}/detail_delete/{item}', [VenteController::class, 'deleteAfterBuyOne'])->name('VenteDetail.delete');


    Route::get('/printDepense/after', [DepensesController::class, 'PrintDepense'])->name('depenses.print');
    Route::get('/printSalaire/after', [SalairesController::class, 'PrintSalaire'])->name('salaires.print');

    //Route to show supply by numero_commande
    Route::get('/supply/numero_commande/{numero_commande}', [SupplyController::class, 'showByNumeroCommande'])->name('supply.showByNumeroCommande');
    //Route to show dette by client
    Route::get('/dette/client/{clientId}', [DetteController::class, 'showDetteByClient'])->name('dette.showByClient');
    //Route to show paiement avance by client
    Route::get('/paiementavance/client/{clientId}', [PaiementAvanceController::class, 'showPaiementAvanceByClient'])->name('paiementavance.showByClient');
    //activer app

    Route::post('/select-boutique', [CustomHomeController::class, 'handleSelection'])->name('select.boutique.submit');
    Route::post('/switch-boutique', [CustomHomeController::class, 'switchBoutique'])->name('switch.boutique');

    Route::get('/transfert/imprimer', [ProductBoutigueController::class, 'printTransfert'])->name('transfert.imprimer');
    Route::get('/product/search', [VenteController::class, 'searchProduct'])->name('productBoutique.search');
    Route::get('fatche_produit/{id}/info', [VenteController::class, 'fatcheProduitInfo']);

    Route::get('/vente/{id}/vente_detail', [VenteController::class, 'showValide'])->name('vente.show_valide_detail');
    Route::put('/vente/{id}/vente_valide_detail', [VenteController::class, 'valideDetail'])->name('vente.valide_detail');

    //Added on June 15 2026
    Route::patch('/clients/{id}/toggle-status', [ClientController::class, 'toggleStatus'])
        ->name('client.toggle-status');
    Route::prefix('wallet')->group(function () {
        Route::get('/client/{id}', [WalletController::class, 'index'])->name('wallet.index');
        Route::get('/client/{id}/create', [WalletController::class, 'create'])->name('wallet.create');
        Route::post('/client/{id}', [WalletController::class, 'store'])->name('wallet.store');
    });


    //resource route
    Route::resource("/client", ClientController::class);
    Route::resource("/boutique", BoutiqueController::class);
    Route::resource("/fournisseur", FournisseurController::class);
    Route::resource("/produit", ProduitController::class);
    Route::resource("/type", TypeController::class);
    Route::resource("/categorie", CategorieController::class);
    Route::resource("/vente", VenteController::class);
    Route::resource("/dette", DetteController::class);
    Route::resource("/barcode", BarcodeController::class);
    Route::resource("/users", UsersController::class);
    Route::resource("/employes", EmployesController::class);
    Route::resource("/depenses", DepensesController::class);
    Route::resource("/paiementavances", PaiementAvanceController::class);
    Route::resource("/salaires", SalairesController::class);
    Route::resource("/banks", BankController::class);
    Route::resource("/commande", SupplyController::class);
    Route::resource("/settings", SettingController::class);
    Route::resource("/venteIndirects", VenteIndirectController::class);
    Route::resource("/gestions", EntreStockController::class);
    Route::resource("/comptes", CompteBancaireController::class);
    Route::resource("/services", ServiceController::class);
    Route::resource("/mobilemoney", MobileMoneyController::class);
    Route::resource("/productBoutique", ProductBoutigueController::class);

    //Resource route for entrepot
    Route::resource("/entrepot", EntrepotController::class);
    Route::resource("/stock", StockController::class);

    Route::get('/autocomplete', [UsersController::class, 'autocompleteNew'])->name('autocomplete');
    Route::get("/historyTransfers", [ProduitController::class, "historyTransfert"])->name("historyTransfert");

    Route::post("/productBoutique/cancel", [ProductBoutigueController::class, "cancelTransfert"])->name("cancelTransfert");

});