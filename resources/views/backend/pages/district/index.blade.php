@extends('backend.layouts.master')

@section('title')
    {{ __('Users - User Panel') }}
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
                    <h4 class="header-title float-left">{{ __('Districts') }}</h4>
                    <p class="float-right mb-2">
                        @if (auth()->user()->can('admin.edit'))
                            <a class="btn btn-primary text-white" href="{{ route('admin.districts.create') }}">
                                {{ __('Create New District') }}
                            </a>
                        @endif
                            @if(auth()->user()->can('district.export'))
                                <a class="btn btn-warning text-white" href="{{ route('admin.districts.export') }}">
                                    {{ __('Export District') }}
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
                                    <th style="width: 105px">{{ __('Sl') }}</th>
                                    <th width="10%">{{ __('Name') }}</th>
                                    <th>{{ __('Average Sales') }}</th>
                                    <th>{{ __('Market Size') }}</th>
                                    <th>{{ __('Market Share') }}</th>
                                    <th>{{ __('Competitor Brand') }}</th>
                                    <th>{{ __('Total Outlets') }}</th>
                                    <th>{{ __('Own Outlets') }}</th>
                                    <th>{{ __('Coverage') }}</th>
                                    <th width="10%">{{ __('Location') }}</th>
                                    <th width="15%">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($districts as $district)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $district->name }}</td>
                                        <td>{{ $district->average_sales }}</td>
                                        <td>{{ $district->market_size }}</td>
                                        <td>{{ $district->market_share }}</td>
                                        <td>{{ $district->competition_brand }}</td>
                                        <td>{{ $district->total_outlets }}</td>
                                        <td>{{ $district->own_outlets }}</td>
                                        <td>{{ $district->coverage }}</td>
                                        <td>{{ $district->location }}</td>
                                        <td>
                                            @if (auth()->user()->can('district.edit'))
                                                <a class="btn btn-success text-white" href="{{ route('admin.districts.edit', $district->id) }}">Edit</a>
                                            @endif

                                            @if (auth()->user()->can('district.delete'))
                                                <a class="btn btn-danger text-white" href="javascript:void(0);"
                                                   onclick="event.preventDefault(); if(confirm('Are you sure you want to delete?')) { document.getElementById('delete-form-{{ $district->id }}').submit(); }">
                                                    {{ __('Delete') }}
                                                </a>

                                                <form id="delete-form-{{ $district->id }}" action="{{ route('admin.districts.destroy', $district->id) }}" method="POST" style="display: none;">
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
                    { width: "10%", targets: 1 },  //
                    { width: "8%", targets: 2 },  //
                    { width: "8%", targets: 3 },  //
                    { width: "8%", targets: 4 },  //
                    { width: "8%", targets: 5 },  //
                    { width: "8%", targets: 6 },  //
                    { width: "8%", targets: 7 },  //
                    { width: "8%", targets: 8 },  //
                    { width: "50%", targets: 9 },  //
                    { width: "20%", targets: 10 }   //
                ]
            });
        }
     </script>


@endsection
