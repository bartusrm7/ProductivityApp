import { useEffect, useState } from "react";
import { Button } from "react-bootstrap";
import { FaRegUserCircle } from "react-icons/fa";
import { IoMdMenu } from "react-icons/io";
import { IoIosNotifications } from "react-icons/io";
import type { UserActiveLogsData } from "../../types/dashboard";

export default function NavbarMenu({ pageName, onToggleMenu }: { pageName: string; onToggleMenu: () => void }) {
	const [userEmail, setUserEmail] = useState<string | null>("");
	const [userName, setUserName] = useState<string | null>("");
	const [userAvatar, setUserAvatar] = useState<string | null>("");
	const [remindersState, setRemindersState] = useState<number>();
	const [noReadedLogs, setNoReadedLogs] = useState<UserActiveLogsData[]>([]);
	const [showLogs, setShowLogs] = useState(false);

	async function getUserEmail() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/user-email", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		setUserEmail(data.email);
	}

	async function getUserName() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/user-name", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		setUserName(data.data.data.name);
	}

	async function getUserAvatar() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-user-avatar", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		setUserAvatar(data.avatar.avatar.avatar);
	}

	async function getRemindersState() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/reminders-state", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		setRemindersState(data.data.reminders);
	}

	async function getNoReadedLogs() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/get-no-readed-logs", {
			method: "GET",
			headers: {
				"Content-Type": "application/json",
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setNoReadedLogs(data.data);
		}
	}

	useEffect(() => {
		getNoReadedLogs();
	}, []);

	useEffect(() => {
		getUserEmail();
		getUserName();
		getUserAvatar();
		getRemindersState();
	}, []);

	return (
		<>
			<div className='navbar-menu d-flex justify-content-between'>
				<div className='d-flex justify-content-between align-items-center w-100 px-3'>
					<div className='navbar-menu__page-name'>{pageName}</div>
					<div className='d-flex align-items-center'>
						{remindersState ? (
							<div className='navbar-menu__message-wrapper me-3' onClick={() => setShowLogs(prevState => !prevState)}>
								<IoIosNotifications size={24} className='navbar-menu__message-btn' />
								<div className='navbar-menu__badge'>{noReadedLogs.length}</div>
							</div>
						) : null}
						<div className='me-2 d-block'>
							<div className='navbar-menu__user-name-row'>{userName}</div>
							<div className='navbar-menu__user-email-row'>{userEmail}</div>
						</div>
						{userAvatar ? <img className='navbar-menu__user-avatar-img' src={`http://productivityapp.local/${userAvatar}`} alt='' /> : <FaRegUserCircle size={80} />}
					</div>
				</div>
				<Button className='custom-btn d-xl-none px-2 py-1 ms-3' onClick={onToggleMenu}>
					<IoMdMenu size={24} />
				</Button>
			</div>
			<hr className='m-0 px-3' />
			{showLogs && (
				<div className='navbar-menu__logs-dropdown'>
					{noReadedLogs.length === 0 ? (
						<div className='navbar-menu__log-empty'>No notifications</div>
					) : (
						noReadedLogs.map(log => (
							<div key={log.id} className='navbar-menu__log-item'>
								<div className='navbar-menu__log-title'>
									{log.action} {log.entity} {log.name}
								</div>
								<div className='navbar-menu__log-meta'>{new Date(log.created_at).toLocaleString()}</div>
							</div>
						))
					)}
				</div>
			)}
		</>
	);
}
