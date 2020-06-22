<div class="modal fade " id="schedulePost" tabindex="-2" role="dialog" aria-labelledby="schedulePost" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="schedulePostLabel">Schedule Post</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <input name="date" type="date" class="form-control" value="{{ date('Y-m-d', time()+(3600*24)) }}">
            </div>
            @error('date')
                <p class="text-danger">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default">Schedule Post</button>
        </div>
      </div>
    </div>
  </div>

