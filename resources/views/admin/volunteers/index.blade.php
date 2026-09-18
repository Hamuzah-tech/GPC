@extends('layouts.admin')
@section('title', 'Volunteers')
@section('heading', 'Volunteers')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Volunteer applications</h3>
    </div>
    <div class="table-scroll">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Email</th><th>Location</th><th>CV</th><th></th></tr></thead>
            <tbody>
            @forelse($applications as $application)
                <tr>
                    <td>{{ $application->full_name }}</td>
                    <td>{{ $application->email }}</td>
                    <td>{{ $application->location }}</td>
                    <td>
                        @if($application->cv_path)
                            <x-admin.icon-link :href="route('admin.volunteers.cv', $application)" icon="arrow-down-tray" label="Download CV" tone="view" />
                        @else
                            —
                        @endif
                    </td>
                    <td class="admin-actions">
                        <x-admin.icon-link :href="route('admin.volunteers.show', $application)" icon="eye" label="View" tone="view" />
                        <x-admin.icon-delete :action="route('admin.volunteers.destroy', $application)" message="Delete this application?" />
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No applications yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $applications->links() }}</div>
</div>
@endsection
