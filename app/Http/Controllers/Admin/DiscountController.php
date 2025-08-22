<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    //
public function index(Request $request)
{
    $query = DiscountCode::query(); 

    // Lọc theo từ khóa
    if ($request->filled('keyword')) {
        $query->where('code', 'like', '%' . $request->keyword . '%');
    }

    // Lọc theo loại
    if ($request->filled('type')) {
        $query->where('type', $request->type); 
    }
    // Lọc theo chương trình
    if ($request->filled('program_type')) {
        $query->where('program_type', $request->program_type); 
    }
    // Lọc theo hoạt động
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active); 
    }
    // Lấy kết quả
    $discounts = $query->paginate(6);

    return view('admin.discounts.index', compact('discounts'));
}
    
    public function create()
    {
        return view('admin.discounts.create');
    }
    public function store(Request $request)
{
    $rules = [
        'code' => 'required|string|max:50|unique:discount_codes,code',
        'type' => 'required|in:0,1',
        'value' => 'required|numeric|min:1',
        'min_order_value' => 'required|numeric|min:1000',
        'max_order_value' => 'required|numeric|min:1000',
        'quantity' => 'required|numeric|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'is_active' => 'sometimes|boolean',
        'program_type' => 'required',
    ];

    // Nếu loại là phần trăm → giới hạn 100
    if ($request->input('type') == '0') {
        $rules['value'] = '|max:100';
    }
    if ($request->input('is_active') === null) {
        $request->merge(['is_active' => 0]);
    }
    $messages = [
        // Mã giảm giá
        'code.required' => 'Vui lòng nhập mã giảm giá.',
        'code.string'   => 'Mã giảm giá phải là chuỗi ký tự.',
        'code.max'      => 'Mã giảm giá không được vượt quá 50 ký tự.',
        'code.unique'      => 'Mã giảm giá đã tồn tại trong hệ thống.',

        // Loại
        'type.required' => 'Vui lòng chọn loại giảm giá.',
        'type.in'       => 'Loại giảm giá không hợp lệ.',

        // Giá trị
        'value.required' => 'Vui lòng nhập giá trị giảm.',
        'value.numeric'  => 'Giá trị giảm phải là số.',
        'value.min'      => 'Giá trị giảm không được nhỏ hơn 0.',
        'value.max'      => 'Khi chọn loại phần trăm, giá trị không được vượt quá 100%.',

        // Giá trị đơn tối thiểu
        'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',
        'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
        'min_order_value.min'     => 'Giá trị đơn tối thiểu không được nhỏ hơn 1000.',
        // Giá trị đơn tối thiểu
        'max_order_value.required' => 'Vui lòng nhập giá trị đơn tối đa.',
        'max_order_value.numeric' => 'Giá trị đơn tối đa phải là số.',
        'max_order_value.min'     => 'Giá trị đơn tối đa không được nhỏ hơn 1000.',
        // Số lượng
        'quantity.required' => 'Vui lòng nhập số lượng.',
        'quantity.numeric'  => 'Số lượng phải là số.',
        'quantity.min'      => 'Số lượng không được nhỏ hơn 1.',
        // Ngày bắt đầu
        'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
        'start_date.date'     => 'Ngày bắt đầu không hợp lệ.',

        // Ngày kết thúc
        'end_date.required'        => 'Vui lòng chọn ngày kết thúc.',
        'end_date.date'            => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal'  => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

        // Hoạt động
        'is_active.boolean' => 'Trạng thái hoạt động không hợp lệ.',
        'program_type.required'        => 'Vui lòng chọn loại chương trình.',
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
    public function show($id)
    {
        $discount = DiscountCode::findOrFail($id);
        return view('admin.discounts.show', compact('discount'));
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
            'value' => ['required','numeric' ,Rule::when($request->input('type') == '0', ['max:100'])],
            'min_order_value' => 'nullable|numeric',
            'max_order_value' => 'required|numeric|min:1000',
            'quantity' => 'required|numeric|min:1',
            'user_specific' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'program_type' => 'required',
                ],
            [
                // Mã giảm giá
                'code.required' => 'Vui lòng nhập mã giảm giá.',
                'code.string'   => 'Mã giảm giá phải là chuỗi ký tự.',
                'code.max'      => 'Mã giảm giá không được vượt quá 50 ký tự.',
                'code.unique'      => 'Mã giảm giá đã tồn tại trong hệ thống.',

                // Loại
                'type.required' => 'Vui lòng chọn loại giảm giá.',
                'type.in'       => 'Loại giảm giá không hợp lệ.',

                // Giá trị
                'value.required' => 'Vui lòng nhập giá trị giảm.',
                'value.numeric'  => 'Giá trị giảm phải là số.',
                'value.min'      => 'Giá trị giảm không được nhỏ hơn 0.',
                'value.max'      => 'Khi chọn loại phần trăm, giá trị không được vượt quá 100%.',

                // Giá trị đơn tối thiểu
                'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',
                'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
                'min_order_value.min'     => 'Giá trị đơn tối thiểu không được nhỏ hơn 0.',
               
                // Giá trị đơn tối thiểu
                'max_order_value.required' => 'Vui lòng nhập giá trị đơn tối đa.',
                'max_order_value.numeric' => 'Giá trị đơn tối đa phải là số.',
                'max_order_value.min'     => 'Giá trị đơn tối đa không được nhỏ hơn 1000.',
                
                // Số lượng
                'quantity.required' => 'Vui lòng nhập số lượng.',
                'quantity.numeric'  => 'Số lượng phải là số.',
                'quantity.min'      => 'Số lượng không được nhỏ hơn 1.',
                
                // Ngày bắt đầu
                'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
                'start_date.date'     => 'Ngày bắt đầu không hợp lệ.',

                // Ngày kết thúc
                'end_date.required'        => 'Vui lòng chọn ngày kết thúc.',
                'end_date.date'            => 'Ngày kết thúc không hợp lệ.',
                'end_date.after_or_equal'  => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

            ]);
            if ($request->input('is_active') === null) {
                $request->merge(['is_active' => 0]);
            }
        $exists = DiscountCode::where('code', $request->code)
                    ->where('discount_id', '!=', $discount->discount_id) // loại trừ size hiện tại
                    ->exists();

        if ($exists) {
            return back()
                ->withErrors(['code' => 'Tên size này đã tồn tại trong hệ thống.'])
                ->withInput();
        }
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
    //check ten co tin tai trong he thong khon
    public function checkCode(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['isUnique' => false]);
        }

        // Kiểm tra trong DB
        $exists = DiscountCode::where('code', $code)->exists();

        return response()->json([
            'isUnique' => !$exists
        ]);
    }
    public function trash(Request $request)
    {
        $discountsQuery = DiscountCode::onlyTrashed();

        // Tìm kiếm theo mã giảm giá
        if ($request->filled('keyword')) {
            $discountsQuery->where('code', 'like', '%' . $request->keyword . '%');
        }
       
        $discounts = $discountsQuery->latest('discount_id')->paginate(5);

        return view('admin.discounts.trash', compact('discounts'));
    }

    /**
     * Khôi phục discount code đã xóa mềm
     */
    public function restore($id)
    {
        $discount = DiscountCode::onlyTrashed()->findOrFail($id);
        $discount->restore();

        return redirect()->route('admin.discounts.trash')
            ->with('success', 'Khôi phục mã giảm giá thành công!');
    }

    /**
     * Xóa cứng discount code
     */
    public function forceDelete($id)
    {
        $discount = DiscountCode::onlyTrashed()->findOrFail($id);
        $discount->forceDelete();

        return redirect()->route('admin.discounts.trash')
            ->with('success', 'Xóa vĩnh viễn mã giảm giá thành công!');
    }
    public function checkExpire()
{
    $updated = DiscountCode::where('end_date', '<', now())
        ->where('is_active', 1)
        ->update(['is_active' => 0]);

    return response()->json(['updated' => $updated]);
}


}


