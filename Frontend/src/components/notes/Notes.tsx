import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useEffect, useState } from "react";
import CreateNote from "./CreateNote";

export default function Habits() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [showModal, setShowModal] = useState<boolean>(false);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	useEffect(() => {
		document.title = "ProductivityApp - Habits";
	});

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Habits"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='habits'>
				<div className='habits__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<h2 className='mb-0'>My Habits</h2>
							<CreateNote show={showModal} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} />
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
