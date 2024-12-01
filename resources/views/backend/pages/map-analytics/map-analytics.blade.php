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
                                <label for="enable-district-range" style="margin-bottom: 0;">District Market Share (%)</label>
                            </div>
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <input type="number" id="district-share-min" class="form-control" placeholder="Min" min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;">
                                <input type="number" id="district-share-max" class="form-control" placeholder="Max" min="0" disabled
                                       style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #f8f9fa;">
                            </div>
                        </div>

                        <!-- District Select -->
                        <div class="form-group mb-4">
                            <label for="select-district" class="form-label"
                                   style="font-weight: 500; font-size: 14px; color: #464A4D;">Select District</label>
                            <select id="select-district" name="select-district" class="form-control"
                                    style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                <option value="" disabled selected>Select</option>
                                <option value="all">All Districts</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district['id'] }}" data-lat="{{ $district['latitude'] }}"
                                            data-lng="{{ $district['longitude'] }}"
                                            data-district="{{ $district['district'] }}">
                                        {{ $district['name'] }}
                                    </option>
                                @endforeach
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

                        <!-- Legend -->
                        <div class="map-legend" style="position: relative; bottom: 0; padding: 5px; background-color: white; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); font-size: 10px; width: 100%; overflow-y: auto;">
                            <div class="legend-header" style="font-weight: bold; font-size: 13px; text-align: center; margin-bottom: 5px;">Legend</div>

                            <!-- Legend items -->
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon" style="background-image: url('{{ asset('images/pharmacy.png') }}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">District</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon" style="background-image: url('{{asset('images/dealer-2.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Dealer</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon" style="background-image: url('{{asset('images/retailer.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Retailer</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon" style="background-image: url('{{asset('images/billboard-black-1.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Billboard</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon" style="background-image: url('{{ asset('images/shop-black.png') }}'); width: 18px; height: 18px; background-size: contain; margin-right: 5px;background-repeat: no-repeat;"></div>
                                <span class="legend-label">Shop Sign</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-icon" style="background-image: url('{{asset('images/highwall-black.png')}}'); width: 20px; height: 20px; background-size: contain; margin-right: 5px;"></div>
                                <span class="legend-label">Highwalls</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-color-box" style="background-color: red; width: 12px; height: 12px; margin-right: 5px;"></div>
                                <span class="legend-label">Fresh Super Cement</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-color-box" style="background-color: blue; width: 12px; height: 12px; margin-right: 5px;"></div>
                                <span class="legend-label">Dhalai Special Cement</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; margin-bottom: 4px;">
                                <div class="legend-color-box" style="background-color: green; width: 12px; height: 12px; margin-right: 5px;"></div>
                                <span class="legend-label">Meghnacem Delux Cement</span>
                            </div>
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

        const districtIconUrl = '{{ asset('images/pharmacy.png') }}'; // Branch icon for district points
        const dealerIconUrl = '{{ asset('images/dealer-2.png') }}'; // Red icon for dealers
        const retailerIconUrl = '{{ asset('images/retailer.png') }}'; // Blue icon for retailers
        const billboardIconUrl = '{{ asset('images/billboard.png') }}'; // Blue icon for retailers
        const shopsignIconUrl = '{{ asset('images/shop-blue.png') }}'; // Blue icon for retailers
        const highwallIconUrl = '{{ asset('images/3415475.png') }}'; // Blue icon for retailers


        let districtsLayer = []; // Layer for district points
        let dealerMarkersLayer = []; // Layer for dealer markers
        let retailerMarkersLayer = []; // Layer for retailer markers
        let billboardMarkersLayer = []; // Layer for billboard markers
        let shopsignMarkerLayer = [];
        let highwallMarkerLayer = [];



        // Function to create a custom marker element
        function createCustomMarkerElement(iconUrl) {
            const markerElement = document.createElement('div');
            markerElement.className = 'marker';
            markerElement.style.backgroundImage = `url(${iconUrl})`;
            markerElement.style.backgroundSize = 'contain';
            markerElement.style.backgroundRepeat = 'no-repeat'; // Prevent the image from repeating
            markerElement.style.width = '30px';
            markerElement.style.height = '30px';
            return markerElement;
        }

        // Function to add district points to the map (Always visible)
        function addDistricts() {
            districts.forEach(point => {
                const marker = new bkoigl.Marker({element: createCustomMarkerElement(districtIconUrl)})
                    .setLngLat([point.longitude, point.latitude])
                    .setPopup(new bkoigl.Popup().setHTML(`
<div style="background-color: lightblue; padding: 10px;">
                    <div><strong>Name: </strong>${point.name}</div>
                    <div><strong>Location: </strong>${point.location || 'N/A'}</div>
</div>
                `))
                    .addTo(map);
                districtsLayer.push(marker); // Store district points markers to a separate layer
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

        // Modify addDistrictMarkers function to use specific icons based on brand
        function addDistrictMarkers(dataType, district) {
            let filteredData = [];
            if (dataType === 'dealers') {
                filteredData = district ? dealers.filter(dealer => dealer.district === district) : dealers;
            } else if (dataType === 'retailers') {
                filteredData = district ? retailers.filter(retailer => retailer.district === district) : retailers;
            } else if (dataType === 'billboards') {
                filteredData = district ? billboards.filter(billboard => billboard.district === district) : billboards;
            } else if (dataType === 'shopsigns') {
                filteredData = district ? shopsigns.filter(shopsign => shopsign.district === district) : shopsigns;
            } else if (dataType === 'highwalls') {
                filteredData = district ? highwalls.filter(highwall => highwall.district === district) : highwalls;
            }
            // Add filtered markers to the map
            filteredData.forEach(point => {
                let iconUrl;
                if (dataType === 'billboards') {
                    // Choose the icon based on the brand
                    iconUrl = billboardIcons[point.brand] || billboardIconUrl; // Default to general icon if brand not matched
                } else if (dataType === 'shopsigns') {
                    iconUrl = shopsignIcons[point.brand] || shopsignIconUrl;
                } else if (dataType === 'highwalls') {
                    iconUrl = highwallIcons[point.brand] || highwallIconurl
                } else {
                    iconUrl = dataType === 'dealers' ? dealerIconUrl : retailerIconUrl;
                }

                const marker = new bkoigl.Marker({element: createCustomMarkerElement(iconUrl)})
                    .setLngLat([point.longitude, point.latitude]);

                // Add popup and click event logic as before
                marker.getElement().addEventListener('click', function () {
                    const selectedDistrictId = document.getElementById('select-district').value;
                    if (selectedDistrictId && selectedDistrictId !== 'all') {
                        const selectedDistrict = districts.find(point => point.id == selectedDistrictId);
                        const distance = calculateDistance(
                            selectedDistrict.latitude, selectedDistrict.longitude,
                            point.latitude, point.longitude
                        );

                        const popupContent = `
    <div style="background-color: lightblue; padding: 10px;">
        <div><strong>Name: </strong>${point.name}</div>
        <div><strong>Location:</strong> ${point.location || 'N/A'}</div>
        <div><strong>Distance from District ${selectedDistrict.name}:</strong> ${distance.toFixed(2)} km</div>
        ${point.size ? `<div><strong>Size:</strong> ${point.size}</div>` : ''}
        ${point.type ? `
            <div><strong>Type:</strong>
                ${
                                point.type === 'single_side' ? 'Single Side' :
                                    point.type === 'unipool' ? 'Unipool' :
                                        point.type === 'neon' ? 'Neon' :
                                            point.type === 'non_lit' ? 'Non Lit' :
                                                point.type === 'lightbox' ? 'Light Box' :
                                                    point.type === 'cold_store' ? 'Cold Store' :
                                                        point.type === 'high_raise' ? 'High Raise' :
                                                            point.type
                            }
            </div>`
                            : ''}
        ${point.brand ? `
            <div><strong>Brand:</strong>
                ${
                                point.brand === 'fresh_super_cement' ? 'Fresh Super Cement' :
                                    point.brand === 'dhalai_special_cement' ? 'Dhalai Special Cement' :
                                        point.brand === 'meghnacem_delux_cement' ? 'Meghnacem Delux Cement' :
                                            point.brand
                            }
            </div>`
                            : ''}
        ${point.average_sales ? `<div><strong>Average Sales:</strong> ${point.average_sales}</div>` : ''}
        ${point.market_size ? `<div><strong>Market Size:</strong> ${point.market_size}</div>` : ''}
        ${point.market_share ? `<div><strong>Market Share:</strong> ${point.market_share}</div>` : ''}
        ${point.competition_brand ? `<div><strong>Competition Brand:</strong> ${point.competition_brand}</div>` : ''}
    </div>
`;


                        marker.setPopup(new bkoigl.Popup().setHTML(popupContent)).addTo(map);
                    }
                });

                marker.setPopup(new bkoigl.Popup().setHTML(`
            <div style="background-color: lightblue; padding: 10px;">
                <div><strong>Name: </strong>${point.name}</div>
                <div><strong>Location:</strong> ${point.location || 'N/A'}</div>
                        ${point.size ? `<div><strong>Size:</strong> ${point.size}</div>` : ''}
                        ${point.type ? `
            <div><strong>Type:</strong>
                ${
                        point.type === 'single_side' ? 'Single Side' :
                            point.type === 'unipool' ? 'Unipool' :
                                point.type === 'neon' ? 'Neon' :
                                    point.type === 'non_lit' ? 'Non Lit' :
                                        point.type === 'lightbox' ? 'Light Box' :
                                            point.type === 'lightbox' ? 'Light Box' :
                                                point.type === 'cold_store' ? 'Cold Store' :
                                                    point.type === 'high_raise' ? 'High Raise' :
                                                        point.type
                    }
            </div>`
                    : ''}
                        ${point.brand ? `
            <div><strong>Brand:</strong>
                ${
                        point.brand === 'fresh_super_cement' ? 'Fresh Super Cement' :
                            point.brand === 'dhalai_special_cement' ? 'Dhalai Special Cement' :
                                point.brand === 'meghnacem_delux_cement' ? 'Meghnacem Delux Cement' :
                                    point.brand
                    }
            </div>`
                    : ''}
                        ${point.average_sales ? `<div><strong>Average Sales:</strong> ${point.average_sales}</div>` : ''}
                        ${point.market_size ? `<div><strong>Market Size:</strong> ${point.market_size}</div>` : ''}
                        ${point.market_share ? `<div><strong>Market Share:</strong> ${point.market_share}</div>` : ''}
                        ${point.competition_brand ? `<div><strong>Competition Brand:</strong> ${point.competition_brand}</div>` : ''}
            </div>
        `)).addTo(map);

                if (dataType === 'dealers') {
                    dealerMarkersLayer.push(marker);
                } else if (dataType === 'retailers') {
                    retailerMarkersLayer.push(marker);
                } else if (dataType === 'billboards') {
                    billboardMarkersLayer.push(marker);
                } else if (dataType === 'shopsigns') {
                    shopsignMarkerLayer.push(marker);
                } else if (dataType === 'highwalls') {
                    highwallMarkerLayer.push(marker);
                }
            });
            console.log(shopsignsByDistrict[district] || 0)
        }


        // Function to populate the data type dropdown based on the selected district
        function populateDataTypeDropdown(district) {
            const dataTypeSelect = document.getElementById('select-data-type');
            dataTypeSelect.innerHTML = ''; // Clear existing options
            const defaultOption = document.createElement('option');
            defaultOption.textContent = 'Select Data Type'; // Default option for the user to choose
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dataTypeSelect.appendChild(defaultOption);

            let dealersCount, retailersCount, billboardsCount, shopsignsCount, highwallsCount;

            if (district === 'all' || district === '') {
                // Calculate counts for all data types across all districts
                dealersCount = dealers.length;
                retailersCount = retailers.length;
                billboardsCount = billboards.length;
                shopsignsCount = shopsigns.length;
                highwallsCount = highwalls.length;
            } else {
                // Calculate counts for the selected district only
                dealersCount = dealersByDistrict[district] || 0;
                retailersCount = retailersByDistrict[district] || 0;
                billboardsCount = billboardsByDistrict[district] || 0;
                shopsignsCount = shopsignsByDistrict[district] || 0;
                highwallsCount = highwallsByDistrict[district] || 0;
                console.log('this is calculated ' + shopsignsCount)
            }

            // Ensure all counts are valid numbers (prevent NaN)
            dealersCount = Number(dealersCount) || 0;
            retailersCount = Number(retailersCount) || 0;
            billboardsCount = Number(billboardsCount) || 0;
            shopsignsCount = Number(shopsignsCount) || 0;
            highwallsCount = Number(highwallsCount) || 0;


            // Calculate total count
            const totalCount = dealersCount + retailersCount + billboardsCount + shopsignsCount + highwallsCount;

            // Add the "All" option to the dropdown with the updated total count
            const allOption = document.createElement('option');
            allOption.value = 'all';
            allOption.textContent = `All (${totalCount})`;
            dataTypeSelect.appendChild(allOption);

            // Add individual options for dealers, retailers, and billboards
            const options = [
                {type: 'dealers', label: 'Dealers', count: dealersCount},
                {type: 'retailers', label: 'Retailers', count: retailersCount},
                {type: 'billboards', label: 'Billboards', count: billboardsCount},
                {type: 'shopsigns', label: 'Shop Signs', count: shopsignsCount},
                {type: 'highwalls', label: 'Highwalls', count: highwallsCount}
            ];

            options.forEach(option => {
                const dataOption = document.createElement('option');
                dataOption.value = option.type;
                dataOption.textContent = `${option.label} (${option.count})`;
                dataTypeSelect.appendChild(dataOption);
            });
        }


        // Event listener for district point selection
        document.getElementById('select-district').addEventListener('change', function () {
            const selectedDistrict = this.value;
            console.log('selected district'+selectedDistrict)

            // If "All" is selected in the district point dropdown
            if (selectedDistrict === 'all') {
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
                addDistrictMarkers('shopsigns', '');
                addDistrictMarkers('highwalls', '');

                // Populate the data type dropdown with updated counts
                populateDataTypeDropdown('');
                return;
            }

            const lat = parseFloat(this.options[this.selectedIndex].dataset.lat);  // Ensure lat is a number
            const lng = parseFloat(this.options[this.selectedIndex].dataset.lng);  // Ensure lng is a number
            const district = this.options[this.selectedIndex].dataset.district;
            console.log('the district is'+ district)

            // Fly to the selected district point and zoom in
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
        document.getElementById('select-data-type').addEventListener('change', function () {
            const selectedDataType = this.value;
            const selectedDistrict = document.getElementById('select-district').value;

            // If "All" is selected in the data type dropdown
            if (selectedDataType === 'all') {
                const district = selectedDistrict === 'all' ? '' : document.querySelector(`#select-district option[value="${selectedDistrict}"]`).getAttribute('data-district');

                // Show all markers (dealers, retailers, billboards)
                addDistrictMarkers('dealers', district);
                addDistrictMarkers('retailers', district);
                addDistrictMarkers('billboards', district);
                addDistrictMarkers('shopsigns', district);
                addDistrictMarkers('highwalls', district);
            } else {
                const district = selectedDistrict === 'all' ? '' : document.querySelector(`#select-district option[value="${selectedDistrict}"]`).getAttribute('data-district');
                clearDataTypeMarkers();
                addDistrictMarkers(selectedDataType, district);
            }
        });

        // Function to clear all data type markers from the map
        function clearDataTypeMarkers() {
            console.log('clear')
            dealerMarkersLayer.forEach(marker => marker.remove());
            retailerMarkersLayer.forEach(marker => marker.remove());
            billboardMarkersLayer.forEach(marker => marker.remove());
            shopsignMarkerLayer.forEach(marker => marker.remove());
            highwallMarkerLayer.forEach(marker => marker.remove());
            dealerMarkersLayer = [];
            retailerMarkersLayer = [];
            billboardMarkersLayer = [];
            shopsignMarkersLayer = [];
            highwalldMarkersLayer = [];
        }

        // Initialize the map with district points
        addDistricts();
        populateDataTypeDropdown('');
        document.getElementById('enable-district-range').addEventListener('change', function () {
            const isChecked = this.checked;
            const minInput = document.getElementById('district-share-min');
            const maxInput = document.getElementById('district-share-max');

            // Enable/disable inputs and change background color
            minInput.disabled = !isChecked;
            maxInput.disabled = !isChecked;
            minInput.style.backgroundColor = isChecked ? '#fff' : '#f8f9fa';
            maxInput.style.backgroundColor = isChecked ? '#fff' : '#f8f9fa';

            // Clear values if unchecked
            if (!isChecked) {
                console.log(isChecked)
                clearDataTypeMarkers()
                populateDataTypeDropdown('')
                minInput.value = '';
                maxInput.value = '';
            }
        });
        // Event listener for min and max input changes
        document.getElementById('district-share-min').addEventListener('input', filterDistrictsByShare);
        document.getElementById('district-share-max').addEventListener('input', filterDistrictsByShare);

        function filterDistrictsByShare() {
            const minShare = parseFloat(document.getElementById('district-share-min').value) || 0;
            const maxShare = parseFloat(document.getElementById('district-share-max').value) || Number.MAX_VALUE;

            const districtSelect = document.getElementById('select-district');
            districtSelect.innerHTML = ''; // Clear existing options

            const defaultOption = document.createElement('option');
            defaultOption.textContent = 'Select'; // Default option for the user to choose
            defaultOption.disabled = true;
            defaultOption.value = '';
            defaultOption.selected = true; // This makes it the default selected option
            districtSelect.appendChild(defaultOption);

            districts.forEach(district => {
                const share = district.market_share || 0; // Assuming `market_share` is a field in your district data

                if (share >= minShare && share <= maxShare) {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = `${district.name} (${share}%)`;
                    option.dataset.lat = district.latitude;
                    option.dataset.lng = district.longitude;
                    option.dataset.district = district.district;
                    districtSelect.appendChild(option);
                }
            });
        }

        ////////////
    </script>
@endsection
