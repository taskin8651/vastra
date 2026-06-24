@extends('layouts.admin')
@section('page-title', 'Audiences')
@section('content')
<div class="admin-page-head"><div><h2 class="admin-page-title">Audiences</h2><p class="admin-page-subtitle">Top-level customer groups: Men, Women and Kids.</p></div><a href="{{ route('admin.audiences.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Add Audience</a></div>
<div class="page-card"><div class="page-card-table"><table class="min-w-full"><thead><tr><th>Name</th><th>Slug</th><th>Order</th><th>Status</th><th></th></tr></thead><tbody>@forelse($audiences as $audience)<tr><td>{{ $audience->name }}</td><td>{{ $audience->slug }}</td><td>{{ $audience->sort_order }}</td><td>{{ $audience->is_active ? 'Active' : 'Hidden' }}</td><td class="action-row"><a class="btn-outline" href="{{ route('admin.audiences.edit', $audience) }}">Edit</a><form method="POST" action="{{ route('admin.audiences.destroy', $audience) }}">@csrf @method('DELETE')<button class="btn-outline btn-outline-danger" onclick="return confirm('Delete this audience?')">Delete</button></form></td></tr>@empty<tr><td colspan="5">No audiences yet.</td></tr>@endforelse</tbody></table></div></div>
@endsection
