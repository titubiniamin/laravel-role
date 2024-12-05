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


function createCustomMarkerElement(iconUrl) {
const markerElement = document.createElement('div');
markerElement.className = 'marker';
markerElement.style.backgroundImage = `url(${iconUrl})`;
markerElement.style.backgroundSize = 'contain';
markerElement.style.backgroundRepeat = 'no-repeat'; // Prevent the image from repeating
markerElement.style.width = '30px';
markerElement.style.height = '30px';
return markerElement;
}

// Function to add central points to the map (Always visible)
function addCentralPoints(customDistricts) {
console.log('addCentralPoints function')
console.log(customDistricts)
customDistricts.forEach(point => {
const marker = new bkoigl.Marker({element: createCustomMarkerElement(centralIconUrl)})
.setLngLat([point.longitude, point.latitude])
.setPopup(new bkoigl.Popup().setHTML(`
<div style="background-color: lightblue; padding: 10px;">
    <div><strong>Name: </strong>${point.name}</div>
    <div><strong>Location: </strong>${point.location || 'N/A'}</div>
</div>
`))
.addTo(map);
});

}

function getPopulatedDistrictDropdownList() {
const selectedCentralPoint = document.getElementById('select-district').value;
const allDistricts = Array.from(document.querySelectorAll('#select-district option'))
.map(option => option.getAttribute('data-district'));

// Get valid districts by filtering out null and empty values
const validDistricts = allDistricts.filter(district => district !== null && district !== '');

// Return filtered districts directly
return districts.filter(districtData => validDistricts.includes(districtData.district));
}


document.getElementById('select-district').addEventListener('change', function () {
const selectElement = document.getElementById('select-district');  // Get the select element
const selectedOption = selectElement.options[selectElement.selectedIndex];  // Get the selected option
const selectedCentralPoint = selectedOption.getAttribute('data-district');
console.log('Selected Central Point:', selectedCentralPoint);

let allPopulatedDistrictsWithData = getPopulatedDistrictDropdownList();  // Get the list of populated districts with data

// If "All" is selected in the central point dropdown
if (selectedCentralPoint === 'all') {
map.flyTo({
center: [90.3938010872331, 23.821600277500405], // Center of the country/region
zoom: 6.5, // Adjust zoom level to show all points
essential: true // Ensures the animation runs even in non-interactive contexts

});


clearCentralPointMarkers();
addCentralPoints(allPopulatedDistrictsWithData);
}

// Clear the central point markers if any exist
clearCentralPointMarkers();

// If a specific district is selected, filter the districts list
const singleDistrictWithData = allPopulatedDistrictsWithData.filter(singleDistrict =>
singleDistrict.district === selectedCentralPoint
);

// Clear existing markers for data types
clearDataTypeMarkers();

// Fly to the selected district's location and add its marker
if (singleDistrictWithData.length > 0) {
const district = singleDistrictWithData[0]; // Assuming there's only one matching district
map.flyTo({
center: [district.longitude, district.latitude],  // Use the selected district's coordinates
zoom: 11,  // Adjust zoom level to focus on the district
essential: true // Ensures the animation runs even in non-interactive contexts
});

// Add the marker for the selected district
const marker = new bkoigl.Marker({element: createCustomMarkerElement(centralIconUrl)}) // Use the appropriate district icon here
.setLngLat([district.longitude, district.latitude])
.setPopup(new bkoigl.Popup().setHTML(`
<div style="background-color: lightblue; padding: 10px;">
    <div><strong>District: </strong>${district.district}</div>
    <div><strong>Location: </strong>${district.location || 'N/A'}</div>
</div>
`))
.addTo(map);

// Populate the data type dropdown with updated counts for the selected district
}
});

// Function to clear central point markers (assuming you have a layer to manage them)



// Event listener for data type selection
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

// Function to clear all data type markers from the map
function clearCentralPointMarkers() {
console.log('clearCentralPointMarkers',centralPointMarkerLayer);
// Loop through the markers in the central point layer and remove them
if (centralPointMarkerLayer && centralPointMarkerLayer.length > 0) {
centralPointMarkerLayer.forEach(marker => {
marker.remove(); // Remove the marker from the map
});
centralPointMarkerLayer = []; // Reset the array after removal
} else {
console.log('No central point markers to remove.');
}
}

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



