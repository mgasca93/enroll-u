<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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
}
