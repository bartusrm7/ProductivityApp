import { useState } from "react";
import { Button } from "react-bootstrap";

export default function Reminders({ refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [remindersData, setRemindersData] = useState<boolean>(true);

	async function handleToggleReminderButton() {
		const reminderNewValue = !remindersData;
		setRemindersData(reminderNewValue);
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/update-reminders", {
			method: "POST",
			headers: {
				Authorization: `Bearer ${jwt}`,
			},
			body: JSON.stringify({ reminders: reminderNewValue }),
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
