<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;



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
        Paginator::useBootstrapFive();
        Model::preventLazyLoading();
        $departments = Department::with('sectors')->get();

        //$user = \App\Models\User::with('sectors')->find(1);
        //dd($user);
        //View::share('departments', $departments);


        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('groupedSectors', auth()->user()->getSectorsGroupedByDepartment());
            } else {
                $view->with('groupedSectors', collect());
            }
        });        
    }
}
