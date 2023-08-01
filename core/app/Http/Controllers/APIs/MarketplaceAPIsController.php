<?php
namespace App\Http\Controllers\APIs;

use Helper;
use DateTime;
use DateInterval;
use App\Models\User;
use App\Utils\EbayUtil;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\ProductsPrices;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MarketplaceAPIsController extends Controller
{

    public function fetchProducts(){
        EbayUtil::fetchProducts();
    }


    public function productsDetail(){
        EbayUtil::fetchProductsDetail();
    }

    public function productsPrices(){
        EbayUtil::fetchProductsPrices();
    }

    public function addDummyPrices(){

     echo "Start Time: ".date('Y-m-d H:i:s');
        
       $products = Products::get();

        foreach($products as $product){

            //count
            $total_prices = rand(1, 10);
            for($count = 0; $count <= $total_prices; $count++){

                $fluctutation = rand(0,4);

                $change_price = rand(1,10);
                $sold_quantity = rand(1, 5);

                $sold_date = (new DateTime())->sub(new DateInterval('P'.($count+1).'D'))->format('Y-m-d H:i:s');
                $sold_price = ($fluctutation == 1 || $fluctutation == 2 || $fluctutation == 3) ? ($product->current_price + $change_price) : ($product->current_price - $change_price);
                $sold_price <= 0 ? ($product->current_price + $change_price) : $sold_price;

                $check_round = rand(0,1);

                $p_price = new ProductsPrices();
                $p_price->ProductID = $product->id;
                $p_price->currency = 'USD';
                $p_price->product_last_sold_price =  $check_round == 1 ? round($sold_price) : $sold_price;
                //$p_price->product_last_sold_date = $sold_date;
                $p_price->total_sold_quantity = $sold_quantity;
                $p_price->created_at = date('Y-m-d H:i:s');
                $p_price->updated_at = date('Y-m-d H:i:s');
                $p_price->save();
            }
        }

        echo "<br>";
        echo "End Time: ".date('Y-m-d H:i:s');
    }
}
?>