<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
class PaymentsController extends Controller
{
    public function index(){
        $customers = DB::table('customers')->get();
        $payments = DB::table('payments_customers')
        ->join('customers', 'payments_customers.customer_id', '=', 'customers.id')
        ->select('payments_customers.id', 'payments_customers.product_name', 'payments_customers.quantity', 'payments_customers.amount', 'customers.name', 'customers.paternal_surname')
        ->get();
        return view('dashboard.payments.index', [ 'payments' => $payments, 'customers' => $customers ]);
    }


    public function store(){
        request()->validate([
            'customer_id'       => 'required',
            'productName'       => 'required|min:10',
            'unitPrice'         => 'required|decimal:0,2',
            'quantity'          => 'required|numeric',
        ]);

        DB::table('payments_customers')->insert([
            'customer_id'       => request('customer_id'),
            'product_name'      => request('productName'),
            'quantity'          => request('quantity'),
            'amount'            => (request('unitPrice') * request('quantity')),
            'created_at'        => now(),
            'updated_at'        => now()
        ]);

        return redirect()->route('payments.view');
    }

    public function delete(){
        request()->validate([
            'paymentID'          => 'required',
        ]);
        DB::table('payments_customers')->where('id', '=', request('paymentID'))->delete();
        return redirect()->route('payments.view');
    }

    public function getPayment( $paymentID ){
        $customer = DB::table('payments_customers')->select('id', DB::raw('(amount/quantity) as price'), 'customer_id', 'product_name', 'quantity')->where('id', '=', $paymentID)->first();
        return response()->json([
            'status'    => 'success',
            'data'      => $customer
        ]);
    }

    public function update(){
        
        request()->validate([
            'paymentID'         => 'required',
            'customer_id'       => 'required',
            'productName'       => 'required|min:10',
            'unitPrice'         => 'required|decimal:0,2',
            'quantity'          => 'required|numeric',
        ]);

        DB::table('payments_customers')
            ->where('id', request('paymentID'))
            ->update([
            'amount'                => (request('unitPrice') * request('quantity')),
            'customer_id'           => request('customer_id'),
            'product_name'          => request('productName'),
            'quantity'              => request('quantity'),
            'updated_at'            => now()
        ]);

        return redirect()->route('payments.view');
    }

    public function showPaymentsAPI(){
        $payments = DB::table('payments_customers')
        ->join('customers', 'payments_customers.customer_id', '=', 'customers.id')
        ->select('payments_customers.id', 'payments_customers.product_name', 'payments_customers.quantity', 'payments_customers.amount', 'customers.name', 'customers.paternal_surname')
        ->get();
        return $payments;
    }

    public function showPaymentsByCustomerId( $customerID = '' ){
        if(strlen( $customerID ) == 0 ) :
            return response()->json( [ 'errors' => 'The customer id param is required'] );
        endif;

        $customer = DB::table('customers')->where('id', '=', $customerID)->select('id', 'name', 'paternal_surname', 'email')->get();
        if( count($customer) == 0 ) :
            return response()->json( [ 'errors' => 'The customer don´t exists'] );
        endif;

        $payments = DB::table('payments_customers')
        ->join('customers', 'payments_customers.customer_id', '=', 'customers.id')
        ->where('customer_id', '=', $customerID)
        ->select('payments_customers.id', 'payments_customers.product_name', 'payments_customers.quantity', 'payments_customers.amount')
        ->get();

        $data = [
            'customer'  => $customer,
            'payments'  => $payments
        ];

        return $data;
    }

    public function registerPayment( $customerID ){

        if( isSuperAdmin() ) :
            if(strlen( $customerID ) == 0 ) :
                return response()->json( [ 'errors' => 'The customer id param is required'] );
            endif;
    
            $validator = Validator::make( request()->all(),[
                'product_name'          => 'required|min:10',
                'unitPrice'             => 'required|decimal:0,2',
                'quantity'              => 'required|numeric', 
            ]);
    
            if( $validator->fails() ){
                return response()->json( $validator->errors() );
            }
    
            $customer = DB::table('customers')->where('id', '=', $customerID)->select('id', 'name', 'paternal_surname', 'email')->get();
            if( count($customer) == 0 ) :
                return response()->json( [ 'errors' => 'The customer don´t exists'] );
            endif;
    
            $payment = DB::table('payments_customers')->insert([
                'customer_id'       => $customerID,
                'product_name'      => request('product_name'),
                'quantity'          => request('quantity'),
                'amount'            => (request('unitPrice') * request('quantity')),
                'created_at'        => now(),
                'updated_at'        => now()
            ]);
    
            $data = [];
            if( $payment ) :
                $data = [ 'message' => 'Payment created with success'];
            endif;
            
            return response()->json( $data );
        endif;

        $data = ['message' => 'Only the super user has permissions to create payments'];
        return response()->json( $data );
    }

    public function deletePayment( $paymentID = ''){
        if( isSuperAdmin() ) :
            if(strlen( $paymentID ) == 0 ) :
                return response()->json( [ 'errors' => 'The customer id param is required'] );
            endif;
    
            $payment = DB::table('payments_customers')->where('id', '=', $paymentID)->select('id')->get();
            if( count($payment) == 0 ) :
                return response()->json( [ 'errors' => 'The customer don´t exists'] );
            endif;
    
            $delete = DB::table('payments_customers')->where('id', '=', $paymentID)->delete();
            if( $delete ) :
                return response()->json( ['message' => 'Payment deleted with success'] );
            endif;
        endif;
        $data = ['message' => 'Only the super user has permissions to delete payments'];
        return response()->json( $data );
    }

    public function updatePayment( $paymentID = '', Request $request ){
        if( isSuperAdmin() ) :
            $payment = DB::table('payments_customers')->where('id', '=', $paymentID)->get();
            if( count($payment) == 0 ) :
                return response()->json( [ 'errors' => 'The customer don´t exists'] );
            endif;

            $dataUpdate = [];
            if( strlen( request('product_name') ) > 0 )array_push($dataUpdate, ['product_name' => request('product_name')]);
            if( strlen( request('quantity') ) > 0 )array_push($dataUpdate, ['quantity' => request('quantity')]);
            if( strlen( request('customer') ) > 0 )array_push($dataUpdate, ['customer_id' => request('customer')]);
            
            if( count( $dataUpdate ) > 0 ) :
                DB::table('payments_customers')
                ->where('id', $paymentID)
                ->update($dataUpdate);
            endif;
            
            $data = ['message' => 'Payment updated with success'];
            return response()->json( $data );
        endif;

        $data = ['message' => 'Only the super user has permissions to update payments'];
        return response()->json( $data );
    }
}
