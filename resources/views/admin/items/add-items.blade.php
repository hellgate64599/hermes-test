@extends('layouts.app')

@section('title', 'Add Items')

@section('content')
    <div class="row pages">
        @if(Auth::user()->role == 'Admin')
        <div class="col-lg-4">
            <div class="card text-left sticky-top p-2">
                <div class="card-header p-3">
                    <h4 class="m-0 text-tertiary">Add Items</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="{{route('store.items')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label><small>Item Name</small></label>
                                <input type="text" name="item_name" class="form-control" placeholder="Item Name">
                            </div>
                            @if($getSubCategories->count() > 0)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Category</small></label>
                                    <select class="form-select" name="item_category" onchange="collectSubCategory(this)">
                                        <option value="" selected>Select Category</option>
                                        @foreach($getSubCategories as $category)
                                            <option data="{{$category->category_id}}" value="{{$category->category_name}}">{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Sub Category</small></label>
                                    <select class="form-select" name="item_sub_category" id="item_sub_category">
                                        <option value="" selected>Choose Category first</option>
                                    </select>
                                </div>
                            </div>
                            @else 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label><small>Category and Sub Category List is not created yet.</small></label>
                                        <br />
                                        <a class="btn btn-tertiary text-light" href="{{route('categories')}}">Create Categories</a>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item barcode (auto format)</small></label>
                                    <input type="text" name="item_barcode" class="form-control" placeholder="00-00-000000" minlength="10" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/^(?=[0-9]{10})([0-9]{2})([0-9]{2})([0-9]{6})$/, '$1-$2-$3');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Quantity</small></label>
                                    <input type="text" name="item_quantity" class="form-control" class="form-control" placeholder="Item Quantity" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Cost</small></label>
                                    <input type="text" name="item_cost" class="form-control" placeholder="Cost" oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>Item Sell</small></label>
                                    <input type="text" name="item_sell" class="form-control" placeholder="Sell" oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
                                </div>
                            </div>
                            <div class="col-lg">
                                <div class="form-group">
                                    <label for="item_img"><small>Item photo (Optional)</small></label>
                                    <input type="file" name="item_photo" class="form-control" id="item_img">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><small>Item Description</small></label>
                                <textarea name="item_description" rows="3" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label><small>Add Notes (Optional)</small></label>
                                <textarea name="item_notes" rows="3" class="form-control" value="No Notes" placeholder="Add notes"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn text-light">Add Items</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        <div class="@if(Auth::user()->role == 'Admin') col-lg-8 @else col-lg @endif">
            <div class="card shadow p-2">
                <div class="card-header">
                    <h4 class="text-tertiary">Items Available</h4>
                    <div class="progress my-2 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">100%</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-1">
                            @if(Auth::user()->role == 'Admin')
                            <form id="importForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" class="d-none" id="import" onchange="document.getElementById('submitImport').click()">
                                <button type="submit" role="button" class="d-none" id="submitImport"></button>
                            </form>
                            <button type="button" role="button" class="btn btn-sm text-light" onclick="document.getElementById('import').click();"><i class="fas fa-file-import mr-2"></i><span>Import Items</span></button>
                            @endif
                            <a href="{{route('export.items')}}" class="btn btn-sm text-light d-flex align-items-center"><i class="fas fa-file-export mr-2"></i><span>Export Items</span></a>
                            <a href="{{asset('vendor/item-format.xlsx')}}" class="btn btn-info btn-sm text-light d-flex align-items-center"><i class="fas fa-file-excel mr-2" download></i><span>Download Item Format</span></a>
                        </div>
                        <div>
                            <input type="text" placeholder="Search Item" oninput="getItems(1, this.value)">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Bar Code</th>
                                    <th>Cost</th>
                                    <th>Sell</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showItems">
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-light" id="totalItems">0</small>
                        <ul class="pagination" id="pagination_link">

                        </ul>
                    </div>
                </div>
            </div>
        </div>  
    </div>

    {{-- view item modal --}}
    <div class="modal fade" id="viewItemsModal" tabindex="-1" aria-labelledby="viewItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body border-0 text-center" id="viewItemsContent">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const viewItemsContent = document.querySelector('#viewItemsContent')
        const paginationLink = document.querySelector('#pagination_link')
        const totalItems = document.querySelector('#totalItems')
        @if(Auth::user()->role == 'Admin')
        const importForm = document.querySelector('#importForm')
        @endif
        var pb = document.querySelector('.progress');
        const progress = document.querySelector('.progress-bar')
        
        // modal init
        var viewItemModal = new bootstrap.Modal(document.getElementById('viewItemsModal'))
        getItems(1, undefined); 
        @if(Auth::user()->role == 'Admin')
        const url = '{{route("import.items")}}';
        importForm.addEventListener('submit', async function(e){
            e.preventDefault();
            let formData = new FormData();
            formData.append('file', document.querySelector('#import').files[0]);
            pb.classList.remove('d-none')

            await axios.post(url, formData, {
                onUploadProgress: function(progressEvent) {
                    const {loaded, total} = progressEvent
                    let percent = Math.floor((loaded * 100) / total)
                    progress.style.width = percent + '%';
                    progress.innerHTML = percent + '%';
                    if(percent === 100){
                        progress.innerHTML = 'Please wait';
                    }
                }
            })
                .then(function(response){
                    pb.classList.add('d-none')
                    getItems(1, undefined)
                    Swal.fire({
                        text: response.data.success,
                        icon: 'success',
                        color: '#ffffff',
                        background: '#24283b',
                    })
                })
                .catch(function(error){
                    pb.classList.add('d-none')
                    Swal.fire({
                        text: error.response.data.error,
                        icon: 'error',
                        color: '#ffffff',
                        background: '#24283b',
                    })
                });
        });
        @endif
        

        async function viewItem(item){
            const dataID = item.getAttribute('data');

            await axios.get(`/view-items/${dataID}`)
                .then(function (response) {
                    if(response.status == 200){
                        viewItemsContent.innerHTML = response.data.data
                        viewItemModal.show()
                    }
                })
                .catch(function (error) {
                    document.querySelector('#showItems').innerHTML = `<tr><td>${error.response.data.errors}</td></tr>`;
                })
        }

        async function getItems(page, filter){
            document.querySelector('#showItems').innerHTML = `<div class="d-flex align-items-center justify-content-center py-4"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div></div>`;

            let url = filter == '' ? `/get-items/${page}/undefined` : `/get-items/${page}/${filter}`;
            
            await axios.get(url)
                .then(function (response) {
                    if(response.status == 200){
                        document.querySelector('#showItems').innerHTML = `<tr><td>${response.data.data}</td></tr>`;
                        if(response.data.pagination){
                            let pages = response.data.pagination.links;
                            paginationLink.innerHTML ='';
                            pages.forEach(function(item){
                                if(!isNaN(item.label)){
                                    totalItems.innerHTML = `10 of ${response.data.pagination.total}`;
                                    paginationLink.innerHTML += `<li style="cursor:pointer;" onclick="getItems(${item.label}, '${filter}')" class="page-item ${item.active ? 'active' : '' } "><a class="page-link" style="background-color: #1a1e29;">${item.label}</a></li>`;
                                }
                            })
                        }
                    }
                })
                .catch(function (error) {
                    document.querySelector('#showItems').innerHTML = `<tr><td>${error.response.data.errors}</td></tr>`;
                    paginationLink.innerHTML ='';
                })
        }

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
        function deleteItem(item){
            const dataID = item.getAttribute('data');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                color: '#ffffff',
                background: '#24283b',
                showCancelButton: true,
                confirmButtonColor: '#d95650',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace(`/delete-items/${dataID}`);
                }
            })
        }
    </script>
@endsection