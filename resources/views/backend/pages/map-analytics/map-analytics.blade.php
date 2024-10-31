@extends('backend.layouts.master')

@section('title')
    Dealers Page - Dealer
@endsection

@section('admin-content')

    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Dealers</h4>
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

            <!-- Right column for additional content -->
            <div class="col-lg-3" style="height: 70vh;">
                <div class="card mt-5 mb-3" style="height: 400px;background-color: white">
                    <div class="p-4">
                        <div class="form-group mb-4">
                            <label for="select-view" class="form-label" style="font-weight: 500; font-size: 14px; color: #464A4D;">Select View</label>
                            <select id="select-view" class="form-control" style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                <option value="" disabled selected>Select</option>
                                <option value="dealers" selected style="background-color: white">Dealers</option>
                                <option value="retailers">Retailers</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                        <!-- Add more content here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        bkoigl.accessToken = "bkoi_0f0c0e2aaed92fda43a85d29493d69776ef1c810e8f3d425f0b90fed001bef50"; // required
        const map = new bkoigl.Map({
            container: "map",
            center: [90.3938010872331, 23.821600277500405],
            zoom: 10,
        });
        map.addControl(new bkoigl.FullscreenControl());
        map.addControl(new bkoigl.NavigationControl());
        map.addControl(new bkoigl.ScaleControl());

        const dealers = @json($dealers); // Pass the dealer data to JavaScript
        console.log("Dealers data:", dealers); // Check dealer data in console
        console.log("Dealers data:", dealers);

        // Function to add markers for dealers on the map
        // Function to add markers for dealers on the map
        function addMarkers(dealers) {
            if (!Array.isArray(dealers)) {
                console.error("Expected dealers to be an array");
                return;
            }

            dealers.forEach(dealer => {
                const longitude = parseFloat(dealer.longitude);
                const latitude = parseFloat(dealer.latitude);


                // Ensure coordinates are valid
                if (!isNaN(longitude) && !isNaN(latitude)) {
                    // Create a popup with styled HTML content
                    let popupContent = `
                <div class="maplibregl-popup-content mapboxgl-popup-content">
                    <div>
                        <span>
                            <div><span class="popup-label">Branch Name: </span>${dealer.name || "N/A"}</div>
                            <div><span class="popup-label">Address: </span>${dealer.location || "N/A"}</div>`;

                    if (dealer.average_sales) {
                        popupContent += `<div><span class="popup-label">Average Sales: </span>${dealer.average_sales}</div>`;
                    }

                    if (dealer.market_size) {
                        popupContent += `<div><span class="popup-label">Market Size: </span>${dealer.market_size}</div>`;
                    }

                    if (dealer.market_share) {
                        popupContent += `<div><span class="popup-label">Market Share: </span>${dealer.market_share}</div>`;
                    }

                    if (dealer.competition_brand) {
                        popupContent += `<div><span class="popup-label">Competition Brand: </span>${dealer.competition_brand}</div>`;
                    }

                    // Close the content div
                    popupContent += `
                        </span>
                    </div>
                </div>
            `;

                    const marker = new bkoigl.Marker()
                        .setLngLat([longitude, latitude])
                        .setPopup(new bkoigl.Popup().setHTML(popupContent)) // Set the popup with styled HTML
                        .addTo(map);
                } else {
                    console.error("Invalid coordinates for dealer:", dealer);
                }
            });
        }



        // Add Marker on Map Load
        map.on("load", () => {
            addMarkers(dealers); // Call the addMarkers function to place dealer markers on the map
        });
    </script>

<style>
    .popup-label {
        font-weight: 600;
        color: rgb(70, 74, 77);
    }
    .maplibregl-popup-content {
        background-color: #3498db; /* Change to your preferred color */
        color: #fff; /* Text color */
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    /* Popup tip styling (the small arrow pointing to the marker) */
    .maplibregl-popup-tip {
        background-color: #3498db; /* Same color as the popup content */
    }

    /* Label styling inside popup */
    .popup-label {
        font-weight: 600;
        color: #fff; /* Adjust text color if needed */
    }
</style>


@endsection
