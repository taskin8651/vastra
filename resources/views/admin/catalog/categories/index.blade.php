@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')

<div class="admin-page-head">
    <div>
        <h2 class="admin-page-title">Categories</h2>
        <p class="admin-page-subtitle">
            Manage all product categories and their linked audiences
        </p>
    </div>

    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i>
        Add Category
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <p class="stat-label">Total Categories</p>
        <p class="stat-value">{{ $categories->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Active</p>
        <p class="stat-value">{{ $categories->where('is_active', true)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Hidden</p>
        <p class="stat-value">{{ $categories->where('is_active', false)->count() }}</p>
    </div>

    <div class="stat-card">
        <p class="stat-label">Added Today</p>
        <p class="stat-value">
            {{ $categories->where('created_at', '>=', now()->startOfDay())->count() }}
        </p>
    </div>
</div>

<div class="page-card">
    <div class="page-card-header">
        <p class="page-card-title">All Categories</p>

        <span class="page-card-note">
            <i class="fas fa-info-circle"></i>
            Categories are linked to one audience
        </span>
    </div>

    <div class="page-card-table">
        <table class="min-w-full datatable datatable-Category">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Audience</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Sort Order</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($categories as $category)
                    <tr data-entry-id="{{ $category->id }}">
                        <td></td>

                        <td>
                            <span class="id-text">#{{ $category->id }}</span>
                        </td>

                        <td>
                            <div class="inline-flex-center">

                                @if($category->image_url)
                                    <img src="{{ $category->image_url }}"
                                         alt="{{ $category->name }}"
                                         style="width:42px;height:42px;border-radius:12px;object-fit:cover;background:#F1F5F9;">
                                @else
                                    @php
                                        $colors = [
                                            '#4F46E5',
                                            '#0EA5E9',
                                            '#10B981',
                                            '#F59E0B',
                                            '#EF4444',
                                            '#8B5CF6',
                                            '#EC4899',
                                            '#14B8A6'
                                        ];

                                        $color = $colors[$category->id % count($colors)];
                                    @endphp

                                    <div class="avatar-circle" style="background: {{ $color }};">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <p class="table-main-text">{{ $category->name }}</p>

                                    <p class="table-sub-text">
                                        {{ optional($category->audience)->name ?? 'No Audience' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($category->audience)
                                <span class="role-tag">
                                    {{ $category->audience->name }}
                                </span>
                            @else
                                <span style="font-size:12px; color:#94A3B8;">—</span>
                            @endif
                        </td>

                        <td style="color:#475569;">
                            {{ $category->slug }}
                        </td>

                        <td>
                            @if($category->is_active)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot status-success"></span>
                                    <span style="font-size:12.5px; color:#166534;">
                                        Active
                                    </span>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-dot status-warning"></span>
                                    <span style="font-size:12.5px; color:#92400E;">
                                        Hidden
                                    </span>
                                </div>
                            @endif
                        </td>

                        <td>
                            <span class="id-text">
                                {{ $category->sort_order ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <div class="action-row">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="btn-outline btn-outline-edit">
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-outline btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($categories->count() === 0)
            <div style="padding:22px;text-align:center;color:#94A3B8;font-size:14px;">
                No categories yet.
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
@parent

<script>
$(function () {
    if (typeof initAdminDataTable === 'function') {
        initAdminDataTable('.datatable-Category', {
            canDelete: false,
            massDeleteUrl: '',
            deleteText: 'Delete selected',
            zeroSelectedText: 'No rows selected',
            confirmText: 'Are you sure?',
            searchPlaceholder: 'Search categories...',
            infoText: 'Showing _START_–_END_ of _TOTAL_ categories'
        });
    }
});
</script>

@endsection