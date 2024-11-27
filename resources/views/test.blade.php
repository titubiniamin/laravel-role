<select id="select-data-type">
    <option value="" disabled>Select</option>

</select>
<button onclick="getValue()">Click</button>
<script>

    const dealers= {
    dealer1:{
        "name":"dealer-1",
        "district":"Dhaka"
    },
    dealer2:{
      "name":"dealer-2",
      "district":"Dhaka"
    }
    }

    const retailers=[
        {
            "name":"retailer1",
            "district":"Dhaka"
        },
        {
            "name":"retailer2",
            "district":"Dhaka"
        }
    ]

    const getValue=()=>{
        console.log('getvaue')
        const dataTypeSelect=document.getElementById('select-data-type')
        dataTypeSelect.innerHTML="";
        const defaultOptions=document.createElement('option')
        defaultOptions.textContent="Select Data Type";
        defaultOptions.disabled=true;
        defaultOptions.selected=true;
        dataTypeSelect.appendChild(defaultOptions);


        retailers.map((value)=>{
            let options=document.createElement('option');
            options.textContent=value.name;
            options.value=value.name;
            dataTypeSelect.appendChild(options)
        })

       const a= retailers.filter((retailer)=>{
           return   retailer.district === 'Dhaka';
        })
        console.log(a)


    }


</script>
