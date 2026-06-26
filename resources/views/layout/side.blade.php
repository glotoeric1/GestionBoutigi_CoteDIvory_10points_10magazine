@if ($configure == 'YES' || auth()->user()->roles == 'Super Admin')
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave-alt"></i>
            <p>
                Vente
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('vente.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vente.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill-wave-alt"></i>
            <p>
                Vente Indirect
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('venteIndirects.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('venteIndirects.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
                Créances
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('dette.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dette.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
                Paiement d'avance
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('paiementavances.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('paiementavances.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
                Service
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('services.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('services.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
                E-Transfert
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('mobilemoney.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('mobilemoney.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    @if (auth()->user()->id == 1)
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                    Entrépot
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('entrepot.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Afficher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('entrepot.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>
    @endif

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-basket"></i>
            <p>
                Point de vente
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('boutique.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher</p>
                </a>
            </li>
            <li class="nav-item {{ count($boutiques) == 10 ? 'd-none' : '' }}">
                <a href="{{ route('boutique.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ajouter</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-money-bill"></i>
                                    <p>
                                        Magasin
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('stock.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Afficher</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('stock.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ajouter</p>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-basket"></i>
            <p>
                Entrepôts
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('produit.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Afficher entrepôts</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('historyTransfert') }}" class="nav-link">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                        Historique des transferts
                    </p>
                </a>
            </li>
        </ul>
    </li>

    {{-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-basket"></i>

                                    <p>
                                        Point de vente
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('boutique.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Nouveau</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        @if (auth()->user()->roles === 'Super Admin')
                                            @foreach ($boutiques as $b)
                                                <a href="{{ route('productBoutique.index', ['id' => $b->id]) }}"
                                                    class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ $b->nom_boutique }}</p>
                                                </a>
                                            @endforeach
                                        @else
                                            @foreach ($boutiques as $b)
                                                @if (auth()->user()->id_boutigue == $b->id)
                                                    <a href="{{ route('productBoutique.index') }}" class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>Mon Stock</p>
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif

                                    </li>
                                </ul>
                            </li> --}}

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
                Magasin
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                @if (auth()->user()->roles === 'Super Admin')
                    <a href="{{ route('stock.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Nouveau</p>
                    </a>
                    @foreach ($magasins as $m)
                        <a href="{{ route('productBoutique.index', ['id' => $m->id]) }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ $m->libelle }}</p>
                        </a>
                    @endforeach
                @else
                    @foreach ($magasins as $m)
                        @if (auth()->user()->id_boutigue == $m->id_boutigue)
                            <a href="{{ route('productBoutique.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mon Stock</p>
                            </a>
                        @endif
                    @endforeach
                @endif
            </li>
        </ul>
    </li>
@endif


@if (auth()->user()->roles == $admin ||
        auth()->user()->roles == $superAdmin ||
        auth()->user()->roles == $gestionaire ||
        auth()->user()->roles == $controlleur)
    @if ($configure == 'YES')
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-solid fa-users"></i>
                <p>
                    Client
                    <i class="right fas fa-angle-left"></i>

                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('client.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Afficher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('client.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Fournisseur
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('fournisseur.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Afficher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('fournisseur.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Catégorie
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('categorie.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Afficher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categorie.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>

        @if (auth()->user()->id == '1')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Sous Catégorie
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('type.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Afficher</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('type.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ajouter</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-barcode"></i>
                <p>
                    Code barre
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('barcode.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Afficher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('barcode.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                    Rapport
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('venteCreate') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Vente</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('detteCreate') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Créances</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gestions.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Entrer / Sortir </p>
                    </a>
                </li>
            </ul>
        </li>

    @endif
    @if (auth()->user()->roles == $admin || auth()->user()->roles == $superAdmin || auth()->user()->roles == $controlleur)
        @if ($configure == 'YES')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Employé
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('employes.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Afficher</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employes.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ajouter</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-money-bill"></i>
                    <p>
                        Salaire
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('salaires.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Afficher</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('salaires.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ajouter</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-money-bill"></i>
                    <p>
                        Dépense
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('depenses.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Afficher</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('depenses.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ajouter</p>
                        </a>
                    </li>
                </ul>
            </li>
            @if (auth()->user()->roles == $admin)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Compte Bancaire
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('comptes.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Afficher</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('comptes.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ajouter</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                        Opération Bancaire
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('banks.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Afficher</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('banks.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ajouter</p>
                        </a>
                    </li>

                </ul>
            </li>
        @endif

        @if ($configure == 'YES' || auth()->user()->roles == 'Super Admin')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                        Les Commandes
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('caisses.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Afficher</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('caisses.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ajouter</p>
                        </a>
                    </li>

                </ul>
            </li>
        @endif
    @endif
    @if (auth()->user()->roles == $admin || auth()->user()->roles == $superAdmin || auth()->user()->roles == $controlleur)
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Compte d'utilisateur
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Afficher</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Caisses
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('caisse.afficher') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Caisse globale</p>
                    </a>
                </li>

            </ul>
        </li>
    @endif



@endif

