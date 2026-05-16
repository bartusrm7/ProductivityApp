import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useEffect, useState } from "react";
import CreateNote from "./CreateNote";
import DisplayAllNotes from "./DisplayAllNotes";
import DisplaySavedToHistoryNotes from "./DisplaySavedToHistoryNotes";

export default function Habits() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [showModal, setShowModal] = useState<boolean>(false);
	const [refresh, setRefresh] = useState<number>(0);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	useEffect(() => {
		document.title = "ProductivityApp - Habits";
	}, [refresh]);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Habits"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='habits'>
				<div className='habits__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<h2 className='mb-0'>Notes</h2>
							<CreateNote show={showModal} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} refreshData={() => setRefresh(prevState => prevState + 1)} />
						</div>
					</div>
				</div>
				<div className='habits__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<DisplayAllNotes />
						</div>
					</div>
				</div>
				<div className='habits__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<DisplaySavedToHistoryNotes />
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
