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
                                        <input type="text" class="form-control bksearch" id="address"/>
                                        <div class="bklist"></div>
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
    <link
        rel="stylesheet"
        href="https://cdn.barikoi.com/bkoi-gl-js/dist/bkoi-gl.css"
    />
    <script src="https://cdn.barikoi.com/bkoi-gl-js/dist/bkoi-gl.js"></script>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/barikoi/barikoi-js@b6f6295467c19177a7d8b73ad4db136905e7cad6/dist/barikoi.min.css"
    />
    <script>
        bkoigl.accessToken = "bkoi_0f0c0e2aaed92fda43a85d29493d69776ef1c810e8f3d425f0b90fed001bef50"; // required
        new bkoigl.Map({
            container: "map",
            center: [90.3938010872331, 23.821600277500405],
            zoom: 12,
        });
    </script>
    <script>
        bkoigl.accessToken = "bkoi_664be9ea6285a489c570bccb707e8f705720d213d832837ac176219bdbe0a218"; // Replace with Barikoi API Key

        document.getElementById("address").addEventListener("input", function () {
            console.log('test');
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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script
        src="https://cdn.jsdelivr.net/gh/barikoi/barikoi-js@b6f6295467c19177a7d8b73ad4db136905e7cad6/dist/barikoi.min.js?key:bkoi_0f0c0e2aaed92fda43a85d29493d69776ef1c810e8f3d425f0b90fed001bef50"></script>

@endsection
