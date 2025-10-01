@extends('layouts.app')

@section('title', 'Videos')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Videos</li>
    </ol>
  </nav>
</div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Videos</h5>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <strong>There were some problems try again later or call support:</strong>
                            <ul class="mb-0 mt-2">
                                <li>{{ session('error') }}</li>
                            </ul>
                        </div>
                    @endif
                    @session('success')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endsession
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                        Add New Video
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.Video.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                <h5 class="modal-title">Add New Video</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="videoTitle" class="form-label">Video Title</label>
                                        <input type="text" class="form-control" id="videoTitle" name="title" required>

                                    </div>

                                    <div class="mb-3">
                                        <label for="videoDesc" class="form-label">Video Description</label>
                                        <input type="text" class="form-control" id="videoDesc" name="description">

                                    </div>

                                    <div class="mb-3">
                                        <label for="videoLink" class="form-label">Video</label>
                                        <input type="file" class="form-control" id="videoLink" name="video" accept="video/*">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div><!-- End Vertically centered Modal-->
                </div>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead>
                  <tr>
                    <th>Video</th>
                    <th>
                      <b>T</b>itle
                    </th>
                    <th>Description</th>
                    <th data-type="date" data-format="YYYY/DD/MM">Created Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>
                            @if($item->video)
                                <video src="{{ asset('storage/' . $item->video) }}" alt="{{ $item->title }}" width="50" controls></video>
                            @else
                                <span class="text-muted">No Video</span>
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->created_at->format('Y/m/d') }}</td>
                        <td>
                            <!-- Edit Button triggers modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $item->id }}">
                                Edit
                            </button>

                            <!-- Edit Brand Modal -->
                            <div class="modal fade" id="editBrandModal{{ $item->id }}" tabindex="-1" aria-labelledby="editBrandModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.Video.edit', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editBrandModalLabel{{ $item->id }}">Edit Video</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editBodyImage{{ $item->id }}" class="form-label">Update Video</label>
                                                    <input type="file" class="form-control" id="editBodyImage{{ $item->id }}" name="video" accept="video/*">
                                                </div>

                                                @if($item->video)
                                                    <div class="mb-3">
                                                        <video src="{{ asset('storage/' . $item->video) }}" alt="{{ $item->title }}" width="70" controls></video>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="editBrandName{{ $item->id }}" class="form-label">Video Title</label>
                                                    <input type="text" class="form-control" id="editBrandName{{ $item->id }}" name="title" value="{{ $item->title }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editBrandName{{ $item->id }}" class="form-label">Video Description</label>
                                                    <input type="text" class="form-control" id="editBrandName{{ $item->id }}" name="description" value="{{ $item->description }}">
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.Video.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            </td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
             <div class="d-flex justify-content-center mt-3">
                    {{ $data->links('pagination::bootstrap-5') }}
            </div>
            </div>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

@endsection
