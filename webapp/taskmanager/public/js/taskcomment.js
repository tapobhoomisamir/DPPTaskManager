// public/js/taskcomment.js

document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const taskId = document.getElementById('commentTaskId').value;
        const comment = document.getElementById('taskComment').value;

        fetch(`/api/tasks/${taskId}/comments`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment })
        })
        .then(res => res.json())
        .then(result => {
            if(result.success){
                window.location.reload();
            // alert('Comment added successfully');
            } else {
                alert(result.message || 'Failed to add comment.');
            }
        })
        .catch(() => alert('Failed to add comment.'));
    });