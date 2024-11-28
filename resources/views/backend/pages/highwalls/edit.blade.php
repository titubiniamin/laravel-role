@extends('backend.layouts.master')

@section('title')
    Edit Page - Highwall
@endsection

@section('admin-content')

    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Highwalls</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li><span>Highwalls</span></li>
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
        <form action="{{ route('admin.highwalls.update',$highwall->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        <div class="row">
            <!--Left Column-->
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-12 mt-5 mb-3">
                        <div class="card">
                            <div class="p-4">
                                <h4>Update Highwall</h4>

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
                                        <input type="text" class="form-control" value="{{ old('name', $highwall->name) }}" name="name" required>
                                    </div>


                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <select id="brand" name="brand" class="form-control">
                                        <option value="" disabled selected>Select</option>
                                        <option value="fresh_super_cement" {{ $highwall->brand == 'fresh_super_cement' ? 'selected' : '' }}>Fresh Super Cement</option>
                                        <option value="dhalai_special_cement" {{$highwall->brand=='dhalai_special_cement'? 'selected':''}}>Dhalai Special Cement</option>
                                        <option value="meghnacem_delux_cement" {{$highwall->brand=='meghnacem_delux_cement'?'selected':''}}>Meghnacem Delux Cement</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="type">Type</label>
{{--                                    @dd($highwall->type)--}}
                                    <select id="type" name="type" class="form-control" style="height: 40px; font-size: 14px; color: #464A4D; border: 1px solid #dcdcdc; border-radius: 4px; background-color: #fff;">
                                        <option value="" disabled selected>Select</option>
                                        <option value="high_raise" {{ $highwall->type == 'high_raise' ? 'selected' : '' }}>Single Side</option>
                                        <option value="cold_store" {{ $highwall->type == 'cold_store' ? 'selected' : '' }}>Unipool</option>
                                    </select>
                                </div>

                                <!-- Start Date and End Date Fields in one row -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ old('start_date', $highwall->start_date) }}" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date', $highwall->end_date) }}" >
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="longitude" value="{{$highwall->longitude}}" id="longitude" hidden>
                                        <input type="text" name="latitude" value="{{$highwall->latitude}}"  id="latitude" hidden>
                                        <input type="text" name="district" value="{{$highwall->district}}"  id="district" hidden>
                                        <input type="text" class="form-control bksearch" value="{{$highwall->location}}"  name="location" id="location"/>
                                        <div class="bklist"></div>
                                        <div id="loading" style="display: none;">Loading...</div> <!-- Loading indicator -->
                                    </div>

                                    <div class="form-group">
                                        <div id="map" style="width: 100%; height: 400px;"></div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Highwall</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right column for image upload -->
            <div class="col-lg-3">
                <div class="card mt-5 mb-3" style="background-color: white">
                    <div class="p-4">
                        <h4>Upload Image</h4>
                        <div class="form-group mb-4">
                            <label for="image">Select Image</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <div class="mt-3">
                                <img id="image-preview" src="{{ asset('storage/' . $highwall->image) }}" alt="Highwall Image" style="width: 100%; max-height: 200px; object-fit: cover; display: block;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </form>
    </div>

    <!-- Your existing script and styles here -->
    <script>
        bkoigl.accessToken = "{{ env('BARIKOI_API_KEY') }}"; // required

        // Fetch highwall's coordinates from backend
        const highwallLongitude = {{ $highwall->longitude ?? 90.3938010872331 }};
        const highwallLatitude = {{ $highwall->latitude ?? 23.821600277500405 }};
        const highwallLocation = "{{ $highwall->location ?? '' }}";

        const map = new bkoigl.Map({
            container: "map",
            center: [highwallLongitude, highwallLatitude], // Set map center to highwall's coordinates
            zoom: 15,
        });
        map.addControl(new bkoigl.FullscreenControl());
        map.addControl(new bkoigl.NavigationControl());
        map.addControl(new bkoigl.ScaleControl());


        // Initialize the marker at highwall's coordinates
        let marker = new bkoigl.Marker({ draggable: true })
            .setLngLat([highwallLongitude, highwallLatitude])
            .addTo(map);

        // Populate location input field with highwall's location
        document.getElementById("location").value = highwallLocation;
        document.getElementById("longitude").value = highwallLongitude;
        document.getElementById("latitude").value = highwallLatitude;

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
                                    document.getElementById("district").value = place.district;
                                };
                                suggestionList.appendChild(suggestionItem);
                            });
                        }
                    })
                    .catch(error => {
                        loadingIndicator.style.display = "none";
                        console.error('Error fetching data:', error);
                    });
            } else {
                document.querySelector('.bklist').innerHTML = '';

                loadingIndicator.style.display = "none";

            }
        });

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
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Function to update the end date min value based on start date
            function updateEndDateMin() {
                const startDate = startDateInput.value;
                if (startDate) {
                    // Set end date min to be the next day of the selected start date
                    const startDateObj = new Date(startDate);
                    startDateObj.setDate(startDateObj.getDate() + 1); // Set to the next day

                    // Format the date to yyyy-mm-dd
                    const minEndDate = startDateObj.toISOString().split('T')[0];
                    endDateInput.setAttribute('min', minEndDate);
                }
            }

            // Listen for changes on the start date input
            startDateInput.addEventListener('change', updateEndDateMin);

            // Initialize on page load if there's an existing start date
            if (startDateInput.value) {
                updateEndDateMin();
            }
        });

        function previewImage(event) {
            const image = event.target.files[0]; // Get the selected image file
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.querySelector('#image-preview');
                imgElement.src = e.target.result;
                imgElement.style.display = 'block';
            };

            if (image) {
                reader.readAsDataURL(image); // Read the image file as data URL
            }
        }
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
        select.form-control {
            height: auto; /* Ensure it auto-adjusts height */
            line-height: 1.5; /* Adjust line-height for better text display */
            padding: 6px 12px; /* Add padding for better text display */
        }

    </style>
@endsection
