@extends('backend.layouts.master')

@section('title')
    Dealers Page - Admin Panel
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
                                        <label for="company">Company</label>
                                        <input type="text" class="form-control" name="company">
                                    </div>

                                    <div class="form-group">
                                        <label for="representative">Representative</label>
                                        <input type="text" class="form-control" name="representative">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="phone">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="text" class="form-control" name="website">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control bksearch"/>
                                        <div class="bklist"></div>
                                    </div>
                                    <div class="form-group">
                                        <div id="map" style="width: 100%;height: 100px;background-color: yellow">Map</div>
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

    <script
        src="https://cdn.jsdelivr.net/gh/barikoi/barikoi-js@b6f6295467c19177a7d8b73ad4db136905e7cad6/dist/barikoi.min.js?key:MTM0MjpTTzVSU0hCOFFO"></script>

    <script>
        const defaultMarker = [23.7104, 90.40744];
        let map = L.map("map");
        map.setView(defaultMarker, 13);
        // Set up the OSM layer
        L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 18,
        }).addTo(map);
        L.marker(defaultMarker).addTo(map);
        Bkoi.onSelect(function () {
            // get selected data from dropdown list
            let selectedPlace = Bkoi.getSelectedData();
            // center of the map
            let center = [selectedPlace.latitude, selectedPlace.longitude];
            // Add marker to the map & bind popup
            map.setView(center, 19);
            L.marker(center).addTo(map).bindPopup(selectedPlace.address);
        });
    </script>
    <script src="https://cdn.barikoi.com/bkoi-gl-js/dist/bkoi-gl.js"></script>
    <script>
        bkoigl.accessToken = "bkoi_664be9ea6285a489c570bccb707e8f705720d213d832837ac176219bdbe0a218"; // Replace with Barikoi API Key

        document.getElementById("address").addEventListener("input", function () {
            let query = this.value;
            if (query.length > 2) {
                fetch(`https://barikoi.com/v1/api/search/autocomplete/${bkoigl.accessToken}/place?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.places) {
                            let suggestions = data.places.map(place => place.address);
                            console.log(suggestions); // Display suggestions or populate autocomplete dropdown
                        }
                    });
            }
        });
    </script>

@endsection
