import { useEffect, useState } from "react";
import { IoIosArrowDown, IoIosArrowUp } from "react-icons/io";
import type { UserTaskData } from "../../types/tasks";
import TaskDelete from "./TaskDelete";
import TaskEdit from "./TaskEdit";
import TaskDoneAsTaskDone from "./TaskDoneAsTaskDone";
import { Button } from "react-bootstrap";

export default function TasksInProgress() {
	const [taskData, setTaskData] = useState<UserTaskData[]>([]);
	const [directionSort, setDirectionSort] = useState<"asc" | "desc">("asc");
	const [sortDataKey, setSortDataKey] = useState<string>();
	const [errorsArray, setErrorsArray] = useState<string[]>([]);
	const [refresh, setRefresh] = useState<number>(0);
	const [isMenuDisplay, setIsMenuDisplay] = useState<boolean>(true);

	async function getInProgressTasks() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch(`http://productivityapp.local/in-progress-tasks?status=in_progress`, {
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
				setRefresh(prevState => prevState + 1);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	async function sortTasksFunction() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/sort-tasks?status=in_progress&direction=${directionSort}&sort=${sortDataKey}`, {
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

	useEffect(() => {
		getInProgressTasks();
	}, [refresh]);

	useEffect(() => {
		if (sortDataKey) {
			sortTasksFunction();
		}
	}, [directionSort, sortDataKey]);

	return (
		<div className='tasks-in-progress'>
			<div className='my-3'>
				<div>
					<div className='d-flex align-items-center border-bottom pb-1'>
						<Button className={`tasks-in-progress__display-tasks-btn display-btn ${isMenuDisplay ? "open" : ""}`} onClick={() => setIsMenuDisplay(prevState => !prevState)}>
							<IoIosArrowUp size={24} className='display-icon' />
						</Button>
						<h4 className='ms-2 mb-0'>In Progress</h4>
					</div>
					<div>
						{isMenuDisplay && (
							<div className='tasks-in-progress__main-container'>
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
										<div className='header-custom-table-names d-none d-md-flex border-bottom'>
											<div className='d-flex align-items-center col-1'>
												<div>#</div>
												<button className='sort-btn ms-2' onClick={handleSortFunction} value='id'>
													{directionSort === "asc" && sortDataKey === "id" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
												</button>
											</div>
											<div className='d-flex align-items-center col-4'>
												<div>Task</div>
												<button className='sort-btn ms-2' onClick={handleSortFunction} value='name'>
													{directionSort === "asc" && sortDataKey === "name" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
												</button>
											</div>
											<div className='d-flex align-items-center col-3'>
												<div>Deadline</div>
												<button className='sort-btn ms-2' onClick={handleSortFunction} value='deadline'>
													{directionSort === "asc" && sortDataKey === "deadline" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
												</button>
											</div>
											<div className='d-flex align-items-center justify-content-center col-2'>
												<div>Priority</div>
												<button className='sort-btn ms-2' onClick={handleSortFunction} value='priority'>
													{directionSort === "asc" && sortDataKey === "priority" ? <IoIosArrowUp className='sort-icon' size={24} /> : <IoIosArrowDown className='sort-icon' size={24} />}
												</button>
											</div>
											<div className='col-2 text-center'>Actions</div>
										</div>
										<div>
											{taskData.map((task, index) => (
												<div className='main-task-container custom-table-row d-flex flex-wrap align-items-center border-bottom py-2' key={index}>
													<div className='task-id col-1 fw-bold'>{index + 1}.</div>
													<div className='task-name col-12 col-md-4'>{task.name}</div>
													<div className='task-date col-7 col-md-3'>{new Date(task.created_at).toLocaleString()}</div>
													<div className={`tasks-in-progress__priority task-priority d-flex justify-content-center col-12 col-md-2 tasks__priority-name--${task.priority}`}>{task.priority}</div>
													<div className='task-btns-container d-flex justify-content-end col-5 col-md-2'>
														<TaskDoneAsTaskDone refreshData={() => setRefresh(prevState => prevState + 1)} taskProp={task} />
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
		</div>
	);
}
