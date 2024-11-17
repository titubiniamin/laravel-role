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
                        <div class="form-group mb-4">
                            <label for="select-central-point" class="form-label" style="font-weight: 500; font-size: 14px; color: #464A4D;">Select Central Point</label>
                            <select id="select-central-point" class="form-control" style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                <option value="" disabled selected>Select</option> <!-- Added this line for "All Central Points" option -->
                                <option value="all">All Central Points</option> <!-- Added this line for "All Central Points" option -->
                                @foreach ($centralPoints as $centralPoint)
                                    <option value="{{ $centralPoint['id'] }}" data-lat="{{ $centralPoint['latitude'] }}" data-lng="{{ $centralPoint['longitude'] }}" data-district="{{ $centralPoint['district'] }}">
                                        {{ $centralPoint['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="select-data-type" class="form-label" style="font-weight: 500; font-size: 14px; color: #464A4D;">Select Data Type</label>
                            <select id="select-data-type" class="form-control" style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                <option value="" disabled selected>Select Data Type</option>
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
            zoom: 6.5,
        });
        map.addControl(new bkoigl.FullscreenControl());
        map.addControl(new bkoigl.NavigationControl());
        map.addControl(new bkoigl.ScaleControl());

        const centralPoints = @json($centralPoints);
        const dealers = @json($dealers);
        const retailers = @json($retailers);
        const billboards = @json($billboards);
        const dealersByDistrict = @json($dealersByDistrict);
        const retailersByDistrict = @json($retailersByDistrict);
        const billboardsByDistrict = @json($billboardsByDistrict);

        const centralIconUrl = '{{ asset('images/pharmacy.png') }}'; // Branch icon for central points
        const dealerIconUrl = '{{ asset('images/red.png') }}'; // Red icon for dealers
        const retailerIconUrl = '{{ asset('images/blue.png') }}'; // Blue icon for retailers
        const billboardIconUrl = '{{ asset('images/billboard.png') }}'; // Blue icon for billboards

        let centralPointsLayer = []; // Layer for central points
        let dealerMarkersLayer = []; // Layer for dealer markers
        let retailerMarkersLayer = []; // Layer for retailer markers
        let billboardMarkersLayer = []; // Layer for billboard markers

        // Function to create a custom marker element
        function createCustomMarkerElement(iconUrl) {
            const markerElement = document.createElement('div');
            markerElement.className = 'marker';
            markerElement.style.backgroundImage = `url(${iconUrl})`;
            markerElement.style.backgroundSize = 'contain';
            markerElement.style.width = '30px';
            markerElement.style.height = '30px';
            return markerElement;
        }

        // Function to add central points to the map (Always visible)
        function addCentralPoints() {
            centralPoints.forEach(point => {
                const marker = new bkoigl.Marker({ element: createCustomMarkerElement(centralIconUrl) })
                    .setLngLat([point.longitude, point.latitude])
                    .setPopup(new bkoigl.Popup().setHTML(`
                    <div><strong>${point.name}</strong></div>
                    <div>Location: ${point.location || 'N/A'}</div>
                `))
                    .addTo(map);
                centralPointsLayer.push(marker); // Store central points markers to a separate layer
            });
        }

        // Function to add markers for the selected data type (dealers, retailers, billboards)
        // Define calculateDistance function first
        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Earth's radius in kilometers
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = R * c; // Distance in kilometers
            return distance;
        }

        // Add district markers after defining the function
        function addDistrictMarkers(dataType, district) {
            let filteredData = [];
            if (dataType === 'dealers') {
                filteredData = district ? dealers.filter(dealer => dealer.district === district) : dealers;
            } else if (dataType === 'retailers') {
                filteredData = district ? retailers.filter(retailer => retailer.district === district) : retailers;
            } else if (dataType === 'billboards') {
                filteredData = district ? billboards.filter(billboard => billboard.district === district) : billboards;
            }

            // Add filtered markers to the map
            filteredData.forEach(point => {
                const iconUrl = dataType === 'dealers' ? dealerIconUrl :
                    dataType === 'retailers' ? retailerIconUrl : billboardIconUrl;
                const marker = new bkoigl.Marker({ element: createCustomMarkerElement(iconUrl) })
                    .setLngLat([point.longitude, point.latitude]);

                // Add a click event listener to calculate and show distance
                marker.getElement().addEventListener('click', function() {
                    const selectedCentralPointId = document.getElementById('select-central-point').value;
                    if (selectedCentralPointId && selectedCentralPointId !== 'all') {
                        const selectedCentralPoint = centralPoints.find(point => point.id == selectedCentralPointId);
                        const distance = calculateDistance(
                            selectedCentralPoint.latitude, selectedCentralPoint.longitude,
                            point.latitude, point.longitude
                        );

                        // Update the popup content dynamically with distance and styling
                        const popupContent = `
    <div style="background-color: lightblue; padding: 10px;">
        <div><strong>${point.name}</strong></div>
        <div><strong>Location:</strong> ${point.location || 'N/A'}</div>
        <div><strong>Distance from ${selectedCentralPoint.name}:</strong> ${distance.toFixed(2)} km</div>
        ${point.average_sales ? `<div><strong>Average Sales:</strong> ${point.average_sales}</div>` : ''}
        ${point.market_size ? `<div><strong>Market Size:</strong> ${point.market_size}</div>` : ''}
        ${point.market_share ? `<div><strong>Market Share:</strong> ${point.market_share}</div>` : ''}
        ${point.competition_brand ? `<div><strong>Competition Brand:</strong> ${point.competition_brand}</div>` : ''}
    </div>
`;


                        // Set the popup content and show it
                        marker.setPopup(new bkoigl.Popup().setHTML(popupContent)).addTo(map);
                    }
                });

                // Set initial popup without distance (optional)
                marker.setPopup(new bkoigl.Popup().setHTML(`
            <div style="background-color: lightblue; padding: 10px;">
                <div><strong>${point.name}</strong></div>
                <div><strong>Location:</strong> ${point.location || 'N/A'}</div>
            </div>
        `))
                    .addTo(map);

                // Add the marker to the appropriate layer
                if (dataType === 'dealers') {
                    dealerMarkersLayer.push(marker);
                } else if (dataType === 'retailers') {
                    retailerMarkersLayer.push(marker);
                } else if (dataType === 'billboards') {
                    billboardMarkersLayer.push(marker);
                }
            });
        }




        // Function to populate the data type dropdown based on the selected district
        // Function to populate the data type dropdown based on the selected district
        // Function to populate the data type dropdown based on the selected district
        // Function to populate the data type dropdown based on the selected district
        // Function to populate the data type dropdown based on the selected district
        // Function to populate the data type dropdown based on the selected district
        function populateDataTypeDropdown(district) {
            const dataTypeSelect = document.getElementById('select-data-type');
            dataTypeSelect.innerHTML = ''; // Clear existing options

            const defaultOption = document.createElement('option');
            defaultOption.textContent = 'Select Data Type'; // Default option for the user to choose
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dataTypeSelect.appendChild(defaultOption);

            let dealersCount, retailersCount, billboardsCount;

            if (district === 'all' || district === '') {
                // Calculate counts for all data types across all districts
                dealersCount = dealers.length;
                retailersCount = retailers.length;
                billboardsCount = billboards.length;
            } else {
                // Calculate counts for the selected district only
                dealersCount = dealersByDistrict[district] || 0;
                retailersCount = retailersByDistrict[district] || 0;
                billboardsCount = billboardsByDistrict[district] || 0;
            }

            // Ensure all counts are valid numbers (prevent NaN)
            dealersCount = Number(dealersCount) || 0;
            retailersCount = Number(retailersCount) || 0;
            billboardsCount = Number(billboardsCount) || 0;

            // Calculate total count
            const totalCount = dealersCount + retailersCount + billboardsCount;

            // Add the "All" option to the dropdown with the updated total count
            const allOption = document.createElement('option');
            allOption.value = 'all';
            allOption.textContent = `All (${totalCount})`;
            dataTypeSelect.appendChild(allOption);

            // Add individual options for dealers, retailers, and billboards
            const options = [
                { type: 'dealers', label: 'Dealers', count: dealersCount },
                { type: 'retailers', label: 'Retailers', count: retailersCount },
                { type: 'billboards', label: 'Billboards', count: billboardsCount }
            ];

            options.forEach(option => {
                const dataOption = document.createElement('option');
                dataOption.value = option.type;
                dataOption.textContent = `${option.label} (${option.count})`;
                dataTypeSelect.appendChild(dataOption);
            });
        }






        // Event listener for central point selection
        document.getElementById('select-central-point').addEventListener('change', function() {
            const selectedCentralPoint = this.value;

            // If "All" is selected in the central point dropdown
            if (selectedCentralPoint === 'all') {
                map.flyTo({
                    center: [90.3938010872331, 23.821600277500405], // Center of the country/region
                    zoom: 6.5, // Adjust zoom level to show all points
                    essential: true // Ensures the animation runs even in non-interactive contexts (like page load)
                });

                // Clear existing markers for data types
                clearDataTypeMarkers();

                // Show all markers (dealers, retailers, billboards)
                addDistrictMarkers('dealers', '');  // Empty district to show all
                addDistrictMarkers('retailers', '');
                addDistrictMarkers('billboards', '');

                // Populate the data type dropdown with updated counts
                populateDataTypeDropdown('');
                return;
            }

            const lat = parseFloat(this.options[this.selectedIndex].dataset.lat);  // Ensure lat is a number
            const lng = parseFloat(this.options[this.selectedIndex].dataset.lng);  // Ensure lng is a number
            const district = this.options[this.selectedIndex].dataset.district;

            // Fly to the selected central point and zoom in
            map.flyTo({
                center: [lng, lat],
                zoom: 11, // Adjust zoom level as needed
                essential: true // Ensures that the animation runs even in non-interactive contexts
            });

            // Clear existing markers for data types
            clearDataTypeMarkers();

            // Populate the data type dropdown based on the selected district
            populateDataTypeDropdown(district);
        });

        // Event listener for data type selection
        document.getElementById('select-data-type').addEventListener('change', function() {
            const selectedDataType = this.value;
            const selectedCentralPoint = document.getElementById('select-central-point').value;

            // If "All" is selected in the data type dropdown
            if (selectedDataType === 'all') {
                const district = selectedCentralPoint === 'all' ? '' : document.querySelector(`#select-central-point option[value="${selectedCentralPoint}"]`).getAttribute('data-district');

                // Show all markers (dealers, retailers, billboards)
                addDistrictMarkers('dealers', district);
                addDistrictMarkers('retailers', district);
                addDistrictMarkers('billboards', district);
            } else {
                const district = selectedCentralPoint === 'all' ? '' : document.querySelector(`#select-central-point option[value="${selectedCentralPoint}"]`).getAttribute('data-district');
                clearDataTypeMarkers();
                addDistrictMarkers(selectedDataType, district);
            }
        });

        // Function to clear all data type markers from the map
        function clearDataTypeMarkers() {
            dealerMarkersLayer.forEach(marker => marker.remove());
            retailerMarkersLayer.forEach(marker => marker.remove());
            billboardMarkersLayer.forEach(marker => marker.remove());
            dealerMarkersLayer = [];
            retailerMarkersLayer = [];
            billboardMarkersLayer = [];
        }

        // Initialize the map with central points
        addCentralPoints();
        populateDataTypeDropdown('');
    </script>
@endsection
