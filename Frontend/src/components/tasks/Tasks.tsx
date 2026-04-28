import NavbarMenu from "../navigation/NavbarMenu";
import Sidebar from "../navigation/Sidebar";
import CreateTask from "./CreateTask";
import { useState } from "react";
import TasksToDo from "./TasksToDo";
import TasksInProgress from "./TasksInProgress";
import TasksDone from "./TasksDone";

export default function Tasks() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [showModal, setShowModal] = useState<boolean>(false);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Tasks"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='tasks'>
				<div className='tasks__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<h3 className='mb-0'>My Tasks</h3>
							<CreateTask show={showModal} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} />
						</div>
						<TasksToDo />
						<TasksInProgress />
						<TasksDone />
					</div>
				</div>
			</div>
		</>
	);
}
