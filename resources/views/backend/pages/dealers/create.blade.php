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
                        <li><span>Dealers</span></li>
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
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-12 mt-5 mb-3">
                        <div class="card">
                            <div class="p-4">
                                <h4>Create Dealer</h4>
                                <form action="{{ route('admin.dealers.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="owner_name">Name</label>
                                        <input type="text" class="form-control" name="owner_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="zone">Zone</label>
                                        <input type="text" class="form-control" name="zone">
                                    </div>

                                    <div class="form-group">
                                        <label for="dealer_code">Dealer Code</label>
                                        <input type="text" class="form-control" name="dealer_code">
                                    </div>



                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>

                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" name="website">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Mobile</label>
                                        <input type="text" class="form-control" name="mobile">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Address</label>
                                        <input type="text" class="form-control" name="address">
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="longitude" id="longitude" hidden>
                                        <input type="text" name="latitude" id="latitude" hidden>
                                        <input type="text" class="form-control bksearch" name="location" id="location"/>
                                        <div class="bklist"></div>
                                        <div id="loading" style="display: none;">Loading...</div> <!-- Loading indicator -->
                                    </div>


                                    <!-- Latitude and Longitude display -->
                                    <div class="form-group">
                                        <div id="map" style="width: 100%; height: 400px; background-color: yellow;"></div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Dealer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        bkoigl.accessToken = "{{ env('BARIKOI_API_KEY') }}"; // required

        const map = new bkoigl.Map({
            container: "map",
            center: [90.3938010872331, 23.821600277500405],
            zoom: 15,
        });

        document.getElementById("location").addEventListener("input", function () {
            let query = this.value;
            let loadingIndicator = document.getElementById("loading");

            if (query.length > 2) {
                console.log('Searching for:', query);
                loadingIndicator.style.display = "block"; // Show loading indicator
                fetch(`/api/proxy/autocomplete?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = "none"; // Hide loading indicator
                        if (data.places) {
                            let suggestions = data.places;
                            console.log('suggestion', suggestions);

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

        // Initialize the marker
        let marker = new bkoigl.Marker({ draggable: true })
            .setLngLat([90.3938010872331, 23.821600277500405])
            .addTo(map);

        // Event listener for marker drag end
        marker.on('dragend', function() {
            const lngLat = marker.getLngLat();
            const longitude = lngLat.lng;
            const latitude = lngLat.lat;

            fetch(`/api/proxy/reverse-geocode?longitude=${longitude}&latitude=${latitude}`)
                .then(response => response.json())
                .then(data => {
                    if (data.place && data.place.address) {
                        const locationInput = document.getElementById("location");
                        const longitudeInput = document.getElementById("longitude");
                        const latitudeInput = document.getElementById("latitude");
                        locationInput.value = data.place.address;
                        longitudeInput.value = longitude;
                        latitudeInput.value = latitude;
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
