<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || isset($_SESSION['loggedin'])!=true){
        header("location:login.php");
        exit;
    }


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Seymour+One&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   
    <link rel="stylesheet" href="style.css">
     <!-- GoalCraft header -->
     <div class="goalcraft-header">
        <h2 class="goalcraft-logo">GoalCraft</h2>
    </div>
</head>
  <body>
   
    <div id="form" class=hi>
        <h1>Welcome <?php echo $_SESSION['username'] ?></h1>
    </div>
    <aside class="sidebar">
        <div class="logo">
            <img src="goal_craft_logo.png" alt="logo">
            <h2>Dashboard</h2>
        </div>
        <ul class="links">
            
         

    <!-- Clickable note icon -->
    <li>
        <span class="material-symbols-outlined" onclick="openNotepad()">note</span>
        <a href="#" onclick="openNotepad()">Notes</a>
    </li>
            <!-- Add the Pomodoro link with an onclick event -->
            <li>
                <span class="material-symbols-outlined">timer</span>
                <a href="#" onclick="togglePomodoroTimer()">Pomodoro</a>
            </li>
            <li>
                
                <span class="material-symbols-outlined">task</span>
                <a href="#" onclick="showTodo()">To do</a>
            </li>
            <li>
             <!--   <span class="material-symbols-outlined">image</span>
                <a href="#">Background</a> 
            </li>
            <li>
                <span class="material-symbols-outlined">speaker</span>
                <a href="#">Music</a>-->
            </li>
            <li>
                <span class="material-symbols-outlined">today</span>
                <a href="#" onclick="toggleCalendar()">Calendar</a>
            </li>
            <li>
                <span class="material-symbols-outlined">person</span>
                <a href="logout.php">Logout</a>
            </li>
         </ul>
    </aside>
<!--notes-->
 <!-- Notepad popup -->
 <div id="notepad-popup" class="notepad-popup">
        <textarea id="notepad-content" class="notepad-content" placeholder="Write your notes here..."></textarea>
        <button onclick="closeNotepad()" class="close-btn">Close</button>
    </div>
    <!-- Pomodoro Timer container -->
    <div id="pomodoro-container" class="container" style="display: none;">
        <h1>-Pomodoro Timer-</h1>
        <div class="main">
            <div class="timer-option">
                <button id="work-button" class="active">Work</button>
                <button id="short-button">Short Break</button>
                <button id="long-button">Long Break</button>
            </div>

            <div class="timer-area">
                <p id="work-timer"><span></span>:<span></span></p>
                <p id="short-timer"><span></span>:<span></span></p>
                <p id="long-timer"><span></span>:<span></span></p>
            </div>

            <div class="timer-title">
                <small>Minutes</small>
                <small>:</small>
                <small>Seconds</small>
            </div>
        
            <div class="timer-settings">
                <button id="start-button">Start</button>
                <button id="pause-button">Pause</button>
                <button id="continue-button">Continue</button>
                <button id="reset-button">Reset</button>
            </div>
        </div>
    </div>
    <!-- to do list container-->
    <div class="task-container" id="taskContainer">
        <div class="wrapper">
            <div class="task-input">
               
                <input type="text" placeholder="Add a new task">
            </div>
            <div class="controls">
                <div class="filters">
                    <span class="active" id="all">All</span>
                    <span id="pending">Pending</span>
                    <span id="completed">Completed</span>
                </div>
                <button class="clear-btn">Clear All</button>
            </div>
            <ul class="task-box"></ul>
        </div>
    </div>
    <script>
  //notes 
  
  function openNotepad() {
            document.getElementById("notepad-popup").style.display = "block";
        }

        function closeNotepad() {
            document.getElementById("notepad-popup").style.display = "none";
        }
        //pomodoro
        const pomodoroContainer = document.getElementById('pomodoro-container');
        const workTimer = document.getElementById('work-timer');
        const shortTimer = document.getElementById('short-timer');
        const longTimer = document.getElementById('long-timer');
        const timerOptionBtns = document.querySelectorAll('.timer-option button');
        const timerSettingsBtns = document.querySelectorAll('.timer-settings button');
        
        shortTimer.style.display = "none";
        longTimer.style.display = "none";
        document.getElementById('reset-button').style.display = "none";
        document.getElementById('continue-button').style.display = "none";
        document.getElementById('pause-button').style.display = "none";
        
        let intervalId;
        let isRunning = false;
        let currentTimer = "work";
        let minutes, seconds;

        function updateTimerDisplay() {
            const formattedMinutes = minutes < 10 ? `0${minutes}` : `${minutes}`;
            const formattedSeconds = seconds < 10  && seconds != 0 ? `0${seconds}` : `${seconds}`;
            workTimer.innerHTML = `${formattedMinutes}:${formattedSeconds}`;
        }
  
        // Add the function to make the container movable
        pomodoroContainer.addEventListener('mousedown', startDragging);
        document.addEventListener('mouseup', stopDragging);

        let offsetX, offsetY;

        function startDragging(e) {
            offsetX = e.clientX - pomodoroContainer.getBoundingClientRect().left;
            offsetY = e.clientY - pomodoroContainer.getBoundingClientRect().top;

            document.addEventListener('mousemove', drag);
        }

        function drag(e) {
            pomodoroContainer.style.left = e.clientX - offsetX + 'px';
            pomodoroContainer.style.top = e.clientY - offsetY + 'px';
        }

        function stopDragging() {
            document.removeEventListener('mousemove', drag);
        }

        function startTimer() {
            if (isRunning) return;

            isRunning = true;
            intervalId = setInterval(function () {
                if (seconds > 0) {
                    seconds--;
                } else {
                    if (minutes === 0) {
                        clearInterval(intervalId);
                        isRunning = false;
                        switchTimer();
                    } else {
                        seconds = 59;
                        minutes--;
                    }
                }

                updateTimerDisplay();
            }, 1000);
        }

        function stopTimer() {
            if (!isRunning) return;

            clearInterval(intervalId);
            isRunning = false;
        }

        function resetTimer() {
            stopTimer();
            if (currentTimer === "work") {
                minutes = 25;
            } else if (currentTimer === "short") {
                minutes = 5;
            } else {
                minutes = 15;
            }
            seconds = '00';
            updateTimerDisplay();
        }

        function switchTimer() {
            if (currentTimer === "work") {
                currentTimer = "short";
                shortTimer.style.display = "block";
                workTimer.style.display = "none";
                minutes = 5;
            } else if (currentTimer === "short") {
                currentTimer = "long";
                longTimer.style.display = "block";
                shortTimer.style.display = "none";
                minutes = 15;
            } else {
                currentTimer = "work";
                workTimer.style.display = "block";
                longTimer.style.display = "none";
                minutes = 25;
            }
            seconds = '00';
            updateTimerDisplay();
            resetTimer();
        }

        timerOptionBtns.forEach(button => {
            button.addEventListener('click', () => {
                timerOptionBtns.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                if (button.id === "work-button") {
                    currentTimer = "work";
                } else if (button.id === "short-button") {
                    currentTimer = "short";
                } else {
                    currentTimer = "long";
                }

                resetTimer();
            });
        });

        timerSettingsBtns.forEach(button => {
            button.addEventListener('click', () => {
                if (button.id === "start-button") {
                    document.getElementById('start-button').style.display = "none";
                    document.getElementById('reset-button').style.display = "";
                    document.getElementById('pause-button').style.display = "";
                    startTimer();
                } else if (button.id === "pause-button") {
                    document.getElementById('continue-button').style.display = "";
                    document.getElementById('pause-button').style.display = "none";
                    stopTimer();
                } else if (button.id === "continue-button") {
                    document.getElementById('pause-button').style.display = "";
                    document.getElementById('continue-button').style.display = "none";
                    startTimer();
                } else if (button.id === "reset-button") {
                    document.getElementById('start-button').style.display = "";
                    document.getElementById('reset-button').style.display = "none";
                    document.getElementById('pause-button').style.display = "none";
                    document.getElementById('continue-button').style.display = "none";
                    resetTimer();
                }
            });
        });

        resetTimer();

        function togglePomodoroTimer() {
            if (pomodoroContainer.style.display === 'none' || pomodoroContainer.style.display === '') {
                pomodoroContainer.style.display = 'block';
                resetTimer(); // Reset the timer when showing the container
                startTimer(); // Start the timer when showing the container
            } else {
                pomodoroContainer.style.display = 'none';
                stopTimer(); // Stop the timer when hiding the container
            }
        }
   
        //calendar 

        var calendarFrame = document.createElement('iframe');
        calendarFrame.id = 'calendarFrame';
        calendarFrame.src = 'https://calendar.google.com/calendar/embed?height=600&wkst=1&ctz=Asia%2FKolkata&bgcolor=%2358325e&src=YmhhbnVzaGFsaXJpZGRoaTIyMDVAZ21haWwuY29t&src=YWRkcmVzc2Jvb2sjY29udGFjdHNAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&src=ZW4uaW5kaWFuI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%23039BE5&color=%2333B679&color=%230B8043';
        calendarFrame.style.border = '0';
        calendarFrame.style.top='16%';
        calendarFrame.style.right='2%';
        calendarFrame.style.cursor='move';
        calendarFrame.style.width = '600px';
        calendarFrame.style.height = '300px';
        calendarFrame.style.position = 'absolute';
        calendarFrame.style.display = 'none';
        calendarFrame.style.zIndex = '1000';
        calendarFrame.style.backgroundColor = 'white';
        document.body.appendChild(calendarFrame);

        var isDragging = false;
        

        function toggleCalendar() {
            if (calendarFrame.style.display === 'none') {
                calendarFrame.style.display = 'block';
                makeDraggable();
            } else {
                calendarFrame.style.display = 'none';
            }
        }

        function makeDraggable() {
            calendarFrame.addEventListener('mousedown', startDrag);
            calendarFrame.addEventListener('mouseup', endDrag);
            calendarFrame.addEventListener('mousemove', drag);
        }

        function startDrag(e) {
            isDragging = true;
            offsetX = e.clientX - calendarFrame.offsetLeft;
            offsetY = e.clientY - calendarFrame.offsetTop;
        }

        function endDrag() {
            isDragging = false;
        }

        function drag(e) {
            if (!isDragging) return;
            calendarFrame.style.left = e.clientX - offsetX + 'px';
            calendarFrame.style.top = e.clientY - offsetY + 'px';
        }

//to do
const taskInput = document.querySelector(".task-input input"),
filters = document.querySelectorAll(".filters span"),
clearAll = document.querySelector(".clear-btn"),
taskBox = document.querySelector(".task-box");

let editId,
isEditTask = false,
todos = JSON.parse(localStorage.getItem("todo-list"));

filters.forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelector("span.active").classList.remove("active");
        btn.classList.add("active");
        showTodo(btn.id);
    });
});

function showTodo(filter) {
    let liTag = "";
    if(todos) {
        todos.forEach((todo, id) => {
            let completed = todo.status == "completed" ? "checked" : "";
            if(filter == todo.status || filter == "all") {
                liTag += `<li class="task">
                            <label for="${id}">
                                <input onclick="updateStatus(this)" type="checkbox" id="${id}" ${completed}>
                                <p class="${completed}">${todo.name}</p>
                            </label>
                            <div class="settings">
                                <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                                <ul class="task-menu">
                                    <li onclick='editTask(${id}, "${todo.name}")'><i class="uil uil-pen"></i>Edit</li>
                                    <li onclick='deleteTask(${id}, "${filter}")'><i class="uil uil-trash"></i>Delete</li>
                                </ul>
                            </div>
                        </li>`;
            }
        });
    }
    taskBox.innerHTML = liTag || `<span>You don't have any task here</span>`;
    let checkTask = taskBox.querySelectorAll(".task");
    !checkTask.length ? clearAll.classList.remove("active") : clearAll.classList.add("active");
    taskBox.offsetHeight >= 300 ? taskBox.classList.add("overflow") : taskBox.classList.remove("overflow");
}
showTodo("all");

function showMenu(selectedTask) {
    let menuDiv = selectedTask.parentElement.lastElementChild;
    menuDiv.classList.add("show");
    document.addEventListener("click", e => {
        if(e.target.tagName != "I" || e.target != selectedTask) {
            menuDiv.classList.remove("show");
        }
    });
}

function updateStatus(selectedTask) {
    let taskName = selectedTask.parentElement.lastElementChild;
    if(selectedTask.checked) {
        taskName.classList.add("checked");
        todos[selectedTask.id].status = "completed";
    } else {
        taskName.classList.remove("checked");
        todos[selectedTask.id].status = "pending";
    }
    localStorage.setItem("todo-list", JSON.stringify(todos))
}

function editTask(taskId, textName) {
    editId = taskId;
    isEditTask = true;
    taskInput.value = textName;
    taskInput.focus();
    taskInput.classList.add("active");
}

function deleteTask(deleteId, filter) {
    isEditTask = false;
    todos.splice(deleteId, 1);
    localStorage.setItem("todo-list", JSON.stringify(todos));
    showTodo(filter);
}

clearAll.addEventListener("click", () => {
    isEditTask = false;
    todos.splice(0, todos.length);
    localStorage.setItem("todo-list", JSON.stringify(todos));
    showTodo()
});

taskInput.addEventListener("keyup", e => {
    let userTask = taskInput.value.trim();
    if(e.key == "Enter" && userTask) {
        if(!isEditTask) {
            todos = !todos ? [] : todos;
            let taskInfo = {name: userTask, status: "pending"};
            todos.push(taskInfo);
        } else {
            isEditTask = false;
            todos[editId].name = userTask;
        }
        taskInput.value = "";
        localStorage.setItem("todo-list", JSON.stringify(todos));
        showTodo(document.querySelector("span.active").id);
    }
});


</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>