import { useEffect, useState } from "react";
import { Button } from "react-bootstrap";
import { FaRegUserCircle } from "react-icons/fa";
import { IoMdMenu } from "react-icons/io";

export default function NavbarMenu({ pageName, onToggleMenu }: { pageName: string; onToggleMenu: void }) {
	const [userName, setUserName] = useState<string | null>("");

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
		setUserName(data.name);
	}

	useEffect(() => {
		getUserName();
	}, []);

	return (
		<>
			<div className='navbar-menu d-flex justify-content-between align-items-center p-3'>
				<div className='d-flex justify-content-between w-100'>
					<div>{pageName}</div>
					<div className='d-flex'>
						<div className='me-2'>{userName}</div>
						<FaRegUserCircle size={24} />
					</div>
				</div>
				<Button className='custom-btn d-xl-none px-2 py-1 ms-3' onClick={onToggleMenu}>
					<IoMdMenu size={24} />
				</Button>
			</div>
			<hr className='m-0 px-3' />
		</>
	);
}
