import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useEffect, useState } from "react";

export default function Goals() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [showModal, setShowModal] = useState<boolean>(false);
	const [refresh, setRefresh] = useState<number>(0);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	useEffect(() => {
		document.title = "ProductivityApp - Goals";
	}, [refresh]);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Goals"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='goals'>
				<div className='goals__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<h2 className='mb-0'>My goals</h2>
							{/* <CreateHabit show={showModal} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} refreshData={() => setRefresh(prevState => prevState + 1)} /> */}
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
