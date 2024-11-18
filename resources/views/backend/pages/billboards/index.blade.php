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
                    <h4 class="header-title float-left">{{ __('Billboards') }}</h4>
                    <p class="float-right mb-2">
                        @if (auth()->user()->can('billboard.create'))
                            <a class="btn btn-primary text-white" href="{{ route('admin.billboards.create') }}">
                                {{ __('Create New Billboard') }}
                            </a>
                        @endif
                        @if(auth()->user()->can('billboard.export'))
                            <a class="btn btn-warning text-white" href="{{ route('admin.billboards.export') }}">
                                {{ __('Export Billboard') }}
                            </a>
                        @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Size') }}</th>
                                    <th> {{ __('Type') }}</th>
                                    <th> {{ __('Brand') }}</th>
                                    <th> {{ __('Location') }}</th>
                                    <th> {{ __('Start Date') }}</th>
                                    <th> {{ __('End Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($billboards as $billboard)
                               <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>
                                        @if($billboard == null || empty($billboard->image))
                                            <img id="image-preview" src="{{ asset('storage/blank.jpg') }}" alt="Billboard Image" style="width: 100%; max-height: 200px; object-fit: cover; display: block;">
                                        @else
                                            <img id="image-preview" src="{{ asset('storage/' . $billboard->image) }}" alt="Billboard Image" style="width: 100%; max-height: 200px; object-fit: cover; display: block;">
                                        @endif

                                    </td>
                                    <td>{{ $billboard->name }}</td>
                                    <td>{{ $billboard->size }}</td>
                                   <td>
                                       @if($billboard->type === 'single_side')
                                           {{ __('Single Side') }}
                                       @elseif($billboard->type === 'unipool')
                                           {{ __('Unipool') }}
                                       @elseif($billboard->type === 'neon')
                                           {{ __('Nion') }}
                                       @endif
                                   </td>
                                   <td>
                                       @if($billboard->brand === 'fresh_super_cement')
                                           {{ __('Fresh Super Cement') }}
                                       @elseif($billboard->brand === 'dhalai_special_cement')
                                           {{ __('Dhalai Special Cement') }}
                                       @elseif($billboard->brand === 'meghnacem_delux_cement')
                                           {{__('Meghnacem Deluxe Cement')}}
                                       @endif
                                   </td>
                                    <td>{{ $billboard->location }}</td>
                                    <td>{{ $billboard->start_date }}</td>
                                    <td>{{ $billboard->end_date }}</td>
                                    <td>
                                        @if (auth()->user()->can('billboard.edit'))
                                            <a class="btn btn-success text-white" href="{{ route('admin.billboards.edit', $billboard->id) }}">Edit</a>
                                        @endif

                                        @if (auth()->user()->can('billboard.delete'))
                                        <a class="btn btn-danger text-white" href="javascript:void(0);"
                                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete?')) { document.getElementById('delete-form-{{ $billboard->id }}').submit(); }">
                                            {{ __('Delete') }}
                                        </a>

                                        <form id="delete-form-{{ $billboard->id }}" action="{{ route('admin.billboards.destroy', $billboard->id) }}" method="POST" style="display: none;">
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
                    { width: "6%", targets: 3 },  // size column
                    { width: "10%", targets: 4 },  // type column
                    { width: "10%", targets: 5 },  // brand column
                    { width: "30%", targets: 6 },  // location column
                    { width: "10%", targets: 7 },  // start column
                    { width: "10%", targets: 8 },  // end column
                    { width: "5%", targets: 9 }   // Action column
                ]
            });
        }
     </script>


@endsection
