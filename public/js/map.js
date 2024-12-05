function addCentralPoints(points) {
    points.forEach(point => {
        const marker = new bkoigl.Marker({ element: createCustomMarkerElement(centralIconUrl) })
            .setLngLat([point.longitude, point.latitude])
            .setPopup(new bkoigl.Popup().setHTML(`
                <div style="background-color: lightblue; padding: 10px;">
                    <div><strong>Name:</strong> ${point.name}</div>
                    <div><strong>Coordinates:</strong> ${point.latitude}, ${point.longitude}</div>
                </div>
            `))
            .addTo(map);

        centralPointMarkerLayer.push(marker); // Save the marker to the array
    });
}

document.getElementById('select-district').addEventListener('change', function () {
    const selectElement = document.getElementById('select-district');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const selectedCentralPoint = selectedOption.getAttribute('data-district');
    console.log('Selected Central Point:', selectedCentralPoint);

    const allPopulatedDistrictsWithData = getPopulatedDistrictDropdownList();

    if (selectedCentralPoint === 'all') {
        map.flyTo({
            center: [90.3938010872331, 23.821600277500405], // Center of the region
            zoom: 6.5,
            essential: true
        });
        clearCentralPoints();
        addCentralPoints(allPopulatedDistrictsWithData);
    } else {
        const singleDistrictWithData = allPopulatedDistrictsWithData.filter(district => district.district === selectedCentralPoint);

        if (singleDistrictWithData.length > 0) {
            const district = singleDistrictWithData[0];
            map.flyTo({
                center: [district.longitude, district.latitude], // Fly to the specific district
                zoom: 11, // Adjust as needed
                essential: true
            });
        } else {
            console.warn("No data found for the selected district.");
        }
        clearCentralPoints();
        addCentralPoints(singleDistrictWithData);
    }
});
function getPopulatedDistrictDropdownList() {
    const selectedCentralPoint = document.getElementById('select-district').value;
    const allDistricts = Array.from(document.querySelectorAll('#select-district option'))
        .map(option => option.getAttribute('data-district'));

    // Get valid districts by filtering out null and empty values
    const validDistricts = allDistricts.filter(district => district !== null && district !== '');

    // Return filtered districts directly
    return districts.filter(districtData => validDistricts.includes(districtData.district));
}

function createCustomMarkerElement(iconUrl) {
    const markerElement = document.createElement('div');
    markerElement.className = 'marker';
    markerElement.style.backgroundImage = `url(${iconUrl})`;
    markerElement.style.backgroundSize = 'contain';
    markerElement.style.width = '30px';
    markerElement.style.height = '30px';
    return markerElement;
}
function clearCentralPoints() {
    centralPointMarkerLayer.forEach(marker => marker.remove()); // Remove each marker
    centralPointMarkerLayer = []; // Reset the array
}
// Add all central points

function addDistrictMarkers(dataType, district) {
    let filteredData = [];

    // Adjust for "All" district or empty value
    if (district === '' || district === 'all') {
        // For "all", include all data, no filter
        filteredData = dataType === 'dealers' ? dealers :
            dataType === 'retailers' ? retailers :
                dataType === 'billboards' ? billboards :
                    dataType === 'shopsigns' ? shopsigns :
                        dataType === 'highwalls' ? highwalls : [];
    } else {
        // Filter by district if specific district is selected
        filteredData = dataType === 'dealers' ? dealers.filter(dealer => dealer.district === district) :
            dataType === 'retailers' ? retailers.filter(retailer => retailer.district === district) :
                dataType === 'billboards' ? billboards.filter(billboard => billboard.district === district) :
                    dataType === 'shopsigns' ? shopsigns.filter(shopsign => shopsign.district === district) :
                        dataType === 'highwalls' ? highwalls.filter(highwall => highwall.district === district) : [];
    }

    // Add filtered markers to the map
    filteredData.forEach(point => {
        let iconUrl;
        if (dataType === 'billboards') {
            iconUrl = billboardIcons[point.brand] || billboardIconUrl;
        } else if (dataType === 'shopsigns') {
            iconUrl = shopsignIcons[point.brand] || shopsignIconUrl;
        } else if (dataType === 'highwalls') {
            iconUrl = highwallIcons[point.brand] || highwallIconurl;
        } else {
            iconUrl = dataType === 'dealers' ? dealerIconUrl : retailerIconUrl;
        }

        const marker = new bkoigl.Marker({element: createCustomMarkerElement(iconUrl)})
            .setLngLat([point.longitude, point.latitude]);

        // Add popup and click event logic as before
        marker.getElement().addEventListener('click', function () {
            const selectedCentralPointId = document.getElementById('select-district').value;
            if (selectedCentralPointId && selectedCentralPointId !== 'all') {
                const selectedCentralPoint = centralPoints.find(point => point.id == selectedCentralPointId);
                const distance = calculateDistance(
                    selectedCentralPoint.latitude, selectedCentralPoint.longitude,
                    point.latitude, point.longitude
                );

                const popupContent = `
                    <div style="background-color: lightblue; padding: 10px;">
                        <div><strong>Name: </strong>${point.name}</div>
                        <div><strong>Location:</strong> ${point.location || 'N/A'}</div>
                        <div><strong>Distance from ${selectedCentralPoint.name}:</strong> ${distance.toFixed(2)} km</div>
                        ${point.size ? `<div><strong>Size:</strong> ${point.size}</div>` : ''}
                        ${point.type ? `<div><strong>Type:</strong> ${point.type}</div>` : ''}
                        ${point.brand ? `<div><strong>Brand:</strong> ${point.brand}</div>` : ''}
                        ${point.average_sales ? `<div><strong>Average Sales:</strong> ${point.average_sales}</div>` : ''}
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
                ${point.type ? `<div><strong>Type:</strong> ${point.type}</div>` : ''}
                ${point.brand ? `<div><strong>Brand:</strong> ${point.brand}</div>` : ''}
                ${point.average_sales ? `<div><strong>Average Sales:</strong> ${point.average_sales}</div>` : ''}
            </div>
        `)).addTo(map);

        // Push marker to appropriate layer based on data type
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
}

document.getElementById('select-data-type').addEventListener('change', function () {
    const selectedDataType = this.value;
    const selectedCentralPoint = document.getElementById('select-district').value;

    // If "All" is selected in the data type dropdown
    if (selectedDataType === 'all') {
        const district = selectedCentralPoint === 'all' ? '' : document.querySelector(`#select-district option[value="${selectedCentralPoint}"]`).getAttribute('data-district');

        // Show all markers (dealers, retailers, billboards)
        addDistrictMarkers('dealers', district);
        addDistrictMarkers('retailers', district);
        addDistrictMarkers('billboards', district);
        addDistrictMarkers('shopsigns', district);
        addDistrictMarkers('highwalls', district);
    } else {
        const district = selectedCentralPoint === 'all' ? '' : document.querySelector(`#select-district option[value="${selectedCentralPoint}"]`).getAttribute('data-district');
        clearDataTypeMarkers();
        addDistrictMarkers(selectedDataType, district);
    }
});
function clearDataTypeMarkers() {
    console.log('this is clearDataTypeMarkers ')
    dealerMarkersLayer.forEach(marker => marker.remove());
    centralPointMarkerLayer.forEach(marker => marker.remove());
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
// Remove all markers after a delay (example)


