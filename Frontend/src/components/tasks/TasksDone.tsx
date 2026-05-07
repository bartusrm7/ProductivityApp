import { useEffect, useState } from "react";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import type { UserTaskData } from "../../types/tasks";
import TaskDelete from "./TaskDelete";
import TaskEdit from "./TaskEdit";

export default function TasksDone() {
	const [taskData, setTaskData] = useState<UserTaskData[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function getDoneTasks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/done-tasks?status=done`, {
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
		const response = await fetch(`http://productivityapp.local/sort-tasks?status=done&direction=${directionSort}&sort=${sortDataKey}`, {
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
		setDirectionSort(prevState => (prevState === "asc" ? "desc" : "asc"));
		setSortDataKey(e.target.value);
	};

	useEffect(() => {
		getDoneTasks();
	}, []);

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
							<IoIosArrowUp size={24} />
							<h4 className='ms-2 mb-0'>Done</h4>
						</div>
						<hr />
					</div>
					<div>
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
								<div className='d-flex fw-bold border-bottom py-2'>
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
										<div className='d-flex align-items-center border-bottom py-2' key={index}>
											<div className='col-1 fw-bold'>{task.id}.</div>
											<div className='col-4'>{task.name}</div>
											<div className='col-3'>{new Date(task.created_at).toLocaleString()}</div>
											<div className={task.priority === "low" ? "tasks-todo__priority bg-success text-center rounded-3 py-2 col-2" : task.priority === "medium" ? "tasks-todo__priority bg-warning text-center rounded-3 py-2 col-2" : task.priority === "high" ? "tasks-todo__priority bg-danger text-center rounded-3 py-2 col-2" : ""}>{task.priority}</div>
											<div className='text-center col-2'>
												<TaskEdit taskProp={task} />
												<TaskDelete taskId={task.id} />
											</div>
										</div>
									))}
								</div>
							</div>
						)}
					</div>
				</div>
			</div>
		</div>
	);
}
