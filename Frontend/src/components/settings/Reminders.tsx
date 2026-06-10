import { useEffect, useState } from "react";
import { Button } from "react-bootstrap";

export default function Reminders({ remindersState }: { remindersState: boolean }) {
	const [remindersData, setRemindersData] = useState<boolean>(remindersState);

	async function handleToggleReminderButton() {
		const reminderNewValue = !remindersData;
		setRemindersData(reminderNewValue);
		
		const jwt = localStorage.getItem("jwt");
		await fetch("http://productivityapp.local/update-reminders", {
			method: "POST",
			headers: {
				Authorization: `Bearer ${jwt}`,
			},
			body: JSON.stringify({ reminders: reminderNewValue }),
		});
	}

	useEffect(() => {
		setRemindersData(remindersState);
	}, [remindersState]);

	return (
		<Button className={`toggle-modern ${remindersData ? "active" : ""}`} onClick={handleToggleReminderButton}>
			<div className='knob'></div>
		</Button>
	);
}
