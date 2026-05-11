import { useEffect, useState } from "react";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import type { UserTaskData } from "../../types/tasks";
import TaskDelete from "./TaskDelete";
import TaskEdit from "./TaskEdit";
import TaskDoneAsInProgress from "./TaskDoneAsInProgress";
import { CiMenuKebab } from "react-icons/ci";

export default function TasksToDo() {
	const [taskData, setTaskData] = useState<UserTaskData[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [isOpenMenuActionButtons, setIsOpenMenuActionButtons] = useState<number | null>(null);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);
	const [refresh, setRefresh] = useState<number>(0);
	const [isMenuDisplay, setIsMenuDisplay] = useState<boolean>(true);

	async function getToDoTasks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/todo-tasks?status=todo", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.errors) {
			setErrorsArray(data.errors);
		} else {
			setTaskData(data.data);
		}
	}

	async function sortTasksFunction() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/sort-tasks?status=todo&direction=${directionSort}&sort=${sortDataKey}`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.errors) {
			setErrorsArray(data.errors);
		} else {
			setTaskData(data.data);
		}
	}

	const handleSortFunction = (e: any) => {
		const key = e.target.value;

		if (sortDataKey === key) {
			setDirectionSort(direction => (direction === "asc" ? "desc" : "asc"));
		} else {
			setDirectionSort("asc");
		}
		setSortDataKey(key);
	};

	const handleOpenMenuWithActionButtons = (taskId: number) => {
		setIsOpenMenuActionButtons(prevState => (prevState === taskId ? null : taskId));
	};

	useEffect(() => {
		getToDoTasks();
	}, [refresh]);

	useEffect(() => {
		if (sortDataKey) {
			sortTasksFunction();
		}
	}, [directionSort, sortDataKey]);

	return (
		<div className='tasks-todo'>
			<div className='my-3'>
				<div>
					<div>
						<div className='d-flex align-items-center'>
							<button className={`tasks-todo__display-tasks-btn display-btn ${isMenuDisplay ? "open" : ""}`} onClick={() => setIsMenuDisplay(prevState => !prevState)}>
								<IoIosArrowUp size={24} className='display-icon' />
							</button>
							<h4 className='ms-2 mb-0'>To Do</h4>
						</div>
						<hr />
					</div>
					{isMenuDisplay && (
						<div className='tasks-todo__main-container'>
							{errorsArray.length > 0 ? (
								<div>
									{errorsArray.map((error, index) => (
										<div key={index} className='alert alert-danger'>
											{error}
										</div>
									))}
								</div>
							) : (
								<div className='col-12'>
									<div className='d-none d-md-flex fw-bold border-bottom py-2'>
										<div className='d-flex align-items-center col-1'>
											<div>#</div>
											<button className='tasks-todo__sort-btn ms-2' onClick={handleSortFunction} value='id'>
												{directionSort === "asc" && sortDataKey === "id" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
											</button>
										</div>
										<div className='d-flex align-items-center col-4'>
											<div>Task</div>
											<button className='tasks-todo__sort-btn ms-2' onClick={handleSortFunction} value='name'>
												{directionSort === "asc" && sortDataKey === "name" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
											</button>
										</div>
										<div className='d-flex align-items-center col-3'>
											<div>Date</div>
											<button className='tasks-todo__sort-btn ms-2' onClick={handleSortFunction} value='created_at'>
												{directionSort === "asc" && sortDataKey === "created_at" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
											</button>
										</div>
										<div className='d-flex align-items-center justify-content-center col-2'>
											<div>Priority</div>
											<button className='tasks-todo__sort-btn ms-2' onClick={handleSortFunction} value='priority'>
												{directionSort === "asc" && sortDataKey === "priority" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
											</button>
										</div>
										<div className='col-2 text-center'>Actions</div>
									</div>
									<div>
										{taskData.map((task, index) => (
											<div className='d-flex flex-wrap align-items-center border-bottom py-2' key={index}>
												<div className='col-1 fw-bold'>{index + 1}.</div>
												<div className='col-6 col-md-4'>{task.name}</div>
												<div className='col-5 col-md-3'>{new Date(task.created_at).toLocaleString()}</div>
												<div className={task.priority === "low" ? "tasks-todo__priority bg-success d-flex justify-content-center rounded-3 py-2 col-6 col-md-2" : task.priority === "medium" ? "tasks-todo__priority bg-warning d-flex justify-content-center rounded-3 py-2 col-6 col-md-2" : task.priority === "high" ? "tasks-todo__priority bg-danger d-flex justify-content-center rounded-3 py-2 col-6 col-md-2" : ""}>{task.priority}</div>
												<div className='col-6 d-md-none text-end'>
													<CiMenuKebab size={24} onClick={() => handleOpenMenuWithActionButtons(task.id)} />
												</div>
												{isOpenMenuActionButtons === task.id && (
													<div className='d-flex d-md-none justify-content-center col-6 col-md-2'>
														<TaskDoneAsInProgress refreshData={() => setRefresh(prevState => prevState + 1)} taskProp={task} />
														<TaskEdit refreshData={() => setRefresh(prevState => prevState + 1)} taskProp={task} />
														<TaskDelete refreshData={() => setRefresh(prevState => prevState + 1)} taskId={task.id} />
													</div>
												)}
												<div className='d-none d-md-flex justify-content-end col-6 col-md-2'>
													<TaskDoneAsInProgress refreshData={() => setRefresh(prevState => prevState + 1)} taskProp={task} />
													<TaskEdit refreshData={() => setRefresh(prevState => prevState + 1)} taskProp={task} />
													<TaskDelete refreshData={() => setRefresh(prevState => prevState + 1)} taskId={task.id} />
												</div>
											</div>
										))}
									</div>
								</div>
							)}
						</div>
					)}
				</div>
			</div>
		</div>
	);
}
