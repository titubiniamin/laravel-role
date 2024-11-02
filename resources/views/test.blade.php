{{--    {{dd($dealers)}}--}}
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="stylesheet"
        href="https://cdn.barikoi.xyz/bkoi-gl-js/dist/bkoi-gl.css"
    />
    <script src="https://cdn.barikoi.xyz/bkoi-gl-js/dist/bkoi-gl.js"></script>
    <style>
        body,
        #map {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
    </style>
    <title>Add Marker To Map</title>
</head>
<body>
<div id="map"></div>

<script>
    bkoigl.accessToken = "bkoi_0f0c0e2aaed92fda43a85d29493d69776ef1c810e8f3d425f0b90fed001bef50"; // required
    const map = new bkoigl.Map({
        container: "map",
        center: [90.3938010872331, 23.821600277500405],
        zoom: 12,
    });

    const dealers = @json($dealers); // Pass the dealer data to JavaScript
    console.log("Dealers data:", dealers); // Check dealer data in console
    console.log("Dealers data:", dealers);

    // Function to add markers for dealers on the map
    function addMarkers(dealers) {
        if (!Array.isArray(dealers)) {
            console.error("Expected dealers to be an array");
            return;
        }

        dealers.forEach(dealer => {
            const longitude = parseFloat(dealer.longitude);
            const latitude = parseFloat(dealer.latitude);

            // Log parsed values and their types
            console.log("Parsed Longitude:", longitude, "Type:", typeof longitude);
            console.log("Parsed Latitude:", latitude, "Type:", typeof latitude);

            // Ensure coordinates are valid
            if (!isNaN(longitude) && !isNaN(latitude)) {
                console.log('this is'+longitude)
                console.log('this is'+latitude)
                const marker = new bkoigl.Marker()
                    .setLngLat([longitude, latitude])
                    .setPopup(new bkoigl.Popup().setText(dealer.name))
                    .addTo(map);
            } else {
                console.error("Invalid coordinates for dealer:", dealer);
            }
        });
    }


    // Add Marker on Map Load
    map.on("load", () => {
        addMarkers(dealers); // Call the addMarkers function to place dealer markers on the map
    });
</script>

</body>
</html>
