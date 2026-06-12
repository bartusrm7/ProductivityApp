import { IoIosNotifications } from "react-icons/io";

export default function Notifications({ logsIds, refreshData }: { logsIds: number[]; refreshData: () => void }) {
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
		refreshData();
	}

	return <IoIosNotifications size={24} className='navbar-menu__message-btn' onClick={setNotificationsAsReaded} />;
}
