@extends('backend.layouts.master')

@section('title')
    Map Analytics - Dealer
@endsection
@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Map Analytics</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li><span>Map Analytics</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- Left column for Map -->
            <div class="col-lg-9">
                <div class="card mt-5 mb-3">
                    <div class="p-4">
                        <h4>Map Analytics</h4>
                        <div id="map" style="width: 100%; height: 80vh; background-color: white;"></div>
                    </div>
                </div>
            </div>

            <!-- Right column for dropdowns and additional content -->
            <div class="col-lg-3" style="height: 70vh;">
                <div class="card mt-5 mb-3" style="background-color: white;">
                    <div class="p-4">
                        <!--range-->
                        <div class="form-group mb-4">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="enable-district-range">
                                <label for="enable-district-range" style="margin-bottom: 0;">District Market Share
                                    (%)</label>
                            </div>
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <input type="number" id="district-share-min" class="form-control" placeholder="Min"
                                       min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;">
                                <input type="number" id="district-share-max" class="form-control" placeholder="Max"
                                       min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;">
                            </div>
                        </div>

                        <!---coverage-->
                        <div class="form-group mb-4">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="enable-coverage-range" >
                                <label for="enable-coverage-range" style="margin-bottom: 0;">Coverage Area (%)</label>
                            </div>
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <input type="number" id="coverage-share-min" class="form-control" placeholder="Min" min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;">
                                <input type="number" id="coverage-share-max" class="form-control" placeholder="Max" min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;">
                            </div>
                        </div>


                        <!-- District Select -->
                        <div class="form-group mb-4">
                            <label for="select-district" class="form-label"
                                   style="font-weight: 500; font-size: 14px; color: #464A4D;">Select District</label>
                            <select id="select-district" name="select-district" class="form-control"
                                    style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">

                            </select>
                        </div>

                        <!-- Data Type Select -->
                        <div class="form-group mb-4">
                            <label for="select-data-type" class="form-label"
                                   style="font-weight: 500; font-size: 14px; color: #464A4D;">Select Data Type</label>
                            <select id="select-data-type" class="form-control"
                                    style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                <option value="" disabled selected>Select Data Type</option>
                            </select>
                        </div>
                        <!--Dealer Market Share Range--range-->
                        <div class="form-group mb-4">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="enable-dealer-range">
                                <label for="enable-dealer-range" style="margin-bottom: 0;">Dealer Market Share
                                    (%)</label>
                            </div>
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <input type="number" id="dealer-share-min" class="form-control" placeholder="Min"
                                       min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa; "
                                       oninput="handleDealerShareInput()">
                                <input type="number" id="dealer-share-max" class="form-control" placeholder="Max"
                                       min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;" oninput="handleDealerShareInput()">
                            </div>
                        </div>
                        <!--Retailer Market Share Range--range-->
                        <div class="form-group mb-4">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="enable-retailer-range">
                                <label for="enable-retailer-range" style="margin-bottom: 0;">Retailer Market Share
                                    (%)</label>
                            </div>
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <input type="number" id="retailer-share-min" class="form-control" placeholder="Min"
                                       min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;" oninput="handleRetailerShareInput()">
                                <input type="number" id="retailer-share-max" class="form-control" placeholder="Max"
                                       min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa; " oninput="handleRetailerShareInput()">
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="map-legend"
                             style="position: relative; bottom: 0; padding: 5px; background-color: white; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); font-size: 10px; width: 100%; overflow-y: auto;">
                            <div class="legend-header"
                                 style="font-weight: bold; font-size: 13px; text-align: center; margin-bottom: 5px;">
                                Legend
                            </div>

                            <!-- Legend items -->
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon"
                                     style="background-image: url('{{ asset('images/pharmacy.png') }}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">District</span>
                                <button onclick="clearCentralPointMarkers()">claer</button>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon"
                                     style="background-image: url('{{asset('images/dealer-2.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Dealer</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon"
                                     style="background-image: url('{{asset('images/retailer.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Retailer</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon"
                                     style="background-image: url('{{asset('images/billboard-black-1.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Billboard</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon"
                                     style="background-image: url('{{ asset('images/shop-black.png') }}'); width: 18px; height: 18px; background-size: contain; margin-right: 5px;background-repeat: no-repeat;"></div>
                                <span class="legend-label">Shop Sign</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon"
                                     style="background-image: url('{{asset('images/highwall-black.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Highwalls</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-color-box"
                                     style="background-color: red; width: 12px; height: 12px; margin-right: 5px;"></div>
                                <span class="legend-label">Fresh Super Cement</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-color-box"
                                     style="background-color: blue; width: 12px; height: 12px; margin-right: 5px;"></div>
                                <span class="legend-label">Dhalai Special Cement</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-color-box"
                                     style="background-color: green; width: 12px; height: 12px; margin-right: 5px;"></div>
                                <span class="legend-label">Meghnacem Delux Cement</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>


        bkoigl.accessToken = "{{ env('BARIKOI_API_KEYs') }}"; // Pass the environment variable to JavaScript
        const map = new bkoigl.Map({
            container: "map",
            center: [90.3938010872331, 23.821600277500405],
            zoom: 6.5,
        });
        map.addControl(new bkoigl.FullscreenControl());
        map.addControl(new bkoigl.NavigationControl());
        map.addControl(new bkoigl.ScaleControl());


        const districts = @json($districts);
        const dealers = @json($dealers);
        const retailers = @json($retailers);
        const billboards = @json($billboards);
        const shopsigns =@json($shopsigns);
        const highwalls =@json($highwalls);
        const dealersByDistrict = @json($dealersByDistrict);
        const retailersByDistrict = @json($retailersByDistrict);
        const billboardsByDistrict = @json($billboardsByDistrict);
        const shopsignsByDistrict =@json($shopsignsByDistrict);
        const highwallsByDistrict =@json($highwallsByDistrict);


        const centralIconUrl = '{{ asset('images/pharmacy.png') }}'; // Branch icon for central points
        const dealerIconUrl = '{{ asset('images/dealer-2.png') }}'; // Red icon for dealers
        const retailerIconUrl = '{{ asset('images/retailer.png') }}'; // Blue icon for retailers
        const billboardIconUrl = '{{ asset('images/billboard.png') }}'; // Blue icon for retailers
        const shopsignIconUrl = '{{ asset('images/shop-blue.png') }}'; // Blue icon for retailers
        const highwallIconUrl = '{{ asset('images/3415475.png') }}'; // Blue icon for retailers


        let centralPointMarkerLayer = []; // Layer for central points
        let dealerMarkersLayer = []; // Layer for dealer markers
        let retailerMarkersLayer = []; // Layer for retailer markers
        let billboardMarkersLayer = []; // Layer for billboard markers
        let shopsignMarkerLayer = [];
        let highwallMarkerLayer = [];
        // Add icon URLs for different billboard brandshttp://127.0.0.1:8000/images/pharmacy.png
        const billboardIcons = {
            'fresh_super_cement': '{{ asset('images/billboard-red-1.png') }}',
            'dhalai_special_cement': '{{ asset('images/billboard-blue-1.png') }}',
            'meghnacem_delux_cement': '{{ asset('images/billboard-green-1.png') }}'
        };
        const shopsignIcons = {
            'fresh_super_cement': '{{asset('images/shop-red.png')}}',
            'dhalai_special_cement': '{{asset('images/shop-blue.png')}}',
            'meghnacem_delux_cement': '{{ asset('images/shop-green.png') }}'
        }
        const highwallIcons = {
            'fresh_super_cement': '{{asset('images/highwall-red.png')}}',
            'dhalai_special_cement': '{{asset('images/highwall-blue.png')}}',
            'meghnacem_delux_cement': '{{ asset('images/highwall-green.png') }}'
        }

    </script>

        <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{asset('js/map.js')}}"></script>
    <style>
        @keyframes blink {
            0% { border-color: red; }
            50% { border-color: transparent; }
            100% { border-color: red; }
        }

        .blink {
            animation: blink 1s infinite;
            border-width: 2px;
            border-style: solid;
        }
    </style>

@endsection
