import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import type { UserTaskData } from "../../types/tasks";
import TaskDelete from "./TaskDelete";
import TaskEdit from "./TaskEdit";

export default function TasksDone() {
	const [taskData, setTaskData] = useState<UserTaskData[]>([]);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function getDoneTasks() {
		try {
			const response = await fetch(`http://productivityapp.local/done-tasks?status=done&userId=21`, {
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
		getDoneTasks();
	}, []);

	return (
		<div className='tasks-todo'>
			<div className='my-3'>
				<div>
					<div>
						<div className='d-flex align-items-center'>
							<IoIosArrowUp size={24} />
							<h3 className='ms-2 mb-0'>Done</h3>
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
									<div className='col-3'>Date</div>
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
