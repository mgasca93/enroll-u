<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\payments_customers;
use App\Models\customers;

class DashboardController extends Controller
{
    public function index(){

        /**
         * Obtener los customers con el total mas alto de payments
         */
        $topFiveCustomers = DB::table('payments_customers')
                                        ->join('customers', 'payments_customers.customer_id', '=', 'customers.id')
                                        ->select('customers.id', DB::raw('SUM( payments_customers.amount ) as monto'), 'customers.name')
                                        ->groupBy('customers.id')
                                        ->orderBy('monto', 'desc')
                                        ->limit(5)
                                        ->get();
        $payments_info = DB::table('payments_customers')
                                    ->select( DB::raw('SUM( quantity ) as total_products'), DB::raw('sum(amount) as total_payments'))
                                    ->get();
        $customersRegistered = DB::table('customers')
                                    ->select( DB::raw('COUNT(id) as customers') )
                                    ->distinct()->get();

        $total = 0;
        if( $payments_info[0]->total_products > 0) :
            $total = $payments_info[0]->total_products;
        endif;
        $porcentageProducts = DB::table('payments_customers')
                                ->select( DB::raw('((SUM( quantity ) * 100 ) / ('.$total.')) as porcentage'), 'product_name')
                                ->groupBy('product_name')
                                ->get();

                        
        /**
         * Construimos la data para echarts
         */
        $data = [];
        $categories = [];
        foreach( $topFiveCustomers as $customer ) :
            array_push( $data, [ 'name' => $customer->name, 'data' => [$customer->monto]]);
            array_push( $categories, $customer->name);
        endforeach;
        $data = json_encode($data);
        $categories = json_encode($categories);


        /**
         * Construimos la data para el pie de productos
         */
        $porcentageProduct = [];
        $productsName = [];
        foreach( $porcentageProducts as $product ) :
            array_push( $porcentageProduct, (float) number_format($product->porcentage, 2) );
            array_push( $productsName, $product->product_name );
        endforeach;

        $porcentageProduct = json_encode( $porcentageProduct );
        $productsName = json_encode( $productsName );


        $data = [
            'data_porcentage'       => $porcentageProduct,
            'data_producst_name'    => $productsName,
            'data_barras'           => $data,
            'data_categories'       => $categories,
            'total_customers'       => $customersRegistered[0]->customers,
            'total_products'        => $total, 
            'total_payments'        => number_format($payments_info[0]->total_payments,2)
        ];

        $topFiveCustomers = json_encode( $topFiveCustomers );
        return view('dashboard.home', $data);
    }
}
