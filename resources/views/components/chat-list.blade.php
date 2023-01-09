<div class="card card-default">
    <div class="card-header">
        <p class="h5 mb-0 py-1">Chats</p>
    </div>
    <div class="chat-box card-body">
        <div class="list-group rounded-0" id="chatList">
        </div>
    </div>
    <div class="card-footer">
    <button type="button" class="w-100 btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create chat</button>
        <x-create-chat :id="'createModal'">
            <x-slot name="title">Create chat</x-slot>
            <x-slot name="body">
                <div class="mb-3">
                    <label for="name" class="form-label">Chat name</label>
                    <input type="name" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="selectUsers" class="form-label">Users</label>
                    <select class="form-select" multiple aria-label="multiple select example" id="selectUsers">
                    </select>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="createChatSubmit" data-bs-dismiss="modal">Create chat</button>
            </x-slot>
        </x-create-chat>
    </div>
</div>
