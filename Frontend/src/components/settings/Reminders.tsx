import { useEffect, useState } from "react";
import { Button } from "react-bootstrap";
import { IoIosNotifications } from "react-icons/io";
import { IoIosNotificationsOff } from "react-icons/io";

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
		<>
			{remindersData ? (
				<Button className='reminders-true' onClick={handleToggleReminderButton}>
					<IoIosNotifications size={24} />
				</Button>
			) : (
				<Button className='reminders-false' onClick={handleToggleReminderButton}>
					<IoIosNotificationsOff size={24} />
				</Button>
			)}
		</>
	);
}
