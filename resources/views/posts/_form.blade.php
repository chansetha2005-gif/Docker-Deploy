<label for="title">Title</label>
<input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}">
@error('title') <div class="error">{{ $message }}</div> @enderror

<label for="content">Content</label>
<textarea id="content" name="content">{{ old('content', $post->content ?? '') }}</textarea>
@error('content') <div class="error">{{ $message }}</div> @enderror

<div style="margin-top: 16px; display: flex; gap: 8px;">
    <button type="submit" class="btn">Save</button>
    <a href="{{ route('posts.index') }}" class="btn btn-gray">Cancel</a>
</div>
