<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function index(){
        $customers = DB::table('customers')->get();
        return view('dashboard.customers.index', [ 'customers' => $customers ]);
    }

    public function store(){
        /**
         * Creamos un arreglo de productos para que se auto inserten en los payments
         */
        $paymentsArray = [
            [
                'name'  => 'Playera Cabron',
                'price' => 171.00
            ],
            [
                'name'  => 'Playera Caballero Tricolor Logo',
                'price' => 171.00
            ],
            [
                'name'  => 'Playera King',
                'price' => 200.00
            ],
            [
                'name'  => 'Playera Candado',
                'price' => 200.00
            ],
            [
                'name'  => 'Playera Rompecabeza',
                'price' => 186.00
            ],
            [
                'name'  => 'Playera Pizza',
                'price' => 186.00
            ],
            [
                'name'  => 'Playera Cara Flechas',
                'price' => 215.00
            ],
            [
                'name'  => 'Playera Mangas',
                'price' => 200.00
            ],
            [
                'name'  => 'Playera Torax Musical',
                'price' => 215.00
            ],
            [
                'name'  => 'Playera Cabron',
                'price' => 171.00
            ],
            [
                'name'  => 'Jeans Cargo Negro',
                'price' => 503.00
            ],
            [
                'name'  => 'Jeans Slim Denin con Destrucción',
                'price' => 459.00
            ],
            [
                'name'  => 'Jeans Relaxed',
                'price' => 431.00
            ],
            [
                'name'  => 'Jeans Slim Básico',
                'price' => 399.00
            ],
            [
                'name'  => 'Jeans Tipo Jogger',
                'price' => 503.00
            ],
            [
                'name'  => 'Jeans desgaste Indigo',
                'price' => 474.00
            ],
            [
                'name'  => 'Jeans Negro Deslavado',
                'price' => 650.00
            ],
            [
                'name'  => 'Jeans Demin Azul',
                'price' => 599.00
            ],
            [
                'name'  => 'Playera Obsidian',
                'price' => 330.00
            ],
            [
                'name'  => 'Camisa Galaxia',
                'price' => 359.00
            ],
        ];

        request()->validate([
            'name'          => 'required',
            'lastname'      => 'required',
            'email'         => 'required|email|unique:customers,email',
        ]);

        DB::table('customers')->insert([
            'name'              => request('name'),
            'paternal_surname'  => request('lastname'),
            'email'             => request('email'),
            'administrator_id'  => Auth::user()->id,
            'created_at'        => now(),
            'updated_at'        => now()
        ]);
        $customerCreated = DB::table('customers')->latest('id')->first();
        
        $cantidadProductos = rand(1, 3);
        for( $i = 1; $i <= $cantidadProductos; $i++ ) :
            $productRandom = rand(0, count( $paymentsArray ) -1 );
            $quantity = rand(1, 50);
            $productName = $paymentsArray[$productRandom]['name'];
            $productsPrice = ($paymentsArray[$productRandom]['price'] * $quantity);
            DB::table('payments_customers')->insert([
                'amount'        => $productsPrice,
                'customer_id'   => $customerCreated->id,
                'product_name'  => $productName,
                'quantity'      => $quantity,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        endfor;
        return redirect()->route('customers.view');
    }

    public function delete(){
        request()->validate([
            'customerID'          => 'required',
        ]);
        DB::table('customers')->where('id', '=', request('customerID'))->delete();
        return redirect()->route('customers.view');
    }

    public function getCustomer( $customerId ){
        $customer = DB::table('customers')->select('id', 'name', 'paternal_surname', 'email')->where('id', '=', $customerId)->first();
        return response()->json([
            'status'    => 'success',
            'data'      => $customer
        ]);
    }

    public function update(){
       
        request()->validate([
            'customerId'          => 'required',
            'nameUpdate'          => 'required',
            'lastnameUpdate'      => 'required'
        ]);

        DB::table('customers')
            ->where('id', request('customerId'))
            ->update([
            'name'              => request('nameUpdate'),
            'paternal_surname'  => request('lastnameUpdate'),
            'updated_at'        => now()
        ]);

        return redirect()->route('customers.view');
    }

}
