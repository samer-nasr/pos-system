<?php

namespace App\Providers;

use App\Models\Items;
use App\Models\Operation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Items::created(function($item){
            // add the operation amounts to account 4000
            $operation = new Operation();
            $operation->amount = $item->cost * $item->quantity;
            $operation->account_ref = '3000';
            $operation->type = 'C';
            $operation->origin = 'item';
            $operation->origin_id = $item->id;
            $operation->user_id = 1;

            $operation->save();

             // add the operation amounts to account inventory
            $operation = new Operation();
            $operation->amount = $item->cost * $item->quantity;
            $operation->account_ref = '1020';
            $operation->type = 'D';
            $operation->origin = 'item';
            $operation->origin_id = $item->id;
            $operation->user_id = 1;

            $operation->save();

        });
    }
}
