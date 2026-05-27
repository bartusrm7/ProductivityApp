import NavbarMenu from "../navigation/NavbarMenu";
import Sidebar from "../navigation/Sidebar";
import CreateTask from "./CreateTask";
import { useEffect, useState } from "react";
import TasksToDo from "./TasksToDo";
import TasksInProgress from "./TasksInProgress";
import TasksDone from "./TasksDone";
import TasksFailed from "./TasksFailed";

export default function Tasks() {
	const [showMenu, setShowMenu] = useState<boolean>(false);
	const [showModal, setShowModal] = useState<boolean>(false);
	const [refresh, setRefresh] = useState<number>(0);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	useEffect(() => {
		document.title = "ProductivityApp - Tasks";
	}, [refresh]);

	return (
		<>
			<Sidebar isMenuOpen={showMenu} />
			<NavbarMenu pageName={"Tasks"} onToggleMenu={() => setShowMenu(prevState => !prevState)} />
			<div className='tasks'>
				<div className='tasks__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<div className='d-flex justify-content-between align-items-center'>
							<h2 className='mb-0'>My Tasks</h2>
							<CreateTask show={showModal} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} refreshData={() => setRefresh(prevState => prevState + 1)} />
						</div>
					</div>
				</div>
				<div className='tasks__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<TasksToDo refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
					</div>
				</div>
				<div className='tasks__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<TasksInProgress refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
					</div>
				</div>
				<div className='tasks__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<TasksDone refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
					</div>
				</div>
				<div className='tasks__main-container mx-3 rounded-3'>
					<div className='p-3 p-md-4'>
						<TasksFailed refreshParent={refresh} refreshData={() => setRefresh(prevState => prevState + 1)} />
					</div>
				</div>
			</div>
		</>
	);
}
