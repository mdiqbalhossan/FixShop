<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('plugin.active:quotation');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $quotations = Quotation::query();
        $quotations->search($search);
        $quotations = $quotations->latest()->paginate($perPage);
        return view('quotation.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $quotation = Quotation::latest()->first();
        if ($quotation) {
            $quotation_id = $quotation->quotation_number;
            $quotation_id = (int) substr($quotation_id, 2);
            ++$quotation_id;
            $quotation_id = str_pad($quotation_id, 6, "0", STR_PAD_LEFT);
            $quotation_id = 'q-' . $quotation_id;
        } else {
            $quotation_id = 'q-000001';
        }
        return view('quotation.create', compact('customers', 'warehouses', 'quotation_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer' => 'required',
            'warehouse' => 'required',
            'date' => 'required',
            'quotation_no' => 'required|unique:quotations,quotation_number',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $quotation = Quotation::create([
                'quotation_number' => $input['quotation_no'],
                'customer_id' => $input['customer'],
                'warehouse_id' => $input['warehouse'],
                'quotation_date' => $input['date'],
                'note' => $input['note'],
                'total_amount' => $input['total_amount'],
                'discount_amount' => $input['discount'],
                'tax_amount' => $input['tax'],
                'grand_total' => $input['grand_total'],
            ]);

            if (isset($input['product_id']) && is_array($input['product_id'])) {
                $this->productPivotSave($input, $quotation, 'add');
            }

            DB::commit();
            return redirect()->route('quotation.index')->with('success', __('Quotation created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation)
    {
        return view('quotation.invoice', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('quotation.edit', compact('customers', 'warehouses', 'quotation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer' => 'required',
            'warehouse' => 'required',
            'date' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $quotation->update([
                'customer_id' => $input['customer'],
                'warehouse_id' => $input['warehouse'],
                'quotation_date' => $input['date'],
                'note' => $input['note'],
                'total_amount' => $input['total_amount'],
                'discount_amount' => $input['discount'],
                'tax_amount' => $input['tax'],
                'grand_total' => $input['grand_total'],
            ]);

            if (isset($input['product_id']) && is_array($input['product_id'])) {
                $this->productPivotSave($input, $quotation, 'update');
            }

            DB::commit();
            return redirect()->route('quotation.index')->with('success', __('Quotation updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
        //
    }

    /**
     * Product Details Save Function
     * @param array $input
     * @param Quotation $quotation
     */
    private function productPivotSave(array $input, Quotation $quotation, $status = 'add')
    {
        foreach ($input['product_id'] as $key => $data) {
            $product = Product::find($data);
            if ($product->product_type == 'variation') {
                if ($status == 'add') {
                    $quotation->products()->attach($data, [
                        'quantity' => $input['quantity'][$key],
                        'price' => $input['price'][$key],
                        'total_price' => $input['total'][$key],
                        'variation_id' => $input['variation_id'][$key],
                        'variation_value' => $input['variation_value'][$key],
                    ]);
                } else {
                    $quotation->products()->detach($data);
                    $quotation->products()->attach($data, [
                        'quantity' => $input['quantity'][$key],
                        'price' => $input['price'][$key],
                        'total_price' => $input['total'][$key],
                        'variation_id' => $input['variation_id'][$key],
                        'variation_value' => $input['variation_value'][$key],
                    ]);
                }

            } else {
                if ($status == 'add') {
                    $quotation->products()->attach($data, [
                        'quantity' => $input['quantity'][$key],
                        'price' => $input['price'][$key],
                        'total_price' => $input['total'][$key],
                    ]);
                }else{
                    $quotation->products()->detach($data);
                    $quotation->products()->attach($data, [
                        'quantity' => $input['quantity'][$key],
                        'price' => $input['price'][$key],
                        'total_price' => $input['total'][$key],
                    ]);
                }
            }
        }
        return 1;
    }
}
