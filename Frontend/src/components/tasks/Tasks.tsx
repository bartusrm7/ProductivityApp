import NavbarMenu from "../navigation/NavbarMenu";
import Sidebar from "../navigation/Sidebar";
import CreateTask from "./CreateTask";
import { useState } from "react";
import TasksToDo from "./TasksToDo";
import TasksInProgress from "./TasksInProgress";
import TasksDone from "./TasksDone";

export default function Tasks() {
	const [showModal, setShowModal] = useState(false);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	return (
		<>
			<Sidebar />
			<NavbarMenu pageName={"Tasks"} />
			<div className='m-5'>
				<div className='tasks rounded-3'>
					<div className='p-4'>
						<div className='d-flex justify-content-between'>
							<h3>My Tasks</h3>
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
