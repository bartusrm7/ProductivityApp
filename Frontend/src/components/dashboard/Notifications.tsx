import { IoIosNotifications } from "react-icons/io";

export default function Notifications({ logsIds }: { logsIds: number[] }) {
	async function setNotificationsAsReaded() {
		const jwt = localStorage.getItem("jwt");
		await fetch("http://productivityapp.local/readed-notification", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
			body: JSON.stringify({ id: logsIds }),
		});
	}

	return <IoIosNotifications size={24} className='navbar-menu__message-btn' onClick={setNotificationsAsReaded} />;
}
