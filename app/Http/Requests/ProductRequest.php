<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'product_name' => 'required',
            'product_price' => 'required',
            'product_discount' => 'required',
            'product_image' => 'sometimes|required_if:image, ""|image|max:2048',
            'product_color' => 'required',
            'product_description' => 'required',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
            'category_id' => 'required'
        ];
    }
    public function message()
    {
        return [
            'product_name' => 'Tên sản phẩm không được để trống',
            'product_price' => 'Giá không được để trống',
            'product_discount' => 'Giá giảm không được để trống',
            'product_image' => 'Hình ảnh không được để trống',
            'product_color' => 'Mauf sắc không được để trống',
            'size_id' => 'Size không được để trống',
            'product_description' => 'Mô tả không được để trống',
            'product_quantity' => 'Mô tả không được để trống',
            'category_id' => 'Loại sản phẩm không được để trống'
        ];
    }
}
