@if ($items->count())
    @php
        $tags = []; // это идентификаторы тегов, привязанных к посту
        if (isset($post)) $tags = $post->tags->keyBy('id')->keys()->toArray();
        if (old('tags')) $tags = old('tags');
    @endphp
    <div class="form-group d-flex flex-wrap">
    @foreach ($items as $item)
        @php $checked = in_array($item->id, $tags) @endphp
        <div class="form-check-inline w-25 mr-0">
            <input class="form-check-input" type="checkbox" name="tags[]" id="tag-id-{{ $item->id }}"
                   value="{{ $item->id }}" @if($checked) checked @endif>
            <label class="form-check-label" for="tag-id-{{ $item->id }}">
                {{ $item->name }}
            </label>
        </div>
    @endforeach
    </div>
@endif