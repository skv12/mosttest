<div class="modal fade {{ $class ?? '' }}" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}-title" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered {{ $size ?? '' }}" role="document">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createChat">
            @csrf
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    {{ $body }}
                </div>
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            </form>
        </div>
    </div>
</div>