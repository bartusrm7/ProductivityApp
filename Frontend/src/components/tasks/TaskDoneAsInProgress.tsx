import { MdDownloadDone } from "react-icons/md";
import type { UserTaskData } from "../../types/tasks";
import { useEffect, useState } from "react";

export default function TaskDoneAsInProgress({ taskProp, refreshData }: { taskProp: UserTaskData; refreshData: () => void }) {
	const [taskData, setTaskData] = useState<UserTaskData>({
		id: 0,
		name: "",
		created_at: "",
		priority: "",
		status: "in progress",
	});

	const handleChangeStatus = () => {
		setTaskData(prevState => ({ ...prevState, status: "in_progress" }));
	};

	async function handleTaskDoneAsInProgress(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			await fetch("http://productivityapp.local/done-task", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(taskData),
			});
			refreshData();
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
			<button className='action-btn success-action-btn me-2' onClick={handleTaskDoneAsInProgress}>
				<MdDownloadDone size={24} />
			</button>
		</>
	);
}
