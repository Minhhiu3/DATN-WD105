<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdviceProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AdviceProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
           $sale = AdviceProduct::with('product')->findOrFail($id);

          return view('admin.sale_product.index', compact('sale'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AdviceProduct $adviceProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdviceProduct $adviceProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sale = AdviceProduct::findOrFail($id);

        // Validate dữ liệu gửi lên
        $validated = $request->validate([
            'value' => 'required|numeric|min:0|max:100', // phần trăm giảm
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Cập nhật dữ liệu
        $sale->value = $validated['value'];
        $sale->start_date = Carbon::parse($validated['start_date']);
        $sale->end_date = Carbon::parse($validated['end_date']);
        $sale->save();

        return response()->json([
            'message' => 'Cập nhật thông tin sale thành công!',
            'sale' => $sale
        ]);
    }


    // thay đổi trạng thái
        public function toggleStatus(Request $request, $id)
    {
        $sale = AdviceProduct::findOrFail($id);

        $request->validate([
            'status' => 'required|in:on,off'
        ]);

        $sale->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Trạng thái đã được cập nhật!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdviceProduct $adviceProduct)
    {
        //
    }
}
