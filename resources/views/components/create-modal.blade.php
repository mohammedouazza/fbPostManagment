<form action="{{ route('posts.store') }}" method="POST">
    @csrf
<div class="modal fade" id="createPost" tabindex="-1" role="dialog" aria-labelledby="createPostLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPostLabel">Create Post</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">


            <div class="form-group">
                <label for="description">Post description</label>
                <textarea required name="description" rows="3" placeholder="Description..." class="form-control"></textarea>
                @error('description')
                    <p class="text-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="page">Select Page</label>
                <select name="page" class="form-control" required>
                    @foreach (auth()->user()->pages()->where('active', true)->get() as $page)
                        <option value="{{ $page->id }}" class="form-control">{{ $page->name }}</option>
                    @endforeach
                </select>
                @error('page')
                    <p class="text-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>


        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Post Now</button>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#schedulePost">Schedule</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>


  </div>
  <x-schedule-post />
</form>

