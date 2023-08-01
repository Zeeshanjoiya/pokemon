<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\WebmasterSection;
use App\Http\Controllers\Controller;

class EbayProuctController extends Controller
{
    //

    public function index(){
         // General for all pages
         $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
         // General END

        $products = Products::get();
        
        return view("dashboard.ebay_products.index", compact("products", "GeneralWebmasterSections"));
    }
}
