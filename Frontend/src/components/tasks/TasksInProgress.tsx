import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import { RiDeleteBin6Line } from "react-icons/ri";
import { CiEdit } from "react-icons/ci";
import type { UserTaskData } from "../../types/tasks";
import { Button } from "react-bootstrap";

export default function TasksInProgress() {
	const [taskData, setTaskData] = useState<UserTaskData[]>([]);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function getInProgressTasks() {
		try {
			const response = await fetch(`http://productivityapp.local/in-progress-tasks?status=in_progress&userId=21`, {
				method: "GET",
				headers: {
					"Content-Type": "application/json",
				},
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				setTaskData(data.data);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	useEffect(() => {
		getInProgressTasks();
	}, []);

	return (
		<div className='tasks-todo'>
			<div className='my-3'>
				<div>
					<div>
						<div className='d-flex align-items-center'>
							<IoIosArrowUp size={24} />
							<h3 className='ms-2 mb-0'>In Progress</h3>
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
									<div className='col-1'>#</div>
									<div className='col-4'>Task</div>
									<div className='col-3'>Deadline</div>
									<div className='col-2 text-center'>Priority</div>
									<div className='col-2 text-center'>Actions</div>
								</div>
								<div>
									{taskData.map((task, index) => (
										<div className='d-flex align-items-center border-bottom py-2' key={index}>
											<div className='col-1 fw-bold'>{task.id}.</div>
											<div className='col-4'>{task.name}</div>
											<div className='col-3'>{new Date(task.createdAt).toLocaleString()}</div>
											<div className={task.priority === "low" ? "tasks-todo__priority bg-success text-center rounded-3 py-2 col-2" : task.priority === "medium" ? "tasks-todo__priority bg-warning text-center rounded-3 py-2 col-2" : task.priority === "high" ? "tasks-todo__priority bg-danger text-center rounded-3 py-2 col-2" : ""}>{task.priority}</div>
											<div className='text-center col-2'>
												<Button className='bg-primary me-2'>
													<CiEdit size={24} />
												</Button>
												<Button className='bg-danger'>
													<RiDeleteBin6Line size={24} />
												</Button>
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
