<div class="row">
  <div class="col-md-6">
    <div class="box">
      <div class="box-title">
        <h3><i class="fa fa-comments"></i> CarBot - Assistant</h3>
      </div>
      <div class="box-content">
        <div id="chat-window" style="height:300px; overflow-y:auto; border:1px solid #ddd; padding:10px; margin-bottom:10px;">
          <div><b>Bot:</b> مرحبا! إسألني: (مثال: "قدّاش عدد السيارات؟" أو "السيارات في Medenine")</div>
        </div>
        <input type="text" id="chat-input" class="form-control" placeholder="أكتب سؤالك هنا...">
        <button id="send-btn" class="btn btn-success" style="margin-top:10px;">إرسال</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('send-btn').addEventListener('click', function() {
    let input = document.getElementById('chat-input').value;
    if(input.trim() === '') return;

    let chatWindow = document.getElementById('chat-window');
    chatWindow.innerHTML += "<div><b>You:</b> " + input + "</div>";

    fetch("<?php echo site_url('admin/analytics/chatbot_api'); ?>", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "question=" + encodeURIComponent(input)
    })
    .then(res => res.text())
    .then(data => {
        chatWindow.innerHTML += "<div><b>Bot:</b> " + data + "</div>";
        chatWindow.scrollTop = chatWindow.scrollHeight;
    });

    document.getElementById('chat-input').value = '';
});
</script>
