<x-app-layout>
    <x-slot name="pageTitle">Messages</x-slot>
    <x-slot name="pageSubtitle">Secure agency communication</x-slot>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem; height:calc(100vh - 180px);">
        <!-- Conversations List -->
        <div class="glass-card-static" style="display:flex; flex-direction:column; overflow:hidden;">
            <div style="padding:1rem; border-bottom:1px solid var(--color-border);">
                <h3 style="font-size:0.9rem; font-weight:700; color:var(--color-text-primary); margin-bottom:0.75rem;">Conversations</h3>
                <!-- New conversation -->
                <form method="GET" style="display:flex; gap:0.5rem;">
                    <select onchange="if(this.value) window.location.href='/messages/'+this.value" class="form-select" style="font-size:0.8rem; padding:0.45rem 0.75rem;">
                        <option value="">Start new chat...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div style="flex:1; overflow-y:auto; padding:0.5rem;">
                @forelse($conversations as $conv)
                    <a href="{{ route('messages.show', $conv) }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; border-radius:var(--radius-md); text-decoration:none; margin-bottom:0.25rem; transition:background 0.2s; {{ request()->route('user') && request()->route('user')->id == $conv->id ? 'background:rgba(217,119,6,0.1); border:1px solid rgba(217,119,6,0.2);' : '' }}" onmouseover="this.style.background='rgba(217,119,6,0.06)'" onmouseout="this.style.background='{{ request()->route('user') && request()->route('user')->id == $conv->id ? 'rgba(217,119,6,0.1)' : 'transparent' }}'">
                        <div style="width:36px; height:36px; border-radius:50%; background:var(--gradient-accent); display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700; color:white; flex-shrink:0;">
                            {{ strtoupper(substr($conv->name, 0, 1)) }}
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:0.85rem; font-weight:600; color:var(--color-text-primary);">{{ $conv->name }}</span>
                                @if($conv->unread_count > 0)
                                    <span style="background:var(--color-danger); color:white; font-size:0.6rem; padding:0.1rem 0.4rem; border-radius:9999px; font-weight:700;">{{ $conv->unread_count }}</span>
                                @endif
                            </div>
                            @if($conv->last_message)
                                <p style="font-size:0.75rem; color:var(--color-text-muted); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ Str::limit($conv->last_message->content, 40) }}
                                </p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="empty-state" style="padding:2rem 1rem;">
                        <div class="empty-icon">💬</div>
                        <p class="empty-text" style="font-size:0.85rem;">No conversations yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="glass-card-static" style="display:flex; align-items:center; justify-content:center;">
            <div class="empty-state">
                <div class="empty-icon">💬</div>
                <p class="empty-text">Select a conversation or start a new one</p>
            </div>
        </div>
    </div>
</x-app-layout>
