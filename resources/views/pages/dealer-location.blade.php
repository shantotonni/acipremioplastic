@extends('layouts.app')

@section('title','Dealer Locator | '.config('app.name'))

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style>
    .dealer-title {
        background: #cc1b7b !important;
        color: white;
        font-size: 27px;
        text-align: center;
    }

    .body-title {
        margin-top: 1.5rem;
        font-size: 22px;
        font-weight: 500;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-md-4, .col-md-8 {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    @media (min-width: 768px) {
        .col-md-4 {
            flex: 0 0 30%;
            max-width: 30%;
        }

        .col-md-8 {
            flex: 0 0 70%;
            max-width: 70%;
        }
    }

    .form-group {
        margin-bottom: 1rem;
    }

    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: 700;
        width: 35%;
        font-size: 15px;
    }

    .form-control {
        width: 60%;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
    }
    .select2-container .select2-selection--single {
        height: 34px;
        /* width: 60%; */
    }
    .list-border-first{
        border-top: 2px solid #9d9d9d;
        border-bottom: 2px solid #9d9d9d;
        padding: 10px;
    }
    .list-border-next{
        border-bottom: 2px solid #9d9d9d;
        padding: 10px;
    }
    .dealer-list{
        max-height: 300px;
        overflow-y: auto;
    }
    .location-container{
        margin-top: 2rem;
    }
    .select2-container {
        width: 300px !important;
    }
    .dealer-list::-webkit-scrollbar{
      width:5px;
      background-color:#9d9d9d;
    }

    .dealer-list::-webkit-scrollbar-thumb{
        background: #cc1b7b ;
        border-radius:5px;
    }
</style>
@endpush

@section('content')
    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page dealer-page">
                    <div class="dealer-title">
                        <strong>DEALER LOCATOR <i class="fas fa-check"></i> </strong>
                    </div>
                    <div class="page-body">
                        <div class="body-title">
                            <p style="text-align: center;">
                                Choose Your district, Select your upazila and desire product group then find the
                                full address of your closest dealer shop
                            </p>
                        </div>
                        <div class="row" style="margin-top: 3rem">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="DistrictCode">District:</label>
                                    <select class="form-select select2" name="DistrictCode" id="DistrictCode">
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->DistrictCode }}">{{ $district->DistrictName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="UpazillaCode">Upazila:</label>
                                    <select class="form-select select2" id="UpazillaCode" name="UpazillaCode">
                                        <option value="">Select Upazila</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ProductGroup">Product Group:</label>
                                    <select class="form-select select2" id="ProductGroup" name="ProductGroup">
                                        <option value="">Select Product Group</option>
                                        <option value="Furniture and Household">Furniture and Household</option>
                                        <option value="Toys">Toys</option>
                                    </select>
                                </div>

                                <div class="location-container">
                                    <p id="result-count" style="margin-bottom:1rem">0 results found</p>
                                    <div class="dealer-list" id="dealer-list">
                                        <!-- Dealers will be appended here -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="map" style="height: 500px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('#DistrictCode').select2({
        placeholder: "Select a District",
        allowClear: true
    });
    $('#UpazillaCode').select2({
        placeholder: "Select a Upazila",
        allowClear: true
    });
    $('#ProductGroup').select2({
        placeholder: "Select a Product Group",
        allowClear: true
    });
    getDealerLocation();

    //initMap();

    $("#DistrictCode").change(function() {
        var district = $("#DistrictCode").val();
        $.ajax({
            type: "POST",
            url: "{{ url('district/wise/thana') }}",
            data: { district: district }
        }).done(function(data) {
            $("#UpazillaCode").html(data);
            getDealerLocation();
        });
    });

    $("#UpazillaCode").change(function() {
        getDealerLocation();
    });

    $("#ProductGroup").change(function() {
        getDealerLocation();
    });

    function getDealerLocation() {
        var district = $("#DistrictCode").val();
        var upazila = $("#UpazillaCode").val();
        var productGroup = $("#ProductGroup").val();
        $.ajax({
            type: "POST",
            url: "{{ url('get-dealer') }}",
            data: {
                district: district,
                upazila: upazila,
                productGroup: productGroup,
            }
        }).done(function(data) {
            let dealers = data.dealers;

            // Update the dealer list
            let dealerListHtml = '';
            dealers.forEach((dealer, index) => {
                let borderClass = index === 0 ? 'list-border-first' : 'list-border-next';
                dealerListHtml += `
                    <div class="${borderClass}">
                        <div class="dealer-info">
                            <h2>${dealer.Name}</h2>
                            <p>${dealer.Address}</p>
                            <p>${dealer.Phone}</p>
                        </div>
                    </div>
                `;
            });
            $('#dealer-list').html(dealerListHtml);
            $('#result-count').text(`${dealers.length} results found`);

            // Initialize the map with the dealers data
            initMap(dealers);
        });
    }

    function initMap(dealers) {
        const defaultLatLng = { lat: 23.685, lng: 90.3563 }; // Center of Bangladesh

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: defaultLatLng
        });

        const infoWindow = new google.maps.InfoWindow();

        if (dealers.length > 0) {
            const bounds = new google.maps.LatLngBounds();

            dealers.forEach(dealer => {
                const latLng = new google.maps.LatLng(parseFloat(dealer.Latitude), parseFloat(dealer.Longitude));
                bounds.extend(latLng);

                const marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: dealer.Name
                });

                // Update the content string to include phone number
                const contentString = `
                    <div>
                        <strong>${dealer.Name}</strong><br>
                        ${dealer.Address}<br>
                        <strong>Phone:</strong> ${dealer.Phone}
                    </div>
                `;

                // Add click event listener to the marker
                marker.addListener('click', () => {
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marker);
                });

                // Optional: Add hover effect
                marker.addListener('mouseover', () => {
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marker);
                });
            });

            map.fitBounds(bounds);

            google.maps.event.addListenerOnce(map, 'bounds_changed', () => {
                const currentZoom = map.getZoom();
                if (currentZoom > 15) {
                    map.setZoom(15);
                }
            });
        }
    }
});

</script>
@endpush
