<?php

namespace App\Utils;

use Helper;
use DateTime;
use DateTimeZone;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\ProductsPrices;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EbayUtil extends Controller
{
    private static $prefix = 'EBAY-001';
    private static $marketplace_id = 'EBAY_US';
    private static $base_url = 'https://svcs.ebay.com/';
    private static $base_url_buy = 'https://api.sandbox.ebay.com/buy/';
    //production app name
    private static $app_name = 'MintBerr-TradingC-PRD-b5833820b-87a929c5';
    //product token
    //private static $access_token = 'v^1.1#i^1#f^0#I^3#r^0#p^1#t^H4sIAAAAAAAAAOVYa2wUVRTudtuaAtUEqCCQugwPo2Zn7+xjOjN2lyx90IU+FnYhSECYnbnTDp3HdmaW3a1AN0VJSkAxBhQMtAnQyB/8o0V+QAwRJVHRIBgFTYxaBCNqTAsminpndynbSgDpJjZx/2zm3HPP/b7vnHPvnQGpktInttZvvV5meaCwNwVShRYLMRGUlhQ/+aC1cEZxAchxsPSm5qaKuqyXq3RWlqLMMqhHVUWHtoQsKTqTNnqxmKYwKquLOqOwMtQZg2NC/sYGxokDJqqphsqpEmYL1HixCElRAqAgIKGHpzwAWZWbMcOqF+PpSpLiWFKgIXRGgDmu6zEYUHSDVQwv5gROlx247QQdBiTjoRkngbtJYhVmWwE1XVQV5IIDzJeGy6TnajlY7wyV1XWoGSgI5gv460LN/kBNbVO4ypETy5fVIWSwRkwf+VSt8tC2gpVi8M7L6GlvJhTjOKjrmMOXWWFkUMZ/E8x9wE9LzQKWEgSBd7kJoZIQiLxIWadqMmvcGYdpEXm7kHZloGKIRvJuiiI1IushZ2SfmlCIQI3N/FsaYyVREKHmxWoX+p/2B4OYr1FUjIVQ0+xhjeVFpaXaHlxWY494KJeLcoKInapkaSfNebILZaJlZR61UrWq8KIpmm5rUlFQhBqO1sadow1yalaaNb9gmIhy/aibGnrIVWZSM1mMGa2KmVcoIyFs6ce7Z2B4tmFoYiRmwOEIowfSEqFcR6Mij40eTNditnwSuhdrNYwo43DE43E87sJVrcXhBIBwrGxsCHGtUGYx5Gv2esZfvPsEu5imwkE0UxcZIxlFWBKoVhEApQXzuUna4yKzuo+E5Rtt/Ychh7NjZEfkq0OchIsVCJKPRFw8QPtNPjrEly1Sh4kDRtikXWa1NmhEJZaDdg7VWUyGmsgzLo/gdFECtPMkLdjdtCCgAuZJOyFAiMBEIhxN/Z8a5V5LPQQ5DRp5qfW81XmM9UO5YQkd6ohRUjiohNpbaxZxzprwsoTcsbJO7gg2JhdXanwiudx7r91wW/LVkoiUCaP18yGA2ev5E6Fe1Q3Ij4leiFOjMKhKIpccXwl2aXyQ1YxkCEoSMoyJpD8aDeRnr84bvX+5Tdwf7/ydUf/R+XRbVrpZsuOLlTlfRwHYqIibJxDOqbLD7HWVRdcP07w2jXpMvEV0cx1XrBHJDFuRz1w58TRdXN/A4RrU1ZiGbtt4s3kDC6ttUEHnmaGpkgS1FcSY+1mWYwYbkeB4a+w8FLjIjrPDliApgqIqPeTY0salj9K1421LysdWXLToPq/VjpEv+b6C9I/ospwEXZYThRYLqALziDlgdol1eZF10gxdNCAusgKuiy0KenfVIN4Gk1FW1AqnFFwH37/G/Vh/eFvbn/H2S09tKsj9xtC7Bkwf/spQaiUm5nxyALNujRQTD00rc7qAm6AB6aGdxCow59ZoEfFw0dQrbzx/aF/3ZNm6ernR4Dg0dNVa/RIoG3ayWIoLirosBZNeOLh7V8lPuxON3a9ce/bxG9zgVwNVm+e+u/dKqufUwUudx/o9FUfP//LmpSPxX1/vuRb3bmoXf/9w2qun5z9zOXgGnjl9YntqW/87G5+b0NPd0vlJaMi9OnmEesTWKc1avOPTdV0XypcGj56bc3hgylCgfwHR173nrZ7ziaapiX3z/5h35MULgxsGTx7rT7KBzQOq5dx707c8eqIBu9E3uBgcr9g5c/9naz6f/mXtz0s2//V1/0eTm3f0dJZ3ru/99rFDtQf6Lp7avxd8Vz+hsfyLHyI73GfnLp2wqGJna9eAta687O2hmbsW/nZAePnG0S1X20vfP/vB8SV7Nu6e/fGpBeukBcXflGkX6T6uoiOTy78BKyOB0P0RAAA=';

    // private static $base_url = 'https://svcs.sandbox.ebay.com/';
    //sandbox app name
    //  private static $app_name = 'MintBerr-TradingC-SBX-b08cfd9b0-9398ecf1';
    //sandbox token
    private static $access_token = 'v^1.1#i^1#I^3#f^0#r^0#p^1#t^H4sIAAAAAAAAAOVYa2wURRzv9doKwQomAj74cFkIpjS7N7t7z03vkmtp4aQvuKNCkZDZ3dl26d7udWe37YlIraaRT0Y/+CSmUUERAhgE4YOIEGOJr0QMRkxMTFPDJ00wAlEszu6Vcq0ECr3EJu6Xzcz8n7/5P2YG9FfMXTG4evBypeee0qF+0F/q8bDzwNyK8ur7vKUPl5eAAgLPUP+y/rIB74UaDDNaVliHcNbQMfL1ZTQdC+5kjLJNXTAgVrGgwwzCgiUJqURTo8AxQMiahmVIhkb5kitjVFTmIR+JBEO8wsm8FCaz+nWZaSNGhULBMBfiQBAE5GBIBmQdYxsldWxB3YpRHOB4GgRpwKVZIAQ4gecZwLLtlK8NmVg1dELCACrumiu4vGaBrbc2FWKMTIsIoeLJREOqJZFcWd+crvEXyIqP45CyoGXjyaM6Q0a+NqjZ6NZqsEstpGxJQhhT/nhew2ShQuK6MXdhvgu1GImK0QCSwoGwEkGKVBQoGwwzA61b2+HMqDKtuKQC0i3Vyt0OUYKGuBVJ1viomYhIrvQ5v7U21FRFRWaMqq9NbEy0tlLxJlW3apFp0mkTyqreUUenajfQIohIihwVAR3loxEkKey4ory0cZinaKozdFl1QMO+ZoMIJVajqdjwBdgQoha9xUwolmNRIR13HUPAtTubmt9F2+rUnX1FGQKEzx3efgcmuC3LVEXbQhMSpi64EMUomM2qMjV10Y3F8fDpwzGq07Kygt/f29vL9PKMYXb4OQBY/4amxpTUiTKQIrROrufp1dsz0KrrioQIJ1YFK5cltvSRWCUG6B1UnI+GA5HIOO6TzYpPnf3XRIHP/skZUawM4VFI5HkJRMOhEA8DsBgZEh8PUr9jBxJhjs5AswtZWQ1KiJZInNkZZKqywAcVjo8oiJZDUYUORBWFFoNyiGYVhABCoihFI/+nRJluqKeQZCKrKLFetDhP25y+dk271h1q83frj4eTddlV6UBTV2M7fMzQ6o3V6uqWerm3JbphY2y62XBT5+s0lSCTJvqLAYCT68UDYbWBLSTPyL2UZGRRq6GpUm52bTBvyq3QtHK1do6MU0jTyG9Griay2WRxKnbRnLzDYnF3fhevU/1HXeqmXmEncGeXVw4/JgJgVmVIH3JyPcdIRsZvQHIIcaa3uFb7phDelMgv2jmmw0bYIpbI5Bw4bSaVFHOGtDR5+iz5hkmcmD4LuWTItmTdlSK3MzMETbWj08J3pLNvJqCIttY1fRYZQW1GIaqSq8asClDiad5lVc7fERjXbwb3SIyJsGGb5HrEtDhH5rTRhXRyALFMQ9OQ2TazEuSU3kzGtqCoodlWg4tQi1SS654rs+yExIYiPOACbJCfkW+Se/7ZMts6SLE75x3chPyT32XiJe7HDng+BgOe46UeDwgDmq0GVRXe9WXeeylMag+DoS6LRh+jQoUhZU+Hlm0ipgvlslA1Sys86vnvpCsFL0JDm8GDE29Cc73svIIHIrDkxko5O39xJceDIOBYEOB4vh0svbFaxi4qe+D7kUOVJ8v3bjvwRs/gJ53NZyGTGAOVE0QeT3kJCd8S46v3jtTsr3nk/DvSDxfaQwv31Vfo3Q0rGq7V7xh+hvstHVjg/Xb5LyUHL10c6RHnjPF/pMbWlFRX9R1pOFn2orz7tW3nHj2/98DZRuqLGrD17eOVB77cvrtOWN60RXm/5eWdC970ch8NLjl2unU7u/NyO3Xwg4VXX39o4x4w8vwLf7c1xiiRqzjsP/PrkdFlzz5R2zd84tXFJ/iff3/Od3V08P5reB3ctP7o/MqjH9Y/+dTFPVUjo4vwXr3mXSVVUh3Ysd9+6fSpoUverad2/fTNqsDhOce+7h7sYRfEn84N76Lf2rftyqbLYxJcVnlpX9PAoVc+H14qRf4c+rRi92jVj2eSyd7PNp/b81c4v43/ACImwb6rEwAA';
    public static function debug($data)
    {
        echo "<pre>";
        print_r($data);
        die();
    }

    public static function getApiCall($url, $params)
    {

        $client = new Client([
            'verify' => false
        ]);

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$access_token,
                'X-EBAY-C-MARKETPLACE-ID' =>  self::$marketplace_id
            ],
            'query' => $params
        ]);

        $body = $response->getBody();
        $content = json_decode($body->getContents());


        return ['status' => 'Success', 'data' =>  $content];
    }

    //function fetching the product from marketplace
    public static function fetchProducts()
    {
        echo "Start Date " . date('Y-m-d H:i:s');
        $url = rtrim(self::$base_url, '/') . '/services/search/FindingService/v1';

        $marketplace_info = [
            'OPERATION-NAME' => 'findItemsAdvanced',
            'SERVICE-VERSION' => '1.0.0',
            'SECURITY-APPNAME' => self::$app_name,
            'RESPONSE-DATA-FORMAT' => 'json',
            'RESTPAYLOAD' => 'true',
            'paginationInput.entriesPerPage' => 100,
            'categoryId' => 183050,
            'keywords' => 'psa pokemon cards 10'
        ];

        //fetch record without loop. so that we know the total number of record
        $marketplace_info['paginationInput.pageNumber'] = 1;
        $response = self::getApiCall($url, $marketplace_info);
        //  self::debug($response);

        if ($response['status'] == 'Success') {

            $page_number = 2;
            $total_pages = $response['data']->findItemsAdvancedResponse[0]->paginationOutput[0]->totalPages[0];
            self::saveProducts($response['data']->findItemsAdvancedResponse[0]->searchResult[0]->item);

            while ($page_number <= $total_pages) {
                $marketplace_info['paginationInput.pageNumber'] = $page_number;
                $response = self::getApiCall($url, $marketplace_info);
                if ($response['status'] == 'Success') {
                    self::saveProducts($response['data']->findItemsAdvancedResponse[0]->searchResult[0]->item);
                }
                $page_number++;
            }
        }

        echo "<br>";
        echo "End Date " . date('Y-m-d H:i:s');
    }


    //function fetching product detail from marketplace
    public static function fetchProductsDetail()
    {
        // getting all ebay products
        //$products = Products::get();
        $itemList = ['385553580877', '125886885227'];
        self::getProductDetail('385553580877');
    }



    public static function getProductDetail($itemId)
    {
        self::$access_token = 'v^1.1#i^1#I^3#f^0#r^0#p^1#t^H4sIAAAAAAAAAOVYa2wURRzv9XGGloohRnwgXraNCmT3Zu+5u+EuuZaWtrRc2zsrNCF1b3euXbq3u92Z5XoSsTSCEQnGD3wwfrAaLT5QadKY+IEPvkB8hYAlASMkNgLySPigggSic3ulXCuBQi+xiftlMzP/52/+j5kBA855y7Y1bLtU6bineGgADBQ7HGwFmOcsW35vSfHDZUUgj8AxNFA9UDpYcmYFElOqIbRDZOgagq7+lKohwZ4MUZapCbqIFCRoYgoiAUtCLNLSLHgYIBimjnVJVylX48oQJXp8ctDr8Qa8UEqAhJfMatdlxvUQ5ZcSnD/AQdYD2CQn8WQdIQs2agiLGg5RHuDx0sBHs3wccALgBZZnWI+nk3J1QBMpukZIGECFbXMFm9fMs/XWpooIQRMTIVS4MVIfi0YaV9atia9w58kKT+AQwyK20NRRrS5DV4eoWvDWapBNLcQsSYIIUe5wTsNUoULkujF3Yb4NNRdIcknyyUDyBVnoLQiU9bqZEvGt7cjOKDKdtEkFqGEFZ26HKEEjsQFKeGK0hohoXOnK/tosUVWSCjRDVF1NZF2ktZUKtygaroGmScdNUVa07lo6VrOWTgBOSsp8AtC8l+eglGQnFOWkTcA8TVOtrslKFjTkWqMTocRqOBWboODPw4YQRbWoGUnirEV5dCy4jiHr78xuam4XLdyjZfcVpggQLnt4+x2Y5MbYVBIWhpMSpi/YEJG0MgxFpqYv2rE4ET79KET1YGwIbnc6nWbSXkY3u90eAFj32pbmmNQDUyJFaLO5nqNXbs9AK7YrEiScSBFwxiC29JNYJQZo3VTYywd9HDeB+1SzwtNn/zWR57N7akYUKkNkv08GgGSI7GeDHpYtRIaEJ4LUnbUDJsQMnRLNXogNVZQgLZE4s1LQVGTB6096vFwS0nKAT9I+PpmkE345QLNJCAGEiYTEc/+nRJlpqMegZEJckFgvWJzHLY/WtrpT7Qt0uPu0p4ONtcaquK+lt7lTbNLVOr1BaYjWyekov3ZdaKbZcFPna1WFIBMn+gsBQDbXCwdCg44wlGflXkzSDdiqq4qUmVsb7DXlVtHEmRorQ8YxqKrkNytXI4bRWJiKXTAn77BY3J3fhetU/1GXuqlXKBu4c8urLD8iAkRDYUgfyuZ6hpH0lFsXySEkO91lW+2aRnhTInfCyjDdFkSYWCKTc+CMmRRSzBnS0uSZs+QaJnFi5izkkiFbEr4rRXZnZgiaSncPRneks382oCQstXfmLDIU1VmFqEKuGnMqQImnOZcVOXdHYGy/GbRRYkyIdMsk1yMmmj0yx/VeqJEDCDZ1VYVmx+xKULb0plIWFhMqnGs1uAC1SCG57rg8x05IbIBjOZ4N+vlZ+SbZ55+uudZBCt057+Am5J76LhMusj920LEPDDo+LXY4QBDQ7HKw1FnyVGnJfAqR2sMgUZMTej+jiEmGlD1NxJYJmV6YMUTFLHY6lOM/SpfzXoSG1oMHJ9+E5pWwFXkPRGDxjZUydsGiSo8X+FgecIBn+U5QdWO1lH2g9P6rp4Pcu09c2LO+6NCuobGmt5ZWCWOgcpLI4SgrIuFbxP4ytnXbk8buwxXXlNbmPU7Le9/5pg2jw9ud51w71u3tiX/RN95e/vWjW7ZEj4weenn4m6pnN42MnjWvHdzrjL6xOv3log8eged62fHxs1cWdf+5ZPdzzzi3M+ObL/6xtX3/7uBPR04N9n6364X5j48s+/Vql+vtj/8aPbps4w+/l3e9+aH32MVypu04aFuoxK+sWn785IKxos4Fe4bf36TF+s50Bb5lDpzc9xs+Za3/6syrn//cufpCE+zjj7303sn614Tt48FPnl+y+OArBzwjOho8b3z/2IlydudGTnnxqFR9uH5kuH3f5r0nQquWWKcr+97p2f/QzvTCqs+Mc6H2j9KBHa/Lm/Clir+rc9v4D9nbKUarEwAA';

        $client = new Client([
            'base_uri' => 'https://api.sandbox.ebay.com',
            'verify' => false,
        ]);

        $itemId = '254850180512';

        $response = $client->request('GET', '/buy/marketplace_insights/v1/item_sales/search', [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$access_token,
                'Content-Type' => 'application/json',
                'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_US'
            ],
            'query' => [
                'filter[itemId][0]' => $itemId,
                'limit' => 10,
                'offset' => 0,
                'sort' => '-soldDate'
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        print_r($data);

        die();
    }

    //function save the product info in products table
    public static function saveProducts($items)
    {
        if (!empty($items)) {
            foreach ($items as $item) {
               $start_time = new DateTime($item->listingInfo[0]->startTime[0], new DateTimeZone('UTC'));
                $end_time = new DateTime($item->listingInfo[0]->endTime[0], new DateTimeZone('UTC'));

                $products[] = [
                    'marketplace_id' => $item->itemId[0],
                    'product_name' => $item->title[0],
                    'parent_id' => $item->isMultiVariationListing[0],
                    'categories' => json_encode($item->primaryCategory[0]),
                    'product_url' => $item->viewItemURL[0],
                    'image_url' => $item->galleryURL[0],
                    'current_price' => $item->sellingStatus[0]->currentPrice[0]->{'__value__'},
                    'currency' => $item->sellingStatus[0]->currentPrice[0]->{'@currencyId'},
                    'product_listing_start_time' => $start_time->format('Y-m-d H:i:s'),
                    'product_listing_end_time' => $end_time->format('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            if (!empty($products)) {
                Products::insertOrIgnore($products);
            }
        }

        return;
    }


    //function fetching product prices from marketplace
    public static function fetchProductsPrices()
    {
        self::getProductsPrices();
    }

    public static function getProductsPrices()
    {
        echo "Start Date " . date('Y-m-d H:i:s');
        $url = rtrim(self::$base_url_buy, '/') . '/marketplace_insights/v1_beta/item_sales/search';

        //$url = $url.'?q=iphone&category_ids=9355';
        $filter = [
            'q' => 'iphone',
            'category_ids' => 9355
        ];

        $response = self::getApiCall($url, $filter);
        //self::debug($response);
        if ($response['status'] == 'Success') {
            self::saveProductsPrices($response['data']->itemSales);
        }

        echo "<br>";
        echo "End Date " . date('Y-m-d H:i:s');
    }

    public static function saveProductsPrices($soldItems)
    {
        if (!empty($soldItems)) {
            foreach ($soldItems as $soldItem) {
                $datetime = new DateTime($soldItem->lastSoldDate, new DateTimeZone('UTC'));;
                $last_sold_datetime = $datetime->format('Y-m-d H:i:s');

                $product_price = new ProductsPrices();

                $product_price->ProductID = 1;
                $product_price->product_last_sold_price = $soldItem->lastSoldPrice->value;
                $product_price->currency = $soldItem->lastSoldPrice->currency;
                $product_price->product_last_sold_date = $last_sold_datetime;
                $product_price->total_sold_quantity = $soldItem->totalSoldQuantity;
                $product_price->created_at = date('Y-m-d H:i:s');
                $product_price->updated_at = date('Y-m-d H:i:s');
                $product_price->save();
            }
        }

        return;
    }
}
