<x-app-layout>
    <x-slot name="pageTitle">My Resources</x-slot>
    <x-slot name="pageSubtitle">Manage your agency's resource inventory</x-slot>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div style="display:flex; gap:0.75rem;">
            @php
                $types = ['personnel','vehicle','equipment','medical','supplies'];
                $typeIcons = ['personnel'=>'👥','vehicle'=>'🚗','equipment'=>'🔧','medical'=>'🏥','supplies'=>'📦'];
            @endphp
            @foreach($types as $t)
                @php $count = $resources->where('type', $t)->count(); @endphp
                <div style="padding:0.4rem 0.8rem; border-radius:var(--radius-md); background:var(--color-bg-secondary); border:1px solid var(--color-border); font-size:0.75rem; color:var(--color-text-secondary);">
                    {{ $typeIcons[$t] }} {{ ucfirst($t) }}: <strong style="color:var(--color-text-primary);">{{ $count }}</strong>
                </div>
            @endforeach
        </div>
        <a href="{{ route('resources.create') }}" class="btn btn-primary">+ Add Resource</a>
    </div>

    <div class="glass-card-static" style="overflow:hidden;">
        @if($resources->count())
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Available</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resources as $resource)
                        <tr>
                            <td>{{ $resource->name }}</td>
                            <td><span class="badge badge-active">{{ $typeIcons[$resource->type] ?? '📦' }} {{ ucfirst($resource->type) }}</span></td>
                            <td>{{ $resource->quantity }}</td>
                            <td>
                                <span style="color:{{ $resource->available_quantity > 0 ? 'var(--color-success)' : 'var(--color-danger)' }}; font-weight:600;">
                                    {{ $resource->available_quantity }}
                                </span>
                            </td>
                            <td><span class="badge badge-{{ $resource->status }}">{{ $resource->status }}</span></td>
                            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $resource->notes ?? '—' }}</td>
                            <td style="text-align:right;">
                                <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                    <a href="{{ route('resources.edit', $resource) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <p class="empty-text">No resources added yet</p>
                <a href="{{ route('resources.create') }}" class="btn btn-primary" style="margin-top:1rem;">Add Your First Resource</a>
            </div>
        @endif
    </div>
</x-app-layout>
