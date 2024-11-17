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
                                <option value="" disabled selected>Select Central Point</option>
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
        const billboardIconUrl = '{{ asset('images/billboard.png') }}'; // Blue icon for retailers

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

        // Function to add dealers for the selected district
        function addDistrictMarkers(dataType, district) {
            let filteredData = [];
            if (dataType === 'dealers') {
                filteredData = dealers.filter(dealer => dealer.district === district);
            } else if (dataType === 'retailers') {
                filteredData = retailers.filter(retailer => retailer.district === district);
            } else if (dataType === 'billboards') {
                filteredData = billboards.filter(billboard => billboard.district === district);
            }

            // Add filtered markers to the map
            filteredData.forEach(point => {
                const iconUrl = dataType === 'dealers' ? dealerIconUrl :
                    dataType === 'retailers' ? retailerIconUrl : billboardIconUrl;
                const marker = new bkoigl.Marker({ element: createCustomMarkerElement(iconUrl) })
                    .setLngLat([point.longitude, point.latitude])
                    .setPopup(new bkoigl.Popup().setHTML(`
                        <div><strong>${point.name}</strong></div>
                        <div>Location: ${point.location || 'N/A'}</div>
                    `))
                    .addTo(map);

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
        function populateDataTypeDropdown(district) {
            const dataTypeSelect = document.getElementById('select-data-type');
            dataTypeSelect.innerHTML = ''; // Clear existing options

            const defaultOption = document.createElement('option');
            defaultOption.textContent = 'Select Data Type'; // Default option for the user to choose
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dataTypeSelect.appendChild(defaultOption);

            const options = [
                { type: 'dealers', label: 'Dealers', count: dealersByDistrict[district] || 0 },
                { type: 'retailers', label: 'Retailers', count: retailersByDistrict[district] || 0 },
                { type: 'billboards', label: 'Billboards', count: billboardsByDistrict[district] || 0 }
            ];

            options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.type;
                opt.textContent = `${option.label} (${option.count})`;
                dataTypeSelect.appendChild(opt);
            });
        }

        // Event listener for central point selection
        document.getElementById('select-central-point').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const district = selectedOption.getAttribute('data-district');
            const lat = parseFloat(selectedOption.getAttribute('data-lat'));
            const lng = parseFloat(selectedOption.getAttribute('data-lng'));

            // Clear all previous markers for data types and reset dropdown
            clearDataTypeMarkers();
            populateDataTypeDropdown(district);

            // Fly to the selected central point location
            map.flyTo({
                center: [lng, lat],
                essential: true,
                zoom: 10
            });
        });

        // Event listener for data type selection
        document.getElementById('select-data-type').addEventListener('change', function() {
            const selectedType = this.value;
            const selectedDistrict = document.getElementById('select-central-point').value;
            const district = document.querySelector(`#select-central-point option[value="${selectedDistrict}"]`).getAttribute('data-district');

            // Clear all markers for the selected data type
            clearDataTypeMarkers();
            addDistrictMarkers(selectedType, district); // Add markers for the selected data type and district
        });

        // Function to clear data type markers (dealers, retailers, and billboards)
        function clearDataTypeMarkers() {
            dealerMarkersLayer.forEach(marker => marker.remove());
            retailerMarkersLayer.forEach(marker => marker.remove());
            billboardMarkersLayer.forEach(marker => marker.remove());
            dealerMarkersLayer = [];
            retailerMarkersLayer = [];
            billboardMarkersLayer = [];
        }

        // Add central points to the map on load
        addCentralPoints();
    </script>
@endsection
