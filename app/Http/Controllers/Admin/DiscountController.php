<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    //
    public function index()
    {
        $discounts = DiscountCode::all();
        return view('admin.discounts.index',compact('discounts'));
    }
    public function create()
    {

        return view('admin.discounts.create');
    }
    public function store(Request $request)
{
    $rules = [
        'code' => 'required|string|max:50',
        'type' => 'required|in:0,1',
        'value' => 'required|numeric|min:0',
        'min_order_value' => 'nullable|numeric|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'is_active' => 'sometimes|boolean',
    ];

    // Nếu loại là phần trăm → giới hạn 100
    if ($request->input('type') == '0') {
        $rules['value'] .= '|max:100';
    }

    $messages = [
        // Mã giảm giá
        'code.required' => 'Vui lòng nhập mã giảm giá.',
        'code.string'   => 'Mã giảm giá phải là chuỗi ký tự.',
        'code.max'      => 'Mã giảm giá không được vượt quá 50 ký tự.',

        // Loại
        'type.required' => 'Vui lòng chọn loại giảm giá.',
        'type.in'       => 'Loại giảm giá không hợp lệ.',

        // Giá trị
        'value.required' => 'Vui lòng nhập giá trị giảm.',
        'value.numeric'  => 'Giá trị giảm phải là số.',
        'value.min'      => 'Giá trị giảm không được nhỏ hơn 0.',
        'value.max'      => 'Khi chọn loại phần trăm, giá trị không được vượt quá 100%.',

        // Giá trị đơn tối thiểu
        'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
        'min_order_value.min'     => 'Giá trị đơn tối thiểu không được nhỏ hơn 0.',

        // Ngày bắt đầu
        'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
        'start_date.date'     => 'Ngày bắt đầu không hợp lệ.',

        // Ngày kết thúc
        'end_date.required'        => 'Vui lòng chọn ngày kết thúc.',
        'end_date.date'            => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal'  => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

        // Hoạt động
        'is_active.boolean' => 'Trạng thái hoạt động không hợp lệ.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
    }

    DiscountCode::create($validator->validated());

    return redirect()->route('admin.discounts.index')->with('success', 'Mã giảm giá đã được tạo.');
}

    public function edit(DiscountCode $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }
    public function update(Request $request, DiscountCode $discount)
    {

       $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string',
            'value' => 'required|numeric',
            'min_order_value' => 'nullable|numeric',
            'user_specific' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // Update the discount code in the database
        // DiscountCode::findOrFail($id)->update($data);
$discount->update($request->all());
        return redirect()->route('admin.discounts.index')->with('success', 'Sửa mã giảm giá thành công.');
    }
    public function destroy(DiscountCode $discount)
    {
        // Logic to delete a discount code
        // DiscountCode::destroy($id);
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('success', 'Mã giảm giá đã được xóa.');
    }
}
