@extends('layouts.admin')
@section('title', 'FAQs')
@section('heading', 'FAQs')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Frequently asked questions</h3>
        <a href="{{ route('admin.faqs.create') }}" class="btn-add">
            <x-icon name="plus" /> Add FAQ
        </a>
    </div>
    <table class="admin-table">
        <thead><tr><th>Question</th><th></th></tr></thead>
        <tbody>
        @forelse($faqs as $faq)
            <tr>
                <td>{{ $faq->question }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route('admin.faqs.edit', $faq)" icon="pencil" label="Edit" />
                    <x-admin.icon-delete :action="route('admin.faqs.destroy', $faq)" message="Delete this FAQ?" />
                </td>
            </tr>
        @empty
            <tr><td colspan="2">No FAQs yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
