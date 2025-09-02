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

            displayTasks();
            const data = await response.json(); 
    
            return data;
        } catch (error) {
            console.log(error);
        }
    });

    // Change Events
    const tasks_list = document.querySelector(".tasks-list");
    tasks_list.addEventListener("change", (event) => {
        switch(true){
            case event.target.matches(".task_status") : 
                updateTaskStatus(event);
            break;

            default : 
                console.log("no changes were made.");
        }
    });

    // Click Events
    tasks_list.addEventListener("click", (event) => {
        switch (true) {
            case event.target.matches(".delete-btn"):
                deleteTask(event);
            break;

            case event.target.matches(".edit-btn"):
                editTask(event);
            break;

            default:
                console.log("no matching button action.");
        }
    });
})

async function deleteTask(event){
    let task_item = event.target.closest(".task-item");
    let task_id = task_item.dataset.id;
    let uri = `/tasks/${task_id}`;
    let method = "DELETE";
    let updatedField = {
        task_deleted : 1
    };

    try {
        const data = await updateTask(updatedField, method, uri);

        if(data.status === "success"){
            displayTasks();
        }

        return data;        
    } catch (error) {
        console.log(error);
    }


    console.log(task_id);
}

async function editTask(event){
    let task_item = event.target.closest(".task-item");
    console.log(task_item);
}

async function updateTaskStatus(event){
    let taskId = event.target.dataset.id;
    let uri = `/tasks/${taskId}`;
    let method = "PATCH"
    let updatedField = {
        task_completed: event.target.checked
    };

    try{
        let data = await updateTask(updatedField, method, uri);
       
        if(data.status === "success"){
            displayTasks();
        }

        return data;
    } catch (error) {
        console.log(error);
    }
}

async function updateTask(updatedFields, method, uri){
    const allowedFields = [
        "task_title",
        "task_description",
        "task_priority",
        "task_due",
        "task_completed",
        "task_deleted"
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
        const response = await fetch(uri, {
            method : method,
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
    tasksContainer.innerHTML = "";

    try{
        let tasks = await getTasks();

        let taskCard = "";
        tasks.forEach((task) => {
            taskCard += `
                <div class="task-item ${task.task_completed ? "completed" : ""}" id="task-item-${task.task_id}" data-id="${task.task_id}">
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
