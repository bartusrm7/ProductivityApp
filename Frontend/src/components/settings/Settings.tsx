import { useEffect, useState } from "react";
import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import AddAvatar from "./AddAvatar";
import { FaUserCircle } from "react-icons/fa";
import UpdateUserName from "./UpdateUserName";
import Reminders from "./Reminders";

export default function Settings() {
	const [userName, setUserName] = useState<string | null>("");
	const [userAvatar, setUserAvatar] = useState<string | null>("");
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [showModal, setShowModal] = useState<boolean>(false);
	const [refresh, setRefresh] = useState<number>(0);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	async function getUserName() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/user-name", {
			method: "GET",
			headers: {
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.data.success) {
			setUserName(data.data.data.name);
		}
	}

	async function getUserAvatar() {
		const jwt = localStorage.getItem("jwt");
		const response = await fetch("http://productivityapp.local/user-avatar", {
			method: "GET",
			headers: {
				Authorization: `Bearer ${jwt}`,
			},
		});
		const data = await response.json();
		if (data.success) {
			setUserAvatar(data.data.avatar);
		}
	}

	useEffect(() => {
		getUserName();
		getUserAvatar();
	}, [refresh]);

	useEffect(() => {
		document.title = "ProductivityApp - Settings";
	}, []);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Settings"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='settings'>
				<div className='settings__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<h2 className='mb-0'>My settings</h2>
						</div>
					</div>
				</div>
				<div className='settings__main-container mx-3 rounded-3'>
					<div className='settings__user-data-container p-3 p-md-4'>
						<div className='settings__user-account-preview'>
							{userAvatar ? <img className='settings__user-avatar-img' src={`http://productivityapp.local/${userAvatar}`} alt='' /> : <FaUserCircle size={80} />}
							<div className="mt-2">{userName}</div>
						</div>

						<div className='d-flex justify-content-between'>
							<div className='settings__item-name'>Avatar</div>
							<AddAvatar refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
						</div>
						<div className='d-flex justify-content-between'>
							<div className='settings__item-name'>Name</div>
							<UpdateUserName refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
						</div>
						<div className='d-flex justify-content-between'>
							<div className='settings__item-name'>Password</div>
							<AddAvatar refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
						</div>
						<div className='d-flex justify-content-between'>
							<div className='settings__item-name'>Reminders</div>
							<Reminders refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
