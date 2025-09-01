document.addEventListener("DOMContentLoaded", () => {
    // display all user's task
    displayTasks();

    // handle adding new tasks
    const add_task_form = document.querySelector("#addTaskForm form");
    add_task_form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const form_data = new FormData(add_task_form);

        try{
            const response = await fetch("/tasks", {
                method: "POST",
                body: form_data
            });

            if (!response.ok) {
                throw new Error("Network error: " + response.statusText);
            }

            const data = await response.json(); 
    
            return data;
        } catch (error) {
            console.log(error);
        }
    });

    // handle task checkbox for task status
    const tasks_list = document.querySelector(".tasks-list");
    tasks_list.addEventListener("change", updateTaskStatus);
})

async function updateTaskStatus(event){
    let taskStatusChanged = event.target.matches(".task_status");

    if(!taskStatusChanged){
        return;
    }

    let taskId = event.target.dataset.id;

    try{
        let updatedField = {
            task_completed: event.target.checked
        };

        let data = await updateTask(taskId, updatedField);
       
        if(data.status === "success"){
            let taskItem = document.querySelector(`#task-item-${data.task_id}`);

            if(data.task_completed === true){
                taskItem.classList.add('completed');
            } else {
                taskItem.classList.remove('completed');
            }
        }

        return data;
    } catch (error) {
        console.log(error);
    }

}

async function updateTask(taskId, updatedFields){
    const allowedFields = [
        "task_title",
        "task_description",
        "task_priority",
        "task_due",
        "task_completed"
    ];

    // Pick only allowed keys
    const filtered = {};
    for (const key of allowedFields) {
        if (key in updatedFields) {
            filtered[key] = updatedFields[key];
        }
    }

    if(!filtered){
        return;
    }

    try{
        const response = await fetch(`/tasks/${taskId}`, {
            method : "PATCH",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                "fields" : filtered
            }),
        });

        if (!response.ok) {
            throw new Error("Network error: " + response.statusText);
        }

        const data = await response.json();

        return data;
    } catch (error) {
        console.log(error);
    }
}

async function getTasks() {
    try {
        const response = await fetch("/tasks");
        if (!response.ok) {
            throw new Error("Network error: " + response.statusText);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Fetch error:", error);
    }
}

async function displayTasks(){
    tasksContainer = document.querySelector(".tasks-list");

    try{
        let tasks = await getTasks();

        let taskCard = "";
        tasks.forEach((task) => {
            taskCard += `
                <div class="task-item ${task.task_completed ? "completed" : ""}" id="task-item-${task.task_id}">
                    <div class="task-checkbox">
                        <input class="task_status" type="checkbox" data-id="${task.task_id}" id="task_status_${task.task_id}" ${task.task_completed ? "checked" : ""}/>
                        <label for="task_status_${task.task_id}"></label>
                    </div>
                    <div class="task-content">
                        <h4>${task.task_title}</h4>
                        <p>${task.task_description}</p>
                        <div class="task-meta">
                            <span class="priority ${task.task_priority}">${task.task_priority} Priority</span>
                            <span class="date">${task.task_completed ? "Completed : " + task.task_completed_date : "Due: " + task.task_due}</span>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="edit-btn">✏️</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                </div>
            `;
        });

        tasksContainer.innerHTML += taskCard;
    } catch (error) {
        console.log(error);
    }
}


function showAddTaskForm() {
    document.getElementById('addTaskForm').style.display = 'block';
}

function hideAddTaskForm() {
    document.getElementById('addTaskForm').style.display = 'none';
}

// Handle task completion
document.querySelectorAll('.task-item input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskItem = this.closest('.task-item');
        if (this.checked) {
            taskItem.classList.add('completed');
        } else {
            taskItem.classList.remove('completed');
        }
    });
});
