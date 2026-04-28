import { Button } from "react-bootstrap";
import { MdDownloadDone } from "react-icons/md";
import type { UserTaskData } from "../../types/tasks";
import { useEffect, useState } from "react";

export default function TaskDoneAsTaskDone({ taskProp }: { taskProp: UserTaskData }) {
	const [taskData, setTaskData] = useState<UserTaskData>({
		id: null,
		name: "",
		createdAt: new Date().toISOString(),
		priority: "",
		status: "done",
	});

	const handleChangeStatus = () => {
		setTaskData(prevState => ({ ...prevState, status: "done" }));
	};

	async function handleTaskDoneAsTaskDone(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/done-task", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(taskData),
			});
			const data = await response.json();
		} catch (error) {
			console.error("Server error. Try again.", error);
		}
	}

	useEffect(() => {
		setTaskData(taskProp);
		handleChangeStatus();
	}, [taskProp]);

	return (
		<>
			<Button className='bg-success me-2' onClick={handleTaskDoneAsTaskDone}>
				<MdDownloadDone size={24} />
			</Button>
		</>
	);
}
