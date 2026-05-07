<x-app-layout>
    <x-slot name="pageTitle">Send Alert</x-slot>
    <x-slot name="pageSubtitle">Create an emergency alert</x-slot>

    <div style="max-width:640px;">
        <div class="glass-card-static" style="padding:2rem;">
            <form method="POST" action="{{ route('alerts.store') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Alert Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="e.g. Immediate Evacuation Required" required>
                    @error('title') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-textarea" rows="4" placeholder="Detailed alert message..." required>{{ old('message') }}</textarea>
                    @error('message') <p style="color:var(--color-danger); font-size:0.75rem; margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Related Calamity (Optional)</label>
                        <select name="calamity_id" class="form-select">
                            <option value="">None</option>
                            @foreach($calamities as $calamity)
                                <option value="{{ $calamity->id }}" {{ old('calamity_id', request('calamity')) == $calamity->id ? 'selected' : '' }}>{{ $calamity->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem; padding:1rem; border-radius:var(--radius-md); border:1px solid var(--color-border); background:var(--color-bg-primary);">
                    <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
                        <input type="checkbox" name="is_broadcast" value="1" id="broadcast-check" {{ old('is_broadcast') ? 'checked' : '' }}
                            onchange="document.getElementById('target-user-wrap').style.display = this.checked ? 'none' : 'block'"
                            style="width:18px; height:18px; accent-color:var(--color-primary);">
                        <div>
                            <span style="font-weight:600; font-size:0.9rem; color:var(--color-text-primary);">📢 Broadcast to All Agencies</span>
                            <p style="font-size:0.75rem; color:var(--color-text-muted); margin:0;">Send this alert to every registered agency</p>
                        </div>
                    </label>
                </div>

                <div id="target-user-wrap" style="margin-bottom:1.5rem; {{ old('is_broadcast') ? 'display:none' : '' }}">
                    <label class="form-label">Target Agency</label>
                    <select name="target_user_id" class="form-select">
                        <option value="">Select agency...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('target_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} {{ $user->organization ? '— '.$user->organization : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:1rem;">
                    <button type="submit" class="btn btn-danger">🔔 Send Alert</button>
                    <a href="{{ route('alerts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
