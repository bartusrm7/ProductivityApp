import { useEffect, useState } from "react";
import { IoIosArrowUp } from "react-icons/io";
import type { UserTaskData } from "../../types/tasks";

export default function TasksToDo() {
	const [taskData, setTaskData] = useState<UserTaskData>({ name: "", createdAt: new Date(), priority: "", status: "todo" });
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function getToDoTasks() {
		try {
			const response = await fetch(`http://productivityapp.local/todo-tasks?status=todo&userId=21`, {
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
				console.log(taskData);
				console.log(data);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}
	
	// useEffect(() => {
	// 	getToDoTasks();
	// }, [taskData]);

	return (
		<div>
			<div className='my-3'>
				<div>
					<div>
						<div className='d-flex align-items-center'>
							<IoIosArrowUp size={24} />
							<h3 className='ms-2 mb-0'>To Do</h3>
						</div>
						<hr />
					</div>
					<div></div>
				</div>
			</div>
		</div>
	);
}
