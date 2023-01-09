<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ItemsExport;
use App\Http\Controllers\Controller;
use App\Imports\ItemCategory;
use App\Imports\ItemImport;
use App\Models\Category;
use App\Models\Items;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Validator;
use File;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $collection = collect(SubCategory::get());
        $unique = $collection->unique(['category_name']);
        $unique->values()->all();
        $getSubCategories = $unique;
        $items = Items::orderBy('created_at', 'desc')->paginate(3);
        return view('admin.items.add-items', compact(['getSubCategories', 'items']));
    }

    public function importItems(Request $request)
    {
        $validator = Validator::make(
        [
            'file'      => $request->file,
            'extension' => strtolower($request->file->getClientOriginalExtension()),
        ],
        [
            'file'          => 'required',
            'extension'      => 'required|in:xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'File type must be xlsx file.',
            ], 412);
        } else {
            $file = $request->file('file');

            Excel::import(new ItemImport, $file);
            Excel::import(new ItemCategory, $file);

            return response()->json([
                'success' => 'File Imported successfull',
            ], 200);
        }
    }

    public function exportItems()
    {
        $items = Items::all();
        if($items->count() > 0){
            return Excel::download(new ItemsExport, 'items.xlsx');
        }
        return back()->with('error', 'No Items found');
    }

    public function getItems($page, $filter){
        
        if($filter == 'undefined' || $filter == ''){
            $items = Items::orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        }else{
            $items = Items::where('item_name', 'like', '%'.ucfirst($filter).'%')
                ->orWhere('item_name', 'like', '%'.$filter.'%')
                ->orWhere('item_name', 'like', '%'.strtolower($filter).'%')
                ->orWhere('item_category', 'like', '%'.ucfirst($filter).'%')
                ->orWhere('item_category', 'like', '%'.$filter.'%')
                ->orWhere('item_sub_category', 'like', '%'.ucfirst($filter).'%')
                ->orWhere('item_sub_category', 'like', '%'.$filter.'%')
                ->orWhere('item_barcode', 'like', '%'.$filter.'%')
                ->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', $page);
        }
        
        
        if($items->count() > 0){
            $html = '';
            foreach($items as $item){
                $quantity = ($item->item_quantity == 0) ? '<small class="text-danger">Out of Stock</small>' : $item->item_quantity;
                $html .= '<tr>';
                $html .='<td>'.mb_strimwidth($item->item_name, 0, 15, "...").'</td>';
                $html .='<td>'.$item->item_category.'</td>';
                $html .='<td>'.$item->item_sub_category.'</td>';
                $html .='<td>'.$item->item_barcode.'</td>';
                $html .='<td>'.number_format((float)$item->item_cost, 2).'</td>';
                $html .='<td>'.number_format((float)$item->item_sell, 2).'</td>';
                $html .='<td>'.$quantity.'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<a data="'.$item->id.'" onclick="viewItem(this)" class="btn bg-info btn-sm text-light"><i class="fas fa-eye fa-sm"></i></a>';
                if(Auth::user()->role == 'Admin'){
                    $html .='<a href="/edit-items/'.$item->id.'" class="btn bg-secondary btn-sm text-light"><i class="fas fa-pencil-alt fa-sm"></i></a>';
                    $html .='<a data="'.$item->id.'" onclick="deleteItem(this)" class="btn bg-tertiary btn-sm text-light"><i class="fas fa-trash fa-sm"></i></a>';
                }
                $html .='</div>';
                $html .='</td></tr>';
            }
            return response()->json([
                'data' => $html,
                'pagination' => $items
            ], 200);
        }
        return response()->json([
            'errors' => '<tr><td>No Item available<td><tr>',
        ], 410);
    }

    public function getPaginate(Request $request)
    {
        $items = Items::orderBy('updated_at', 'desc')->paginate(10, ['*'], 'page', 2);
    }

    public function viewItems($id)
    {
        $item = Items::find($id);
        if($item){
            $img = empty($item->item_photo) ? asset('img/default_item_photo.jpg') : asset('storage/img/item_photo/'.$item->item_photo.'');
            $description = empty($item->item_description) ? 'No item Description' : $item->item_description;
            $item_name = empty($item->item_name) ? 'No item Name' : $item->item_name;
            $html = '<div class="row">';
            $html .= '<div class="col-lg-6">';
            $html .= '<a href="">';
            $html .= '<img class="img-fluid item-photo" src="'.$img.'" alt="'.$item_name.'">';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '<div class="col-lg-6 m-auto">';
            $html .= '<div>';
            $html .= '<h1>$'.$item->item_sell.'</h1>';
            $html .= '<br>';
            $html .= '<p>'.$item_name.'</p>';
            $html .= '<hr class="bg-tertiary">';
            $html .= '<p>'.$description.'</p>';
            $html .= '<small class="text-secondary">Item Quantity</small>';
            $html .= '<p>'.$item->item_quantity.'</p>';
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-4">';
            $html .= '<small class="text-secondary">Item barcode</small>';
            $html .= '<p>'.$item->item_barcode.'</p>';
            $html .= '</div>';
            $html .= '<div class="col-lg-4">';
            $html .= '<small class="text-secondary">Item Cost</small>';
            $html .= '<p>$'.$item->item_cost.'</p>';
            $html .= '</div>';
            $html .= '<div class="col-lg-4">';
            $html .= '<small class="text-secondary">Item Sell</small>';
            $html .= '<p>$'.$item->item_sell.'</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'data' => 'Opppppps',
        ], 410);
    }

    public function collectSubCategory($id){
        $subCategories = SubCategory::where('category_id', $id)->get();
        if($subCategories->count() > 0){
            $html = '';
            foreach($subCategories as $subCategory){
                $html .= '<option>'.$subCategory->sub_category_name.'</option>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<option>No sub category found</option>',
        ], 404);
    }

    public function store(Request $request){
        $request->validate([
            'item_category' => 'required',
            'item_sub_category' => 'required',
            'item_quantity' => 'required|numeric|min:1',
            // 'item_barcode' => 'required|regex:/([0-9]{2})-([0-9]{2})-([0-9]{6})/',
            'item_barcode' => 'required',
            'item_cost' => 'required|numeric',
            'item_sell' => 'required|numeric',
        ],
        [
            // 'item_barcode.regex' => 'Barcode format must be 00-00-000000',
            'item_barcode.regex' => 'Barcode is required',
        ]);
        

        $quantity = $request->item_quantity <= 0 ? 0 : $request->item_quantity;
        $item = Items::where('item_category', $request->item_category)
                ->where('item_sub_category', $request->item_sub_category)
                ->where('item_barcode', $request->item_barcode)->first();

        if(!empty($item)){
            $item->where('id', $item->id)->increment('item_quantity', $quantity);
            return back()->with('success', ucfirst($request->item_name). ' already exist. Item quantity updated.');
        }

        if(!empty($request->item_photo)){
            $item_photo = uniqid() . '.' . $request->item_photo->extension();
        }else{
            $item_photo = NULL;
        }
        
        $barcode = empty($request->item_barcode) ? str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT) : $request->item_barcode;
        $storeItems = Items::create([
            'item_name' => ucfirst($request->item_name),
            'item_category' => ucfirst($request->item_category),
            'item_sub_category' => ucfirst($request->item_sub_category),
            'item_quantity' => $quantity,
            'item_barcode' => $barcode,
            'item_description' => ucfirst($request->item_description),
            'item_cost' => (float)$request->item_cost,
            'item_sell' => (float)$request->item_sell,
            'item_notes' => $request->item_notes,
            'item_photo' => $item_photo,
            'total_cost' => ((float)$request->item_cost * $quantity),
        ]);
        if($storeItems){
            if(!empty($request->item_photo)){
                $request->item_photo->storeAs('public/img/item_photo', $item_photo);
                $like = scandir('img/item_photo');
                foreach ($like as $thisFile) {
                    $rs = Items::where('item_photo', $thisFile)->first();
                    if (!$rs) {
                        if($thisFile != "." and $thisFile != ".."){
                            Storage::delete(public_path().'/img/item_photo/' . $thisFile);
                        }
                    }
                }
            }
            return back()->with('success', ucfirst($request->item_name). ' Added Successfully.');
        }
        return back()->with('error', 'Somthing went wrong');
    }

    public function editItems(String $id){
        $collection = collect(SubCategory::get());
        $unique = $collection->unique(['category_name']);
        $unique->values()->all();
        $getSubCategories = $unique;
        $item = Items::where('id', $id)->firstOrFail();
        return view('admin.items.edit-item', compact(['getSubCategories', 'item']));
    }

    public function update($id, Request $request){
        if($item = Items::find($id)){
            $request->validate([
                'item_category' => 'required',
                'item_sub_category' => 'required',
                'item_quantity' => 'required|numeric|min:0',
                // 'item_barcode' => 'required|regex:/([0-9]{2})-([0-9]{2})-([0-9]{6})/',
                'item_barcode' => 'required',
                'item_cost' => 'required|numeric',
                'item_sell' => 'required|numeric',
            ],
            [
                'item_barcode.regex' => 'Barcode format must be 00-00-000000',
            ]);

            if(!empty($request->item_photo)){
                $item_photo = uniqid() . '.' . $request->item_photo->extension();
            }else{
                $item_photo = $item->item_photo;
            }
    
            $quantity = ($request->item_quantity <= 0) ? 0 : $request->item_quantity;
            $checkUp = Items::where('id', $id)->firstOrFail();
            if($checkUp){
                $updateItem = $checkUp->update([
                    'item_name' => ucfirst($request->item_name),
                    'item_category' => ucfirst($request->item_category),
                    'item_sub_category' => ucfirst($request->item_sub_category),
                    'item_quantity' => $quantity,
                    'item_barcode' => $request->item_barcode,
                    'item_description' => $request->item_description,
                    'item_cost' => str_replace(',', '', (float)$request->item_cost),
                    'item_sell' => str_replace(',', '', (float)$request->item_sell),
                    'item_notes' => $request->item_notes,
                    'item_photo' => $item_photo,
                    'total_cost' => ((float)$request->item_cost * $quantity),
                ]);
        
                if($updateItem){
                    if(!empty($request->item_photo)){
                        $request->item_photo->storeAs('public/img/item_photo', $item_photo);

                        $like = scandir('img/item_photo');
                        foreach ($like as $thisFile) {
                            $rs = Items::where('item_photo', $thisFile)->first();
                            if (!$rs) {
                                if($thisFile != "." and $thisFile != ".."){
                                    Storage::delete(public_path().'/img/item_photo/' . $thisFile);
                                }
                            }
                        }
                    }
                    return redirect(route('items'))->with('success', ucfirst($request->item_name). ' Updated Successfully.');
                }
            }
        }
        return back()->with('error', 'Item not found');
    }

    public function destroy(String $id){
        if($item = Items::where('id', $id)->first()){
            $delete = $item->delete();
            if($delete){
                return back()->with('success', 'Item Deleted.');
            }
        }
        return back()->with('error', 'Item not found.');
    }
}
