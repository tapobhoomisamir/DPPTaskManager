// public/js/tasks.js

let currentPage = 1;
const rowsPerPage = 10;
let tasksData = [];

// ✅ Escape HTML to avoid XSS
function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[\"&'<>]/g, function (a) {
        return {
            '"': '&quot;',
            '&': '&amp;',
            "'": '&#39;',
            '<': '&lt;',
            '>': '&gt;'
        }[a];
    });
}

// ✅ Render tasks into the table body
function renderTasks(tasks) {
    const tbody = document.getElementById('tasksBody');
    const role = document.getElementById('currentUserRole') ? document.getElementById('currentUserRole').value : '';
    if (!tbody) return;

    tbody.innerHTML = '';
    tasks.forEach(task => {
        let editoption = '';
        let statusoption = `<li><a class="dropdown-item" href="#" onclick="openStatusModal(${task.id}, '${task.tasktype_name}','${task.status}')">Change Status</a></li>`;
        let commentoption = `<li><a class="dropdown-item" href="#" onclick="openCommentModal(${task.id})">Add Comment</a></li>`;
        let deleteoption = '';
        let approvaloption = '';
        if (role === 'Administrator' || role === 'Authority' || role === 'Incharge' ) { 
            editoption =`<li><a class="dropdown-item" href="/tasks/edit/${task.id}">Edit</a></li>`
            deleteoption =`<li><a class="dropdown-item" href="#" onclick="deleteTask(${task.id})">Delete</a></li>`
        } 
        else if (role === 'Viewer') {
            statusoption = '';
            commentoption = '';
        }

        if(task.tasktype_name === 'Approval' && task.status === 'Await Approval' && (role === 'Administrator' || role === 'Authority' || role === 'Incharge' )) {
            approvaloption = `<li><a class="dropdown-item" href="#" onclick="openCommentModal(${task.id},'Approved',${task.user_id})">Approve</a></li>`;
            statusoption = '';
        }

        if(task.status === 'Approved') {
            statusoption = '';
        }

        tbody.innerHTML += `
            <tr data-task-id="${task.id}" style="cursor: pointer;">
                <td>${escapeHtml(task.tasktype_name)}</td>
                <td>${escapeHtml(task.title)}</td>
                <td>${escapeHtml(task.latest_comment)}</td>
                <td>${escapeHtml(task.department_name)}</td>
                <td>${escapeHtml(task.user_name)}</td>
                <td>
                    <span class="badge ${task.status === 'Done' ? 'bg-success' : 'bg-warning text-dark'}">
                        ${escapeHtml(task.status)}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span style="font-size:1.5em;">&#8942;</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 180px;">
                            ${editoption}
                            <li><a class="dropdown-item" href="/tasks/view/${task.id}">View</a></li>
                            ${statusoption}
                            ${approvaloption}
                            ${commentoption}
                            ${deleteoption}
                        </ul>
                    </div>
                </td>
            </tr>
        `;
    });
}

// ✅ Render pagination buttons
function renderPagination(current, total) {
    const container = document.getElementById('pagination');
    if (!container) return;

    container.innerHTML = '';

    for (let i = 1; i <= total; i++) {
        container.innerHTML += `
            <li class="page-item ${i === current ? 'active' : ''}">
                <a class="page-link" href="#" onclick="fetchTasks(${i})">${i}</a>
            </li>
        `;
    }
}

// ✅ Fetch tasks from API (server-side pagination)
async function fetchTasks(page = 1, filters = {}) {

    const params = new URLSearchParams();
    createFilters(params);

    fetchTaskByParams(page,params);

}

async function fetchTaskByParams(page = 1,params) {
    
    currentPage = page;

    params.append('page', currentPage);
    params.append('per_page', rowsPerPage);

    const apiUrl = `/api/tasks?${params.toString()}`;
    console.log("API URL:", apiUrl);

   // const query = new URLSearchParams({ page, ...filters });
   
    const response = await fetch(apiUrl);
    const data = await response.json();

    tasksData = data.tasks || [];

    renderTasks(tasksData);
    renderPagination(data.pager.currentPage, data.pager.totalPages);
}

function createFilters(params) {

    const status = document.getElementById('status')?.value?.trim() || null;
    const department_id = document.getElementById('department_id')?.value?.trim() || null;
    const tasktype_id = document.getElementById('tasktype_id')?.value?.trim() || null;
    let user_id = null;
    const pageId = document.getElementById('pageId');
    const hiddenUserIdEl = document.getElementById('currentUserId');
    if (pageId && pageId.value.trim() == "dashboard" && hiddenUserIdEl && hiddenUserIdEl.value.trim() != "") {
        user_id = hiddenUserIdEl.value.trim();
        assign_by = hiddenUserIdEl.value.trim();
    } else {
        user_id = document.getElementById('user_id')?.value?.trim() || null;
    }
    const workweek_id = document.getElementById('workweek_id')?.value?.trim() || null;


    if (status) params.append('status', status);
    if (department_id) params.append('department_id', department_id);
    if (tasktype_id) params.append('tasktype_id', tasktype_id);
    if (workweek_id) params.append('workweek_id', workweek_id);

    if (pageId && pageId.value.trim() === "dashboard" && hiddenUserIdEl?.value.trim() !== "") {
        user_id = hiddenUserIdEl.value.trim();
        assign_by = hiddenUserIdEl.value.trim(); // adjust if assign_by is separate
    } else {
        user_id = document.getElementById('user_id')?.value?.trim() || null;
        assign_by = document.getElementById('assign_by')?.value?.trim() || null;
    }

    // Add OR filters as a single query parameter (pipe-separated)
    const orFilters = [];
    if (user_id) orFilters.push(`user_id:${user_id}`);
    if (assign_by) orFilters.push(`assign_by:${assign_by}`);
    if (orFilters.length > 0) {
        params.append('or_filters', orFilters.join('|'));
    }

    // set private param: 1 if current page is dashboard, else 0
    const pageIdEl = document.getElementById('pageId');
    const privateVal = (pageIdEl && pageIdEl.value.trim() === 'dashboard') ? null : '0';
    if(privateVal)
    {
        params.append('private', privateVal);
    }
}

// ✅ Open status change modal
function openStatusModal(taskId, tasktype,currentStatus) {
    const modal = document.getElementById('statusModal');
    if (!modal) return;

    document.getElementById('statusTaskId').value = taskId;

    const statusSelect = document.getElementById('newStatus');
    if (statusSelect) {
        // Example: only show Approved/Closed if Await Approval
        if (currentStatus === 'Await Approval') {
            statusSelect.innerHTML = `
                <option value="Approved">Approved</option>
                <option value="Closed">Closed</option>
            `;
        } else {
            let awaitApprovalStatusOption = '';
            if(tasktype === 'Approval') {
                awaitApprovalStatusOption = `<option value="Await Approval">Await Approval</option>`;
            }
            statusSelect.innerHTML = `
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>`+
                awaitApprovalStatusOption +
                `<option value="Hold">Hold</option>
                <option value="Done">Done</option>
                <option value="Closed">Closed</option>
            `;
        }
    }

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// ✅ Open comment modal
function openCommentModal(taskId, newstatus = null, userId = null) {
    const modal = document.getElementById('commentModal');
    if (!modal) return;

    document.getElementById('commentTaskId').value = taskId;
    document.getElementById('taskComment').value = '';
    
    document.getElementById('userAssignDiv').hidden = false;
    let isApprovedStatus = false;
    if(newstatus != null && newstatus.trim() != "") {
        document.getElementById('commentTaskStatus').value = newstatus;
        if(newstatus === 'Approved') {
            document.getElementById('commentModalTitle').textContent = "Approve Task";
            const select = document.getElementById('user_id_assign');
            if (userId) {
                select.value = userId; // set default
            } else {
                select.selectedIndex = 0; // fallback
            }
            isApprovedStatus = true;
        }
       
    }
     if(!isApprovedStatus) {
        document.getElementById('userAssignDiv').hidden = true;
        document.getElementById('commentModalTitle').textContent = "Add Comment";      
    }

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Handle status form submit
document.getElementById('statusModal').addEventListener('submit', function(e) {
    e.preventDefault();
    const taskId = document.getElementById('statusTaskId').value;
    const status = document.getElementById('newStatus').value;

    fetch(`/api/tasks/${taskId}/status`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            fetchTasks(currentPage);
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
        } else {
            alert(result.message || 'Failed to update status.');
        }
    })
    .catch(() => alert('Failed to update status.'));
});

function filterTasks(status) {
    const params = new URLSearchParams();
    if(status == null || status.trim() == "" || status.trim() == "all")
    {
        fetchTaskByParams(1,params);
        return;
    }
    if(status == 'allassigned')
    {
        params.append('statuses', 'In Progress,Pending,Hold,Await Approval');
    }
    else
    {
        params.append('statuses', status);
    }

    
    const currentUserId = document.getElementById('currentUserId')?.value?.trim() || null;
    if(currentUserId)
    {
        params.append('user_id', currentUserId );
    }
    
    // set private param: 1 if current page is dashboard, else 0
    const pageIdEl = document.getElementById('pageId');
    const privateVal = (pageIdEl && pageIdEl.value.trim() === 'dashboard') ? null : '0';
    if(privateVal)
    {
        params.append('private', privateVal);
    }

    fetchTaskByParams(1,params);
}


// Handle comment form submit
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const currentUserId = document.getElementById('currentUserId') ? document.getElementById('currentUserId').value : '';
    const taskId = document.getElementById('commentTaskId').value;
    const taskStatus = document.getElementById('commentTaskStatus').value;
    const comment = document.getElementById('taskComment').value;
    const assignUserId = document.getElementById('user_id_assign')?.value?.trim() || null;

    fetch(`/api/tasks/${taskId}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({  comment: comment , status: taskStatus, currentUserId: currentUserId,assignUserId: assignUserId  })
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            bootstrap.Modal.getInstance(document.getElementById('commentModal')).hide();
            alert('Comment added successfully');
        } else {
            alert(result.message || 'Failed to add comment.');
        }
    })
    .catch(() => alert('Failed to add comment.'));
});


//delete task
function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;
    fetch(`/api/tasks/${taskId}`, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            fetchTasks();
        } else {
            alert(result.message || 'Failed to delete task.');
        }
    })
    .catch(() => alert('Failed to delete task.'));
}

document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchTasks(1);
});

function getFieldValue(contextOrForm, name, fallback = null) {
    let el = null;
    // if contextOrForm is a form element with .elements
    if (contextOrForm && contextOrForm.elements) {
        el = contextOrForm.elements[name];
    }
    // fallback to global lookup by id or name
    if (!el) el = document.getElementById(name) || document.querySelector(`[name="${name}"]`);
    if (!el) return fallback;

    // checkbox handling
    const type = el.type || el.tagName && el.tagName.toLowerCase();
    if (type === 'checkbox') {
        return el.checked ? (el.value !== undefined ? el.value : '1') : 0;
    }

    const val = el.value;
    if (val === undefined || val === null) return fallback;
    return (typeof val === 'string')  && val.trim() == ""? fallback : val;
}

const newTaskFormEl = document.getElementById('newTaskForm');
    if (newTaskFormEl) {
   newTaskFormEl.addEventListener('submit', function(e) {
        const currentUserId = document.getElementById('currentUserId') ? document.getElementById('currentUserId').value : '';
        
            e.preventDefault();
            const form = newTaskFormEl; 
            //const form = e.target;
            const data = {
                title: getFieldValue(newTaskFormEl, 'title', ''),
                description: getFieldValue(newTaskFormEl, 'description', ''),
                department_id: getFieldValue(newTaskFormEl, 'department_id', null),
                tasktype_id: getFieldValue(newTaskFormEl, 'tasktype_id', null),
                user_id: getFieldValue(newTaskFormEl, 'user_id', currentUserId),
                work_week: getFieldValue(newTaskFormEl, 'work_week', null) || getFieldValue(newTaskFormEl, 'workweek_id', null),
                due_date: getFieldValue(newTaskFormEl, 'due_date', null),
                private: getFieldValue(newTaskFormEl, 'private', 0) ? 1 : 0,
                assign_by: currentUserId,
                status: "Pending"
            };
            fetch(`/api/tasks`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if(result.success){
                    form.reset();
                    fetchTasks(1);
                    const modalEl = document.getElementById('newTaskModal');
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalInstance.hide();
                } else {
                    alert(result.message || 'Failed to create task.');
                }
            })
            .catch(() => alert('Failed to create task.'));
    });
}

document.getElementById('downloadReport').addEventListener('click', function () {
    // collect filters (if any from dropdowns/inputs)
    const params = new URLSearchParams();
    createFilters(params);

    // Build query string
    // const params = new URLSearchParams({
    //     ...filters
    // });

    // Open download
   // window.location.href = `/tasks/exportXls?${params.toString()}`;
   window.location.href = `/tasks/download/pdf?${params.toString()}`; // PDF download
});

// Add row click navigation (event delegation) — ignore clicks on links, buttons, dropdowns
document.getElementById('tasksBody')?.addEventListener('click', function (e) {
    const tr = e.target.closest('tr[data-task-id]');
    if (!tr) return;

    // if clicked on a link/button or inside dropdown, do not navigate by row click
    if (e.target.closest('a') || e.target.closest('button') || e.target.closest('.dropdown')) {
        return;
    }

    const taskId = tr.getAttribute('data-task-id');
    if (taskId) {
        window.location.href = `/tasks/view/${taskId}`;
    }
});
  