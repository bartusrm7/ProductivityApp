import { useState } from "react";
import { Button } from "react-bootstrap";

export default function Reminders({ refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [remindersData, setRemindersData] = useState<boolean>(false);

	async function handleToggleReminderButton() {
		const reminderNewValue = setRemindersData(!remindersData);
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/set-reminders", {
			method: "POST",
			headers: {
				Authorization: `Bearer ${jwt}`,
			},
			body: JSON.stringify(reminderNewValue),
		});
		const data = await response.json();
		if (data.success) {
			refreshData();
		}
	}

	return (
		<Button className={`toggle-modern ${remindersData ? "active" : ""}`} onClick={handleToggleReminderButton}>
			<div className='knob'></div>
		</Button>
	);
}
