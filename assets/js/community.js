document.addEventListener('DOMContentLoaded', function() {
    // 处理点赞功能
    document.querySelectorAll('.like-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const likeCountSpan = document.getElementById(`like-count-${postId}`);

            fetch('/nutribox/pages/like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `post_id=${postId}`
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      likeCountSpan.textContent = data.like_count;
                  }
              });
        });
    });
});
