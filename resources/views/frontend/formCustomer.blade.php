@extends('frontend.index')
@section('content')
<div class="container" style="margin-top: 200px!important">
    <div class="container">
        <form action="{{url('payment')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="exampleInput" class="form-label">Họ tên</label>
                <input type="text" class="form-control" id="exampleInput" name="customer_name">
              </div>
              <div class="mb-3">
                <label for="exampleInput" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="exampleInput" name="customer_address">
              </div>
              <div class="mb-3">
                <label for="exampleInput" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="exampleInput" name="customer_phone">
              </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email</label>
              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="customer_email">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-danger">Thanh toán khi nhận hàng</button>
              </div>
            </form>
    </div>
</div>
@endsection