@extends('backend.layouts.master')

@section('title')
    Dealers Page - Dealer
@endsection

@section('admin-content')

    <!-- Page title area start -->
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
    <!-- Page title area end -->

    <div class="main-content-inner">
        <form action="{{ route('admin.dealers.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Left column for form inputs -->
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-md-12 mt-5 mb-3">
                            <div class="card">
                                <div class="p-4">
                                    <h4>Create Dealer</h4>

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
                                        <label for="owner_name">Owner Name</label>
                                        <input type="text" class="form-control" value="{{ old('owner_name') }}" name="owner_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="zone">Zone</label>
                                        <input type="text" class="form-control" value="{{ old('zone') }}" name="zone">
                                    </div>

                                    <div class="form-group">
                                        <label for="dealer_code">Dealer Code</label>
                                        <input type="text" class="form-control" value="{{ old('dealer_code') }}" name="dealer_code">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" value="{{ old('email') }}" name="email">
                                    </div>

                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" value="{{ old('website') }}" name="website">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Mobile</label>
                                        <input type="text" class="form-control" value="{{ old('mobile') }}" name="mobile">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" value="{{ old('address') }}" name="address">
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
                                        <div id="map" style="width: 100%; height: 400px; background-color: yellow;"></div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Dealer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right column for additional content -->
                <div class="col-lg-3">
                    <div class="card mt-5 mb-3" style="height: 400px; background-color: white;">
                        <div class="p-4">
                            <div class="form-group">
                                <label for="average_sales">Average Sales</label>
                                <input type="text" class="form-control" value="{{ old('average_sales') }}" name="average_sales">
                            </div>

                            <div class="form-group">
                                <label for="market_size">Market Size</label>
                                <input type="text" class="form-control" value="{{ old('market_size') }}" name="market_size">
                            </div>

                            <div class="form-group">
                                <label for="market_share">Market Share</label>
                                <input type="text" class="form-control" value="{{ old('market_share') }}" name="market_share">
                            </div>

                            <div class="form-group">
                                <label for="competition_brand">Competition Brand</label>
                                <input type="text" class="form-control" value="{{ old('competition_brand') }}" name="competition_brand">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts for map and autocomplete -->
    <script>
        bkoigl.accessToken = "{{ env('BARIKOI_API_KEY') }}";

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                console.log(latitude)
                console.log(longitude)
                initializeMap(latitude, longitude);
                fetchLocationName(latitude, longitude);
            },
            (error) => {
                console.error("Error fetching location:", error);
            },
            { enableHighAccuracy: true } // Request high-accuracy location
        );


        function initializeMap(latitude, longitude) {
            const map = new bkoigl.Map({
                container: "map",
                center: [longitude, latitude],
                zoom: 15,
            });

            let marker = new bkoigl.Marker({ draggable: true })
                .setLngLat([longitude, latitude])
                .addTo(map);

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

        document.addEventListener("DOMContentLoaded", getCurrentLocation);
    </script>

    <!-- Styling for autocomplete suggestions -->
    <style>
        .bklist {
            position: absolute;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ddd;
            width: 100%;
            display: none;
        }
        .bklist .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }
        .bklist .suggestion-item:hover {
            background-color: #f0f0f0;
        }
    </style>

@endsection
