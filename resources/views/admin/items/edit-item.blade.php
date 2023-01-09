@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
    <div class="row pages">
        <div class="col-lg">
            <div class="card shadow p-4">
                <div class="card-header">
                    <h4 class="text-tertiary">Edit Item</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('update.item', $item->id)}}" method="POST" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf 
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Name</small></label>
                                    <input type="text" name="item_name" class="form-control" value="{{$item->item_name}}" placeholder="Item Name">
                                </div>
                            </div>
                            @if($getSubCategories->count() > 0)
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Category</small></label>
                                    <select class="form-select" name="item_category" onchange="collectSubCategory(this)">
                                        <option value="{{$item->item_category}}" selected>Select Category</option>
                                        @foreach($getSubCategories as $category)
                                            <option data="{{$category->category_id}}" value="{{$category->category_name}}">{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Sub Category</small></label>
                                    <select class="form-select" name="item_sub_category" id="item_sub_category">
                                        <option value="{{$item->item_sub_category}}" selected>Choose Category first</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Quantity</small></label>
                                    <input type="text" name="item_quantity" class="form-control" class="form-control" value="{{$item->item_quantity}}" placeholder="Item Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item barcode</small></label>
                                    {{-- <input type="text" name="item_barcode" class="form-control" value="{{$item->item_barcode}}" placeholder="Item Bar code" maxlength="6" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"> --}}
                                    <input type="text" name="item_barcode" class="form-control" value="{{$item->item_barcode}}" placeholder="Item Bar code">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><small>Item Cost</small></label>
                                    <input type="text" name="item_cost" class="form-control" value="{{$item->item_cost}}" placeholder="Cost" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Sell</small></label>
                                    <input type="text" name="item_sell" class="form-control" value="{{$item->item_sell}}" placeholder="Sell" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="item_img"><small>Item photo (Optional)</small></label>
                                    <input type="file" name="item_photo" class="form-control" id="item_img">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><small>Item Description</small></label>
                                <textarea name="item_description" value="{{$item->item_description}}" rows="3" class="form-control" placeholder="Description">{{$item->item_description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label><small>Add Notes (Optional)</small></label>
                                <textarea name="item_notes" value="{{$item->item_notes}}" rows="3" class="form-control" value="No Notes" placeholder="Add notes"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn text-light">Update Items</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
@endsection

@section('script')
    <script>
        async function collectSubCategory(data){
            var selectedOption = data.options[data.selectedIndex];
            var dataID = selectedOption.getAttribute('data');
            await axios.get('/collect-sub-categories/' + dataID)
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#item_sub_category').innerHTML = response.data.data;
                    }
                })
                .catch(function (error) {
                    document.querySelector('#item_sub_category').innerHTML = error.response.data.errors;
                })
        }
    </script>
@endsection