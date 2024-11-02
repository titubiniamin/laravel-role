@extends('backend.layouts.master')

@section('title')
    Dealers Page - Dealer
@endsection

@section('admin-content')
    <!-- Page title area start -->
    <div class="page-title-area">
        <!-- Your existing page title and breadcrumb code here -->
    </div>
    <!-- Page title area end -->

    <div class="main-content-inner">
        <!-- Existing success message display -->
        @if (session('success'))
            <div id="flash-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.dealers.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12 mt-5 mb-3">
                            <div class="card">
                                <div class="p-4">
                                    <h4>Import Dealers</h4>

                                    <!-- Existing error handling and success message code here -->

                                    <!-- Sample File Download Button -->
                                    <div class="mb-3">
                                        <a href="{{ route('admin.dealers.sample-excel') }}" class="btn btn-secondary">Download Sample Excel</a>
                                    </div>

                                    <!-- File Upload Field -->
                                    <div class="form-group">
                                        <label for="file">Upload Dealer Data (Excel)</label>
                                        <input type="file" class="form-control" name="file" accept=".xls,.xlsx" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Import Dealers</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        // Automatically hide the flash message after 5 seconds
        setTimeout(function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.transition = 'opacity 0.5s ease'; // Fade-out transition
                flashMessage.style.opacity = '0'; // Start fading

                setTimeout(() => flashMessage.remove(), 500); // Remove from DOM after fade-out
            }
        }, 5000); // 5-second delay
    </script>
e
@endsection
