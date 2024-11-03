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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        bkoigl.accessToken = "{{ env('BARIKOI_API_KEY') }}"; // Pass the environment variable to JavaScript
        const map = new bkoigl.Map({
            container: "map",
            center: [90.3938010872331, 23.821600277500405],
            zoom: 10,
        });
        map.addControl(new bkoigl.FullscreenControl());
        map.addControl(new bkoigl.NavigationControl());
        map.addControl(new bkoigl.ScaleControl());

        const dealers = @json($dealers); // Pass the dealer data to JavaScript
        const retailers = @json($retailers); // Pass the retailer data to JavaScript
        console.log("Dealers data:", dealers);
        console.log("Retailers data:", retailers);

        // Create arrays to keep track of markers
        let dealerMarkers = [];
        let retailerMarkers = [];

        // Custom icon URLs
        const retailerIconUrl = '{{ asset('images/red.png') }}'; // Custom icon for retailers
        const dealerIconUrl = '{{ asset('images/blue.png') }}'; // Custom icon for dealers
        const centralIconUrl = '{{ asset('images/branch-icon.png') }}'; // Custom icon for central point

        // Central point coordinates (you can adjust this)
        const centralCoordinates = [90.3938010872331, 23.821600277500405];

        // Function to add the central point marker
        function addCentralPoint() {
            const marker = new bkoigl.Marker({ element: createCustomMarkerElement(centralIconUrl) })
                .setLngLat(centralCoordinates)
                .addTo(map);
        }

        // Function to calculate distance between two points (in km)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius of the Earth in km
            const dLat = (lat2 - lat1) * (Math.PI / 180);
            const dLon = (lon2 - lon1) * (Math.PI / 180);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c; // Distance in km
        }

        // Function to add markers for dealers on the map with custom icon
        function addDealerMarkers(dealers) {
            dealers.forEach(dealer => {
                const longitude = parseFloat(dealer.longitude);
                const latitude = parseFloat(dealer.latitude);

                if (!isNaN(longitude) && !isNaN(latitude)) {
                    const distance = calculateDistance(centralCoordinates[1], centralCoordinates[0], latitude, longitude).toFixed(2);
                    let popupContent = `
                    <div class="maplibregl-popup-content">
                        <div>
                            <span>
                                <div><span class="popup-label">Branch Name: </span>${dealer.name || "N/A"}</div>
                                <div><span class="popup-label">Address: </span>${dealer.location || "N/A"}</div>
                                <div><span class="popup-label">Distance from Central Point: </span>${distance} km</div>
                                ${dealer.average_sales ? `<div><span class="popup-label">Average Sales: </span>${dealer.average_sales}</div>` : ''}
                                ${dealer.market_size ? `<div><span class="popup-label">Market Size: </span>${dealer.market_size}</div>` : ''}
                                ${dealer.market_share ? `<div><span class="popup-label">Market Share: </span>${dealer.market_share}</div>` : ''}
                                ${dealer.competition_brand ? `<div><span class="popup-label">Competition Brand: </span>${dealer.competition_brand}</div>` : ''}
                            </span>
                        </div>
                    </div>
                `;

                    const marker = new bkoigl.Marker({ element: createCustomMarkerElement(dealerIconUrl) }) // Use custom icon for dealers
                        .setLngLat([longitude, latitude])
                        .setPopup(new bkoigl.Popup().setHTML(popupContent))
                        .addTo(map);

                    dealerMarkers.push(marker); // Store the marker in the array
                }
            });
        }

        // Function to add markers for retailers on the map with custom icon
        function addRetailerMarkers(retailers) {
            retailers.forEach(retailer => {
                const longitude = parseFloat(retailer.longitude);
                const latitude = parseFloat(retailer.latitude);

                if (!isNaN(longitude) && !isNaN(latitude)) {
                    const distance = calculateDistance(centralCoordinates[1], centralCoordinates[0], latitude, longitude).toFixed(2);
                    let popupContent = `
                    <div class="maplibregl-popup-content" style="background-color:orangered; border-radius: 5px; padding: 10px;">
                        <div>
                            <span>
                                <div><strong>Retailer Name:</strong> ${retailer.name || "N/A"}</div>
                                <div><strong>Address:</strong> ${retailer.location || "N/A"}</div>
                                <div><strong>Distance from Central Point:</strong> ${distance} km</div>
                                ${retailer.average_sales ? `<div><span class="popup-label">Average Sales: </span>${retailer.average_sales}</div>` : ''}
                                ${retailer.market_size ? `<div><span class="popup-label">Market Size: </span>${retailer.market_size}</div>` : ''}
                                ${retailer.market_share ? `<div><span class="popup-label">Market Share: </span>${retailer.market_share}</div>` : ''}
                                ${retailer.competition_brand ? `<div><span class="popup-label">Competition Brand: </span>${retailer.competition_brand}</div>` : ''}
                            </span>
                        </div>
                    </div>
                `;

                    const marker = new bkoigl.Marker({ element: createCustomMarkerElement(retailerIconUrl) }) // Use custom icon for retailers
                        .setLngLat([longitude, latitude])
                        .setPopup(new bkoigl.Popup().setHTML(popupContent))
                        .addTo(map);

                    retailerMarkers.push(marker); // Store the marker in the array
                }
            });
        }

        // Function to create a custom marker element
        function createCustomMarkerElement(iconUrl) {
            const markerElement = document.createElement('div');
            markerElement.className = 'marker'; // You can add a class for styling if needed
            markerElement.style.backgroundImage = `url(${iconUrl})`;
            markerElement.style.backgroundSize = 'contain';
            markerElement.style.width = '30px'; // Adjust size as needed
            markerElement.style.height = '30px'; // Adjust size as needed
            return markerElement;
        }

        // Function to update markers based on the dropdown selection
        function updateMarkers() {
            // Remove existing markers from the map
            dealerMarkers.forEach(marker => marker.remove());
            retailerMarkers.forEach(marker => marker.remove());

            // Clear the marker arrays
            dealerMarkers = [];
            retailerMarkers = [];

            const selectedValue = document.getElementById('select-view').value;

            if (selectedValue === 'dealers') {
                addDealerMarkers(dealers);
            } else if (selectedValue === 'retailers') {
                addRetailerMarkers(retailers);
            } else if (selectedValue === 'all') {
                addDealerMarkers(dealers);
                addRetailerMarkers(retailers);
            }
        }

        // Add Central Point and Marker on Map Load
        map.on("load", () => {
            addCentralPoint(); // Add the central marker
            updateMarkers(); // Call the updateMarkers function to place markers based on default selection
        });

        // Event listener for dropdown change
        document.getElementById('select-view').addEventListener('change', updateMarkers);
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
