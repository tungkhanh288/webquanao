<?php

namespace App\Http\Controllers;
use App\Http\Requests\CustomerRequest;
use App\Customer;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Bill;
use App\Bill_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VnPayController extends Controller
{
    //
    public function getFormCustomersVnPay(){
        return view('frontend.formCustomerVnPay');
    }
    public function payment(CustomerRequest $request)
    {  
        DB::transaction(function () use ($request) {
                $carts = Session::get('carts') ?? [];
                $customer = Customer::query()->firstOrCreate([
                    'customer_email' => $request->get('customer_email'),
                    'customer_phone' => $request->get('customer_phone'),
                ], [
                    'customer_name' => $request->get('customer_name'),
                    'customer_address' => $request->get('customer_address'),
                ]);

                $bill = new Bill;
                $bill->customer_id = $customer->customer_id;
                $bill->user_id = Session::get('user_id');
                $bill->bill_date = now();
                $bill->bill_total = Session::get('total');

                $bill->save();

                if (count($carts) > 0) {
                    foreach ($carts as $key => $item) {
                        $billDetail = new Bill_detail;
                        $billDetail->bill_id = $bill->bill_id;
                        $billDetail->product_id = $item['id'];
                        $billDetail->size_name = $item['size_name'];
                        $billDetail->quantity = $item['qty'];
                        $billDetail->price = $item['price'];
                        $billDetail->save();

                        $product = Product::query()->findOrFail($item['id']);
                        $quantity = $product->sizes()
                        ->where('product_id', $item['id'])
                        ->where('sizes.size_id', $item['size_id'])
                        ->first();
                        $product->sizes()->updateExistingPivot($item['size_id'], ['quantity' => $quantity->pivot->quantity-$item['qty']]);
                    }
                }
            });
            Session::put('redirect');
        return redirect()->route('vnpay');
    }
    public function vnpay(){
        $total = Session::get('total');
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = url('paySuccess');
        $vnp_TmnCode = "6SUPCEOM";//Mã website tại VNPAY 
        $vnp_HashSecret = "MHSWCUFTKVNIZLISADIOMUUXWGBMVVYC"; //Chuỗi bí mật
        
        $vnp_TxnRef = rand(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán hóa đơn';
        $vnp_OrderType = 'web quan ao';
        $vnp_Amount = $total * 100;
        $vnp_Locale = 'VN';
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }
        
        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00'
            , 'message' => 'success'
            , 'data' => $vnp_Url);
            if (Session::get('redirect') == null) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
    }
}
