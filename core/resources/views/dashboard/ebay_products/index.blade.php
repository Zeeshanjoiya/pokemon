@extends('dashboard.layouts.master')

@push("after-styles")
<link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">

<style>
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: bold;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        color: #fff;
        background-color: #dc3545;
    }

    .badge-success {
        background-color: #28a745;
    }
    
    .dataTables_length {
     text-align: left !important; 
}
</style>
@endpush
@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header dker">
            <?php
            $cf_title_var = "title_" . @Helper::currentLanguage()->code;
            $cf_title_var2 = "title_" . env('DEFAULT_LANGUAGE');

            $title_var = "title_" . @Helper::currentLanguage()->code;
            $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
            ?>
            <h3>Ebay Products</h3>
            <small>
                <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                <a href="">Ebay Products</a>
            </small>
        </div>
        <div class="box-tool">
            <ul class="nav">
                <li class="nav-item inline">
                    <button type="button" class="btn btn-outline b-success text-success" id="filter_btn"><i class="fa fa-search"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="table-responsive" style="padding: 1rem;">
    <table class="table table-bordered" style="width: 100%" id="products-table">
        <thead class="dker">
            <th style="width: 11px !important;"></th>
            <th style="width:100px;">Item ID</th>
            <th style="width:100px;">Title</th>
            <th style="width:100px;">Current Price</th>
            <th style="width:100px;">Categories</th>
            <th style="width:100px;">Start Time</th>
            <th style="width:100px;">End Time</th>
            <th style="width:100px;">Product Image</th>
        </thead>

        <tbody>
            @foreach($products as $key => $product)
            <tr class='parent-<?= $product->id ?>'>

                <td><a href="javascript:;" class="fa fa-plus-circle fa-2x show-prices" data-product-id="<?= $product->id ?>"></a></td>
                <td>{{$product->marketplace_id}}</td>
                <td>{{$product->product_name}}</td>
                <td>{{$product->currency.' '.$product->current_price}}</td>
                <td><span class="badge badge-success">{{ json_decode($products[0]->categories)->categoryName[0] }}</span></td>
                <td>{{$product->product_listing_start_time}}</td>
                <td>{{$product->product_listing_end_time}}</td>
                <td><img src="{{$product->image_url}}" alt="Url not found" width="100px" height="100px"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push("after-scripts")
<script src="{{ asset('assets/dashboard/js/datatables/datatables.min.js') }}"></script>
<script>
    $("#products-table").DataTable();

    $(".show-prices").on("click", function() {


        var current_btn = $(this);
        var product_id = $(this).attr('data-product-id');

        if (current_btn.hasClass('fa-plus-circle')) {

            $.ajax({
                type: "GET",
                url: "show-ebay-product-prices/" + product_id,
                success: function(data) {
                    data = JSON.parse(data);
                    // Do something with the data
                    $('.parent-' + product_id).after(data.content);
                    current_btn.removeClass('fa-plus-circle');
                    current_btn.addClass('fa-minus-circle');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

        } else {
            $('.child-'+product_id).remove();
            current_btn.removeClass('fa-minus-circle');
            current_btn.addClass('fa-plus-circle');
        }

    })
</script>
@endpush