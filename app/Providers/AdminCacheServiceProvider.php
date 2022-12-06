<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AdminCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // if (!request()->is('admin/*')) {
        //     view()->composer('*', function ($view) {
        //         if (!Cache::has('shop_categories_menu')) {
        //             Cache::forever('shop_categories_menu', Category::tree());
        //         }
        //         $shop_categories_menu = Cache::get('shop_categories_menu');

        //         if (!Cache::has('shop_tags_menu')) {
        //             Cache::forever('shop_tags_menu', Tag::whereStatus(true)->get());
        //         }
        //         $shop_tags_menu = Cache::get('shop_tags_menu');

        //         $view->with([
        //             'shop_categories_menu' => $shop_categories_menu,
        //             'shop_tags_menu' => $shop_tags_menu,
        //         ]);
        //     });
        // }


        //اذا كان الريكوست موجود فيه ادمن وبعديه اي حاجه تانية
        if (request()->is('admin/*')) {

            //بستخدمها علشان ابعت الكود دا باي صفحهة داخل الادمن
            view()->composer('*', function ($view) {

                //لو مافيش قيمة مخزنة عند تحميل الصفحات باسم هذا الكاش - نفذ الخطوات التالية
                if (!Cache::has('admin_side_menu'))
                {
                    //تذكر هذا الكاش دائما وهعطيه اسمه
                    //'admin_side_menu'=>اسم الكاش
                    //Permission::tree()=>قيمة الكاش او القيمة اللي هيتذكرها الكاش
                    Cache::forever('admin_side_menu', Permission::tree());
                }
                //لو فيه بقي قيمة متخزنة هاتها و ارجع بقيمتها
                $admin_side_menu = Cache::get('admin_side_menu');

                //هرسل القيمة بقي مع الفيو
                $view->with([
                    'admin_side_menu' => $admin_side_menu,
                ]);

            });

        }

    }
}
