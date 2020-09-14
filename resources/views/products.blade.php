<?php

//index.php

include('database_connection.php');

?>

    <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Product filter in php</title>

    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href = "css/jquery-ui.css" rel = "stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <br />
        <h2 align="center">Advance Ajax Product Filters in PHP</h2>
        <br />
        <div class="col-md-3">
            <div class="list-group">
                <h3>Price</h3>
                <input type="hidden" id="hidden_minimum_price" value="0" />
                <input type="hidden" id="hidden_maximum_price" value="65000" />
                <p id="price_show">1000 - 65000</p>
                <div id="price_range"></div>
            </div>
            <div class="list-group">
                <h3>Brand</h3>
                <div style="height: 180px; overflow-y: auto; overflow-x: hidden;">
                   @foreach($brands as $brand)
                    <div class="list-group-item checkbox">
                        <label><input type="checkbox" class="common_selector brand" value="{{ $brand['product_brand'] }}"  > {{ $brand['product_brand'] }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="list-group">
                <h3>RAM</h3>
                @foreach($rams as $ram)
                <div class="list-group-item checkbox">
                    <label><input type="checkbox" class="common_selector ram" value="{{ $ram['product_ram'] }}" > {{ $ram['product_ram'] }} GB</label>
                </div>
                @endforeach
            </div>

            <div class="list-group">
                <h3>Internal Storage</h3>
                @foreach($storages as $strorage)
                <div class="list-group-item checkbox">
                    <label><input type="checkbox" class="common_selector storage" value="{{ $strorage['product_storage'] }}"  > {{ $strorage['product_storage'] }} GB</label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-9">
            <br />
            <div class="row filter_data">

            </div>
        </div>
    </div>

</div>
{{--<style>--}}
{{--    #loading--}}
{{--    {--}}
{{--        text-align:center;--}}
{{--        background: url('loader.gif') no-repeat center;--}}
{{--        height: 150px;--}}
{{--    }--}}
{{--</style>--}}

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        let route = "http://127.0.0.1:8000";
        filter_data();

        function filter_data()
        {
            $('.filter_data').html('<div id="loading" style="" ></div>');
            var action = 'fetch_data';
            var minimum_price = $('#hidden_minimum_price').val();
            var maximum_price = $('#hidden_maximum_price').val();
            var brand = get_filter('brand');
            var ram = get_filter('ram');
            var storage = get_filter('storage');
            var product = "";
            $.ajax({
                url:  route+"/productsdata",
                method:"POST",
                data:{action:action, minimum_price:minimum_price, maximum_price:maximum_price, brand:brand, ram:ram, storage:storage},
                success:function(response){
                    // console.log(response.products);
                    response.products.forEach( productData =>{
                        console.log(productData);
                        product += `<div class="col-sm-4 col-lg-3 col-md-3">
                                        <div style="border:1px solid #ccc; border-radius:5px; padding:16px; margin-bottom:16px; height:450px;">
                                        <img src='image/${productData.product_image}' alt="" class="img-responsive" >
                                        <p align="center"><strong><a href="#">${productData.product_name}</a></strong></p>
                                        <h4 style="text-align:center;" class="text-danger" > ${productData.product_brand} </h4>
                                        <p>Camera : ${productData.product_camera} MP<br />
                                        Brand : ${productData.product_brand} <br />
                                        RAM : ${productData.product_ram} GB<br />
                                        Storage : ${productData.product_storage} GB </p>
                                        </div>
                                   </div>`;

                        // console.log(product);
                        $('.filter_data').html(product);
                    });
                }
            });
        }

        function get_filter(class_name)
        {
            var filter = [];
            $('.'+class_name+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }

        $('.common_selector').click(function(){
            filter_data();
        });

        $('#price_range').slider({
            range:true,
            min:1000,
            max:65000,
            values:[1000, 65000],
            step:500,
            stop:function(event, ui)
            {
                $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
                $('#hidden_minimum_price').val(ui.values[0]);
                $('#hidden_maximum_price').val(ui.values[1]);
                filter_data();
            }
        });

    });
</script>

</body>

</html>
