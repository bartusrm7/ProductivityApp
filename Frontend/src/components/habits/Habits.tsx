import Sidebar from "../navigation/Sidebar";
import NavbarMenu from "../navigation/NavbarMenu";
import { useEffect, useState } from "react";
import CreateHabit from "./CreateHabit";
import DisplayHabits from "./DisplayHabits";

export default function Habits() {
	const [showMenu, setShowMenu] = useState<boolean>(false);

	const handleOpenModal = () => setShowMenu(true);
	const handleCloseModal = () => setShowMenu(false);

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
							<CreateHabit show={showMenu} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} />
						</div>
					</div>
				</div>
				<div className='habits__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<DisplayHabits />
					</div>
				</div>
			</div>
		</>
	);
}
