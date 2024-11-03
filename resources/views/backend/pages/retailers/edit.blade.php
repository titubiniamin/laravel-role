@extends('backend.layouts.master')

@section('title')
    Edit Page - Retailer
@endsection

@section('admin-content')

    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Retailers</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li><span>Retailers</span></li>
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
        <form action="{{ route('admin.retailers.update',$retailer->id) }}" method="POST">
            @csrf
            @method('PUT')
        <div class="row">
            <!--Left Column-->
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-12 mt-5 mb-3">
                        <div class="card">
                            <div class="p-4">
                                <h4>Update Retailer</h4>

                                <!-- Display validation errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <!-- Display success message -->
                                @if (session('success'))
                                    <div id="flash-message" class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <script>
                                    // Automatically hide the flash message after 5 seconds
                                    setTimeout(function() {
                                        const flashMessage = document.getElementById('flash-message');
                                        if (flashMessage) {
                                            flashMessage.style.transition = 'opacity 0.5s ease'; // Fade-out transition
                                            flashMessage.style.opacity = '0'; // Start fading

                                            setTimeout(() => flashMessage.remove(), 500); // Remove from DOM after fade-out
                                        }
                                    }, 5000); // 5-second delay
                                </script>



                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" value="{{ old('name', $retailer->name) }}" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="owner_name">Owner Name</label>
                                        <input type="text" class="form-control" value="{{ old('owner_name', $retailer->owner_name) }}" name="owner_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="zone">Zone</label>
                                        <input type="text" class="form-control" value="{{ old('zone', $retailer->zone) }}" name="zone">
                                    </div>

                                    <div class="form-group">
                                        <label for="retailer_code">Retailer Code</label>
                                        <input type="text" class="form-control" value="{{ old('retailer_code', $retailer->retailer_code) }}" name="retailer_code">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" value="{{old('email',$retailer->email)}}" name="email">
                                    </div>

                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" value="{{old('website',$retailer->website)}}" name="website">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Mobile</label>
                                        <input type="text" class="form-control" value="{{old('mobile',$retailer->mobile)}}" name="mobile">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Address</label>
                                        <input type="text" class="form-control" value="{{old('address', $retailer->address)}}" name="address">
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="longitude" value="{{$retailer->longitude}}" id="longitude" hidden>
                                        <input type="text" name="latitude" value="{{$retailer->latitude}}"  id="latitude" hidden>
                                        <input type="text" class="form-control bksearch" value="{{$retailer->location}}"  name="location" id="location"/>
                                        <div class="bklist"></div>
                                        <div id="loading" style="display: none;">Loading...</div> <!-- Loading indicator -->
                                    </div>

                                    <div class="form-group">
                                        <div id="map" style="width: 100%; height: 400px; background-color: yellow;"></div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Retailer</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right column for additional content -->
            <div class="col-lg-3" style="height: 70vh;">
                <div class="card mt-5 mb-3" style="height: 400px;background-color: white">
                    <div class="p-4">
                        <div class="form-group mb-4">
                            <div class="form-group">
                                <label for="name">Average Sales</label>
                                <input type="text" class="form-control" value="{{ old('average_sales', $retailer->average_sales) }}" name="average_sales">
                            </div>
                            <div class="form-group">
                                <label for="name">Market Size</label>
                                <input type="text" class="form-control" value="{{ old('market_size', $retailer->market_size) }}" name="market_size">
                            </div>
                            <div class="form-group">
                                <label for="market-share">Market Share</label>
                                <input type="text" class="form-control" value="{{ old('market_share', $retailer->market_share) }}" name="market_share">
                            </div>
                            <div class="form-group">
                                <label for="name">Competition Brand</label>
                                <input type="text" class="form-control" value="{{ old('competition_brand', $retailer->competition_brand )}}" name="competition_brand">
                            </div>
                        </div>
                        <!-- Additional content goes here -->
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- Your existing script and styles here -->


    <script>
        bkoigl.accessToken = "{{ env('BARIKOI_API_KEY') }}"; // required

        // Fetch retailer's coordinates from backend
        const retailerLongitude = {{ $retailer->longitude ?? 90.3938010872331 }};
        const retailerLatitude = {{ $retailer->latitude ?? 23.821600277500405 }};
        const retailerLocation = "{{ $retailer->location ?? '' }}";

        const map = new bkoigl.Map({
            container: "map",
            center: [retailerLongitude, retailerLatitude], // Set map center to retailer's coordinates
            zoom: 15,
        });
        map.addControl(new bkoigl.FullscreenControl());
        map.addControl(new bkoigl.NavigationControl());
        map.addControl(new bkoigl.ScaleControl());


        // Initialize the marker at retailer's coordinates
        let marker = new bkoigl.Marker({ draggable: true })
            .setLngLat([retailerLongitude, retailerLatitude])
            .addTo(map);

        // Populate location input field with retailer's location
        document.getElementById("location").value = retailerLocation;
        document.getElementById("longitude").value = retailerLongitude;
        document.getElementById("latitude").value = retailerLatitude;

        // Event listener for location search
        document.getElementById("location").addEventListener("input", function () {
            let query = this.value;
            let loadingIndicator = document.getElementById("loading");

            if (query.length > 2) {
                loadingIndicator.style.display = "block"; // Show loading indicator
                fetch(`/api/proxy/autocomplete?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = "none"; // Hide loading indicator
                        if (data.places) {
                            let suggestions = data.places;
                            let suggestionList = document.querySelector('.bklist');
                            suggestionList.innerHTML = ''; // Clear previous suggestions

                            suggestions.forEach(place => {
                                let suggestionItem = document.createElement('div');
                                suggestionItem.textContent = place.address;
                                suggestionItem.className = 'suggestion-item';
                                suggestionItem.onclick = function () {
                                    marker.setLngLat([place.longitude, place.latitude]);
                                    map.flyTo({ center: [place.longitude, place.latitude], zoom: 15 });
                                    suggestionList.innerHTML = '';
                                    document.getElementById("location").value = place.address;
                                    document.getElementById("longitude").value = place.longitude;
                                    document.getElementById("latitude").value = place.latitude;
                                };
                                suggestionList.appendChild(suggestionItem);
                            });
                        }
                    })
                    .catch(error => {
                        loadingIndicator.style.display = "none"; // Hide loading indicator on error
                        console.error('Error fetching data:', error);
                    });
            } else {
                document.querySelector('.bklist').innerHTML = ''; // Clear suggestions if query is too short
                loadingIndicator.style.display = "none"; // Hide loading indicator if no query
            }
        });

        // Event listener for marker drag end to update coordinates and address
        marker.on('dragend', function() {
            const lngLat = marker.getLngLat();
            const longitude = lngLat.lng;
            const latitude = lngLat.lat;

            fetch(`/api/proxy/reverse-geocode?longitude=${longitude}&latitude=${latitude}`)
                .then(response => response.json())
                .then(data => {
                    if (data.place && data.place.address) {
                        const locationInput = document.getElementById("location");
                        locationInput.value = data.place.address;
                        document.getElementById("longitude").value = longitude;
                        document.getElementById("latitude").value = latitude;
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                });
        });
    </script>


    <style>
        .suggestion-item {
            padding: 5px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0; /* Highlight on hover */
        }
        #loading {
            display: none; /* Initially hidden */
            font-size: 14px;
            color: #888;
            padding: 10px 0;
        }


    </style>
@endsection