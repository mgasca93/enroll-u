<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    protected $paymentsArray = [
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

    public function index(){
        $customers = DB::table('customers')->get();
        return view('dashboard.customers.index', [ 'customers' => $customers ]);
    }

    public function store(){
        
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
            $productRandom = rand(0, count( $this->paymentsArray ) -1 );
            $quantity = rand(1, 50);
            $productName = $this->paymentsArray[$productRandom]['name'];
            $productsPrice = ($this->paymentsArray[$productRandom]['price'] * $quantity);
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

    public function showCustomersAPI(){
        $customers = DB::table('customers')->select('id', 'name', 'paternal_surname', 'email')->get();
        return $customers;
    }

    public function registerCustomer(){
        $data = [];
        
        $validator = Validator::make( request()->all(),[
            'name'          => 'required',
            'lastname'      => 'required',
            'email'         => 'required|email|unique:customers,email', 
        ]);

        if( $validator->fails() ){
            return response()->json( $validator->errors() );
        }

        if( isSuperAdmin() ) :
            DB::table('customers')->insert([
                'name'              => request('name'),
                'paternal_surname'  => request('lastname'),
                'email'             => request('email'),
                'administrator_id'  => 1,
                'created_at'        => now(),
                'updated_at'        => now()
            ]);
            $customerCreated = DB::table('customers')->latest('id')->first();
            $cantidadProductos = rand(1, 3);
            for( $i = 1; $i <= $cantidadProductos; $i++ ) :
                $productRandom = rand(0, count( $this->paymentsArray ) -1 );
                $quantity = rand(1, 50);
                $productName = $this->paymentsArray[$productRandom]['name'];
                $productsPrice = ($this->paymentsArray[$productRandom]['price'] * $quantity);
                DB::table('payments_customers')->insert([
                    'amount'        => $productsPrice,
                    'customer_id'   => $customerCreated->id,
                    'product_name'  => $productName,
                    'quantity'      => $quantity,
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);
            endfor;
    
            $customer = DB::table('customers')->where('id', '=', $customerCreated->id)->select('id', 'name', 'paternal_surname', 'email')->get();
            $payments = DB::table('payments_customers')
            ->join('customers', 'payments_customers.customer_id', '=', 'customers.id')
            ->where('customer_id', '=', $customerCreated->id)
            ->select('payments_customers.id', 'payments_customers.product_name', 'payments_customers.quantity', 'payments_customers.amount')
            ->get();
    
            $data = [
                'customer'  => $customer,
                'payments'  => $payments
            ];
            return response()->json( $data, 200);
        endif;
        $data = ['message' => 'Only the super user has permissions to create customers'];
        return response()->json( $data );
    }

    public function showCustomerById( $customerID = '' ){
        if(strlen( $customerID ) == 0 ) :
            return response()->json( [ 'errors' => 'The customer id param is required'] );
        endif;

        $customer = DB::table('customers')->where('id', '=', $customerID)->select('id', 'name', 'paternal_surname', 'email')->get();
        if( count($customer) == 0 ) :
            return response()->json( [ 'errors' => 'The customer don´t exists'] );
        endif;

        return $customer;
    }

    public function deleteCustomer( $customerID = ''){
        if(strlen( $customerID ) == 0 ) :
            return response()->json( [ 'errors' => 'The customer id param is required'] );
        endif;

        if( isSuperAdmin() ) :
            $customer = DB::table('customers')->where('id', '=', $customerID)->select('id', 'name', 'paternal_surname', 'email')->get();
            if( count($customer) == 0 ) :
                return response()->json( [ 'errors' => 'The customer don´t exists'] );
            endif;

            $delete = DB::table('customers')->where('id', '=', $customerID)->delete();
            if( $delete ) :
                return response()->json( ['message' => 'Customer deleted with success'] );
            endif;
        endif;
        $data = ['message' => 'Only the super user has permissions to delete customers'];
        return response()->json( $data );
    }

    public function updateCustomer( $customerID = '', Request $request ){

        if(strlen( $customerID ) == 0 ) :
            return response()->json( [ 'errors' => 'The customer id param is required'] );
        endif;

        if( isSuperAdmin() ) :
            $customer = DB::table('customers')->where('id', '=', $customerID)->select('id', 'name', 'paternal_surname', 'email')->get();
            if( count($customer) == 0 ) :
                return response()->json( [ 'errors' => 'The customer don´t exists'] );
            endif;

            $dataUpdate = [];
            if( strlen( request('name') ) > 0 )array_push($dataUpdate, ['name' => request('name')]);
            if( strlen( request('lastname') ) > 0 )array_push($dataUpdate, ['paternal_surname' => request('lastname')]);
            if( strlen( request('email') ) > 0 )array_push($dataUpdate, ['email' => request('email')]);
            
            DB::table('customers')
            ->where('id', $customerID)
            ->update($dataUpdate);
            $data = ['message' => 'Customer updated with success'];
            return response()->json( $data );
        endif;
        $data = ['message' => 'Only the super user has permissions to update customers'];
        return response()->json( $data );
    }
}
