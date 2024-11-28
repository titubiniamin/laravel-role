@extends('backend.layouts.master')

@section('title')
    {{ __('Highwall - User Panel') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ __('Users') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('All Users') }}</span></li>
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
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">{{ __('Highwalls') }}</h4>
                    <p class="float-right mb-2">
                        @if (auth()->user()->can('admin.edit'))
                            <a class="btn btn-primary text-white" href="{{ route('admin.highwalls.create') }}">
                                {{ __('Create New Highwall') }}
                            </a>
                        @endif
                            @if(auth()->user()->can('highwall.export'))
                                <a class="btn btn-warning text-white" href="{{ route('admin.highwalls.export') }}">
                                    {{ __('Export Highwall') }}
                                </a>
                            @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="data-tables">
                            @include('backend.layouts.partials.messages')
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Type') }}</th>
                                    <th> {{ __('Brand') }}</th>
                                    <th> {{ __('Location') }}</th>
                                    <th> {{ __('Start Date') }}</th>
                                    <th> {{ __('End Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($highwalls as $highwall)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>
                                            @if($highwall == null || empty($highwall->image))
                                                <img id="image-preview" src="{{ asset('storage/blank.jpg') }}" alt="Highwall Image" style="width: 100%; max-height: 200px; object-fit: cover; display: block;">
                                            @else
                                                <img id="image-preview" src="{{ asset('storage/' . $highwall->image) }}" alt="Highwall Image" style="width: 100%; max-height: 200px; object-fit: cover; display: block;">
                                            @endif

                                        </td>
                                        <td>{{ $highwall->name }}</td>
                                        <td>
                                            @if($highwall->type === 'cold_store')
                                                {{ __('Cold Store') }}
                                            @elseif($highwall->type === 'high_raise')
                                                {{ __('High Raise') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($highwall->brand === 'fresh_super_cement')
                                                {{ __('Fresh Super Cement') }}
                                            @elseif($highwall->brand === 'dhalai_special_cement')
                                                {{ __('Dhalai Special Cement') }}
                                            @elseif($highwall->brand === 'meghnacem_delux_cement')
                                                {{__('Meghnacem Deluxe Cement')}}
                                            @endif
                                        </td>
                                        <td>{{ $highwall->location }}</td>
                                        <td>{{ $highwall->start_date }}</td>
                                        <td>{{ $highwall->end_date }}</td>
                                        <td>
                                            @if (auth()->user()->can('highwall.edit'))
                                                <a class="btn btn-success text-white" href="{{ route('admin.highwalls.edit', $highwall->id) }}">Edit</a>
                                            @endif

                                            @if (auth()->user()->can('highwall.delete'))
                                                <a class="btn btn-danger text-white" href="javascript:void(0);"
                                                   onclick="event.preventDefault(); if(confirm('Are you sure you want to delete?')) { document.getElementById('delete-form-{{ $highwall->id }}').submit(); }">
                                                    {{ __('Delete') }}
                                                </a>

                                                <form id="delete-form-{{ $highwall->id }}" action="{{ route('admin.highwalls.destroy', $highwall->id) }}" method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
</div>
@endsection

@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

     <script>
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true,
                columnDefs: [
                    { width: "5%", targets: 0 },   // Sl column
                    { width: "5%", targets: 1 },   // image column
                    { width: "20%", targets: 2 },  // Name column
                    { width: "10%", targets: 3 },  // type column
                    { width: "10%", targets: 4 },  // brand column
                    { width: "30%", targets: 5 },  // location column
                    { width: "10%", targets: 6 },  // start column
                    { width: "10%", targets: 7 },  // end column
                    { width: "5%", targets: 8 }   // Action column
                ]
            });
        }
     </script>


@endsection
