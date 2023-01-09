<div class="card card-default">
    <div class="card-header">
      <div class="row justify-content-start">
        <div class="col-auto">
          <p class="h5 mb-0 py-1" id="chatName">Chat Messages</p>
        </div>
        <div class="col-auto">
          <small class="text-muted mb-0 pt-2" id="chatStatus" style="display:none"><span id="chatUser"></span> is typing...</small>
        </div>
      </div>
        
    </div>
    <div class="card-body messages-box">
    </div>
    <form class="card-footer">
        <input type="hidden" value="" id="chatID">
        <div class="row g-0">
            <div class="col-11 pe-2">
                <input class="form-control text" placeholder="Message" type="text" id="message">
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-primary" id="sendMessage">Send</button>
            </div>
        </div>
    </form>
</div>