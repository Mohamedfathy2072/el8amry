@extends('layouts.app')

@section('title', 'Schedules')
@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="pagetitle">
        <h1>Schedules</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Schedules</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Schedules</h5>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                                Add New Schedule
                            </button>
                        </div>

                        <!-- Add Modal -->
                        <div class="modal fade" id="addScheduleModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('dashboard.schedules.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Schedule</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Date</label>
                                                <input type="date" name="date" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Times</label>
                                                <div id="times-container">
                                                    <input type="time" name="times[]" class="form-control mb-2" required>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-success" id="add-time">Add Time</button>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="is_available" class="form-check-input" checked>
                                                <label class="form-check-label">Available</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Times</th>
                                    <th>Available</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') }}</td>
                                        <td>
                                            @foreach($schedule->times as $time)
                                                <span class="badge bg-info">{{ \Carbon\Carbon::parse($time->time)->format('H:i') }}</span>

                                            @endforeach
                                        </td>
                                        <td>{{ $schedule->is_available ? '✅' : '❌' }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSchedule{{ $schedule->id }}">Edit</button>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editSchedule{{ $schedule->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="{{ route('dashboard.schedules.update', $schedule->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Schedule</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label>Date</label>
                                                                    <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') }}" required>

                                                                </div>
                                                                <div class="mb-3">
                                                                    <label>Times</label>
                                                                    <div class="times-container">
                                                                        @foreach($schedule->times as $time)
                                                                            <input type="time" name="times[]" value="{{ \Carbon\Carbon::parse($time->time)->format('H:i') }}" class="form-control mb-2" required>

                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button" class="btn btn-sm btn-success add-time-btn">Add Time</button>
                                                                </div>
                                                                <div class="form-check d-flex align-items-center gap-2 mt-3">
                                                                    <input
                                                                        type="checkbox"
                                                                        name="is_available"
                                                                        id="is_available_{{ $schedule->id }}"
                                                                        class="form-check-input m-0"
                                                                        {{ $schedule->is_available ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label mb-0" for="is_available_{{ $schedule->id }}">Available</label>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete -->
                                            <form action="{{ route('dashboard.schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;">
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
                                {{ $schedules->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-time-btn')) {
                const container = e.target.closest('.modal-body').querySelector('.times-container');
                const input = document.createElement('input');
                input.type = 'time';
                input.name = 'times[]';
                input.classList.add('form-control', 'mb-2');
                container.appendChild(input);
            }
        });

        document.getElementById('add-time').addEventListener('click', function () {
            const container = document.getElementById('times-container');
            const input = document.createElement('input');
            input.type = 'time';
            input.name = 'times[]';
            input.classList.add('form-control', 'mb-2');
            container.appendChild(input);
        });
    </script>
@endsection
