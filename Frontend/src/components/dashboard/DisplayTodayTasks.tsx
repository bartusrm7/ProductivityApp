import { useEffect, useState } from "react";

export default function DisplayTodayTasks({ taskStatus }: { taskStatus: string }) {
	const [tasksName, setTasksName] = useState<string[]>([]);

	async function getTodayTasks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch(`http://productivityapp.local/get-today-tasks?status=${taskStatus}`, {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		console.log(data);
		if (data.success) {
			setTasksName(data.data);
		}
	}

	useEffect(() => {
		getTodayTasks();
	}, []);

	return (
		<>
			{tasksName.map((task, index) => (
				<div key={index}>{task}</div>
			))}
		</>
	);
}
