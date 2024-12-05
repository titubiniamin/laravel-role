///create default district select dropdown

function createDistrictSelect(districts) {
    const selectElement = document.getElementById('select-district');

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.textContent = 'Select';
    selectElement.appendChild(defaultOption);

    const allDistrictsOption = document.createElement('option');
    allDistrictsOption.value = 'all';
    allDistrictsOption.textContent = 'All Districts';
    allDistrictsOption.setAttribute('data-district', 'all');
    selectElement.appendChild(allDistrictsOption);

    districts.forEach(district => {
        const option = document.createElement('option');
        option.value = district.id;
        option.setAttribute('data-lat', district.latitude);
        option.setAttribute('data-lng', district.longitude);
        option.setAttribute('data-district', district.district);
        option.textContent = `${district.district} (${district.market_share})`

        selectElement.appendChild(option);
    });

}

////end create default dropdown

//call default select-district drop down
createDistrictSelect(districts);

document.getElementById('select-district').addEventListener('change', function () {
    const selectedDistrict = this.value;
    checkDealerRetailerRangeAndPopulateDistricts();

    // If "All" is selected
    if (selectedDistrict === 'all') {
        populateDataTypeDropdownAll(dealers, retailers, billboards, shopsigns, highwalls);
        return;
    }

    // If a specific district (e.g., Dhaka) is selected
    const selectedDistrictData = districts.find(district => district.id == selectedDistrict);
    if (selectedDistrictData) {
        populateDataTypeDropdownForDistrict(selectedDistrictData, dealers, retailers, billboards, shopsigns, highwalls);
    }
});


// Populate the dropdown with counts for a specific district (e.g., Dhaka)
document.getElementById('select-district').addEventListener('change', function () {
    const selectedDistrict = this.value;
    const districtShareMinInput = parseFloat(document.getElementById('district-share-min').value);
    const districtShareMaxInput = parseFloat(document.getElementById('district-share-max').value);
    ////coverage inputs
    const coverageShareMinInput = parseFloat(document.getElementById('coverage-share-min').value);
    const coverageShareMaxInput = parseFloat(document.getElementById('coverage-share-max').value);

    // If "All" is selected
    if (selectedDistrict === 'all') {
        ///Get all ranged districts and its entities
        if (!isNaN(districtShareMinInput) && !isNaN(districtShareMaxInput)) {
            const filteredDistricts = getDistrictsWithinRange(districts, districtShareMinInput, districtShareMaxInput);
            const filteredDealers = filterEntitiesByDistricts(filteredDistricts, dealers);
            const filteredRetailers = filterEntitiesByDistricts(filteredDistricts, retailers);
            const filteredBillboards = filterEntitiesByDistricts(filteredDistricts, billboards);
            const filteredShopsigns = filterEntitiesByDistricts(filteredDistricts, shopsigns);
            const filteredHighwalls = filterEntitiesByDistricts(filteredDistricts, highwalls);

            populateDataTypeDropdownAll(filteredDealers, filteredRetailers, filteredBillboards, filteredShopsigns, filteredHighwalls)
            return;

        }
        if (!isNaN(coverageShareMinInput) && !isNaN(coverageShareMaxInput)) {
            const filteredDistricts = getDistrictsWithinCoverageRange(districts, coverageShareMinInput, coverageShareMaxInput);
            const filteredDealers = filterEntitiesByDistricts(filteredDistricts, dealers);
            const filteredRetailers = filterEntitiesByDistricts(filteredDistricts, retailers);
            const filteredBillboards = filterEntitiesByDistricts(filteredDistricts, billboards);
            const filteredShopsigns = filterEntitiesByDistricts(filteredDistricts, shopsigns);
            const filteredHighwalls = filterEntitiesByDistricts(filteredDistricts, highwalls);

            populateDataTypeDropdownAll(filteredDealers, filteredRetailers, filteredBillboards, filteredShopsigns, filteredHighwalls)
            return;

        }
        populateDataTypeDropdownAll(dealers, retailers, billboards, shopsigns, highwalls);
        return;
    }

    // If a specific district (e.g., Dhaka) is selected
    const selectedDistrictData = districts.find(district => district.id == selectedDistrict);
    if (selectedDistrictData) {
        populateDataTypeDropdownForDistrict(selectedDistrictData, dealers, retailers, billboards, shopsigns, highwalls);
    }
});

// Populate the dropdown with counts for all districts
function populateDataTypeDropdownAll(d, r, b, s, h) {
    const dataTypeSelect = document.getElementById('select-data-type');
    dataTypeSelect.innerHTML = ''; // Clear existing options

    // Create and append default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.textContent = 'Select Data Type';
    dataTypeSelect.appendChild(defaultOption);

    // Calculate counts for all data types
    const totalDealers = d.length;
    const totalRetailers = r.length;
    const totalBillboards = b.length;
    const totalShopsigns = s.length;
    const totalHighwalls = h.length;
    console.log(totalDealers, totalRetailers, totalBillboards, totalShopsigns)

    // Add an "All Types" option with the combined count
    const totalAllTypes = totalDealers + totalRetailers + totalBillboards + totalShopsigns + totalHighwalls;
    const allTypesOption = document.createElement('option');
    allTypesOption.value = 'all-types';
    allTypesOption.textContent = `All Types (${totalAllTypes})`;
    dataTypeSelect.appendChild(allTypesOption);

    // Add data type options with counts
    const dataTypes = [
        {name: 'Dealers', count: totalDealers},
        {name: 'Retailers', count: totalRetailers},
        {name: 'Billboards', count: totalBillboards},
        {name: 'Shop Signs', count: totalShopsigns},
        {name: 'Highwalls', count: totalHighwalls}
    ];

    dataTypes.forEach(dataType => {
        const option = document.createElement('option');
        option.value = dataType.name.toLowerCase();
        option.textContent = `${dataType.name} (${dataType.count})`;
        dataTypeSelect.appendChild(option);
    });
}

// Populate the dropdown with counts for a specific district (e.g., Dhaka)
function populateDataTypeDropdownForDistrict(districtData, d, r, b, s, h) {
    const dataTypeSelect = document.getElementById('select-data-type');
    dataTypeSelect.innerHTML = ''; // Clear existing options

    // Create and append default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.textContent = 'Select Data Type';
    dataTypeSelect.appendChild(defaultOption);

    // Filter the data based on the selected district
    const dealersInDistrict = d.filter(dealer => dealer.district === districtData.district);
    const retailersInDistrict = r.filter(retailer => retailer.district === districtData.district);
    const billboardsInDistrict = b.filter(billboard => billboard.district === districtData.district);
    const shopsignsInDistrict = s.filter(shopsign => shopsign.district === districtData.district);
    const highwallsInDistrict = h.filter(highwall => highwall.district === districtData.district);

    // Calculate the total for "All Types"
    const totalInDistrict = dealersInDistrict.length + retailersInDistrict.length + billboardsInDistrict.length
        + shopsignsInDistrict.length + highwallsInDistrict.length;

    // Add the "All Types" option with the combined count for the selected district
    const allTypesOption = document.createElement('option');
    allTypesOption.value = 'all-types';
    allTypesOption.textContent = `All Types (${totalInDistrict})`;
    dataTypeSelect.appendChild(allTypesOption);

    // Add data type options with counts for the selected district
    const dataTypes = [
        {name: 'Dealers', count: dealersInDistrict.length},
        {name: 'Retailers', count: retailersInDistrict.length},
        {name: 'Billboards', count: billboardsInDistrict.length},
        {name: 'Shop Signs', count: shopsignsInDistrict.length},
        {name: 'Highwalls', count: highwallsInDistrict.length}
    ];

    dataTypes.forEach(dataType => {
        const option = document.createElement('option');
        option.value = dataType.name.toLowerCase();
        option.textContent = `${dataType.name} (${dataType.count})`;
        dataTypeSelect.appendChild(option);
    });
}


// Get the elements
const enableDistrictRangeCheckbox = document.getElementById('enable-district-range');
const districtShareMinInput = document.getElementById('district-share-min');
const districtShareMaxInput = document.getElementById('district-share-max');
const selectDistrictDropdown = document.getElementById('select-district');

// Enable or disable inputs and adjust dropdown population based on checkbox state
enableDistrictRangeCheckbox.addEventListener('change', () => {
    if (enableDistrictRangeCheckbox.checked) {
        // Enable range inputs and clear the dropdown
        const selectDataType = document.getElementById('select-data-type');
        selectDataType.innerHTML = '';
        districtShareMinInput.disabled = false;
        districtShareMaxInput.disabled = false;
        districtShareMinInput.style.backgroundColor = '#fff';
        districtShareMaxInput.style.backgroundColor = '#fff';
        districtShareMinInput.value = '';
        districtShareMaxInput.value = '';
        selectDistrictDropdown.innerHTML = ''; // Clear dropdown options
    } else {
        // Disable range inputs and reset the dropdown
        const districtSelect = document.getElementById('select-district');
        districtSelect.innerHTML = '';
        const selectDataType = document.getElementById('select-data-type');
        selectDataType.innerHTML = '';
        districtShareMinInput.disabled = true;
        districtShareMaxInput.disabled = true;
        districtShareMinInput.style.backgroundColor = '#f8f9fa';
        districtShareMaxInput.style.backgroundColor = '#f8f9fa';
        districtShareMinInput.value = '';
        districtShareMaxInput.value = '';
        createDistrictSelect(districts); // Show all districts by default
    }
});

// Filter districts based on the market share range when the min or max input changes
districtShareMinInput.addEventListener('input', filterDistrictsByMarketShare);
districtShareMaxInput.addEventListener('input', filterDistrictsByMarketShare);
//////////Coverage..Start//////
///////////////////////////
// enable-coverage-range
// coverage-share-min
// coverage-share-max
// Get the elements
const enableCoverageRangeCheckbox = document.getElementById('enable-coverage-range');
const coverageShareMinInput = document.getElementById('coverage-share-min');
const coverageShareMaxInput = document.getElementById('coverage-share-max');
// const selectDistrictDropdown = document.getElementById('select-district');

// Enable or disable inputs and adjust dropdown population based on checkbox state
enableCoverageRangeCheckbox.addEventListener('change', () => {
    if (enableCoverageRangeCheckbox.checked) {
        // Enable range inputs and clear the dropdown
        const selectDataType = document.getElementById('select-data-type');
        selectDataType.innerHTML = '';
        coverageShareMinInput.disabled = false;
        coverageShareMaxInput.disabled = false;
        coverageShareMinInput.style.backgroundColor = '#fff';
        coverageShareMaxInput.style.backgroundColor = '#fff';
        coverageShareMinInput.value = '';
        coverageShareMaxInput.value = '';
        selectDistrictDropdown.innerHTML = ''; // Clear dropdown options
    } else {
        // Disable range inputs and reset the dropdown
        const districtSelect = document.getElementById('select-district');
        districtSelect.innerHTML = '';
        const selectDataType = document.getElementById('select-data-type');
        selectDataType.innerHTML = '';
        coverageShareMinInput.disabled = true;
        coverageShareMaxInput.disabled = true;
        coverageShareMinInput.style.backgroundColor = '#f8f9fa';
        coverageShareMaxInput.style.backgroundColor = '#f8f9fa';
        coverageShareMinInput.value = '';
        coverageShareMaxInput.value = '';
        createDistrictSelect(districts); // Show all districts by default
    }
});

// Filter districts based on the market share range when the min or max input changes
coverageShareMinInput.addEventListener('input', filterDistrictsByCoverage);
coverageShareMaxInput.addEventListener('input', filterDistrictsByCoverage);

function filterDistrictsByCoverage() {
    const minShare = parseFloat(coverageShareMinInput.value);
    const maxShare = parseFloat(coverageShareMaxInput.value);
    const districtSelect = document.getElementById('select-district');
    //reset dealer retailer max min
    document.getElementById('dealer-share-min').value='';
    document.getElementById('dealer-share-max').value='';
    document.getElementById('retailer-share-min').value='';
    document.getElementById('retailer-share-max').value='';
    districtSelect.innerHTML='';
    if (!isNaN(minShare) && !isNaN(maxShare) && (minShare <= maxShare)) {
        const filteredDistricts = getDistrictsWithinCoverageRange(districts, minShare, maxShare)

        districtSelect.innerHTML = '';
        createDistrictSelect(filteredDistricts); // Populate the dropdown with filtered districts
        blinkSelectDistrict();
    }


}

//////////////////////////////
//////////Coverage End

function filterDistrictsByMarketShare() {
    console.log('filterDistrictsByMarketShare')
    const minShare = parseFloat(districtShareMinInput.value);
    const maxShare = parseFloat(districtShareMaxInput.value);
    ///reset dealer retailer max min input
    document.getElementById('dealer-share-min').value='';
    document.getElementById('dealer-share-max').value='';
    document.getElementById('retailer-share-min').value='';
    document.getElementById('retailer-share-max').value='';
    const districtSelect = document.getElementById('select-district');
    districtSelect.innerHTML = '';

////if share is greater then get the distict list
    if (!isNaN(minShare) && !isNaN(maxShare) && minShare <= maxShare) {
        const filteredDistricts = getDistrictsWithinRange(districts, minShare, maxShare);
        districtSelect.innerHTML = ''; // Clear existing options
        createDistrictSelect(filteredDistricts); // Populate the dropdown with filtered districts
        blinkSelectDistrict();
    }
}

function getDistrictsWithinRange(districtsList, districtShareMinInput, districtShareMaxInput) {
    return districts.filter(district => {
        return district.market_share >= districtShareMinInput && district.market_share <= districtShareMaxInput;
    });
}

function getDistrictsWithinCoverageRange(districtsList, coverageShareMinInput, coverageShareMaxInput) {
    return districts.filter(district => {
        return district.coverage >= coverageShareMinInput && district.coverage <= coverageShareMaxInput;
    });
}

///when range is selected it will filter all dealer,retailers according this districts
function filterEntitiesByDistricts(districts, entities) {
    // Extract district IDs from the filtered districts
    const districtNamess = districts.map(district => district.district);

    // Filter entities that belong to any of the filtered districts
    return entities.filter(entity => districtNamess.includes(entity.district));
}

/////Dealer Retailer Range part////

document.addEventListener('DOMContentLoaded', function () {
    const dealerCheckbox = document.getElementById('enable-dealer-range');
    const retailerCheckbox = document.getElementById('enable-retailer-range');
    const dealerMinInput = document.getElementById('dealer-share-min');
    const dealerMaxInput = document.getElementById('dealer-share-max');
    const retailerMinInput = document.getElementById('retailer-share-min');
    const retailerMaxInput = document.getElementById('retailer-share-max');
    const dataTypeSelect = document.getElementById('select-data-type');

    dealerCheckbox.addEventListener('change', function () {
        if (this.checked) {
            enableDealerRange();
            disableRetailerRange();
            disableDataTypeSelect();
        } else {
            disableDealerRange(true);
            enableDataTypeSelect();
        }
    });

    retailerCheckbox.addEventListener('change', function () {
        if (this.checked) {
            enableRetailerRange();
            disableDealerRange();
            disableDataTypeSelect();
        } else {
            disableRetailerRange(true);
            enableDataTypeSelect();
        }
    });

    function enableDealerRange() {
        dealerMinInput.disabled = false;
        dealerMaxInput.disabled = false;
        dealerMinInput.style.backgroundColor = '#fff';
        dealerMaxInput.style.backgroundColor = '#fff';
    }

    function disableDealerRange(clearValues = false) {
        dealerMinInput.disabled = true;
        dealerMaxInput.disabled = true;
        dealerMinInput.style.backgroundColor = '#f8f9fa';
        dealerMaxInput.style.backgroundColor = '#f8f9fa';
        dealerMinInput.value = '';
        dealerMaxInput.value = '';
        dealerCheckbox.checked = false;

    }

    function enableRetailerRange() {
        retailerMinInput.disabled = false;
        retailerMaxInput.disabled = false;
        retailerMinInput.style.backgroundColor = '#fff';
        retailerMaxInput.style.backgroundColor = '#fff';
    }

    function disableRetailerRange(clearValues = false) {
        retailerMinInput.disabled = true;
        retailerMaxInput.disabled = true;
        retailerMinInput.style.backgroundColor = '#f8f9fa';
        retailerMaxInput.style.backgroundColor = '#f8f9fa';
        retailerMinInput.value = '';
        retailerMaxInput.value = '';
        retailerCheckbox.checked = false;

    }

    function disableDataTypeSelect() {
        dataTypeSelect.disabled = true;
        dataTypeSelect.style.backgroundColor = '#f8f9fa';
    }

    function enableDataTypeSelect() {
        dataTypeSelect.disabled = false;
        dataTypeSelect.style.backgroundColor = '#fff';
    }
});

function getPopulatedDistrictsFromDropdown() {
    const districtSelect = document.getElementById('select-district');
    const populatedDistricts = [];
    // Loop through all the options in the dropdown
    for (let option of districtSelect.options) {
        // Skip the 'Select' option (empty id and name 'Select')
        if (option.value !== "all" && option.value !== "") {
            // Remove numbers in parentheses from the district name
            const cleanedName = option.textContent.replace(/\(\d+(\.\d+)?\)/, '').trim();
            populatedDistricts.push({
                id: option.value,
                name: cleanedName // Remove numbers in parentheses
            });
        }
    }

    // Get the selected option from the dropdown
    const selectedIndex = districtSelect.selectedIndex;
    const selectedOption = districtSelect.options[selectedIndex];

    // Clean up selected district name (remove the number in parentheses)
    const selectedDistrictName = selectedOption.textContent.replace(/\(\d+(\.\d+)?\)/, '').trim();


    return {
        allDistricts: populatedDistricts,
        selectedDistrict: {
            id: selectedOption.value,
            name: selectedDistrictName
        }
    };
}

function handleDealerShareInput() {
    console.log('handleDealerShareInput')
    const populatedDistricts = getPopulatedDistrictsFromDropdown();
    //populatedDistricts contain : allDistricts and selectedDistrict
    const selectedDistrict = populatedDistricts.selectedDistrict;
    const allDistricts = populatedDistricts.allDistricts;

    const minShare = parseFloat(document.getElementById('dealer-share-min').value) || 0;
    const maxShare = parseFloat(document.getElementById('dealer-share-max').value) || 100;
    const dealerResult = filterDealers(dealers, selectedDistrict, allDistricts, minShare, maxShare)
    if (dealerResult && dealerResult.length > 0) {
        dealerResult.forEach((deal) => {
            console.log('Dealer Name:', deal.name);
        });
    } else {
        console.log('No dealers found.');
    }

}

function handleRetailerShareInput() {
    console.log('handleRetailerShareInput()')

    const populatedDistricts = getPopulatedDistrictsFromDropdown();
    //populatedDistricts contain : allDistricts and selectedDistrict
    const selectedDistrict = populatedDistricts.selectedDistrict;
    const allDistricts = populatedDistricts.allDistricts;

    const minShare = parseFloat(document.getElementById('retailer-share-min').value) || 0;
    const maxShare = parseFloat(document.getElementById('retailer-share-max').value) || 100;
    const retailerResult = filterRetailers(retailers, selectedDistrict, allDistricts, minShare, maxShare)
    if (retailerResult && retailerResult.length > 0) {
        retailerResult.forEach((ret) => {
            console.log('Retailer Name:', ret.name);
        });
    } else {
        console.log('No retailer found.');
    }
}

/////if dealer and retailer
function checkDealerRetailerRangeAndPopulateDistricts() {
    const dealerRangeCheckbox = document.getElementById('enable-dealer-range');
    const dealerShareMinInput = document.getElementById('dealer-share-min');
    const dealerShareMaxInput = document.getElementById('dealer-share-max');

    const retailerRangeCheckbox = document.getElementById('enable-retailer-range');
    const retailerShareMinInput = document.getElementById('retailer-share-min');
    const retailerShareMaxInput = document.getElementById('retailer-share-max');

    // Check if dealer range is enabled and min/max values are provided
    if (dealerRangeCheckbox.checked && !isNaN(dealerShareMinInput.value) && !isNaN(dealerShareMaxInput.value)) {
        // Call the function to get populated districts
        const populatedDistricts = getPopulatedDistrictsFromDropdown();
        // Now you can do further processing with the populatedDistricts (e.g., filter dealers)
    }

///Retailer check
    if (retailerRangeCheckbox.checked && !isNaN(retailerShareMinInput.value) && !isNaN(retailerShareMaxInput.value)) {
        // Call the function to get populated districts
        const populatedDistricts = getPopulatedDistrictsFromDropdown();
    }
}

function filterDealers(dealers, selectedDistrict, allDistricts, minShare, maxShare) {
    if (selectedDistrict.name === "All Districts") {
        // Normalize all district names to lowercase
        const districtNames = allDistricts.map((dist) => dist.name.trim().toLowerCase());


        // Filter dealers by matching district and average_share within the range
        const filteredDealers = dealers.filter((dealer) => {
            // Normalize dealer district to lowercase for comparison
            const dealerDistrict = dealer.district.trim().toLowerCase();

            // Compare normalized district names and check the average share range
            return districtNames.includes(dealerDistrict) &&
                parseFloat(dealer.market_share) >= minShare &&
                parseFloat(dealer.market_share) <= maxShare;
        });

        console.log('Filtered Dealers:', filteredDealers);  // Debugging line
        return filteredDealers;  // Return filtered dealers
    } else {
        // Filter by specific district and average_share range
        return dealers.filter(dealer =>
            dealer.district === selectedDistrict.name &&
            parseFloat(dealer.market_share) >= minShare &&
            parseFloat(dealer.market_share) <= maxShare
        );
    }
}


function filterRetailers(retailers, selectedDistrict, allDistricts, minShare, maxShare) {
    if (selectedDistrict.name === "All Districts") {
        const districtNames = allDistricts.map((dist) => dist.name.trim().toLowerCase());

        const filteredRetailers = retailers.filter((retailer) => {
            const retailerDistrict = retailer.district.trim().toLowerCase();
            return districtNames.includes(retailerDistrict) &&
                parseFloat(retailer.market_share) >= minShare &&
                parseFloat(retailer.market_share) <= maxShare;
        });

        console.log('Filtered Retailers:', filteredRetailers);
        return filteredRetailers;
    } else {
        return retailers.filter(retailer =>
            retailer.district === selectedDistrict.name &&
            parseFloat(retailer.market_share) >= minShare &&
            parseFloat(retailer.market_share) <= maxShare
        );
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Get the elements
    const districtCheckbox = document.getElementById("enable-district-range");
    const coverageCheckbox = document.getElementById("enable-coverage-range");
    const districtMin = document.getElementById("district-share-min");
    const districtMax = document.getElementById("district-share-max");
    const coverageMin = document.getElementById("coverage-share-min");
    const coverageMax = document.getElementById("coverage-share-max");

    // Disable inputs on page load
    districtMin.disabled = true;
    districtMax.disabled = true;
    coverageMin.disabled = true;
    coverageMax.disabled = true;

    // Utility to enable/disable inputs and set background color
    function toggleInputs(inputs, isEnabled) {
        inputs.forEach(input => {
            input.disabled = !isEnabled;
            input.style.backgroundColor = isEnabled ? "#fff" : "#f8f9fa";
            if (!isEnabled) input.value = ""; // Clear values when disabled
        });
    }

    // Handle district checkbox click
    districtCheckbox.addEventListener("change", function () {
        if (this.checked) {
            toggleInputs([districtMin, districtMax], true);
            toggleInputs([coverageMin, coverageMax], false);
            coverageCheckbox.checked = false;
        } else {
            toggleInputs([districtMin, districtMax], false);
        }
    });

    // Handle coverage checkbox click
    coverageCheckbox.addEventListener("change", function () {
        if (this.checked) {
            toggleInputs([coverageMin, coverageMax], true);
            toggleInputs([districtMin, districtMax], false);
            districtCheckbox.checked = false;
        } else {
            toggleInputs([coverageMin, coverageMax], false);
        }
    });
});

function blinkSelectDistrict() {
    const districtSelect = document.getElementById('select-district');

    // Add the blinking class
    districtSelect.classList.add('blink');

    // Remove the blinking class after a few seconds (e.g., 5 seconds)
    setTimeout(() => {
        districtSelect.classList.remove('blink');
    }, 5000);
}
/////////dealer retailer range part end///
