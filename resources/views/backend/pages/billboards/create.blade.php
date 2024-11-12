@extends('backend.layouts.master')

@section('title')
    Billboards Page - Billboard
@endsection

@section('admin-content')

    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Billboards</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li><span>Billboards</span></li>
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
        <form action="{{ route('admin.billboards.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Left column for form inputs -->
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-md-12 mt-5 mb-3">
                            <div class="card">
                                <div class="p-4">
                                    <h4>Create Billboard</h4>

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

                                    <!-- Flash message fade-out script -->
                                    <script>
                                        setTimeout(function() {
                                            const flashMessage = document.getElementById('flash-message');
                                            if (flashMessage) {
                                                flashMessage.style.transition = 'opacity 0.5s ease';
                                                flashMessage.style.opacity = '0';
                                                setTimeout(() => flashMessage.remove(), 500);
                                            }
                                        }, 5000);
                                    </script>

                                    <!-- Form fields -->
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="size">Size<span>(Decimal)</span></label>
                                        <input type="text" placeholder="ex:23.45 or 23 .." class="form-control" value="{{ old('size') }}" name="size">
                                    </div>
                                    <div class="form-group">
                                        <label for="brand">Brand</label>
                                        <input type="text" class="form-control" value="{{ old('brand') }}" name="brand">
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select id="type" name="type" class="form-control" style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                            <option value="" disabled selected>Select</option>
                                            <option value="2 side" >2 Side</option>
                                            <option value="unipool">Unipool</option>
                                            <option value="neon">Neon</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="longitude" id="longitude" hidden>
                                        <input type="text" name="latitude" id="latitude" hidden>
                                        <input type="text" name="district" id="district" hidden>
                                        <input type="text" class="form-control bksearch" name="location" id="location" />
                                        <div class="bklist"></div>
                                        <div id="loading" style="display: none;">Loading...</div>
                                    </div>

                                    <div class="form-group">
                                        <div id="map" style="width: 100%; height: 400px; "></div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Billboard</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Your existing script and styles here -->

    <!-- Your existing script and styles here -->


    <script>
        bkoigl.accessToken = "{{ env('BARIKOI_API_KEY') }}";

        let map, marker;

        // Use geolocation to show the current location on map load
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                console.log("Initial location:", latitude, longitude);

                initializeMap(latitude, longitude);
                fetchLocationName(latitude, longitude);
            },
            (error) => {
                console.error("Error fetching location:", error);
                // Fallback coordinates if geolocation fails
                initializeMap(23.821600277500405, 90.3938010872331);
            },
            { enableHighAccuracy: true } // Request high-accuracy location
        );

        function initializeMap(latitude, longitude) {
            map = new bkoigl.Map({
                container: "map",
                center: [longitude, latitude],
                zoom: 15,
            });

            map.addControl(new bkoigl.FullscreenControl());
            map.addControl(new bkoigl.NavigationControl());
            map.addControl(new bkoigl.ScaleControl());

            // Initialize a draggable marker
            marker = new bkoigl.Marker({ draggable: true })
                .setLngLat([longitude, latitude])
                .addTo(map);

            // Update input fields when the marker is dragged
            marker.on('dragend', () => {
                const lngLat = marker.getLngLat();
                fetchLocationName(lngLat.lat, lngLat.lng);
            });
        }

        function fetchLocationName(latitude, longitude) {
            fetch(`/api/proxy/reverse-geocode?longitude=${longitude}&latitude=${latitude}`)
                .then(response => response.json())
                .then(data => {
                    if (data.place && data.place.address) {
                        document.getElementById("location").value = data.place.address;
                        document.getElementById("longitude").value = longitude;
                        document.getElementById("latitude").value = latitude;
                        document.getElementById("district").value = data.place.district;
                    }
                })
                .catch(error => {
                    console.error("Error fetching address:", error);
                });
        }

        document.getElementById("location").addEventListener("input", function () {
            let query = this.value;
            let loadingIndicator = document.getElementById("loading");

            if (query.length > 2) {
                loadingIndicator.style.display = "block";
                fetch(`/api/proxy/autocomplete?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = "none";
                        let suggestionList = document.querySelector('.bklist');
                        suggestionList.innerHTML = '';

                        if (data.places) {
                            data.places.forEach(place => {
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
                                    document.getElementById("district").value = place.district;
                                };
                                suggestionList.appendChild(suggestionItem);
                            });
                        }
                    })
                    .catch(error => {
                        loadingIndicator.style.display = "none";
                        console.error("Error fetching data:", error);
                    });
            } else {
                document.querySelector('.bklist').innerHTML = '';
                loadingIndicator.style.display = "none";
            }
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
