import { Button } from "react-bootstrap";
import { MdDownloadDone } from "react-icons/md";
import type { UserTaskData } from "../../types/tasks";
import { useEffect, useState } from "react";

export default function TaskDone({ taskProp }: { taskProp: UserTaskData }) {
	const [taskData, setTaskData] = useState<UserTaskData>({
		id: null,
		name: "",
		createdAt: new Date().toISOString(),
		priority: "",
		status: "in progress",
	});

	const handleChangeStatus = () => {
		setTaskData(prevState => ({ ...prevState, status: "in_progress" }));
	};

	async function handleTaskDone(e: any) {
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
			<Button className='bg-success me-2' onClick={handleTaskDone}>
				<MdDownloadDone size={24} />
			</Button>
		</>
	);
}
