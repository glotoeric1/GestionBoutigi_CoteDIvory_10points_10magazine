<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\User;

class DaillyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send user daily email about sales.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subject='Rapport de vente quotidien - Low Cost'; 
        $msg="C'est votre rapport quotidien pour la vente d'hier.";
        $msg2="Cliquez sur le lien pour voir plus de détails.";
        $btn='Voir les détails';
        $name="Eric k GLOTO";
        $dates=Carbon::now()->format('Y-m-d');
        $routes=url("/daily/repport/".$dates."/".$dates);
        $mail=Mail::to("glotoeric@gmail.com")->send( new WelcomeMail($name, $msg, $msg2, $routes, $btn, $subject));

        $this->info('Successfully sent daily quote to everyone.');
    }
}
