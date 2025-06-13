<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
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
        $request->validate([
            'code' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'value' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'min_order_value' => 'required|numeric',
            'user_specific' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_active' => 'boolean',
        ]);

        DiscountCode::create($request->all());
        return redirect()->route('discounts.index')->with('success', 'Mã giảm giá đã được tạo.');
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
            'max_discount' => 'nullable|numeric',
            'min_order_value' => 'nullable|numeric',
            'user_specific' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // Update the discount code in the database
        // DiscountCode::findOrFail($id)->update($data);
$discount->update($request->all());
        return redirect()->route('discounts.index')->with('success', 'Sửa mã giảm giá thành công.');
    }
    public function destroy(DiscountCode $discount)
    {
        // Logic to delete a discount code
        // DiscountCode::destroy($id);
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', 'Mã giảm giá đã được xóa.');
    }
}
