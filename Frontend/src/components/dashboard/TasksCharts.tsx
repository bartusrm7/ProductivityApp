import { PieChart } from "@mui/x-charts";
import type { UserTaskData } from "../../types/tasks";
import { useEffect, useState } from "react";

export default function TasksCharts() {
	const [tasksData, setTasksData] = useState<UserTaskData[]>([]);

	async function getTodayTasks() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-all-tasks", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setTasksData(data.data);
		}
	}

	const data = [
		{
			label: "Todo",
			value: tasksData.filter(task => task.status === "todo").length,
		},
		{
			label: "In Progress",
			value: tasksData.filter(task => task.status === "in_progress").length,
		},
		{
			label: "Done",
			value: tasksData.filter(task => task.status === "done").length,
		},
	];

	useEffect(() => {
		getTodayTasks();
	}, []);

	return (
		<>
			<PieChart
				series={[
					{
						data: data,
						innerRadius: 50,
						outerRadius: 90,
						paddingAngle: 3,
						cornerRadius: 5,
					},
				]}
				height={200}
				width={200}
			/>
		</>
	);
}
