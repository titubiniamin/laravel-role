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

                // Log parsed values and their types
                console.log("Parsed Longitude:", longitude, "Type:", typeof longitude);
                console.log("Parsed Latitude:", latitude, "Type:", typeof latitude);

                // Ensure coordinates are valid
                if (!isNaN(longitude) && !isNaN(latitude)) {
                    console.log('this is ' + longitude);
                    console.log('this is ' + latitude);

                    // Create a popup with styled HTML content
                    const popupContent = `
                <div style="font-family: Arial, sans-serif; line-height: 1.5;">
                    <h4 style="margin: 0; font-size: 16px;">${dealer.name}</h4>
                    <p style="margin: 0; color: gray;">${dealer.location}</p>
                    <p style="margin: 0;">Mobile: <strong>${dealer.mobile}</strong></p>
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




@endsection
