<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Products;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Models\ProductsPrices;
use App\Models\WebmasterSection;
use App\Http\Controllers\Controller;

class EbayProductController extends Controller
{
    //

    public function index(){
         // General for all pages
         $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
         // General END

        $products = Products::get();
        
        return view("dashboard.ebay_products.index", compact("products", "GeneralWebmasterSections"));
    }


    public function showPrices($id){
        $product_prices = ProductsPrices::Join('products', 'products.id', '=', 'products_prices.ProductID')->where(['ProductID' => $id])->get();
        if($product_prices->count() == 0){ 
            $product_prices = Products::where(['id' => $id])->get();   
        }
        $html = view('dashboard.ebay_products.product_prices', ['product_id' => $id, 'prices' => $product_prices])->render();
       
       return json_encode(['status' => true, 'content' => $html]);
    }
}
