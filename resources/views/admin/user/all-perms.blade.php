<h5>Права</h5>
<div class="form-group d-flex flex-wrap">
    @php
        
        $perms = $user->permissions->keyBy('id')->keys()->toArray();
        if (old('perms')) $perms = old('perms');
    @endphp
    @foreach ($allperms as $item)
        @php $checked = in_array($item->id, $perms) @endphp
        <div class="form-check-inline w-25 mr-0">
            <input class="form-check-input" type="checkbox"
                   name="perms[]" id="perm-id-{{ $item->id }}"
                   value="{{ $item->id }}" @if($checked) checked @endif>
            <label class="form-check-label" for="perm-id-{{ $item->id }}">
                {{ $item->name }}
            </label>
        </div>
    @endforeach
</div>