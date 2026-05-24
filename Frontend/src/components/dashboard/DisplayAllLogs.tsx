import { useEffect, useState } from "react";
import type { UserActiveLogsData } from "../../types/dashboard";

export default function DisplayAllLogs() {
	const [allLogs, setAllLogs] = useState<UserActiveLogsData[]>([]);

	async function getAllLogs() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-all-logs", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setAllLogs(data.data);
			console.log(data.data);
		}
	}

	const displayLogMessage = (log: UserActiveLogsData) => {
		switch (log.action) {
			case "create":
				return `Created new ${log.name} ${log.entity} at ${log.created_at}`;
			case "edit":
				return `Edited ${log.name} ${log.entity} at ${log.created_at}`;
			case "set":
				return `Set ${log.name} ${log.entity} at ${log.created_at}`;
			case "done":
				return `Done ${log.name} ${log.entity} at ${log.created_at}`;
		}
	};

	useEffect(() => {
		getAllLogs();
	}, []);

	return (
		<div className='display-all-logs'>
			{allLogs.map((item, index) => (
				<div className='display-all-logs__log-row' key={index}>
					<span className='fw-bold'>{index + 1}. </span>
					{displayLogMessage(item)}
				</div>
			))}
		</div>
	);
}
