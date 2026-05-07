<x-app-layout>
    <x-slot name="pageTitle">Chat with {{ $user->name }}</x-slot>
    <x-slot name="pageSubtitle">{{ $user->organization ?? 'Direct Message' }}</x-slot>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem; height:calc(100vh - 180px);">
        <!-- Sidebar -->
        <div class="glass-card-static" style="display:flex; flex-direction:column; overflow:hidden;">
            <div style="padding:1rem; border-bottom:1px solid var(--color-border);">
                <h3 style="font-size:0.9rem; font-weight:700; color:var(--color-text-primary);">Conversations</h3>
            </div>
            <div style="flex:1; overflow-y:auto; padding:0.5rem;">
                @foreach($allUsers as $u)
                    <a href="{{ route('messages.show', $u) }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:var(--radius-md); text-decoration:none; margin-bottom:0.2rem; {{ $u->id == $user->id ? 'background:rgba(217,119,6,0.1);' : '' }}">
                        <div style="width:32px; height:32px; border-radius:50%; background:var(--gradient-accent); display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:white;">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                        <span style="font-size:0.8rem; color:var(--color-text-primary);">{{ $u->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Chat -->
        <div class="glass-card-static" style="display:flex; flex-direction:column; overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--color-border); display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; border-radius:50%; background:var(--gradient-accent); display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:700; color:white;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <h3 style="font-size:0.9rem; font-weight:700; color:var(--color-text-primary); margin:0;">{{ $user->name }}</h3>
                    <p style="font-size:0.7rem; color:var(--color-text-muted); margin:0;">{{ $user->organization ?? $user->email }}</p>
                </div>
            </div>

            <div style="flex:1; overflow-y:auto; padding:1.25rem; display:flex; flex-direction:column; gap:0.75rem;" id="messages-container">
                @forelse($messages as $msg)
                    <div style="display:flex; {{ $msg->sender_id == auth()->id() ? 'justify-content:flex-end' : 'justify-content:flex-start' }};">
                        <div class="msg-bubble {{ $msg->sender_id == auth()->id() ? 'msg-sent' : 'msg-received' }}">
                            <p style="margin:0; color:var(--color-text-primary);">{{ $msg->content }}</p>
                            <span style="font-size:0.65rem; color:var(--color-text-muted); margin-top:0.25rem; display:block;">{{ $msg->created_at->format('M d, H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="margin:auto;">
                        <div class="empty-icon">👋</div>
                        <p class="empty-text">Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <div style="padding:1rem 1.25rem; border-top:1px solid var(--color-border);">
                <form method="POST" action="{{ route('messages.store') }}" style="display:flex; gap:0.75rem;">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <input type="text" name="content" class="form-input" placeholder="Type a message..." required autocomplete="off" style="flex:1;">
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const mc = document.getElementById('messages-container');
        if (mc) mc.scrollTop = mc.scrollHeight;
    </script>
    @endpush
</x-app-layout>
